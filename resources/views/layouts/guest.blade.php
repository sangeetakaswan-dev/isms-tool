<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ISMS Audit') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=playfair-display:400,500,600,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-gradient-start: #0a0a2a;
            --bg-gradient-end: #1a1a3a;
            --card-bg: rgba(255, 255, 255, 0.96);
            --card-border: rgba(0, 0, 0, 0.08);
            --text-color: #1a202c;
            --text-muted: #4a5568;
            --input-bg: #ffffff;
            --input-border: #e2e8f0;
            --primary: #4F46E5;
            /* ISMS Indigo */
            --primary-dark: #4338CA;
            --primary-light: #818CF8;
            --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        }

        body.dark-mode {
            --bg-gradient-start: #050510;
            --bg-gradient-end: #0a0a1a;
            --card-bg: rgba(10, 15, 25, 0.92);
            --card-border: rgba(79, 70, 229, 0.3);
            --text-color: #f1f5f9;
            --text-muted: #94a3b8;
            --input-bg: rgba(255, 255, 255, 0.08);
            --input-border: rgba(79, 70, 229, 0.4);
            --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            background-attachment: fixed;
            position: relative;
            overflow-x: hidden;
            transition: background 0.4s ease;
        }

        /* Stars */
        .stars-container {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            animation: twinkle 5s infinite alternate;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.2;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        .shooting-star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: linear-gradient(135deg, white, transparent);
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
        }

        @keyframes shoot {
            0% {
                transform: translateX(0) translateY(0) rotate(45deg);
                opacity: 1;
            }

            100% {
                transform: translateX(300px) translateY(300px) rotate(45deg);
                opacity: 0;
            }
        }

        .dark-mode-toggle {
            position: fixed;
            top: 25px;
            right: 25px;
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 60px;
            padding: 10px 24px;
            cursor: pointer;
            z-index: 100;
            transition: all 0.3s ease;
            color: var(--text-color);
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: var(--shadow);
        }

        .dark-mode-toggle:hover {
            transform: translateY(-2px);
            border-color: var(--primary);
        }

        /* Premium Card */
        .premium-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 48px;
            padding: 52px 48px;
            width: 520px;
            max-width: 94%;
            transition: all 0.4s ease;
            animation: cardReveal 0.6s ease;
            position: relative;
            z-index: 10;
            box-shadow: var(--shadow);
        }

        @keyframes cardReveal {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .custom-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin: 0 auto 15px;
            display: block;
            border-radius: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            background: white;
            padding: 10px;
        }

        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .brand-name span {
            color: var(--primary);
        }

        .brand-tagline {
            font-size: 0.75rem;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Inputs */
        .input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .input-group i {
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            z-index: 1;
            opacity: 0.7;
        }

        .input-group input {
            width: 100%;
            padding: 16px 24px 16px 56px;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 100px !important;
            font-size: 0.95rem;
            transition: all 0.35s ease;
            outline: none;
            color: var(--text-color);
        }

        .input-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.15);
        }

        .password-toggle {
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* Buttons */
        .premium-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 100px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.35s ease;
            margin-top: 10px;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(79, 70, 229, 0.3);
        }

        .premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.45);
        }

        .premium-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .premium-link:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            position: relative;
            margin: 25px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: var(--input-border);
        }

        .divider span {
            background: var(--card-bg);
            padding: 0 15px;
            position: relative;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        @media (max-width: 540px) {
            .premium-card {
                padding: 35px 24px;
                border-radius: 30px;
            }

            .brand-name {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>
    <div class="stars-container" id="starsContainer"></div>
    <button class="dark-mode-toggle" id="darkModeToggle"><i class="fas fa-moon"></i> Dark Mode</button>

    <div class="premium-card">
        {{ $slot }}
    </div>

    <script>
        // Stars Generator
        class StarsGenerator {
            constructor(count = 200) {
                this.container = document.getElementById('starsContainer');
                for (let i = 0; i < count; i++) {
                    const star = document.createElement('div');
                    star.className = 'star';
                    star.style.left = Math.random() * window.innerWidth + 'px';
                    star.style.top = Math.random() * window.innerHeight + 'px';
                    star.style.width = (1 + Math.random() * 3) + 'px';
                    star.style.height = star.style.width;
                    star.style.animationDuration = (3 + Math.random() * 4) + 's';
                    star.style.animationDelay = (Math.random() * 5) + 's';
                    this.container.appendChild(star);
                }
            }
        }
        new StarsGenerator(200);

        // Shooting Stars
        function createShootingStar() {
            const star = document.createElement('div');
            star.className = 'shooting-star';
            star.style.left = Math.random() * window.innerWidth + 'px';
            star.style.top = Math.random() * (window.innerHeight / 2) + 'px';
            const angle = 30 + Math.random() * 30;
            star.style.transform = `rotate(${angle}deg)`;
            star.style.width = (80 + Math.random() * 100) + 'px';
            star.style.height = '2px';
            document.querySelector('.stars-container').appendChild(star);
            star.style.animation = `shoot ${0.8 + Math.random() * 0.5}s linear forwards`;
            setTimeout(() => star.remove(), 1500);
        }
        setInterval(() => { if (Math.random() < 0.5) createShootingStar(); }, 4000);

        // Dark Mode
        const toggle = document.getElementById('darkModeToggle');
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            toggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
        }
        toggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
                toggle.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
            } else {
                localStorage.setItem('darkMode', 'disabled');
                toggle.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
            }
        });
    </script>
</body>

</html>