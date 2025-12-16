<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fbff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .login-box {
            background: #cce7ff;
            padding: 40px;
            border-radius: 10px;
            width: 400px;
        }
        .btn-login {
            background-color: #007bff;
            color: white;
            width: 100%;
        }
        .btn-login:hover {
            background-color: #0056d2;
        }
        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .forgot-password a {
            color: #0056d2;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="text-center">
        @php
        $logo = \App\Models\User::whereNotNull('logo')->value('logo');
        @endphp

        @if($logo)
            <img src="{{ asset($logo) }}" alt="Logo" style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 20px;">
        @endif
    </div>

    <h3 class="text-center mb-4 fw-bold">Administrator Login</h3>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-login">Login</button>

        <div class="forgot-password">
           <a href="{{ route('forgot.password') }}">Forgot Password?</a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif
    </form>
</div>

</body>
</html>
