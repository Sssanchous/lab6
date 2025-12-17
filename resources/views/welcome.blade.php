<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Авто Каталог — Laravel Lab</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Figtree', system-ui, sans-serif;
        }
        .welcome-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2.5rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .welcome-card h1 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1e293b;
        }
        .welcome-card p {
            color: #64748b;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-group .btn {
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            margin: 0 6px;
        }
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .btn-outline-secondary {
            background-color: transparent;
            border-color: #cbd5e1;
            color: #475569;
        }
        .car-icon {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="welcome-card">
        <div class="car-icon">
            <i class="fas fa-car"></i>
        </div>
        <h1>Добро пожаловать в Авто Каталог!</h1>
        <p>Зарегистрируйтесь, чтобы добавлять, редактировать и управлять автомобилями. Администраторы могут управлять всеми записями.</p>

        <div class="btn-group">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/cards') }}" class="btn btn-primary">Перейти в каталог</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Войти</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary">Регистрация</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>