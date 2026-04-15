<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libris Access - Register</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(rgba(26, 82, 118, 0.8), rgba(26, 82, 118, 0.8)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=2000');
            background-size: cover;
        }

        .login-container {
            display: flex;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 850px;
        }

        .login-box {
            flex: 1;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }

        h2 {
            color: #1a5276;
            margin-bottom: 20px;
        }

        .input-group { margin-bottom: 15px; }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #2471a3;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #1a5276;
        }

        .error-msg {
            background-color: #fceaea;
            color: red;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .link {
            display: block;
            margin-top: 15px;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">

        <h2>Daftar Akun</h2>

        @if ($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="input-group">
                <input type="text" name="name" placeholder="Nama Lengkap" required>
            </div>

            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn-login">Daftar</button>
        </form>

        <a href="{{ route('login') }}" class="link">
            Sudah punya akun? Login
        </a>

    </div>
</div>

</body>
</html>