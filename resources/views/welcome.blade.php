<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JACHA TOURS — Circuito de Cholets</title>

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cormorant+Garamond:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* ========== HEADER ========== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid rgba(201, 168, 76, 0.2);
            transition: all 0.3s;
        }

        .header.scrolled {
            padding: 0.7rem 2rem;
            background: rgba(10, 10, 10, 0.98);
        }

        .header__logo img {
            height: 45px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .header__logo img:hover {
            transform: scale(1.05);
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 5px;
            z-index: 1001;
        }

        .hamburger span {
            width: 25px;
            height: 2px;
            background: #C9A84C;
            transition: 0.3s;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .header__nav {
            display: flex;
            gap: 2rem;
        }

        .nav-link {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #C9A84C;
            transition: width 0.3s;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #C9A84C;
        }

        .header-user {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1002;
        }

        .btn-login {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #c9a84c;
            color: #000;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            transition: .3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            background: #e4c46c;
        }

        .user-box {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            padding: 8px 14px;
            border-radius: 50px;
            cursor: pointer;
            transition: .3s;
        }

        .user-box:hover {
            border-color: #c9a84c;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #c9a84c;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
        }

        .user-email {
            color: rgba(255,255,255,.55);
            font-size: .65rem;
        }

        .user-dropdown {
            position: absolute;
            top: 60px;
            right: 0;
            width: 220px;
            background: #111;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: .3s;
            z-index: 1000;
        }

        .user-box:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown a,
        .user-dropdown button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            background: none;
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: .85rem;
            transition: .3s;
        }

        .user-dropdown a:hover,
        .user-dropdown button:hover {
            background: rgba(201,168,76,.12);
            color: #c9a84c;
        }

        /* ========== HERO ========== */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 600px;
            overflow: hidden;
        }

        .hero__video-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .hero__video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero__overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);
        }

        .hero__content {
            position: absolute;
            bottom: 15%;
            left: 10%;
            max-width: 700px;
            z-index: 2;
        }

        .hero__title {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .title-light {
            font-weight: 300;
            display: block;
        }

        .title-bold {
            font-weight: 800;
            display: block;
        }

        .title-gold {
            color: #C9A84C;
            display: block;
        }

        .hero__description {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn--primary {
            background: #C9A84C;
            color: #000;
        }

        .btn--primary:hover {
            transform: translateY(-3px);
            background: #e4c46c;
            box-shadow: 0 10px 25px rgba(201,168,76,0.3);
        }

        /* ========== SECCIONES ========== */
        .section-block {
            padding: 5rem 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-subtitle {
            color: #C9A84C;
            font-size: 0.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1rem;
        }

        .section-title {
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-description {
            color: rgba(255,255,255,0.7);
            max-width: 600px;
            margin: 0 auto;
        }

        /* ========== FONDOS DE IMAGEN ========== */
        .circuito-section {
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('{{ asset("img/ft.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .experiencias-section {
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('{{ asset("img/ft.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .mis-reservas-section {
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('{{ asset("img/ft.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .contacto-profesional {
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('{{ asset("img/ft.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* ========== CHOLETS GRID ========== */
        .cholets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .cholet-card {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            display: block;
            text-decoration: none;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            transition: all 0.4s ease;
            border: 1px solid rgba(255,255,255,0.08);
            cursor: pointer;
        }

        .cholet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            border-color: rgba(201,168,76,0.4);
            background: rgba(0,0,0,0.8);
        }

        .cholet-card .cholet-image {
            width: 100%;
            height: 280px;
            overflow: hidden;
        }

        .cholet-card .cholet-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .cholet-card:hover .cholet-image img {
            transform: scale(1.08);
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.3) 60%, transparent 100%);
            pointer-events: none;
        }

        .cholet-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            z-index: 2;
        }

        .cholet-tag {
            display: inline-block;
            background: rgba(201,168,76,0.9);
            color: #000;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 8px;
        }

        .cholet-content h3 {
            color: #fff;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .cholet-content p {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
            line-height: 1.4;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .cholet-card:hover .cholet-content p {
            max-height: 80px;
            opacity: 1;
            margin-top: 8px;
        }

        /* ========== EXPERIENCIAS ========== */
        .experiencias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .exp-card {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            padding: 2rem;
            border-radius: 18px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .exp-card:hover {
            background: rgba(201,168,76,0.2);
            transform: translateY(-10px);
            border-color: rgba(201,168,76,0.4);
        }

        .exp-card i {
            font-size: 3rem;
            color: #C9A84C;
            margin-bottom: 1rem;
        }

        .exp-card h3 {
            color: #fff;
            margin-bottom: 1rem;
        }

        .exp-card p {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .exp-price {
            display: inline-block;
            color: #C9A84C;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* ========== MIS RESERVAS ========== */
        .reservas-lista {
            max-width: 1000px;
            margin: 0 auto;
        }

        .reserva-item {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 16px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .reserva-item:hover {
            border-color: rgba(201,168,76,0.5);
            transform: translateX(5px);
            background: rgba(0,0,0,0.8);
        }

        .reserva-info {
            flex: 1;
        }

        .reserva-info h4 {
            color: #C9A84C;
            margin-bottom: 0.3rem;
        }

        .reserva-info p {
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
        }

        .reserva-codigo {
            font-family: monospace;
            color: #C9A84C;
            font-size: 0.9rem;
        }

        .reserva-estado {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .estado-pendiente { background: #f59e0b; color: #000; }
        .estado-pago_reportado { background: #8b5cf6; color: #fff; }
        .estado-confirmada { background: #10b981; color: #fff; }
        .estado-cancelada { background: #ef4444; color: #fff; }

        .ver-mas-btn {
            background: transparent;
            border: 1px solid #C9A84C;
            color: #C9A84C;
            padding: 8px 20px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            font-size: 0.8rem;
        }

        .ver-mas-btn:hover {
            background: #C9A84C;
            color: #000;
        }

        .empty-reservas {
            text-align: center;
            padding: 3rem;
            color: #ccc;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 20px;
        }

        /* ========== CONTACTO PROFESIONAL ========== */
        .contacto-wrapper {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .contacto-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contacto-card {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .contacto-card:hover {
            transform: translateY(-5px);
            border-color: rgba(201,168,76,0.4);
            background: rgba(0,0,0,0.8);
        }

        .contacto-icon {
            width: 50px;
            height: 50px;
            background: rgba(201,168,76,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .contacto-icon i {
            font-size: 1.5rem;
            color: #C9A84C;
        }

        .contacto-card h3 {
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .contacto-card p {
            color: rgba(255,255,255,0.7);
            margin-bottom: 1rem;
        }

        .contacto-link {
            color: #C9A84C;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .contacto-link:hover {
            color: #e4c46c;
            text-decoration: underline;
        }

        .contacto-formulario {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .contacto-formulario h3 {
            color: #C9A84C;
            margin-bottom: 1.5rem;
        }

        .form-group-contacto {
            margin-bottom: 1rem;
        }

        .form-group-contacto input,
        .form-group-contacto textarea {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.5);
            color: #fff;
            font-family: inherit;
        }

        .form-group-contacto input:focus,
        .form-group-contacto textarea:focus {
            outline: none;
            border-color: #C9A84C;
        }

        .form-group-contacto textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn-enviar {
            width: 100%;
            background: #C9A84C;
            color: #000;
            border: none;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-enviar:hover {
            background: #e4c46c;
            transform: translateY(-2px);
        }

        /* ========== TOAST ========== */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1a1a1a;
            border-left: 4px solid #10b981;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            z-index: 9999;
            animation: slideInRight 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* ========== FOOTER ========== */
        .footer {
            background: #0a0a0a;
            padding: 3rem 2rem 2rem;
            text-align: center;
            border-top: 1px solid rgba(201,168,76,0.2);
        }

        .footer-logo img {
            width: 80px;
            margin-bottom: 1rem;
        }

        .footer-social {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .footer-social a {
            color: #fff;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        .footer-social a:hover {
            color: #C9A84C;
            transform: translateY(-3px);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 900px) {
            .hamburger {
                display: flex;
            }

            .header__nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                background: #0a0a0a;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                transition: 0.3s;
                z-index: 1000;
            }

            .header__nav.open {
                right: 0;
            }

            .hero__title {
                font-size: 2.5rem;
            }

            .hero__content {
                left: 5%;
                right: 5%;
                bottom: 20%;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .cholets-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .contacto-wrapper {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .reserva-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<!-- HEADER -->
<header class="header" id="header">
    <div class="header__logo" onclick="window.location.href='{{ url('/') }}'">
        <img src="{{ asset('img/logo.png') }}" alt="JACHA TOURS">
    </div>

    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <nav class="header__nav" id="navMenu">
        <a href="#inicio" class="nav-link">Inicio</a>
        <a href="#circuito" class="nav-link">Circuito</a>
        <a href="#experiencias" class="nav-link">Experiencias</a>
        <a href="#mis-reservas" class="nav-link">Mis Reservas</a>
        <a href="#contacto" class="nav-link">Contacto</a>
    </nav>

    <div class="header-user">
        @auth
            <div class="user-box">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nombre ?? Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->nombre ?? Auth::user()->name }}</span>
                    <span class="user-email">{{ Auth::user()->email }}</span>
                </div>
                <div class="user-dropdown">
                    <a href="{{ route('reservas.mis-reservas') }}">
                        <i class="fas fa-calendar-alt"></i> Ver todas mis reservas
                    </a>
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-gear"></i> Mi Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-right-from-bracket"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-user"></i> Iniciar Sesión
            </a>
        @endauth
    </div>
</header>

<!-- HERO SECTION -->
<section class="hero" id="inicio">
    <div class="hero__video-wrapper">
        <video class="hero__video" autoplay muted loop playsinline>
            <source src="{{ asset('video/cholets.mp4') }}" type="video/mp4">
        </video>
        <div class="hero__overlay"></div>
    </div>

    <div class="hero__content" data-aos="fade-up">
        <h1 class="hero__title">
            <span class="title-light">Descubre la</span>
            <span class="title-bold">arquitectura</span>
            <span class="title-gold">ANDINA MODERNA</span>
        </h1>
        <p class="hero__description">
            Explora los Cholets más icónicos de El Alto,
            una experiencia cultural única en Bolivia.
        </p>
        <a href="#circuito" class="btn btn--primary">
            <span>EXPLORAR</span>
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>
</section>

<!-- CIRCUITO - CHOLETS (CORREGIDO) -->
<section class="section-block circuito-section" id="circuito">
    <div class="section-header" data-aos="fade-up">
        <span class="section-subtitle">CIRCUITO ARQUITECTÓNICO</span>
        <h2 class="section-title">Cholets Icónicos de El Alto</h2>
        <p class="section-description">Explora la arquitectura neoandina moderna.</p>
    </div>

    <div class="cholets-grid">
        @forelse($cholets as $cholet)
            <!-- ✅ CORREGIDO: Redirige a la página de detalles del cholet -->
            <div class="cholet-card" data-aos="zoom-in" onclick="window.location.href='{{ route('cholets.show', $cholet->id) }}'">
                <div class="cholet-image">
                    <img src="{{ asset('storage/cholets/' . $cholet->imagen) }}" alt="{{ $cholet->nombre }}">
                </div>
                <div class="card-overlay"></div>
                <div class="cholet-content">
                    <span class="cholet-tag">{{ $cholet->categoria ?? 'Cholet' }}</span>
                    <h3>{{ $cholet->nombre }}</h3>
                    <p>{{ Str::limit($cholet->descripcion, 100) }}</p>
                </div>
            </div>
        @empty
            <p style="color:white; text-align:center;">No hay cholets disponibles.</p>
        @endforelse
    </div>
</section>

<!-- EXPERIENCIAS -->
<section class="section-block experiencias-section" id="experiencias">
    <div class="section-header" data-aos="fade-up">
        <span class="section-subtitle">VIVE LA EXPERIENCIA</span>
        <h2 class="section-title">Experiencias Únicas</h2>
        <p class="section-description">Elige cómo vivir los Cholets.</p>
    </div>

    <div class="experiencias-grid">
        <div class="exp-card" data-aos="flip-left">
            <i class="fas fa-camera"></i>
            <h3>Blue / Silver</h3>
            <p>Visita exterior guiada con fotografía profesional. Acceso a fachadas y exteriores con audioguía.</p>
            <span class="exp-price">Desde Bs 341</span>
        </div>
        <div class="exp-card" data-aos="flip-left" data-aos-delay="100">
            <i class="fas fa-champagne-glasses"></i>
            <h3>Gold</h3>
            <p>Acceso a salones de eventos exclusivos, realidad aumentada, guía cultural y degustación.</p>
            <span class="exp-price">Desde Bs 619</span>
        </div>
        <div class="exp-card" data-aos="flip-left" data-aos-delay="200">
            <i class="fas fa-crown"></i>
            <h3>Platinum</h3>
            <p>Acceso total a penthouses superiores, encuentro con propietarios y servicios VIP personalizados.</p>
            <span class="exp-price">Desde Bs 1,037</span>
        </div>
    </div>
</section>

<!-- MIS RESERVAS -->
<section class="section-block mis-reservas-section" id="mis-reservas">
    <div class="section-header" data-aos="fade-up">
        <span class="section-subtitle">TUS RESERVAS</span>
        <h2 class="section-title">Mis Reservas Activas</h2>
        <p class="section-description">Aquí están tus reservas recientes. Haz clic en "Ver todas" para más detalles.</p>
    </div>

    <div class="reservas-lista">
        @auth
            @php
                $misReservas = App\Models\Reserva::where('user_id', Auth::id())
                            ->whereIn('estado', ['pendiente', 'pago_reportado', 'confirmada'])
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
            @endphp
            
            @if($misReservas->count() > 0)
                @foreach($misReservas as $reserva)
                    <div class="reserva-item" data-aos="fade-up">
                        <div class="reserva-info">
                            <h4>{{ $reserva->cholet->nombre }}</h4>
                            <p>
                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} &nbsp;|&nbsp;
                                <i class="fas fa-users"></i> {{ $reserva->personas }} personas &nbsp;|&nbsp;
                                <i class="fas fa-ticket"></i> {{ $reserva->experiencia }}
                            </p>
                            <div class="reserva-codigo">
                                <i class="fas fa-barcode"></i> {{ $reserva->codigo_reserva }}
                            </div>
                        </div>
                        <div>
                            <span class="reserva-estado estado-{{ $reserva->estado }}">
                                @switch($reserva->estado)
                                    @case('pendiente') Pendiente @break
                                    @case('pago_reportado') Pago Reportado @break
                                    @case('confirmada') Confirmada @break
                                    @default {{ ucfirst($reserva->estado) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                @endforeach
                
                <div style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('reservas.mis-reservas') }}" class="ver-mas-btn">
                        <i class="fas fa-arrow-right"></i> Ver todas mis reservas
                    </a>
                </div>
            @else
                <div class="empty-reservas">
                    <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: 1rem; display: block; color: #C9A84C;"></i>
                    <p>Aún no tienes reservas activas.</p>
                    <a href="#circuito" class="ver-mas-btn" style="margin-top: 1rem; display: inline-block;">
                        <i class="fas fa-search"></i> Explorar Cholets
                    </a>
                </div>
            @endif
        @else
            <div class="empty-reservas">
                <i class="fas fa-lock" style="font-size: 3rem; margin-bottom: 1rem; display: block; color: #C9A84C;"></i>
                <p>Inicia sesión para ver tus reservas.</p>
                <a href="{{ route('login') }}" class="ver-mas-btn" style="margin-top: 1rem; display: inline-block;">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            </div>
        @endauth
    </div>
</section>

<!-- CONTACTO PROFESIONAL -->
<section class="section-block contacto-profesional" id="contacto">
    <div class="section-header" data-aos="fade-up">
        <span class="section-subtitle">CONTACTO</span>
        <h2 class="section-title">¿Listo para la aventura?</h2>
        <p class="section-description">Estamos aquí para ayudarte. Contáctanos de la forma que prefieras.</p>
    </div>

    <div class="contacto-wrapper">
        <div class="contacto-info">
            <div class="contacto-card" data-aos="fade-right">
                <div class="contacto-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>📍 Ubicación</h3>
                <p>El Alto - Bolivia</p>
                <a href="https://maps.google.com/?q=El+Alto+Bolivia" target="_blank" class="contacto-link">
                    Ver ubicación <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="contacto-card" data-aos="fade-right" data-aos-delay="100">
                <div class="contacto-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3>📱 WhatsApp</h3>
                <p>+591 69375465</p>
                <a href="https://wa.me/59169375465" target="_blank" class="contacto-link">
                    Enviar mensaje <i class="fab fa-whatsapp"></i>
                </a>
            </div>

            <div class="contacto-card" data-aos="fade-right" data-aos-delay="200">
                <div class="contacto-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>📧 Email</h3>
                <p>info@jachatours.com</p>
                <a href="mailto:info@jachatours.com" class="contacto-link">
                    info@jachatours.com <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="contacto-card" data-aos="fade-right" data-aos-delay="300">
                <div class="contacto-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>🕒 Horario de atención</h3>
                <p>Lunes a Domingo: 9:00 - 20:00</p>
                <p style="font-size: 0.8rem; margin-top: 0.5rem;">Atención personalizada 24/7 por WhatsApp</p>
            </div>
        </div>

        <div class="contacto-formulario" data-aos="fade-left">
            <h3><i class="fas fa-paper-plane"></i> Envíanos un mensaje</h3>
            <form id="contactoForm" onsubmit="enviarWhatsAppContacto(event)">
                <div class="form-group-contacto">
                    <input type="text" id="contacto_nombre" placeholder="Tu nombre completo" required>
                </div>
                <div class="form-group-contacto">
                    <input type="email" id="contacto_email" placeholder="Tu correo electrónico" required>
                </div>
                <div class="form-group-contacto">
                    <input type="tel" id="contacto_telefono" placeholder="Tu WhatsApp (opcional)">
                </div>
                <div class="form-group-contacto">
                    <textarea id="contacto_mensaje" placeholder="¿En qué podemos ayudarte?" required></textarea>
                </div>
                <button type="submit" class="btn-enviar">
                    <i class="fab fa-whatsapp"></i> Enviar mensaje
                </button>
            </form>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-logo">
        <img src="{{ asset('img/logo.png') }}" alt="JACHA TOURS">
    </div>
    <h3 style="color:#C9A84C; margin:1rem 0;">JACHA TOURS</h3>
    <p style="color:rgba(255,255,255,0.6);">Arquitectura neoandina · El Alto Bolivia</p>
    <div class="footer-social">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-tiktok"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
    </div>
    <span style="color:rgba(255,255,255,0.4); font-size:0.8rem;">© 2026 JACHA TOURS. Todos los derechos reservados.</span>
</footer>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1200, once: true, offset: 100 });

    // Hamburguesa
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    hamburger?.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('open');
    });

    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger?.classList.remove('active');
            navMenu?.classList.remove('open');
        });
    });

    // Header scroll
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Smooth scroll para anclas
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ========== CONTACTO WHATSAPP ==========
    const numeroWhatsApp = "59169375465";

    function enviarWhatsAppContacto(event) {
        event.preventDefault();
        
        const nombre = document.getElementById('contacto_nombre').value;
        const email = document.getElementById('contacto_email').value;
        const telefono = document.getElementById('contacto_telefono').value;
        const mensaje = document.getElementById('contacto_mensaje').value;
        
        const textoWhatsApp = `*🏛️ NUEVO MENSAJE DE CONTACTO - JACHA TOURS* 🏛️
        
*👤 Nombre:* ${nombre}
*📧 Email:* ${email}
*📱 Teléfono:* ${telefono || 'No especificado'}

*💬 Mensaje:*
${mensaje}

---
📅 Enviado desde la página web de JACHA TOURS`;
        
        window.open(`https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(textoWhatsApp)}`, '_blank');
        
        mostrarNotificacion('Mensaje enviado', 'Redirigiendo a WhatsApp...', 'success');
        
        document.getElementById('contactoForm').reset();
    }

    function mostrarNotificacion(titulo, mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        
        let icon = 'fa-check-circle';
        let borderColor = '#10b981';
        
        if (tipo === 'error') {
            icon = 'fa-exclamation-circle';
            borderColor = '#ef4444';
        } else if (tipo === 'warning') {
            icon = 'fa-exclamation-triangle';
            borderColor = '#f59e0b';
        }
        
        toast.style.borderLeftColor = borderColor;
        toast.innerHTML = `
            <i class="fas ${icon}" style="font-size: 1.2rem;"></i>
            <div>
                <strong style="display: block; font-size: 0.85rem;">${titulo}</strong>
                <span style="font-size: 0.75rem;">${mensaje}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 3500);
    }

    @if(session('success'))
        mostrarNotificacion('Éxito', '{{ session('success') }}', 'success');
    @endif
</script>

</body>
</html>