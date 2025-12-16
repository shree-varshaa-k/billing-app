<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fbff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .forgot-box {
            background: #cce7ff;
            padding: 40px;
            border-radius: 10px;
            width: 400px;
        }
        .btn-send {
            background-color: #007bff;
            color: white;
            width: 100%;
        }
        .btn-send:hover {
            background-color: #0056d2;
        }
    </style>
</head>
<body>

<div class="forgot-box">
    <h3 class="text-center mb-4 fw-bold">Reset Password</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('forgot.password.post') }}">
        @csrf

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="btn btn-send">Reset Password</button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </form>
</div>

</body>
</html>
