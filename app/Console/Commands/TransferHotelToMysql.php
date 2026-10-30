<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;
use RuntimeException;
use Throwable;

class TransferHotelToMysql extends Command
{
    protected $signature = 'hotel:transfer-mysql';
    protected $description = 'Copy a fresh SQLite snapshot to the local hotel_management MySQL database';

    public function handle(): int
    {
        $target = null;
        $originalDefault = config('database.default');
        $phase = 'preflight';

        try {
            if (!app()->isDownForMaintenance()) {
                throw new RuntimeException('Run php artisan down, and stop queue workers and the scheduler first.');
            }

            $live = DB::connection();
            if ($live->getDriverName() !== 'sqlite') {
                throw new RuntimeException('The current default connection must still be SQLite.');
            }
            if (!extension_loaded('pdo_mysql')) {
                throw new RuntimeException('PHP pdo_mysql is unavailable.');
            }

            // Take a CURRENT snapshot, not the older backup from the previous session.
            $backupDir = (getenv('USERPROFILE') ?: storage_path('app/private')).'/ResortDatabaseBackups';
            if (!is_dir($backupDir) && !mkdir($backupDir, 0700, true)) {
                throw new RuntimeException('Cannot create the backup directory.');
            }
            $backupFile = $backupDir.'/before-mysql-'.date('Y-m-d-His').'-'.bin2hex(random_bytes(3)).'.sqlite';
            $live->statement('VACUUM INTO '.$live->getPdo()->quote($backupFile));
            $source = new PDO('sqlite:'.$backupFile, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $source->exec('PRAGMA query_only = ON');
            if ($source->query('PRAGMA integrity_check')->fetchColumn() !== 'ok') {
                throw new RuntimeException('The fresh SQLite backup failed its integrity check.');
            }
            if ($source->query('PRAGMA foreign_key_check')->fetch(PDO::FETCH_ASSOC) !== false) {
                throw new RuntimeException('SQLite contains broken foreign-key references. Fix them before transferring.');
            }
            $this->info('Verified fresh backup: '.$backupFile);

            $sourceTables = $source->query("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('migrations', $sourceTables, true)) {
                throw new RuntimeException('The SQLite migration history is missing.');
            }
            $applied = $source->query('SELECT migration FROM migrations')->fetchAll(PDO::FETCH_COLUMN);
            $files = glob(database_path('migrations/*.php'));
            $expected = array_map(fn ($file) => pathinfo($file, PATHINFO_FILENAME), $files);
            sort($applied);
            sort($expected);
            if ($applied !== $expected) {
                throw new RuntimeException('SQLite migration history differs from the local migration files. Send this message for review; do not run migrations blindly.');
            }

            $password = $this->secret('MySQL root password (hidden; never saved)');
            if (!is_string($password)) {
                throw new RuntimeException('Password input cancelled.');
            }
            $mysqlConfig = config('database.connections.mysql');
            $mysqlConfig = array_replace($mysqlConfig, [
                'url' => null, 'host' => '127.0.0.1', 'port' => '3306',
                'database' => 'hotel_management', 'username' => 'root',
                'password' => $password, 'unix_socket' => '', 'prefix' => '',
                'charset' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci',
                'strict' => true, 'engine' => 'InnoDB', 'timezone' => '+00:00',
            ]);
            config(['database.connections.hotel_transfer' => $mysqlConfig]);
            unset($password);
            DB::purge('hotel_transfer');
            $target = DB::connection('hotel_transfer');
            $target->getPdo();

            // Permit a retry after a failed import only when every data table is empty.
            $existing = $target->select("SELECT TABLE_NAME AS name, TABLE_TYPE AS kind FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()");
            foreach ($existing as $table) {
                if ($table->kind !== 'BASE TABLE') {
                    throw new RuntimeException('The target contains views; use a dedicated empty database.');
                }
                if ($table->name !== 'migrations' && $target->table($table->name)->exists()) {
                    throw new RuntimeException('The target contains records in '.$table->name.'. Nothing has been overwritten.');
                }
            }

            // These migrations use both Schema and DB facades. Set the runtime default
            // to the target, without editing .env or any saved configuration.
            $phase = 'creating MySQL tables';
            DB::setDefaultConnection('hotel_transfer');
            Schema::clearResolvedInstance('db.schema');
            if (Artisan::call('migrate', ['--database' => 'hotel_transfer', '--force' => true]) !== 0) {
                throw new RuntimeException('MySQL migration failed. Review the migration output.');
            }
            $this->output->write(Artisan::output());
            DB::setDefaultConnection($originalDefault);
            Schema::clearResolvedInstance('db.schema');

            $targetInfo = $target->select("SELECT TABLE_NAME AS name, ENGINE AS engine FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = 'BASE TABLE'");
            $targetTables = [];
            foreach ($targetInfo as $table) {
                $targetTables[] = $table->name;
                if (strtolower((string) $table->engine) !== 'innodb') {
                    throw new RuntimeException('Every target table must use InnoDB: '.$table->name);
                }
                if ($table->name !== 'migrations' && $target->table($table->name)->exists()) {
                    throw new RuntimeException('A migration populated '.$table->name.'. Review it before importing.');
                }
            }
            sort($sourceTables);
            sort($targetTables);
            if ($sourceTables !== $targetTables) {
                throw new RuntimeException('Source and target table lists differ. No records have been copied.');
            }
            $targetMigrations = $target->table('migrations')->pluck('migration')->all();
            sort($targetMigrations);
            if ($targetMigrations !== $applied) {
                throw new RuntimeException('The migration histories do not match.');
            }

            // Temporary application state should start empty; durable data is copied.
            $skip = ['migrations', 'cache', 'cache_locks', 'sessions'];
            $tables = array_values(array_diff($sourceTables, $skip));
            $metadata = [];
            foreach ($tables as $table) {
                $sourceColumns = $source->query('PRAGMA table_info('.$this->quoteSqlite($table).')')->fetchAll(PDO::FETCH_ASSOC);
                $sourceNames = array_column($sourceColumns, 'name');
                $meta = $target->select('SELECT COLUMN_NAME AS name, DATA_TYPE AS type, NUMERIC_SCALE AS scale FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION', [$table]);
                $targetNames = array_map(fn ($column) => $column->name, $meta);
                sort($sourceNames);
                sort($targetNames);
                if ($sourceNames !== $targetNames) {
                    throw new RuntimeException('Column mismatch in '.$table.'. No records have been copied.');
                }
                usort($meta, fn ($a, $b) => strcmp($a->name, $b->name));
                $metadata[$table] = $meta;
            }

            // Copy parent tables before child tables. FK checks remain ENABLED.
            $foreignKeys = $target->select('SELECT TABLE_NAME AS child, REFERENCED_TABLE_NAME AS parent FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL');
            $ordered = [];
            $pending = $tables;
            while ($pending !== []) {
                $progress = false;
                foreach ($pending as $index => $table) {
                    $blocked = false;
                    foreach ($foreignKeys as $fk) {
                        if ($fk->child === $table && !in_array($fk->parent, $ordered, true)) {
                            $blocked = true;
                        }
                    }
                    if (!$blocked) {
                        $ordered[] = $table;
                        unset($pending[$index]);
                        $progress = true;
                    }
                }
                if (!$progress) {
                    throw new RuntimeException('Unresolved foreign-key dependency. No records have been copied.');
                }
            }

            $phase = 'copying and verifying records';
            $results = [];
            $target->transaction(function () use ($source, $target, $ordered, $metadata, &$results) {
                foreach ($ordered as $table) {
                    $statement = $source->query('SELECT * FROM '.$this->quoteSqlite($table));
                    $batch = [];
                    $sourceHashes = [];
                    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $sourceHashes[] = $this->rowHash($row, $metadata[$table]);
                        $batch[] = $row;
                        if (count($batch) === 100) {
                            $target->table($table)->insert($batch);
                            $batch = [];
                        }
                    }
                    if ($batch !== []) {
                        $target->table($table)->insert($batch);
                    }
                    $targetHashes = [];
                    foreach ($target->table($table)->cursor() as $row) {
                        $targetHashes[] = $this->rowHash((array) $row, $metadata[$table]);
                    }
                    sort($sourceHashes, SORT_STRING);
                    sort($targetHashes, SORT_STRING);
                    if ($sourceHashes !== $targetHashes) {
                        throw new RuntimeException('Row verification failed for '.$table.'. The data transaction is being rolled back.');
                    }
                    $results[] = [$table, count($sourceHashes), count($targetHashes), 'MATCH'];
                    $this->line('Verified '.$table.': '.count($sourceHashes).' rows');
                }
            });

            $this->table(['Table', 'SQLite', 'MySQL', 'Result'], $results);
            $this->info('TRANSFER VERIFIED AND COMMITTED');
            $this->line('.env is unchanged. Keep maintenance mode on until you switch to MySQL.');
            $this->line('Sessions and cache were left empty. Existing users will need to sign in again.');
            return self::SUCCESS;
        } catch (Throwable $exception) {
            // SQL exception messages can contain password hashes, tokens or guest data.
            // Print our own validation messages only; do not print raw SQL/bindings.
            $this->error('Stopped during '.$phase.'.');
            if (get_class($exception) === RuntimeException::class) {
                $this->error($exception->getMessage());
            } else {
                $code = $exception->getCode();
                $this->error('Database/framework error '.(string) $code.'. No raw SQL or personal data is printed.');
                if ($phase === 'creating MySQL tables') {
                    $this->output->write(Artisan::output());
                }
            }
            $this->warn('SQLite and .env remain unchanged. Any data transaction was rolled back; created MySQL tables may remain.');
            $this->line('To resume SQLite: php artisan up');
            return self::FAILURE;
        } finally {
            DB::setDefaultConnection($originalDefault);
            Schema::clearResolvedInstance('db.schema');
            DB::purge('hotel_transfer');
            config(['database.connections.hotel_transfer.password' => null]);
        }
    }

    private function quoteSqlite(string $identifier): string
    {
        return '"'.str_replace('"', '""', $identifier).'"';
    }

    private function rowHash(array $row, array $metadata): string
    {
        $values = [];
        foreach ($metadata as $column) {
            $value = $row[$column->name];
            if ($value !== null) {
                $value = (string) $value;
                if (in_array($column->type, ['decimal', 'numeric'], true)) {
                    // Compare decimals without float rounding. Refuse precision loss.
                    if (!preg_match('/^(-?)(\d+)(?:\.(\d+))?$/D', $value, $parts)) {
                        throw new RuntimeException('Unexpected decimal format in '.$column->name.'.');
                    }
                    $scale = (int) $column->scale;
                    $fraction = $parts[3] ?? '';
                    if (trim(substr($fraction, $scale), '0') !== '') {
                        throw new RuntimeException('Decimal precision would be lost in '.$column->name.'.');
                    }
                    $whole = ltrim($parts[2], '0') ?: '0';
                    $fraction = substr(str_pad($fraction, $scale, '0'), 0, $scale);
                    $sign = $parts[1] === '-' && ($whole !== '0' || trim($fraction, '0') !== '') ? '-' : '';
                    $value = $sign.$whole.($scale ? '.'.$fraction : '');
                }
            }
            $values[] = $value;
        }
        return hash('sha256', serialize($values));
    }
}
