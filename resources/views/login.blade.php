<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libris Access - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 850px;
            align-items: center;
        }

        .illustration-side {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .illustration-side img {
            max-width: 100%;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
        }

        .login-box {
            flex: 1;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }

        .login-box h2 {
            color: #1a5276;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
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
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #1a5276;
        }

        .error-msg {
            background-color: #fceaea;
            color: #e74c3c;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 15px;
            list-style: none;
            border: 1px solid #fadbd8;
        }

        .forgot-link {
            display: block;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .illustration-side {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="illustration-side">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </div>

        <div class="login-box">
            <h2>Libris Access</h2>

            {{-- Pesan Error dari Laravel --}}
            @if ($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Form Login Utama --}}
            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="input-group">
                    <input type="text" name="email" placeholder="email" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">Login</button>

            </form>
            <a href="{{ route('register') }}" class="forgot-link">
                Belum punya akun? Daftar
            </a>

        </div>
    </div>

</body>

</html>