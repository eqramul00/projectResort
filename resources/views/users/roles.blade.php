<x-app-layout>
    <div class="card">
       <div class="card-header">
    <div>
        <span class="page-title-badge">STAFF ACCESS CONTROL</span>
        <h2>User Roles</h2>
        <p class="muted">Manage admin, manager, and reception permissions.</p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
</div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th style="width: 260px;">Change Role</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>#{{ $u->id }}</td>
                        <td><strong>{{ $u->name }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="status" style="background:#eef2ff; color:#3730a3;">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('users.role.update', $u) }}" class="actions">
                                @csrf

                                <select name="role" style="min-width: 130px;">
                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>admin</option>
                                    <option value="manager" {{ $u->role === 'manager' ? 'selected' : '' }}>manager</option>
                                    <option value="reception" {{ $u->role === 'reception' ? 'selected' : '' }}>reception</option>
                                </select>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>