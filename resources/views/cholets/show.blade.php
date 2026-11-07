<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $cholet->nombre }} | Experiencia de Lujo en El Alto | JACHA TOURS</title>
    <meta name="description" content="Descubre {{ $cholet->nombre }}, una experiencia de lujo única en El Alto, Bolivia.">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="{{ $cholet->nombre }} | JACHA TOURS">
    <meta property="og:description" content="{{ Str::limit($cholet->descripcion, 160) }}">
    <meta property="og:image" content="{{ asset('storage/cholets/' . $cholet->imagen) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <style>
        /* ===== VARIABLES ===== */
        :root {
            --black:       #080808;
            --black-2:     #0e0e0e;
            --black-3:     #151515;
            --black-4:     #1c1c1c;
            --black-5:     #252525;
            --gold:        #C9A84C;
            --gold-bright: #E8C96A;
            --gold-muted:  #8A6E30;
            --gold-thin:   rgba(201,168,76,0.18);
            --gold-glow:   rgba(201,168,76,0.08);
            --cream:       #F5EDD8;
            --cream-dim:   #C8BAA0;
            --cream-faint: #887A61;
            --white:       #FFFFFF;
            --font-display: 'DM Serif Display', serif;
            --font-serif:   'Playfair Display', serif;
            --font-sans:    'DM Sans', sans-serif;
            --ease-luxury:  cubic-bezier(0.19, 1, 0.22, 1);
        }

        /* ===== RESET ===== */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--black);
            color: var(--cream-dim);
            font-family: var(--font-sans);
            font-weight: 300;
            line-height: 1.65;
            overflow-x: hidden;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
            opacity: 0.025;
            pointer-events: none;
            z-index: 9998;
        }

        body::before {
            content: '';
            position: fixed;
            top: -40vh;
            left: 50%;
            transform: translateX(-50%);
            width: 120vw;
            height: 80vh;
            background: radial-gradient(ellipse, rgba(201,168,76,0.04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 5%;
            position: relative;
            z-index: 2;
        }

        a { text-decoration: none; color: inherit; }

        /* ===== HEADER ===== */
        .header {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            z-index: 900;
            padding: 28px 5%;
            transition: padding 0.5s var(--ease-luxury), background 0.5s;
        }
        .header--scrolled {
            padding: 16px 5%;
            background: rgba(8,8,8,0.97);
            border-bottom: 1px solid var(--gold-thin);
            backdrop-filter: blur(24px);
        }
        .header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ===== LOGO MÁS GRANDE ===== */
        .logo {
            height: 80px;
            width: auto;
            transition: height 0.4s var(--ease-luxury), filter 0.4s;
            filter: drop-shadow(0 0 15px rgba(201,168,76,0.15));
        }
        .header--scrolled .logo {
            height: 60px;
            filter: drop-shadow(0 0 10px rgba(201,168,76,0.1));
        }

        @media (max-width: 768px) {
            .logo { height: 60px; }
            .header--scrolled .logo { height: 45px; }
        }

        .nav { display: flex; align-items: center; gap: 44px; }
        .nav__link {
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--cream-faint);
            transition: color 0.3s;
            position: relative;
        }
        .nav__link::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0;
            width: 0; height: 1px;
            background: var(--gold);
            transition: width 0.4s var(--ease-luxury);
        }
        .nav__link:hover { color: var(--gold); }
        .nav__link:hover::after { width: 100%; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            transition: all 0.4s var(--ease-luxury);
            position: relative;
            overflow: hidden;
        }
        .btn--gold {
            background: var(--gold);
            color: var(--black);
        }
        .btn--gold::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        .btn--gold:hover::before { transform: translateX(100%); }
        .btn--gold:hover {
            background: var(--gold-bright);
            box-shadow: 0 0 40px rgba(201,168,76,0.3);
            transform: translateY(-2px);
        }
        .btn--ghost {
            background: transparent;
            color: var(--gold);
            border: 1px solid var(--gold-thin);
        }
        .btn--ghost:hover {
            border-color: var(--gold);
            background: var(--gold-glow);
            transform: translateY(-2px);
        }

        /* ===== HERO CON CARRUSEL ===== */
        .hero {
            height: 100svh;
            min-height: 700px;
            position: relative;
            overflow: hidden;
        }

        .hero__swiper {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .hero__slide {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--black-2);
        }

        .hero__slide img,
        .hero__slide video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .hero__slide video {
            background: var(--black);
        }

        /* Efecto de zoom suave en imágenes */
        .hero__slide img {
            transform: scale(1.05);
            transition: transform 8s ease-out;
        }
        .swiper-slide-active .hero__slide img {
            transform: scale(1);
        }

        .hero__overlay {
            position: absolute;
            inset: 0;
            z-index: 2;
            background:
                linear-gradient(to right, rgba(8,8,8,0.88) 0%, rgba(8,8,8,0.3) 55%, transparent 100%),
                linear-gradient(to top, rgba(8,8,8,0.97) 0%, rgba(8,8,8,0.4) 30%, transparent 60%);
        }

        .hero__rule {
            position: absolute;
            left: 5%;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 40vh;
            background: linear-gradient(to bottom, transparent, var(--gold), transparent);
            z-index: 3;
            opacity: 0.4;
        }

        .hero__content {
            position: absolute;
            bottom: 12%;
            left: 5%; right: 5%;
            z-index: 4;
            max-width: 780px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .eyebrow__line {
            width: 40px;
            height: 1px;
            background: var(--gold);
            opacity: 0.7;
        }
        .eyebrow__text {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--gold);
        }

        .hero__title {
            font-family: var(--font-display);
            font-size: clamp(56px, 9vw, 108px);
            line-height: 0.92;
            color: var(--white);
            margin-bottom: 32px;
        }
        .hero__title em {
            display: block;
            color: var(--gold);
            font-style: italic;
        }

        .hero__meta {
            display: flex;
            align-items: center;
            gap: 32px;
            margin-bottom: 44px;
            flex-wrap: wrap;
        }
        .hero__meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            letter-spacing: 0.08em;
            color: var(--cream-dim);
        }
        .hero__meta-item i { color: var(--gold); font-size: 14px; }

        .hero__actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero__scroll {
            position: absolute;
            bottom: 40px;
            right: 5%;
            z-index: 4;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--cream-faint);
            writing-mode: vertical-rl;
        }
        .hero__scroll::after {
            content: '';
            width: 1px;
            height: 60px;
            background: linear-gradient(to bottom, var(--gold), transparent);
            animation: scrollLine 2s ease-in-out infinite;
        }
        @keyframes scrollLine {
            0%, 100% { opacity: 0.4; transform: scaleY(1); }
            50% { opacity: 1; transform: scaleY(0.6); transform-origin: top; }
        }

        /* ===== SWIPER CONTROLES ===== */
        .swiper-button-next,
        .swiper-button-prev {
            color: var(--gold) !important;
            width: 50px !important;
            height: 50px !important;
            background: rgba(8,8,8,0.5) !important;
            border: 1px solid var(--gold-thin) !important;
            border-radius: 50% !important;
            transition: all 0.4s var(--ease-luxury) !important;
            backdrop-filter: blur(8px) !important;
        }
        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: var(--gold) !important;
            color: var(--black) !important;
            border-color: var(--gold) !important;
            transform: scale(1.1) !important;
        }
        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 16px !important;
            font-weight: 700 !important;
        }

        .swiper-pagination-bullet {
            background: rgba(255,255,255,0.3) !important;
            opacity: 1 !important;
            width: 10px !important;
            height: 10px !important;
            transition: all 0.3s !important;
        }
        .swiper-pagination-bullet-active {
            background: var(--gold) !important;
            width: 30px !important;
            border-radius: 10px !important;
        }

        /* ===== SECCIONES ===== */
        section { padding: 110px 0; position: relative; }

        .section-header { margin-bottom: 64px; }

        .section-label {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
        }
        .section-label__line {
            flex-shrink: 0;
            width: 36px; height: 1px;
            background: var(--gold);
            opacity: 0.6;
        }
        .section-label__text {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--gold);
        }

        .section-title {
            font-family: var(--font-serif);
            font-size: clamp(36px, 4.5vw, 60px);
            font-weight: 400;
            line-height: 1.1;
            color: var(--white);
        }
        .section-title em {
            color: var(--gold);
            font-style: italic;
        }

        .desc-section {
            background: var(--black-2);
            border-top: 1px solid var(--gold-thin);
            border-bottom: 1px solid var(--gold-thin);
            overflow: hidden;
        }

        .desc-inner {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 0;
            min-height: 420px;
        }

        .desc-aside {
            border-right: 1px solid var(--gold-thin);
            padding: 72px 60px 72px 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .desc-aside__ornament {
            font-family: var(--font-display);
            font-size: 160px;
            line-height: 1;
            color: var(--gold);
            opacity: 0.07;
            font-style: italic;
            user-select: none;
        }

        .desc-aside__quote {
            font-family: var(--font-serif);
            font-size: 14px;
            font-style: italic;
            color: var(--gold);
            letter-spacing: 0.04em;
            line-height: 1.6;
        }

        .desc-body {
            padding: 72px 0 72px 72px;
        }

        .desc-body__text {
            font-family: var(--font-sans);
            font-size: 17px;
            line-height: 1.85;
            color: var(--cream-dim);
            font-weight: 300;
        }
        .desc-body__text p { margin-bottom: 1.4em; }
        .desc-body__text p:last-child { margin-bottom: 0; }
        .desc-body__text strong { color: var(--gold); font-weight: 500; }

        .gallery-section { background: var(--black-2); }

        .gallery-masonry {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-auto-rows: 80px;
            gap: 6px;
            margin-top: 56px;
        }

        .gitem {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            background: var(--black-3);
        }
        .gitem:nth-child(1) { grid-column: span 5; grid-row: span 5; }
        .gitem:nth-child(2) { grid-column: span 7; grid-row: span 3; }
        .gitem:nth-child(3) { grid-column: span 4; grid-row: span 3; }
        .gitem:nth-child(4) { grid-column: span 3; grid-row: span 2; }
        .gitem:nth-child(5) { grid-column: span 5; grid-row: span 4; }
        .gitem:nth-child(6) { grid-column: span 7; grid-row: span 4; }
        .gitem:nth-child(7) { grid-column: span 4; grid-row: span 2; }

        .gitem img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 1.4s var(--ease-luxury);
        }
        .gitem:hover img { transform: scale(1.07); }

        .gitem__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201,168,76,0.25), rgba(8,8,8,0.75));
            opacity: 0;
            transition: opacity 0.4s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gitem:hover .gitem__overlay { opacity: 1; }
        .gitem__overlay i {
            font-size: 28px;
            color: var(--gold);
            transform: scale(0.7);
            transition: transform 0.3s;
        }
        .gitem:hover .gitem__overlay i { transform: scale(1); }

        .amenities-section { background: var(--black); }

        .amenities-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--gold-thin);
            border: 1px solid var(--gold-thin);
            margin-top: 56px;
        }

        .amenity {
            background: var(--black);
            padding: 52px 36px;
            transition: background 0.4s;
            position: relative;
            overflow: hidden;
        }
        .amenity::after {
            content: '';
            position: absolute;
            bottom: 0; left: 36px;
            width: 0; height: 1px;
            background: var(--gold);
            transition: width 0.5s var(--ease-luxury);
        }
        .amenity:hover::after { width: calc(100% - 72px); }
        .amenity:hover { background: var(--black-3); }

        .amenity__icon-wrap {
            width: 52px; height: 52px;
            border: 1px solid var(--gold-thin);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
            transition: border-color 0.3s, background 0.3s;
        }
        .amenity:hover .amenity__icon-wrap {
            border-color: var(--gold);
            background: var(--gold-glow);
        }
        .amenity__icon-wrap i { font-size: 22px; color: var(--gold); }

        .amenity__title {
            font-family: var(--font-serif);
            font-size: 19px;
            font-weight: 400;
            color: var(--white);
            margin-bottom: 12px;
        }
        .amenity__desc {
            font-size: 13px;
            line-height: 1.7;
            color: var(--cream-faint);
        }

        .videos-section { background: var(--black-2); }

        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
            margin-top: 56px;
        }

        .video-card {
            border: 1px solid var(--gold-thin);
            overflow: hidden;
            transition: all 0.5s var(--ease-luxury);
            position: relative;
        }
        .video-card:hover {
            border-color: var(--gold);
            transform: translateY(-6px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
        }
        .video-card video {
            display: block;
            width: 100%; height: 230px;
            object-fit: cover;
        }
        .video-card__body { padding: 28px; }
        .video-card__title {
            font-family: var(--font-serif);
            font-size: 18px;
            color: var(--gold);
            margin-bottom: 6px;
        }
        .video-card__sub {
            font-size: 12px;
            color: var(--cream-faint);
        }

        .rating-section {
            background: var(--black);
            border-top: 1px solid var(--gold-thin);
        }

        .rating-inner {
            max-width: 560px;
            margin: 0 auto;
            text-align: center;
        }

        .stars-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 48px 0 16px;
        }
        .star-btn {
            background: none;
            border: none;
            font-size: 44px;
            color: rgba(255,255,255,0.06);
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
            line-height: 1;
        }
        .star-btn:hover, .star-btn.active {
            color: var(--gold);
            transform: scale(1.12);
        }

        .rating-feedback {
            font-size: 13px;
            color: var(--gold);
            letter-spacing: 0.08em;
            min-height: 28px;
            margin-bottom: 28px;
        }

        .cta-band {
            background: var(--black-2);
            padding: 0;
            border-top: 1px solid var(--gold-thin);
            border-bottom: 1px solid var(--gold-thin);
            position: relative;
            overflow: hidden;
        }
        .cta-band::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 80% 50%, rgba(201,168,76,0.07) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, rgba(201,168,76,0.04) 0%, transparent 50%);
            pointer-events: none;
        }

        .cta-band__decor {
            position: absolute;
            right: 3%;
            top: 50%;
            transform: translateY(-50%);
            font-family: var(--font-display);
            font-size: 260px;
            line-height: 1;
            color: var(--gold);
            opacity: 0.025;
            user-select: none;
            font-style: italic;
        }

        .cta-band__inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 80px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .cta-band__sub {
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
        }
        .cta-band__title {
            font-family: var(--font-serif);
            font-size: clamp(32px, 3.5vw, 52px);
            font-weight: 400;
            color: var(--white);
            line-height: 1.1;
        }
        .cta-band__title em { color: var(--gold); font-style: italic; }

        .footer {
            padding: 60px 5% 40px;
            text-align: center;
            background: var(--black);
            border-top: 1px solid var(--gold-thin);
        }
        .footer__wordmark {
            font-family: var(--font-display);
            font-size: 30px;
            color: var(--white);
            letter-spacing: 0.12em;
            margin-bottom: 10px;
        }
        .footer__wordmark em { color: var(--gold); font-style: italic; }
        .footer__copy {
            font-size: 12px;
            color: var(--cream-faint);
            letter-spacing: 0.08em;
        }
        .footer__divider {
            width: 40px; height: 1px;
            background: var(--gold);
            opacity: 0.4;
            margin: 20px auto;
        }

        .lightbox {
            position: fixed;
            inset: 0;
            background: rgba(4,4,4,0.97);
            z-index: 3000;
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
        }
        .lightbox--open { display: flex; }

        .lightbox__img {
            max-width: 88vw;
            max-height: 84vh;
            object-fit: contain;
            border: 1px solid var(--gold-thin);
        }
        .lightbox__close {
            position: absolute;
            top: 32px; right: 40px;
            font-size: 36px;
            color: var(--gold);
            cursor: pointer;
            line-height: 1;
            transition: transform 0.3s;
        }
        .lightbox__close:hover { transform: rotate(90deg); }

        .lb-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: 1px solid var(--gold-thin);
            color: var(--gold);
            font-size: 20px;
            padding: 16px 20px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .lb-nav:hover { background: var(--gold); color: var(--black); border-color: var(--gold); }
        .lb-nav--l { left: 32px; }
        .lb-nav--r { right: 32px; }

        .lightbox__counter {
            position: absolute;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 11px;
            letter-spacing: 0.2em;
            color: var(--cream-faint);
        }

        .modal-bg {
            position: fixed;
            inset: 0;
            background: rgba(4,4,4,0.96);
            backdrop-filter: blur(12px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s;
        }
        .modal-bg--open {
            opacity: 1;
            pointer-events: all;
        }

        .modal {
            background: var(--black-2);
            border: 1px solid var(--gold-thin);
            width: 100%;
            max-width: 520px;
            padding: 52px 48px;
            position: relative;
            transform: translateY(24px);
            transition: transform 0.5s var(--ease-luxury);
        }
        .modal-bg--open .modal { transform: translateY(0); }

        .modal::before, .modal::after {
            content: '';
            position: absolute;
            width: 20px; height: 20px;
        }
        .modal::before {
            top: -1px; right: -1px;
            border-top: 1px solid var(--gold);
            border-right: 1px solid var(--gold);
        }
        .modal::after {
            bottom: -1px; left: -1px;
            border-bottom: 1px solid var(--gold);
            border-left: 1px solid var(--gold);
        }

        .modal__close {
            position: absolute;
            top: 20px; right: 24px;
            background: none;
            border: none;
            color: var(--cream-faint);
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .modal__close:hover { color: var(--gold); }

        .modal__title {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 400;
            color: var(--white);
            margin-bottom: 36px;
        }
        .modal__title em { color: var(--gold); font-style: italic; }

        .form-group { margin-bottom: 22px; }
        .form-label {
            display: block;
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 0.24em;
            text-transform: uppercase;
            color: var(--gold-muted);
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 13px 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--gold-thin);
            color: var(--cream);
            font-family: var(--font-sans);
            font-size: 14px;
            font-weight: 300;
            transition: border-color 0.3s, background 0.3s;
            appearance: none;
        }
        .form-control::placeholder { color: var(--cream-faint); }
        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            background: rgba(201,168,76,0.04);
        }
        .form-control option { background: var(--black-2); color: var(--cream); }

        .total-preview {
            margin-top: 8px;
            font-size: 13px;
            color: var(--gold);
            letter-spacing: 0.04em;
        }
        .cupos-info {
            margin-top: 8px;
            font-size: 12px;
        }

        .toast-stack {
            position: fixed;
            bottom: 28px; right: 28px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--black-3);
            border: 1px solid var(--gold-thin);
            border-left: 2px solid var(--gold);
            padding: 14px 22px;
            font-size: 13px;
            color: var(--cream);
            animation: toastIn 0.4s var(--ease-luxury), toastOut 0.3s 2.7s forwards;
            min-width: 260px;
        }
        .toast i { color: var(--gold); }
        @keyframes toastIn {
            from { transform: translateX(110%); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }
        @keyframes toastOut {
            to { transform: translateX(30px); opacity: 0; }
        }

        @media (max-width: 1024px) {
            .desc-inner { grid-template-columns: 1fr; }
            .desc-aside { display: none; }
            .desc-body { padding: 60px 0; }
            .gallery-masonry { grid-template-columns: repeat(6, 1fr); }
            .gitem:nth-child(n) { grid-column: span 3; grid-row: span 3; }
            .nav { display: none; }
        }
        @media (max-width: 768px) {
            .amenities-row { grid-template-columns: 1fr; }
            .gallery-masonry { grid-template-columns: repeat(2, 1fr); }
            .gitem:nth-child(n) { grid-column: span 1; grid-row: span 3; }
            .cta-band__inner { flex-direction: column; text-align: center; }
            section { padding: 72px 0; }
            .modal { padding: 36px 28px; }
            .hero__meta { gap: 16px; }
            .hero__title { font-size: clamp(40px, 12vw, 60px); }
        }
    </style>
</head>
<body>

<div class="toast-stack" id="toastStack"></div>

<header class="header" id="header">
    <div class="header__inner">
        <!-- LOGO MÁS GRANDE -->
        <img src="{{ asset('img/logo.bl.png') }}" alt="JACHA TOURS" class="logo">
        <nav class="nav">
            <a href="{{ url('/#inicio') }}" class="nav__link">Inicio</a>
            <a href="{{ url('/#circuito') }}" class="nav__link">Circuito</a>
            <a href="{{ url('/#experiencias') }}" class="nav__link">Experiencias</a>
            <a href="{{ url('/#contacto') }}" class="nav__link">Contacto</a>
        </nav>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn--ghost"><i class="fas fa-user"></i> {{ Auth::user()->name ?? 'Mi Cuenta' }}</a>
        @else
            <a href="{{ route('login') }}" class="btn btn--ghost"><i class="fas fa-sign-in-alt"></i> Ingresar</a>
        @endauth
    </div>
</header>

<!-- ===== HERO CON CARRUSEL PROFESIONAL ===== -->
<section class="hero">
    <div class="swiper hero__swiper" id="heroSwiper">
        <div class="swiper-wrapper" id="heroSliderWrapper">
            {{-- Las imágenes y videos se agregarán dinámicamente con JavaScript --}}
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="hero__overlay"></div>
    <div class="hero__rule"></div>

    <div class="hero__content" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1100">
        <div class="eyebrow">
            <div class="eyebrow__line"></div>
            <span class="eyebrow__text">Arquitectura Andina Contemporánea</span>
        </div>
        <h1 class="hero__title">
            {{ $cholet->nombre }}
            <em>El Alto</em>
        </h1>
        <div class="hero__meta">
            <div class="hero__meta-item"><i class="fas fa-map-marker-alt"></i> El Alto, Bolivia</div>
            <div class="hero__meta-item"><i class="fas fa-clock"></i> 4–6 horas</div>
            <div class="hero__meta-item"><i class="fas fa-star"></i> <span id="avgRatingDisplay">{{ number_format($cholet->ratings()->avg('rating') ?? 4.9, 1) }}</span> valoración</div>
        </div>
        <div class="hero__actions">
            <button class="btn btn--gold" onclick="scrollToReserva()">Reservar Experiencia</button>
            <button class="btn btn--ghost" onclick="document.getElementById('galeria').scrollIntoView({behavior:'smooth'})">
                <i class="fas fa-images"></i> Explorar Galería
            </button>
        </div>
    </div>

    <div class="hero__scroll">Descubrir</div>
</section>

<!-- ===== DESCRIPCIÓN ===== -->
<section class="desc-section">
    <div class="container">
        <div class="desc-inner" data-aos="fade-up" data-aos-duration="1000">
            <div class="desc-aside">
                <div class="desc-aside__ornament">"</div>
                <div class="desc-aside__quote">
                    Arquitectura que<br>nace del alma<br>andina
                </div>
            </div>
            <div class="desc-body">
                <div class="section-label" style="margin-bottom: 28px;">
                    <div class="section-label__line"></div>
                    <span class="section-label__text">Sobre este espacio</span>
                </div>
                <div class="desc-body__text">
                    {!! nl2br(e($cholet->descripcion)) !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== GALERÍA ===== -->
<section id="galeria" class="gallery-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-label"><div class="section-label__line"></div><span class="section-label__text">Galería Visual</span></div>
            <h2 class="section-title">Explora <em>Visualmente</em></h2>
        </div>

        @php
            $allImages = [];
            if($cholet->imagen) $allImages[] = asset('storage/cholets/' . $cholet->imagen);
            foreach($cholet->imagenes as $img) $allImages[] = asset('storage/cholets/galeria/' . $img->imagen);
        @endphp

        @if(count($allImages) > 0)
        <div class="gallery-masonry">
            @foreach($allImages as $i => $src)
                @if($i < 7)
                <div class="gitem" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}" onclick="openLB({{ $i }})">
                    <img src="{{ $src }}" alt="Galería {{ $cholet->nombre }}" loading="lazy">
                    <div class="gitem__overlay"><i class="fas fa-expand-alt"></i></div>
                </div>
                @endif
            @endforeach
        </div>

        @if(count($allImages) > 7)
        <div style="text-align:center; margin-top:44px;">
            <button class="btn btn--ghost" onclick="openLB(0)"><i class="fas fa-images"></i> Ver todas las imágenes ({{ count($allImages) }})</button>
        </div>
        @endif
        @endif
    </div>
</section>

<!-- ===== AMENITIES ===== -->
<section class="amenities-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-label"><div class="section-label__line"></div><span class="section-label__text">Servicios Premium</span></div>
            <h2 class="section-title">Lo que <em>incluye</em></h2>
        </div>
        <div class="amenities-row">
            @php
                $amenities = [
                    ['icon'=>'fa-camera',           'title'=>'Fotografía Profesional', 'desc'=>'Sesión completa durante el recorrido con equipo profesional de alta gama.'],
                    ['icon'=>'fa-language',          'title'=>'Guía Trilingüe',         'desc'=>'Español, Inglés y Aymara nativo para una experiencia auténtica.'],
                    ['icon'=>'fa-champagne-glasses', 'title'=>'Degustación Gourmet',    'desc'=>'Bebidas premium y aperitivos seleccionados de la región andina.'],
                    ['icon'=>'fa-car-side',          'title'=>'Transporte Ejecutivo',   'desc'=>'Servicio de recojo y retorno VIP incluido desde su hospedaje.'],
                ];
            @endphp
            @foreach($amenities as $i => $a)
            <div class="amenity" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                <div class="amenity__icon-wrap"><i class="fas {{ $a['icon'] }}"></i></div>
                <div class="amenity__title">{{ $a['title'] }}</div>
                <div class="amenity__desc">{{ $a['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== VIDEOS ===== -->
@if($cholet->videos && $cholet->videos->count() > 0)
<section class="videos-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-label"><div class="section-label__line"></div><span class="section-label__text">Recorrido Audiovisual</span></div>
            <h2 class="section-title">Vive la <em>Experiencia</em></h2>
        </div>
        <div class="videos-grid">
            @foreach($cholet->videos as $video)
            <div class="video-card" data-aos="fade-up">
                <video controls muted preload="metadata">
                    <source src="{{ asset('storage/videos/' . $video->url) }}" type="video/mp4">
                </video>
                <div class="video-card__body">
                    <div class="video-card__title">{{ $video->titulo ?? $cholet->nombre }}</div>
                    <div class="video-card__sub">Recorrido interior · El Alto, Bolivia</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===== RATING ===== -->
<section class="rating-section">
    <div class="container">
        <div class="rating-inner" data-aos="fade-up">
            <div class="section-label" style="justify-content:center;">
                <div class="section-label__line"></div>
                <span class="section-label__text">Tu Opinión</span>
                <div class="section-label__line"></div>
            </div>
            <h2 class="section-title">¿Qué te <em>pareció?</em></h2>

            <div class="stars-row" id="starsRow">
                @for($i=1; $i<=5; $i++)
                <button class="star-btn" data-rating="{{ $i }}"><i class="fa-regular fa-star"></i></button>
                @endfor
            </div>

            <div class="rating-feedback" id="ratingFeedback"></div>

            @auth
                <button class="btn btn--gold" onclick="submitRating({{ $cholet->id }})" id="submitRatingBtn">
                    <i class="fas fa-paper-plane"></i> Enviar Valoración
                </button>
            @else
                <p style="font-size:14px;">
                    <a href="{{ route('login') }}" style="color:var(--gold); border-bottom:1px solid var(--gold-thin);">Inicia sesión</a>
                    para calificar esta experiencia
                </p>
            @endauth
        </div>
    </div>
</section>

<!-- ===== CTA ===== -->
<div class="cta-band" id="reservaSection">
    <div class="cta-band__decor">JT</div>
    <div class="cta-band__inner">
        <div data-aos="fade-right">
            <div class="cta-band__sub">Disponibilidad Inmediata</div>
            <div class="cta-band__title">Reserva tu <em>experiencia</em></div>
        </div>
        @auth
            <button class="btn btn--gold" onclick="openModal()" data-aos="fade-left" style="font-size:12px; padding:16px 40px;">
                Reservar Ahora <i class="fas fa-arrow-right"></i>
            </button>
        @else
            <a href="{{ route('login') }}" class="btn btn--gold" data-aos="fade-left" style="font-size:12px; padding:16px 40px;">
                Ingresar para Reservar <i class="fas fa-arrow-right"></i>
            </a>
        @endauth
    </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="footer__wordmark">JACHA <em>TOURS</em></div>
    <div class="footer__divider"></div>
    <div class="footer__copy">© 2026 · Experiencias Premium en El Alto, Bolivia</div>
</footer>

<!-- ===== LIGHTBOX ===== -->
<div class="lightbox" id="lightbox">
    <span class="lightbox__close" onclick="closeLB()">&times;</span>
    <button class="lb-nav lb-nav--l" onclick="moveLB(-1)">&#10094;</button>
    <button class="lb-nav lb-nav--r" onclick="moveLB(1)">&#10095;</button>
    <img class="lightbox__img" id="lbImg" alt="">
    <div class="lightbox__counter" id="lbCounter"></div>
</div>

<!-- ===== MODAL RESERVA ===== -->
<div class="modal-bg" id="modalBg">
    <div class="modal">
        <button class="modal__close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="modal__title">Reservar <em>Experiencia</em></div>

        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
            @csrf
            <input type="hidden" name="cholet_id" value="{{ $cholet->id }}">
            <input type="hidden" name="hora_reserva" id="horaHidden">

            <div class="form-group">
                <label class="form-label">Fecha de Visita</label>
                <input type="date" name="fecha" class="form-control" id="fechaInput"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
            </div>

            <div class="form-group" id="horarioGroup" style="display:none;">
                <label class="form-label">Horario</label>
                <select name="horario_id" id="horarioSelect" class="form-control" required>
                    <option value="">Selecciona una fecha primero</option>
                </select>
                <div class="cupos-info" id="cuposInfo"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Experiencia</label>
                <select name="experiencia_id" id="expSelect" class="form-control" required>
                    <option value="">Cargando experiencias...</option>
                    @foreach($experiencias as $exp)
                        <option value="{{ $exp->id }}" 
                                data-precio="{{ $exp->precio }}"
                                data-duracion="{{ $exp->duracion_horas }}"
                                data-descripcion="{{ $exp->descripcion }}">
                            {{ $exp->nombre }} — Bs {{ number_format($exp->precio, 2) }}
                        </option>
                    @endforeach
                </select>
                <div id="expDetails" class="experiencia-details" style="margin-top: 12px; display: none;">
                    <div style="border-top: 1px solid var(--gold-thin); padding-top: 12px;">
                        <strong id="expNombre" style="color: var(--gold);"></strong>
                        <p id="expDescripcion" style="margin-top: 8px; font-size: 13px;"></p>
                        <small><i class="fas fa-clock"></i> Duración: <span id="expDuracion"></span> horas</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Número de Personas</label>
                <input type="number" name="personas" id="persInput" class="form-control" min="1" max="20" value="2" required>
                <div class="total-preview" id="totalPreview"></div>
            </div>

            <div class="form-group">
                <label class="form-label">WhatsApp</label>
                <input type="tel" name="telefono" class="form-control" placeholder="+591 77777777" required>
            </div>

            <button type="submit" class="btn btn--gold" style="width:100%; justify-content:center; padding:16px;">
                Confirmar Reserva <i class="fas fa-check"></i>
            </button>
        </form>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true, offset: 50, easing: 'ease-out-quart' });

    // ===== RECOPILAR MEDIA =====
    const mediaItems = [];
    
    @if($cholet->imagen)
    mediaItems.push({
        type: 'image',
        src: '{{ asset('storage/cholets/' . $cholet->imagen) }}',
        alt: '{{ $cholet->nombre }}'
    });
    @endif
    
    @foreach($cholet->imagenes as $img)
    mediaItems.push({
        type: 'image',
        src: '{{ asset('storage/cholets/galeria/' . $img->imagen) }}',
        alt: 'Galería {{ $cholet->nombre }}'
    });
    @endforeach
    
    @if($cholet->videos && $cholet->videos->count() > 0)
        @foreach($cholet->videos as $video)
        mediaItems.push({
            type: 'video',
            src: '{{ asset('storage/videos/' . $video->url) }}',
            alt: '{{ $video->titulo ?? $cholet->nombre }}'
        });
        @endforeach
    @endif

    // ===== CONSTRUIR SLIDER =====
    const sliderWrapper = document.getElementById('heroSliderWrapper');
    
    function buildSlider() {
        if (!sliderWrapper) return;
        sliderWrapper.innerHTML = '';
        
        mediaItems.forEach((item) => {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            
            const mediaWrapper = document.createElement('div');
            mediaWrapper.className = 'hero__slide';
            
            if (item.type === 'image') {
                const img = document.createElement('img');
                img.src = item.src;
                img.alt = item.alt;
                img.loading = 'eager';
                mediaWrapper.appendChild(img);
            } else if (item.type === 'video') {
                const video = document.createElement('video');
                video.muted = true;
                video.playsInline = true;
                video.loop = false;
                video.setAttribute('preload', 'metadata');
                
                const source = document.createElement('source');
                source.src = item.src;
                source.type = 'video/mp4';
                video.appendChild(source);
                
                // Cargar el video para que esté listo
                video.load();
                
                mediaWrapper.appendChild(video);
            }
            
            slide.appendChild(mediaWrapper);
            sliderWrapper.appendChild(slide);
        });
    }
    
    buildSlider();

    // ===== INICIALIZAR SWIPER =====
    let heroSwiper = null;
    let autoPlayDelay = 6000;
    
    function initHeroSwiper() {
        const hasMultipleItems = mediaItems.length > 1;
        
        heroSwiper = new Swiper('#heroSwiper', {
            slidesPerView: 1,
            loop: hasMultipleItems,
            speed: 1200,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: hasMultipleItems ? {
                delay: autoPlayDelay,
                disableOnInteraction: true,
                pauseOnMouseEnter: true,
                waitForTransition: true
            } : false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: false
            },
            watchSlidesProgress: true,
            on: {
                slideChangeTransitionStart: () => {
                    // Pausar todos los videos al cambiar
                    document.querySelectorAll('#heroSwiper video').forEach(v => {
                        if (!v.paused) v.pause();
                    });
                },
                slideChangeTransitionEnd: function() {
                    // Reproducir video si existe en el slide activo
                    const currentSlide = this.slides[this.activeIndex];
                    const video = currentSlide?.querySelector('video');
                    if (video) {
                        video.play().catch(() => {});
                    }
                },
                autoplayStop: function() {
                    // No hacer nada especial
                }
            }
        });
    }
    
    // Iniciar después de construir
    setTimeout(() => {
        if (mediaItems.length > 0) {
            initHeroSwiper();
            
            // Reproducir primer video si existe
            setTimeout(() => {
                const firstVideo = document.querySelector('#heroSwiper video');
                if (firstVideo) {
                    firstVideo.play().catch(() => {});
                }
            }, 300);
        }
    }, 150);

    // ===== PAUSAR AUTOPLAY EN INTERACCIÓN =====
    let autoPlayTimeout = null;
    
    function pauseAutoplay() {
        if (heroSwiper && heroSwiper.autoplay) {
            heroSwiper.autoplay.stop();
            if (autoPlayTimeout) clearTimeout(autoPlayTimeout);
            autoPlayTimeout = setTimeout(() => {
                if (heroSwiper && mediaItems.length > 1) {
                    heroSwiper.autoplay.start();
                }
            }, 10000);
        }
    }
    
    document.querySelectorAll('.swiper-button-next, .swiper-button-prev, .swiper-pagination-bullet').forEach(el => {
        el.addEventListener('click', pauseAutoplay);
    });

    // ===== HEADER SCROLL =====
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        header.classList.toggle('header--scrolled', window.scrollY > 60);
    });

    // ===== LIGHTBOX =====
    const imgs = @json($allImages);
    let lbIdx = 0;
    const lb = document.getElementById('lightbox');
    const lbImg = document.getElementById('lbImg');
    const lbCounter = document.getElementById('lbCounter');
    
    function openLB(i) {
        if (!imgs.length) return;
        lbIdx = i;
        lbImg.src = imgs[lbIdx];
        lbCounter.textContent = (lbIdx + 1) + ' / ' + imgs.length;
        lb.classList.add('lightbox--open');
        document.body.style.overflow = 'hidden';
        pauseAutoplay();
    }
    
    function closeLB() { 
        lb.classList.remove('lightbox--open'); 
        document.body.style.overflow = ''; 
    }
    
    function moveLB(d) { 
        lbIdx = (lbIdx + d + imgs.length) % imgs.length; 
        lbImg.src = imgs[lbIdx]; 
        lbCounter.textContent = (lbIdx+1)+' / '+imgs.length; 
    }
    
    lb.addEventListener('click', e => { if (e.target === lb) closeLB(); });

    // ===== MODAL =====
    function openModal() { 
        document.getElementById('modalBg').classList.add('modal-bg--open'); 
        document.body.style.overflow = 'hidden';
        pauseAutoplay();
    }
    
    function closeModal() { 
        document.getElementById('modalBg').classList.remove('modal-bg--open'); 
        document.body.style.overflow = '';
    }
    
    document.getElementById('modalBg')?.addEventListener('click', e => { 
        if (e.target.id === 'modalBg') closeModal(); 
    });
    
    function scrollToReserva() { 
        document.getElementById('reservaSection').scrollIntoView({ behavior: 'smooth' }); 
        pauseAutoplay();
    }

    // ===== EXPERIENCIA DETAILS =====
    const expSel = document.getElementById('expSelect');
    const persSel = document.getElementById('persInput');
    const totalPrev = document.getElementById('totalPreview');
    const expDetails = document.getElementById('expDetails');
    const expNombre = document.getElementById('expNombre');
    const expDescripcion = document.getElementById('expDescripcion');
    const expDuracion = document.getElementById('expDuracion');

    function updateExperienciaDetails() {
        const selected = expSel.options[expSel.selectedIndex];
        if (selected && selected.value) {
            const nombre = selected.text.split(' — ')[0];
            const precio = parseFloat(selected.dataset.precio);
            const duracion = selected.dataset.duracion;
            const descripcion = selected.dataset.descripcion;
            const personas = parseInt(persSel.value) || 1;
            
            expNombre.textContent = nombre;
            expDescripcion.textContent = descripcion || 'Descripción no disponible';
            expDuracion.textContent = duracion;
            expDetails.style.display = 'block';
            
            totalPrev.innerHTML = `<i class="fas fa-coins"></i> Total: <strong>Bs ${(precio * personas).toFixed(2)}</strong>`;
        } else {
            expDetails.style.display = 'none';
            totalPrev.innerHTML = '';
        }
    }

    expSel?.addEventListener('change', updateExperienciaDetails);
    persSel?.addEventListener('input', updateExperienciaDetails);

    // ===== HORARIOS =====
    const fechaInput = document.getElementById('fechaInput');
    const horGrp = document.getElementById('horarioGroup');
    const horSel = document.getElementById('horarioSelect');
    const cuposInfo = document.getElementById('cuposInfo');
    const horaHidden = document.getElementById('horaHidden');
    
    fechaInput?.addEventListener('change', function() {
        if (!this.value) return;
        horGrp.style.display = 'block';
        horSel.innerHTML = '<option>Cargando horarios…</option>';
        fetch(`/api/horarios-disponibles?cholet_id={{ $cholet->id }}&fecha=${this.value}`)
            .then(r => r.json()).then(h => {
                horSel.innerHTML = '<option value="">— Selecciona un horario —</option>';
                if (!h.length) { 
                    horSel.innerHTML = '<option>Sin disponibilidad este día</option>'; 
                    cuposInfo.innerHTML = '<span style="color:#ef4444;">Sin cupos disponibles</span>'; 
                    return; 
                }
                h.forEach(hor => {
                    const o = document.createElement('option');
                    o.value = hor.id;
                    o.textContent = `${hor.hora_inicio} – ${hor.hora_fin}  ·  ${hor.cupos_disponibles} cupos`;
                    o.dataset.hora = hor.hora_inicio;
                    o.dataset.cupos = hor.cupos_disponibles;
                    horSel.appendChild(o);
                });
                cuposInfo.innerHTML = `<span style="color:#10b981;">${h.length} horarios disponibles</span>`;
            });
    });
    
    horSel?.addEventListener('change', function() {
        const sel = this.options[this.selectedIndex];
        if (sel && sel.dataset.hora) {
            horaHidden.value = sel.dataset.hora;
            const cupos = parseInt(sel.dataset.cupos);
            cuposInfo.innerHTML = cupos <= 3
                ? `<span style="color:#ef4444;">Últimos ${cupos} cupos</span>`
                : cupos <= 8
                    ? `<span style="color:#f59e0b;">${cupos} cupos disponibles</span>`
                    : `<span style="color:#10b981;">${cupos} cupos disponibles</span>`;
        }
    });

    // ===== RATING =====
    let selectedRating = 0;
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            selectedRating = parseInt(btn.dataset.rating);
            document.querySelectorAll('.star-btn').forEach((s, i) => {
                const ic = s.querySelector('i');
                if (i < selectedRating) { ic.className = 'fa-solid fa-star'; s.classList.add('active'); }
                else { ic.className = 'fa-regular fa-star'; s.classList.remove('active'); }
            });
            const labels = ['', 'Muy malo', 'Malo', 'Regular', 'Bueno', 'Excelente'];
            document.getElementById('ratingFeedback').innerHTML = `<i class="fas fa-star"></i> ${selectedRating} — ${labels[selectedRating]}`;
        });
    });

    function submitRating(id) {
        if (!selectedRating) return showToast('Selecciona una calificación primero', 'warning');
        fetch('{{ route("rating.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ cholet_id: id, rating: selectedRating })
        }).then(r => r.json()).then(d => {
            if (d.success) {
                showToast('¡Gracias por tu valoración!');
                document.getElementById('submitRatingBtn').style.display = 'none';
                if (d.average) document.getElementById('avgRatingDisplay').textContent = d.average.toFixed(1);
            }
        });
    }

    function showToast(msg, type = 'success') {
        const t = document.createElement('div');
        t.className = 'toast';
        t.innerHTML = `<i class="fas fa-${type === 'warning' ? 'exclamation-circle' : 'check-circle'}"></i> ${msg}`;
        document.getElementById('toastStack').appendChild(t);
        setTimeout(() => t.remove(), 3100);
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeLB(); closeModal(); }
        if (lb.classList.contains('lightbox--open')) {
            if (e.key === 'ArrowLeft') moveLB(-1);
            if (e.key === 'ArrowRight') moveLB(1);
        }
    });

    @if(session('success')) showToast('{{ session('success') }}'); @endif
    @if(session('error')) showToast('{{ session('error') }}', 'warning'); @endif
</script>
</body>
</html>