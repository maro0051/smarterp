<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - SmartERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        h2 { color: #1e3a8a; font-weight: 700; }
        .btn-primary {
            background: #1e3a8a;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Verify Your Email</h2>
        <p class="text-muted">A verification link has been sent to your email address.</p>
        
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">A new verification link has been sent.</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary mt-2">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none">Log Out</button>
        </form>
    </div>
</body>
</html>