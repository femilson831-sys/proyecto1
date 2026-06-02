<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $cholet->nombre }} | JACHA TOURS - Circuito de Cholets</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Cormorant+Garamond:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Swiper -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <style>
        /* ========== VARIABLES - TEMA NEGRO Y DORADO ========== */
        :root {
            --black: #0a0a0a;
            --black-light: #111111;
            --black-medium: #1a1a1a;
            --black-lighter: #222222;
            --gold: #c9a84c;
            --gold-light: #e4c46c;
            --gold-dark: #a8893a;
            --gold-pale: #d4b85c;
            --white: #ffffff;
            --white-soft: #f5f5f5;
            --white-muted: #999999;
            --white-dim: #666666;
            --border-subtle: rgba(201, 168, 76, 0.15);
            --border-medium: rgba(201, 168, 76, 0.3);
            --border-light: rgba(201, 168, 76, 0.5);
            --overlay-dark: rgba(0, 0, 0, 0.85);
            --serif: 'Cormorant Garamond', Georgia, serif;
            --sans: 'DM Sans', sans-serif;
            --ease: cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--black);
            color: var(--white-soft);
            font-family: var(--sans);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
        }
        ::-webkit-scrollbar-track {
            background: var(--black-medium);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 20px;
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 9000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            background: var(--black-lighter);
            color: var(--white-soft);
            padding: 14px 24px;
            border-radius: 4px;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInR 0.5s var(--ease-out), fadeOut 0.4s ease 3s forwards;
            min-width: 260px;
            border-left: 3px solid var(--gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }
        .toast i { color: var(--gold); }
        @keyframes slideInR {
            from { transform: translateX(110%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(20px); }
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 800;
            padding: 28px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.6s var(--ease);
            background: transparent;
        }
        .header.scrolled {
            padding: 18px 6%;
            background: rgba(10, 10, 10, 0.98);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-subtle);
        }
        .header__logo img {
            width: 160px;
            transition: 0.4s var(--ease);
            filter: brightness(1.2);
        }
        .header__nav {
            display: flex;
            align-items: center;
            gap: 40px;
        }
        .nav-link {
            font-family: var(--sans);
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s;
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--gold);
            transition: width 0.4s var(--ease);
        }
        .nav-link:hover { color: var(--gold-light); }
        .nav-link:hover::after { width: 100%; }

        .btn-login {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--gold);
            border: 1px solid var(--border-light);
            padding: 10px 24px;
            border-radius: 2px;
            transition: all 0.35s var(--ease);
            background: transparent;
            cursor: pointer;
        }
        .btn-login:hover {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--black);
            box-shadow: 0 0 25px rgba(201, 168, 76, 0.3);
        }

        /* Hero */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 700px;
            overflow: hidden;
            background: var(--black);
        }
        .hero-swiper {
            width: 100%;
            height: 100%;
        }
        .hero-swiper .swiper-slide img,
        .hero-swiper .swiper-slide video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: linear-gradient(160deg, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0.5) 40%, rgba(10,10,10,0.95) 100%);
        }
        .hero-content {
            position: absolute;
            z-index: 10;
            bottom: 12%;
            left: 6%;
            max-width: 720px;
        }
        .hero-eyebrow {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 22px;
        }
        .hero-eyebrow .rule {
            width: 40px;
            height: 1px;
            background: var(--gold);
        }
        .hero-eyebrow span {
            font-family: var(--sans);
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--gold-light);
        }
        .hero-title {
            font-family: var(--serif);
            font-size: clamp(3.5rem, 7vw, 6.5rem);
            font-weight: 300;
            line-height: 0.95;
            letter-spacing: -0.02em;
            color: var(--white);
            margin-bottom: 28px;
        }
        .hero-title em {
            font-style: italic;
            font-weight: 400;
            color: var(--gold);
        }
        .hero-sub {
            font-family: var(--sans);
            font-size: 0.95rem;
            font-weight: 300;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.6);
            max-width: 480px;
            margin-bottom: 42px;
        }
        .hero-actions {
            display: flex;
            gap: 18px;
            align-items: center;
        }
        .btn-primary-hero {
            font-family: var(--sans);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            background: var(--gold);
            color: var(--black);
            padding: 15px 36px;
            border-radius: 2px;
            border: none;
            cursor: pointer;
            transition: all 0.4s var(--ease);
        }
        .btn-primary-hero:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 14px 40px rgba(201, 168, 76, 0.5);
        }
        .btn-ghost-hero {
            font-family: var(--sans);
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-ghost-hero:hover { color: var(--gold-light); }

        /* Sections */
        .section {
            padding: 110px 6%;
            background: var(--black);
        }
        .container {
            max-width: 1360px;
            margin: auto;
        }
        .eyebrow {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }
        .eyebrow .rule {
            width: 32px;
            height: 1px;
            background: var(--gold);
        }
        .eyebrow span {
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: var(--gold);
        }
        .section-title {
            font-family: var(--serif);
            font-size: clamp(2.6rem, 4.5vw, 4.2rem);
            font-weight: 300;
            line-height: 1.05;
            margin-bottom: 60px;
            color: var(--white);
        }
        .section-title em {
            font-style: italic;
            color: var(--gold);
        }

        /* Stats */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid var(--border-subtle);
            border-bottom: 1px solid var(--border-subtle);
            margin-top: 70px;
        }
        .stat-item {
            padding: 40px 36px;
            border-right: 1px solid var(--border-subtle);
            transition: background 0.3s;
        }
        .stat-item:last-child { border-right: none; }
        .stat-item.clickable { cursor: pointer; }
        .stat-item.clickable:hover { background: rgba(201, 168, 76, 0.05); }
        .stat-label {
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--white-dim);
            margin-bottom: 10px;
        }
        .stat-value {
            font-family: var(--serif);
            font-size: 1.5rem;
            font-weight: 400;
            color: var(--white);
        }
        .stat-cta {
            margin-top: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.66rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
        }

        .map-wrapper {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.8s var(--ease-out);
        }
        .map-wrapper.open { max-height: 460px; }
        .map-inner {
            margin-top: 2px;
            height: 420px;
            position: relative;
            border: 1px solid var(--border-subtle);
        }
        .map-inner iframe {
            width: 100%;
            height: 100%;
            border: none;
            filter: grayscale(30%);
        }
        .map-close {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 5;
            width: 32px;
            height: 32px;
            background: var(--black);
            color: var(--gold);
            border: 1px solid var(--border-light);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Gallery */
        .gallery-masonry {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-auto-rows: 90px;
            gap: 10px;
            margin-top: 60px;
        }
        .g-item {
            overflow: hidden;
            cursor: pointer;
            position: relative;
            background: var(--black-lighter);
            border: 1px solid var(--border-subtle);
        }
        .g-item:nth-child(1) { grid-column: span 7; grid-row: span 4; }
        .g-item:nth-child(2) { grid-column: span 5; grid-row: span 2; }
        .g-item:nth-child(3) { grid-column: span 5; grid-row: span 2; }
        .g-item:nth-child(4) { grid-column: span 4; grid-row: span 3; }
        .g-item:nth-child(5) { grid-column: span 4; grid-row: span 3; }
        .g-item:nth-child(6) { grid-column: span 4; grid-row: span 3; }
        .g-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s var(--ease);
        }
        .g-item:hover img { transform: scale(1.06); }
        .g-item-overlay {
            position: absolute;
            inset: 0;
            background: rgba(201, 168, 76, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.4s;
        }
        .g-item:hover .g-item-overlay { opacity: 1; }

        /* Amenities */
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: rgba(201, 168, 76, 0.08);
            margin-top: 60px;
        }
        .amenity {
            background: var(--black-medium);
            padding: 44px 32px;
            border-top: 1px solid rgba(201, 168, 76, 0.08);
            transition: all 0.4s;
            position: relative;
        }
        .amenity::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gold);
            transition: width 0.5s var(--ease);
        }
        .amenity:hover::before { width: 100%; }
        .amenity-num {
            font-family: var(--serif);
            font-size: 0.75rem;
            color: var(--gold);
            margin-bottom: 28px;
        }
        .amenity-icon { font-size: 1.6rem; color: var(--gold-pale); margin-bottom: 18px; }
        .amenity-title {
            font-family: var(--serif);
            font-size: 1.3rem;
            font-weight: 400;
            color: var(--white);
            margin-bottom: 10px;
        }
        .amenity-desc {
            font-size: 0.82rem;
            font-weight: 300;
            line-height: 1.7;
            color: var(--white-muted);
        }

        /* Rating */
        .rating-wrap {
            max-width: 680px;
            margin: 0 auto;
            text-align: center;
            padding-top: 30px;
        }
        .stars-row {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 36px 0 20px;
        }
        .star-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 2.2rem;
            color: rgba(255, 255, 255, 0.1);
            transition: transform 0.25s;
        }
        .star-btn.lit i { color: var(--gold); }
        .btn-rate {
            font-family: var(--sans);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            background: var(--gold);
            color: var(--black);
            padding: 15px 40px;
            border: none;
            border-radius: 2px;
            cursor: pointer;
        }

        /* Modal RESERVA con horarios */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(8px);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s;
        }
        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-box {
            background: var(--black-lighter);
            width: 100%;
            max-width: 550px;
            padding: 40px 40px;
            position: relative;
            transform: translateY(28px);
            transition: transform 0.5s var(--ease-out);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
        }
        .modal-overlay.active .modal-box { transform: translateY(0); }
        .modal-close {
            position: absolute;
            top: 18px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: var(--white-dim);
        }
        .modal-close:hover { color: var(--gold); }
        .modal-title {
            font-family: var(--serif);
            font-size: 1.8rem;
            font-weight: 300;
            color: var(--white);
            margin-bottom: 25px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--white-dim);
            margin-bottom: 8px;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 12px 16px;
            background: var(--black);
            border: 1px solid var(--border-subtle);
            color: var(--white);
            font-family: var(--sans);
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s;
            border-radius: 8px;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 15px rgba(201, 168, 76, 0.2);
        }
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23c9a84c' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
        }
        .btn-submit {
            width: 100%;
            margin-top: 10px;
            padding: 14px;
            background: var(--gold);
            color: var(--black);
            border: none;
            cursor: pointer;
            font-family: var(--sans);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            border-radius: 8px;
            transition: all 0.35s;
        }
        .btn-submit:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
        }

        .cupos-info {
            font-size: 0.7rem;
            margin-top: 5px;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .cupos-disponible { background: rgba(16, 185, 129, 0.15); color: #10b981; }
        .cupos-limitado { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .cupos-agotado { background: rgba(239, 68, 68, 0.15); color: #ef4444; }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: var(--gold);
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .cta-band {
            padding: 100px 6%;
            background: var(--black);
            position: relative;
        }
        .cta-inner {
            max-width: 1360px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
        }
        .btn-reserva {
            flex-shrink: 0;
            font-family: var(--sans);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            background: var(--gold);
            color: var(--black);
            padding: 20px 52px;
            border: none;
            border-radius: 2px;
            cursor: pointer;
        }
        .footer {
            padding: 50px 6%;
            background: var(--black);
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-brand {
            font-family: var(--serif);
            font-size: 1.4rem;
            font-weight: 400;
            color: var(--white);
        }
        .footer-brand em { color: var(--gold); font-style: italic; }

        @media (max-width: 900px) {
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .amenities-grid { grid-template-columns: repeat(2, 1fr); }
            .cta-inner { flex-direction: column; text-align: center; }
            .gallery-masonry { grid-template-columns: repeat(2, 1fr); grid-auto-rows: 200px; }
            .g-item { grid-column: span 1 !important; grid-row: span 1 !important; }
        }
        @media (max-width: 600px) {
            .stats-bar { grid-template-columns: 1fr; }
            .amenities-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="toast-container" id="toastContainer"></div>

<!-- HEADER -->
<header class="header" id="header">
    <a href="{{ url('/') }}" class="header__logo">
        <img src="{{ asset('img/logo.png') }}" alt="JACHA TOURS">
    </a>
    <nav class="header__nav">
        <a href="{{ url('/#inicio') }}" class="nav-link">Inicio</a>
        <a href="{{ url('/#circuito') }}" class="nav-link">Circuito</a>
        <a href="{{ url('/#experiencias') }}" class="nav-link">Experiencias</a>
        <a href="{{ url('/#contacto') }}" class="nav-link">Contacto</a>
    </nav>
    @auth
        <a href="{{ route('dashboard') }}" class="btn-login">
            <i class="fas fa-user"></i> {{ Auth::user()->name ?? Auth::user()->nombre ?? 'Mi Cuenta' }}
        </a>
    @else
        <a href="{{ route('login') }}" class="btn-login">
            <i class="fas fa-user"></i> Iniciar Sesión
        </a>
    @endauth
</header>

<!-- HERO -->
<div class="hero">
    <div class="swiper hero-swiper" id="heroSwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <img src="{{ asset('storage/cholets/' . $cholet->imagen) }}" alt="{{ $cholet->nombre }}">
            </div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="rule"></div>
            <span>Experiencia Exclusiva · El Alto, Bolivia</span>
        </div>
        <h1 class="hero-title">{{ $cholet->nombre }}</h1>
        <p class="hero-sub">{{ Str::limit($cholet->descripcion, 160) }}</p>
        <div class="hero-actions">
            <button class="btn-primary-hero" onclick="scrollToReserva()">Reservar Ahora →</button>
            <button class="btn-ghost-hero" onclick="document.getElementById('galeria').scrollIntoView({behavior:'smooth'})">
                <i class="fas fa-images"></i> Ver Galería
            </button>
        </div>
    </div>
</div>

<!-- INFO SECTION -->
<section class="section" style="padding-top:80px;padding-bottom:0;">
    <div class="container">
        <div class="eyebrow reveal">
            <div class="rule"></div>
            <span>Información</span>
        </div>
        <h2 class="section-title reveal reveal-delay-1">Detalles de la<br><em>Experiencia</em></h2>

        <div class="stats-bar reveal">
            <div class="stat-item clickable" onclick="toggleMap()" id="mapTrigger">
                <div class="stat-label">Ubicación</div>
                <div class="stat-value">{{ $cholet->ubicacion ?? 'El Alto, Bolivia' }}</div>
                <div class="stat-cta"><span>Ver en mapa</span> <i class="fas fa-chevron-down" id="mapArrow"></i></div>
            </div>
            <div class="stat-item"><div class="stat-label">Duración</div><div class="stat-value">4 – 6 horas</div></div>
            <div class="stat-item"><div class="stat-label">Capacidad</div><div class="stat-value">Hasta 20 personas</div></div>
            <div class="stat-item">
                <div class="stat-label">Valoración</div>
                <div class="stat-value" id="averageRating">{{ number_format($cholet->ratings()->avg('rating') ?? 4.9, 1) }} ★</div>
            </div>
        </div>

        <div class="map-wrapper" id="mapWrapper">
            <div class="map-inner">
                <button class="map-close" onclick="toggleMap()"><i class="fas fa-times"></i></button>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.5!2d-68.2!3d-16.5!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTbCsDMwJzAwLjAiUyA2OMKwMTInMDAuMCJX!5e0!3m2!1ses!2sbo!4v1" allowfullscreen loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>

<!-- GALLERY -->
<section class="section" id="galeria">
    <div class="container">
        <div class="eyebrow reveal"><div class="rule"></div><span>Galería</span></div>
        <h2 class="section-title reveal reveal-delay-1">Explora<br><em>Visualmente</em></h2>
        <div class="gallery-masonry">
            @for($i = 1; $i <= 6; $i++)
            <div class="g-item reveal" onclick="openLightbox('{{ asset('storage/cholets/' . $cholet->imagen) }}')">
                <img src="{{ asset('storage/cholets/' . $cholet->imagen) }}" alt="{{ $cholet->nombre }}">
                <div class="g-item-overlay"><i class="fas fa-expand-alt"></i></div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- AMENITIES -->
<section class="section amenities-section">
    <div class="container">
        <div class="eyebrow reveal"><div class="rule"></div><span>Servicios Incluidos</span></div>
        <h2 class="section-title reveal reveal-delay-1">Servicios<br><em>Premium</em></h2>
        <div class="amenities-grid">
            <div class="amenity reveal"><div class="amenity-num">01</div><div class="amenity-icon"><i class="fas fa-camera-retro"></i></div><div class="amenity-title">Fotografía Profesional</div><div class="amenity-desc">Sesión completa durante el tour</div></div>
            <div class="amenity reveal reveal-delay-1"><div class="amenity-num">02</div><div class="amenity-icon"><i class="fas fa-language"></i></div><div class="amenity-title">Guía Trilingüe</div><div class="amenity-desc">Español, inglés y aymara</div></div>
            <div class="amenity reveal reveal-delay-2"><div class="amenity-num">03</div><div class="amenity-icon"><i class="fas fa-wine-glass-alt"></i></div><div class="amenity-title">Degustación Gourmet</div><div class="amenity-desc">Bebidas premium y aperitivos</div></div>
            <div class="amenity reveal reveal-delay-3"><div class="amenity-num">04</div><div class="amenity-icon"><i class="fas fa-car"></i></div><div class="amenity-title">Transporte Ejecutivo</div><div class="amenity-desc">Recojo y retorno VIP</div></div>
        </div>
    </div>
</section>

<!-- RATING -->
<section class="section rating-section">
    <div class="container">
        <div class="rating-wrap">
            <div class="eyebrow reveal" style="justify-content:center;"><div class="rule"></div><span>Tu Opinión</span><div class="rule"></div></div>
            <h2 class="section-title reveal reveal-delay-1" style="text-align:center;">¿Qué te<br><em>pareció?</em></h2>
            <div class="stars-row" id="starsRow">
                <button class="star-btn" data-rating="1"><i class="fa-regular fa-star"></i></button>
                <button class="star-btn" data-rating="2"><i class="fa-regular fa-star"></i></button>
                <button class="star-btn" data-rating="3"><i class="fa-regular fa-star"></i></button>
                <button class="star-btn" data-rating="4"><i class="fa-regular fa-star"></i></button>
                <button class="star-btn" data-rating="5"><i class="fa-regular fa-star"></i></button>
            </div>
            <div class="rating-msg" id="ratingMsg"></div>
            @auth
                <button class="btn-rate" onclick="submitRating({{ $cholet->id }})">Enviar Valoración</button>
            @else
                <p><a href="{{ route('login') }}" style="color:var(--gold);">Inicia sesión</a> para calificar</p>
            @endauth
        </div>
    </div>
</section>

<!-- CTA RESERVA -->
<div class="cta-band" id="reservaSection">
    <div class="cta-inner container">
        <div class="cta-copy">
            <div class="eyebrow"><div class="rule"></div><span>Disponibilidad inmediata</span></div>
            <div class="cta-title">Reserva tu<br><em>experiencia</em></div>
        </div>
        @auth
            <button class="btn-reserva" onclick="openModal()">Reservar Ahora</button>
        @else
            <a href="{{ route('login') }}"><button class="btn-reserva">Iniciar Sesión</button></a>
        @endauth
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-brand">JACHA <em>TOURS</em></div>
    <div class="footer-copy">© 2026 · Experiencias Premium en El Alto, Bolivia</div>
</footer>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.97); z-index:9500; align-items:center; justify-content:center;">
    <span class="lightbox-close" style="position:absolute; top:28px; right:32px; color:var(--gold); font-size:1.8rem; cursor:pointer;">&times;</span>
    <img src="" id="lightboxImg" style="max-width:90vw; max-height:90vh; object-fit:contain;">
</div>

<!-- MODAL RESERVA CON HORARIOS -->
<div class="modal-overlay" id="modalReserva">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="modal-title">Reservar Experiencia</div>
        
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
            @csrf
            <input type="hidden" name="cholet_id" value="{{ $cholet->id }}">
            <input type="hidden" name="hora_reserva" id="hora_reserva_hidden">
            
            <div class="form-group">
                <label class="form-label">📅 Fecha de visita</label>
                <input type="date" name="fecha" class="form-input" id="fechaReserva" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
            </div>

            <div class="form-group" id="horarioGroup" style="display:none;">
                <label class="form-label">⏰ Horario disponible</label>
                <select name="horario_id" id="horarioSelect" class="form-select" required>
                    <option value="">Selecciona una fecha primero</option>
                </select>
                <div id="cuposInfo" class="cupos-info" style="margin-top:8px;"></div>
            </div>

            <div class="form-group">
                <label class="form-label">🎫 Tipo de experiencia</label>
                <select name="experiencia" id="experienciaSelect" class="form-select" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Blue">🔵 Blue - Bs49 </option>
                    <option value="Gold">🟡 Gold - Bs89 </option>
                    <option value="Platinum">🟣 Platinum - Bs149 </option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">👥 Número de personas</label>
                <input type="number" name="personas" id="personasInput" class="form-input" min="1" max="20" value="2" required>
                <div id="totalPrecio" style="font-size:12px; color:var(--gold); margin-top:8px;"></div>
            </div>

            <div class="form-group">
                <label class="form-label">📞 WhatsApp de contacto</label>
                <input type="tel" name="telefono" class="form-input" placeholder="+591 77777777" required>
            </div>

            <button type="submit" class="btn-submit">Confirmar Reserva</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true, offset: 50 });

    // Header scroll
    window.addEventListener('scroll', () => {
        document.getElementById('header').classList.toggle('scrolled', window.scrollY > 60);
    });

    // Hero Swiper
    new Swiper('#heroSwiper', {
        slidesPerView: 1, loop: true, speed: 900, effect: 'fade', fadeEffect: { crossFade: true },
        autoplay: { delay: 5500, disableOnInteraction: false },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        pagination: { el: '.swiper-pagination', clickable: true }
    });

    // Map toggle
    let mapOpen = false;
    function toggleMap() {
        mapOpen = !mapOpen;
        document.getElementById('mapWrapper').classList.toggle('open', mapOpen);
        document.getElementById('mapArrow').style.transform = mapOpen ? 'rotate(180deg)' : '';
    }

    // Lightbox
    function openLightbox(src) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Modal Reserva
    function openModal() { document.getElementById('modalReserva').classList.add('active'); document.body.style.overflow = 'hidden'; }
    function closeModal() { document.getElementById('modalReserva').classList.remove('active'); document.body.style.overflow = ''; }

    document.getElementById('modalReserva').addEventListener('click', (e) => { if (e.target === e.currentTarget) closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeLightbox(); closeModal(); } });

    function scrollToReserva() { document.getElementById('reservaSection').scrollIntoView({ behavior: 'smooth' }); }

    // ========== HORARIOS DISPONIBLES ==========
    const fechaInput = document.getElementById('fechaReserva');
    const horarioGroup = document.getElementById('horarioGroup');
    const horarioSelect = document.getElementById('horarioSelect');
    const horaReservaHidden = document.getElementById('hora_reserva_hidden');
    const cuposInfo = document.getElementById('cuposInfo');
    const personasInput = document.getElementById('personasInput');
    const experienciaSelect = document.getElementById('experienciaSelect');
    const totalPrecio = document.getElementById('totalPrecio');

    const PRECIOS = { Blue: 49, Gold: 89, Platinum: 149 };

    function actualizarPrecioTotal() {
        const experiencia = experienciaSelect.value;
        const personas = parseInt(personasInput.value) || 0;
        if (experiencia && PRECIOS[experiencia] && personas > 0) {
            const total = PRECIOS[experiencia] * personas;
            totalPrecio.innerHTML = `💰 Total: $${total} USD (Bs ${(total * 6.96).toFixed(2)})`;
        } else {
            totalPrecio.innerHTML = '';
        }
    }

    personasInput.addEventListener('input', actualizarPrecioTotal);
    experienciaSelect.addEventListener('change', actualizarPrecioTotal);
    actualizarPrecioTotal();

    // Cargar horarios al seleccionar fecha
    fechaInput.addEventListener('change', function() {
        const fecha = this.value;
        if (!fecha) {
            horarioGroup.style.display = 'none';
            return;
        }

        horarioSelect.innerHTML = '<option value="">⏳ Cargando horarios...</option>';
        horarioGroup.style.display = 'block';
        cuposInfo.innerHTML = '<span class="loading-spinner"></span> Buscando horarios...';

        fetch(`/api/horarios-disponibles?cholet_id={{ $cholet->id }}&fecha=${fecha}`)
            .then(response => response.json())
            .then(horarios => {
                horarioSelect.innerHTML = '<option value="">-- Selecciona un horario --</option>';
                
                if (!horarios || horarios.length === 0) {
                    horarioSelect.innerHTML = '<option value="">❌ No hay horarios disponibles</option>';
                    cuposInfo.innerHTML = '⚠️ No hay cupos disponibles para esta fecha. Prueba con otra fecha.';
                    cuposInfo.className = 'cupos-info cupos-agotado';
                    return;
                }

                horarios.forEach(horario => {
                    const option = document.createElement('option');
                    option.value = horario.id;
                    let icono = '🕐';
                    if (horario.hora_inicio < '12:00') icono = '🌅';
                    else if (horario.hora_inicio < '18:00') icono = '☀️';
                    else icono = '🌙';
                    
                    option.textContent = `${icono} ${horario.hora_inicio} - ${horario.hora_fin} (${horario.cupos_disponibles} cupos)`;
                    option.dataset.hora = horario.hora_inicio;
                    option.dataset.cupos = horario.cupos_disponibles;
                    horarioSelect.appendChild(option);
                });

                cuposInfo.innerHTML = `✅ ${horarios.length} horario(s) disponible(s) para el ${fecha}`;
                cuposInfo.className = 'cupos-info cupos-disponible';
            })
            .catch(error => {
                console.error('Error:', error);
                horarioSelect.innerHTML = '<option value="">⚠️ Error al cargar horarios</option>';
                cuposInfo.innerHTML = '❌ Error de conexión. Intenta de nuevo.';
                cuposInfo.className = 'cupos-info cupos-agotado';
            });
    });

    // Al seleccionar horario
    horarioSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.hora) {
            horaReservaHidden.value = selectedOption.dataset.hora;
            const cuposDisponibles = parseInt(selectedOption.dataset.cupos) || 0;
            const personas = parseInt(personasInput.value) || 1;
            
            if (personas > cuposDisponibles) {
                cuposInfo.innerHTML = `⚠️ Solo hay ${cuposDisponibles} cupos disponibles. Reduce a ${cuposDisponibles} personas máximo.`;
                cuposInfo.className = 'cupos-info cupos-agotado';
            } else if (cuposDisponibles <= 5) {
                cuposInfo.innerHTML = `🎉 ¡Últimos ${cuposDisponibles} cupos! Reserva pronto.`;
                cuposInfo.className = 'cupos-info cupos-limitado';
            } else {
                cuposInfo.innerHTML = `✅ ${cuposDisponibles} cupos disponibles. ¡Puedes reservar!`;
                cuposInfo.className = 'cupos-info cupos-disponible';
            }
        }
    });

    // Validar personas contra cupos
    personasInput.addEventListener('input', function() {
        const selectedOption = horarioSelect.options[horarioSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.cupos) {
            const cuposDisponibles = parseInt(selectedOption.dataset.cupos);
            const personas = parseInt(this.value) || 1;
            
            if (personas > cuposDisponibles) {
                cuposInfo.innerHTML = `⚠️ Solo hay ${cuposDisponibles} cupos disponibles. Reduce a ${cuposDisponibles} personas máximo.`;
                cuposInfo.className = 'cupos-info cupos-agotado';
            } else if (cuposDisponibles <= 5) {
                cuposInfo.innerHTML = `🎉 ¡Últimos ${cuposDisponibles} cupos!`;
                cuposInfo.className = 'cupos-info cupos-limitado';
            } else {
                cuposInfo.innerHTML = `✅ ${cuposDisponibles} cupos disponibles. ¡Puedes reservar!`;
                cuposInfo.className = 'cupos-info cupos-disponible';
            }
        }
        actualizarPrecioTotal();
    });

    // Validar formulario antes de enviar
    document.getElementById('reservaForm').addEventListener('submit', function(e) {
        if (!fechaInput.value) {
            e.preventDefault();
            alert('❌ Por favor selecciona una fecha');
            return false;
        }
        
        if (!horarioSelect.value || horarioSelect.value === '') {
            e.preventDefault();
            alert('❌ Por favor selecciona un horario disponible');
            return false;
        }
        
        if (!experienciaSelect.value) {
            e.preventDefault();
            alert('❌ Por favor selecciona un tipo de experiencia');
            return false;
        }
        
        const personas = parseInt(personasInput.value) || 0;
        if (personas < 1 || personas > 20) {
            e.preventDefault();
            alert('❌ El número de personas debe ser entre 1 y 20');
            return false;
        }
        
        const telefono = document.querySelector('[name=telefono]').value;
        if (!telefono || telefono.length < 8) {
            e.preventDefault();
            alert('❌ Ingresa un número de WhatsApp válido (mínimo 8 dígitos)');
            return false;
        }
        
        const btn = this.querySelector('.btn-submit');
        btn.innerHTML = '<span class="loading-spinner"></span> Procesando...';
        btn.disabled = true;
    });

    // Rating
    let selectedRating = 0;
    const stars = document.querySelectorAll('.star-btn');
    const ratingMsg = document.getElementById('ratingMsg');

    function updateStars(ratingValue) {
        stars.forEach((star, index) => {
            const icon = star.querySelector('i');
            if (index < ratingValue) {
                icon.className = 'fa-solid fa-star';
                icon.style.color = 'var(--gold)';
            } else {
                icon.className = 'fa-regular fa-star';
                icon.style.color = 'rgba(255,255,255,0.1)';
            }
        });
    }

    stars.forEach(star => {
        star.addEventListener('mouseenter', () => updateStars(parseInt(star.dataset.rating)));
        star.addEventListener('mouseleave', () => updateStars(selectedRating));
        star.addEventListener('click', () => {
            selectedRating = parseInt(star.dataset.rating);
            updateStars(selectedRating);
            ratingMsg.textContent = `⭐ Calificación: ${selectedRating} estrellas`;
        });
    });

    function submitRating(choletId) {
        if (!selectedRating) { ratingMsg.textContent = 'Selecciona una calificación'; return; }
        fetch('{{ route("rating.store") }}', {
            method: 'POST', 
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ cholet_id: choletId, rating: selectedRating })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                ratingMsg.innerHTML = '✅ ¡Gracias por tu valoración!';
                document.querySelector('.btn-rate').style.display = 'none';
                if (data.average) document.getElementById('averageRating').innerHTML = data.average.toFixed(1) + ' ★';
            }
        })
        .catch(() => ratingMsg.innerHTML = '❌ Error al enviar');
    }

    function showToast(msg, icon = 'fa-check-circle') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `<i class="fas ${icon}"></i><span>${msg}</span>`;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3400);
    }

    @if(session('success')) showToast('{{ session('success') }}'); @endif
    @if(session('error')) showToast('{{ session('error') }}', 'fa-exclamation-triangle'); @endif
</script>
</body>
</html>