<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Worksync Login</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Reset CSS dan variabel */
        :root {
            --primary: #1d72dd;
            --primary-hover: #155cb8;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border-color: #D1D5DB;
            --bg-color: #FFFFFF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        .split-layout {
            display: flex;
            width: 100vw;
            min-height: 100vh;
        }

        /* Bagian kiri - Latar belakang biru muda */
        .left-pane {
            flex: 1;
            background: linear-gradient(145deg, #d4edf9 0%, #cae8f7 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Animasi bentuk latar belakang */
        .left-pane::before {
            content: '';
            position: absolute;
            width: 80vh;
            height: 80vh;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: pulse 8s infinite alternate ease-in-out;
            z-index: 1;
        }

        .hero-image {
            width: 85%;
            max-width: 600px;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
            z-index: 10;
            position: relative;
            animation: float 6s ease-in-out infinite;
            border-radius: 20px;
        }

        @keyframes pulse {
            0%   { transform: translate(-50%, -50%) scale(0.9); opacity: 0.8; }
            100% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.5; }
        }

        @keyframes float {
            0%   { transform: translateY(0px); }
            50%  { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Bagian kanan - Area Form */
        .right-pane {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background-color: var(--bg-color);
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
        }

        /* Penyesuaian Responsif */
        @media (max-width: 768px) {
            .split-layout {
                flex-direction: column;
            }
            .left-pane {
                flex: none;
                height: 200px;
            }
            .right-pane {
                padding: 1.5rem;
                align-items: flex-start;
                padding-top: 3rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- Panel Kiri: Gambar / Ilustrasi -->
        <div class="left-pane">
            <img src="{{ asset('images/logo-worksync.png') }}" alt="Worksync Illustration" class="hero-image">
        </div>

        <!-- Panel Kanan: Form (diisi oleh $slot dari komponen Livewire) -->
        <div class="right-pane">
            <div class="login-container">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
