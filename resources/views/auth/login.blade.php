<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Login</title>
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                linear-gradient(rgba(8,15,30,0.55), rgba(8,15,30,0.72)),
                linear-gradient(135deg, #0f766e, #2563eb 60%, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-wrap {
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 28px;
            overflow: hidden;
            backdrop-filter: blur(16px);
            box-shadow: 0 30px 80px rgba(0,0,0,0.25);
        }

        .auth-left {
            padding: 52px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
        }

        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.8px;
            margin-bottom: 18px;
            width: fit-content;
        }

        .auth-left h1 {
            margin: 0;
            font-size: 46px;
            line-height: 1.1;
        }

        .auth-left p {
            color: rgba(255,255,255,0.84);
            font-size: 16px;
            line-height: 1.7;
            margin-top: 18px;
            max-width: 500px;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 28px;
        }

        .feature {
            padding: 16px;
            border-radius: 18px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.10);
            font-weight: 700;
        }

        .auth-right {
            background: #ffffff;
            padding: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
        }

        .login-card h2 {
            margin: 0 0 8px;
            font-size: 32px;
            color: #0f172a;
        }

        .login-card p {
            color: #64748b;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #0f172a;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dbe3ef;
            border-radius: 16px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #7dd3fc;
            box-shadow: 0 0 0 4px rgba(125, 211, 252, 0.24);
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            background: linear-gradient(135deg, #0f766e, #2563eb);
            color: #fff;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.22);
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 14px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-weight: 600;
        }

        .backup-box {
            margin-top: 20px;
            padding: 16px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .backup-box h4 {
            margin: 0 0 10px;
            color: #0f172a;
        }

        .backup-box p {
            margin: 4px 0;
            color: #475569;
        }

        @media (max-width: 900px) {
            .auth-wrap {
                grid-template-columns: 1fr;
            }

            .auth-left {
                padding: 32px;
            }

            .auth-right {
                padding: 28px;
            }

            .auth-left h1 {
                font-size: 34px;
            }

            .feature-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-left">
            <span class="badge">SAJEK RESORT CONTROL</span>
            <h1>Luxury resort operations, beautifully organized.</h1>
            <p>
                Manage bookings, rooms, guests, and user roles from one clean resort dashboard.
                Designed for fast front-desk work and smooth admin control.
            </p>

         
        </div>

        <div class="auth-right">
            <div class="login-card">
                <h2>Sign In</h2>
                <p>Access your resort management dashboard.</p>

                @if($errors->any())
                    <div class="error-box">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password">
                    </div>

                    <button type="submit" class="btn">Login to Dashboard</button>
                </form>

                
            </div>
        </div>
    </div>
</body>
</html>