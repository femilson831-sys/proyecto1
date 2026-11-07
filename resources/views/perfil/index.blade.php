<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Perfil | JACHA TOURS</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- WebcamJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #fff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Video Background */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .video-background video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.25;
        }

        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            z-index: -1;
        }

        .video-control {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 10px 18px;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid rgba(201, 168, 76, 0.4);
        }

        .video-control:hover {
            background: rgba(201, 168, 76, 0.2);
            transform: scale(1.05);
        }

        .video-control i {
            color: #C9A84C;
            font-size: 1.2rem;
        }

        .video-control span {
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
            margin-left: 5px;
        }

        /* ===== HEADER MEJORADO ===== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 10, 10, 0.92);
            backdrop-filter: blur(15px);
            padding: 0.8rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            border-bottom: 1px solid rgba(201, 168, 76, 0.15);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .logo:hover {
            transform: scale(1.02);
        }

        /* ===== LOGO MÁS GRANDE ===== */
        .logo img {
            height: 75px;
            width: auto;
            filter: drop-shadow(0 0 20px rgba(201, 168, 76, 0.12));
            transition: filter 0.3s, transform 0.3s;
        }

        .logo img:hover {
            filter: drop-shadow(0 0 35px rgba(201, 168, 76, 0.25));
            transform: scale(1.05);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-text .brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: #C9A84C;
            letter-spacing: 0.15em;
            font-family: 'Cormorant Garamond', serif;
        }

        .logo-text .sub {
            font-size: 0.55rem;
            color: rgba(255, 255, 255, 0.35);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            margin-top: -2px;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-nav {
            background: rgba(201, 168, 76, 0.1);
            color: #C9A84C;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 1px solid rgba(201, 168, 76, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.8rem;
            backdrop-filter: blur(5px);
        }

        .btn-nav:hover {
            background: #C9A84C;
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(201, 168, 76, 0.3);
        }

        .btn-logout {
            background: rgba(220, 53, 69, 0.12);
            color: #dc3545;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 1px solid rgba(220, 53, 69, 0.2);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            backdrop-filter: blur(5px);
        }

        .btn-logout:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.3);
        }

        @media (max-width: 768px) {
            .header {
                padding: 0.6rem 1rem;
                flex-wrap: wrap;
                gap: 10px;
            }

            .logo img {
                height: 50px;
            }

            .logo-text .brand {
                font-size: 1rem;
            }
            .logo-text .sub {
                display: none;
            }

            .btn-nav, .btn-logout {
                padding: 8px 14px;
                font-size: 0.7rem;
            }
            .btn-nav span, .btn-logout span {
                display: none;
            }
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 100px auto 0;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* ===== WELCOME BANNER MEJORADO ===== */
        .welcome-banner {
            background: linear-gradient(135deg, rgba(201, 168, 76, 0.15), rgba(0, 0, 0, 0.85));
            border-radius: 24px;
            padding: 2.5rem 3rem;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(201, 168, 76, 0.2);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 80%;
            height: 200%;
            background: radial-gradient(ellipse, rgba(201, 168, 76, 0.08) 0%, transparent 70%);
            animation: pulseGlow 6s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
            50% { transform: scale(1.2) rotate(5deg); opacity: 1; }
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #C9A84C, transparent);
            opacity: 0.5;
        }

        .welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .welcome-title span {
            color: #C9A84C;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.65);
            position: relative;
            z-index: 1;
            font-size: 1rem;
        }

        .badge-rol {
            display: inline-block;
            background: rgba(201, 168, 76, 0.2);
            color: #C9A84C;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.75rem;
            margin-top: 1rem;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(201, 168, 76, 0.2);
        }

        /* Grid Layout */
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 2rem;
        }

        @media (max-width: 900px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ===== CARDS MEJORADAS ===== */
        .card {
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            border-color: rgba(201, 168, 76, 0.25);
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            padding: 1.5rem 1.8rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
        }

        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #C9A84C;
            letter-spacing: 0.02em;
        }

        .card-header i {
            font-size: 1.4rem;
            color: #C9A84C;
            opacity: 0.7;
        }

        .card-body {
            padding: 1.8rem;
        }

        /* ===== AVATAR MEJORADO ===== */
        .avatar-section {
            text-align: center;
            margin-bottom: 1.8rem;
        }

        .avatar-circle {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C9A84C, #e4c46c);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.4s;
            position: relative;
            box-shadow: 0 0 30px rgba(201, 168, 76, 0.15);
            border: 3px solid rgba(201, 168, 76, 0.3);
        }

        .avatar-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 0 40px rgba(201, 168, 76, 0.35);
            border-color: #C9A84C;
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-circle i {
            font-size: 4rem;
            color: #000;
        }

        .avatar-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.75);
            padding: 8px;
            font-size: 0.7rem;
            opacity: 0;
            transition: opacity 0.3s;
            text-align: center;
            color: #C9A84C;
            letter-spacing: 0.05em;
        }

        .avatar-circle:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 10px;
        }

        .btn-cambiar-foto, .btn-camara {
            background: transparent;
            border: 1px solid rgba(201, 168, 76, 0.3);
            color: #C9A84C;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-cambiar-foto:hover, .btn-camara:hover {
            background: #C9A84C;
            color: #000;
            transform: translateY(-2px);
        }

        /* ===== FORM MEJORADO ===== */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.65);
            letter-spacing: 0.03em;
        }

        .form-group label i {
            color: #C9A84C;
            width: 18px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.4);
            color: #fff;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #C9A84C;
            background: rgba(0, 0, 0, 0.6);
            box-shadow: 0 0 20px rgba(201, 168, 76, 0.05);
        }

        .form-group input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: rgba(0, 0, 0, 0.2);
        }

        .form-help {
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.7rem;
            display: block;
            margin-top: 6px;
        }

        .btn-guardar {
            background: #C9A84C;
            color: #000;
            border: none;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.05em;
        }

        .btn-guardar:hover {
            background: #e4c46c;
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(201, 168, 76, 0.35);
        }

        /* ===== STATS MEJORADOS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: rgba(201, 168, 76, 0.06);
            border: 1px solid rgba(201, 168, 76, 0.08);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            background: rgba(201, 168, 76, 0.12);
            transform: translateY(-3px);
            border-color: rgba(201, 168, 76, 0.2);
        }

        .stat-card i {
            font-size: 1.8rem;
            color: #C9A84C;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 2px;
        }

        .chart-container {
            margin-top: 0.5rem;
            padding: 0.5rem;
        }

        /* ===== RESERVAS LIST ===== */
        .reservas-list {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .reservas-list::-webkit-scrollbar {
            width: 4px;
        }

        .reservas-list::-webkit-scrollbar-thumb {
            background: #C9A84C;
            border-radius: 4px;
        }

        .reserva-item {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            transition: all 0.3s;
            border-radius: 12px;
        }

        .reserva-item:hover {
            background: rgba(201, 168, 76, 0.05);
            padding-left: 1.8rem;
        }

        .reserva-info h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.2rem;
        }

        .reserva-info p {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .reserva-info p i {
            color: #C9A84C;
            width: 14px;
        }

        .reserva-estado {
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .estado-confirmada { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
        .estado-pendiente { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
        .estado-pago_reportado { background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.2); }
        .estado-cancelada { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }

        /* ===== RATINGS ===== */
        .rating-item {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            transition: background 0.3s;
        }

        .rating-item:hover {
            background: rgba(201, 168, 76, 0.03);
        }

        .rating-stars {
            color: #C9A84C;
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }

        .rating-stars i {
            margin-right: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            color: #C9A84C;
            opacity: 0.4;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.2rem;
        }

        .btn-action {
            background: transparent;
            border: 1px solid rgba(201, 168, 76, 0.2);
            color: #C9A84C;
            padding: 10px 16px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
            flex: 1;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-action:hover {
            background: rgba(201, 168, 76, 0.1);
            border-color: #C9A84C;
            transform: translateY(-2px);
        }

        /* ===== MODAL MEJORADO ===== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.92);
            backdrop-filter: blur(12px);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(135deg, #1a1a1a, #0a0a0a);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            border: 1px solid rgba(201, 168, 76, 0.2);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
        }

        .modal-content h3 {
            color: #C9A84C;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .modal-buttons button {
            flex: 1;
            padding: 12px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-modal-confirm {
            background: #C9A84C;
            color: #000;
            border: none;
        }

        .btn-modal-confirm:hover {
            background: #e4c46c;
            transform: translateY(-2px);
        }

        .btn-modal-cancel {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-modal-cancel:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Webcam Modal */
        #webcamModal .modal-content {
            text-align: center;
        }

        #webcam {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 12px;
            margin: 1rem 0;
        }

        .webcam-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* ===== TOAST MEJORADO ===== */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(20, 20, 20, 0.95);
            backdrop-filter: blur(15px);
            border-left: 4px solid #10b981;
            padding: 1rem 1.8rem;
            border-radius: 14px;
            z-index: 9999;
            animation: slideInRight 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            gap: 14px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        @keyframes slideInRight {
            from { transform: translateX(100%) scale(0.9); opacity: 0; }
            to { transform: translateX(0) scale(1); opacity: 1; }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0) scale(1); opacity: 1; }
            to { transform: translateX(100%) scale(0.9); opacity: 0; }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            background: #C9A84C;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #e4c46c;
        }

        /* ===== FOOTER MEJORADO ===== */
        .footer {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            text-align: center;
            margin-top: 2.5rem;
            border-top: 1px solid rgba(201, 168, 76, 0.1);
        }

        .footer p {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        .footer p i {
            color: #C9A84C;
        }

        @media (max-width: 768px) {
            .container {
                padding: 80px 1rem 1rem;
            }

            .welcome-banner {
                padding: 1.5rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .card-body {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>

<!-- Video Background -->
<div class="video-background">
    <video id="bgVideo" autoplay loop muted>
        <source src="{{ asset('video/perfil.mp4') }}" type="video/mp4">
    </video>
</div>
<div class="video-overlay"></div>

<!-- Video Control Button -->
<div class="video-control" id="videoControl" onclick="toggleAudio()">
    <i class="fas fa-volume-mute" id="audioIcon"></i>
    <span>Audio</span>
</div>

<header class="header">
    <div class="logo" onclick="window.location.href='{{ url('/') }}'">
        <img src="{{ asset('img/logp.bl.nara.png') }}" alt="JACHA TOURS">
        <div class="logo-text">
            <span class="brand">JACHA TOURS</span>
            <span class="sub">El Alto · Bolivia</span>
        </div>
    </div>
    
    <div class="nav-buttons">
        <button class="btn-nav" onclick="goBack()">
            <i class="fas fa-arrow-left"></i> <span>Volver</span>
        </button>
        
        <button class="btn-nav" onclick="goToHome()">
            <i class="fas fa-home"></i> <span>Inicio</span>
        </button>
        
        <a href="{{ route('logout') }}" class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
        </a>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
</header>

<div class="container">
    <!-- Welcome Banner -->
    <div class="welcome-banner" data-aos="fade-up">
        <h1 class="welcome-title">
            <i class="fas fa-hand-peace"></i> ¡Hola, <span>{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>!
        </h1>
        <p class="welcome-subtitle">Bienvenido a tu espacio personal. Aquí puedes gestionar tu información y reservas.</p>
        <span class="badge-rol">
            <i class="fas fa-user-tag"></i> {{ ucfirst(Auth::user()->rol) }}
        </span>
    </div>

    <div class="profile-grid">
        <!-- Columna Izquierda -->
        <div>
            <!-- Datos Personales -->
            <div class="card" data-aos="fade-right">
                <div class="card-header">
                    <h2><i class="fas fa-user"></i> Mis Datos</h2>
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="card-body">
                    <div class="avatar-section">
                        <div class="avatar-circle" onclick="document.getElementById('inputFoto').click()">
                            @if(Auth::user()->foto && file_exists(public_path(Auth::user()->foto)))
                                <img src="{{ asset(Auth::user()->foto) }}" alt="Foto perfil" id="avatarImg">
                            @else
                                <i class="fas fa-user-circle" id="avatarIcon"></i>
                            @endif
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i> Cambiar foto
                            </div>
                        </div>
                        <div class="avatar-buttons">
                            <button class="btn-cambiar-foto" onclick="document.getElementById('inputFoto').click()">
                                <i class="fas fa-upload"></i> Subir foto
                            </button>
                            <button class="btn-camara" onclick="abrirCamara()">
                                <i class="fas fa-camera"></i> Tomar foto
                            </button>
                        </div>
                        <input type="file" id="inputFoto" accept="image/*" style="display: none;" onchange="subirFoto(this)">
                    </div>

                    <form id="perfilForm" onsubmit="event.preventDefault(); actualizarPerfil();">
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Nombre</label>
                                <input type="text" value="{{ Auth::user()->nombre }}" disabled>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Apellido</label>
                                <input type="text" value="{{ Auth::user()->apellido }}" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Correo electrónico</label>
                            <input type="email" value="{{ Auth::user()->email }}" disabled>
                            <small class="form-help">
                                <i class="fas fa-info-circle"></i> El correo no se puede modificar por seguridad
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fab fa-whatsapp"></i> Teléfono / WhatsApp</label>
                            <input type="tel" id="telefono" value="{{ Auth::user()->telefono ?? '' }}" placeholder="Ej: 69375465">
                        </div>
                        
                        <button type="submit" class="btn-guardar">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </form>

                    <div class="action-buttons">
                        <button class="btn-action" onclick="abrirModalCambioPass()">
                            <i class="fas fa-key"></i> Cambiar contraseña
                        </button>
                        <a href="{{ route('reservas.mis') }}" class="btn-action">
                            <i class="fas fa-calendar-alt"></i> Mis Reservas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card" data-aos="fade-right" data-aos-delay="100" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2><i class="fas fa-chart-line"></i> Estadísticas</h2>
                    <i class="fas fa-chart-simple"></i>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-ticket-alt"></i>
                            <div class="stat-value">{{ $totalReservas }}</div>
                            <div class="stat-label">Reservas Totales</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-check-circle"></i>
                            <div class="stat-value">{{ $reservasConfirmadas }}</div>
                            <div class="stat-label">Confirmadas</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-star"></i>
                            <div class="stat-value">{{ number_format($promedioPuntuacion, 1) }}</div>
                            <div class="stat-label">Puntuación Media</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-calendar-check"></i>
                            <div class="stat-value">{{ $diasComoCliente }}</div>
                            <div class="stat-label">Días con Nosotros</div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="reservasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha -->
        <div>
            <!-- Historial de Reservas -->
            <div class="card" data-aos="fade-left">
                <div class="card-header">
                    <h2><i class="fas fa-history"></i> Historial de Reservas</h2>
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-body">
                    <div class="reservas-list">
                        @forelse($historialReservas as $reserva)
                            <div class="reserva-item">
                                <div class="reserva-info">
                                    <h4>{{ $reserva->cholet?->nombre ?? 'Cholet' }}</h4>
                                    <p>
                                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} |
                                        <i class="fas fa-users"></i> {{ $reserva->personas }} personas |
                                        <i class="fas fa-ticket"></i> {{ $reserva->experiencia ?? 'Experiencia' }}
                                    </p>
                                </div>
                                <div>
                                    <span class="reserva-estado estado-{{ $reserva->estado }}">
                                        @switch($reserva->estado)
                                            @case('pendiente') <i class="fas fa-hourglass-half"></i> Pendiente @break
                                            @case('pago_reportado') <i class="fas fa-clock"></i> Pago Reportado @break
                                            @case('confirmada') <i class="fas fa-check-circle"></i> Confirmada @break
                                            @case('cancelada') <i class="fas fa-times-circle"></i> Cancelada @break
                                            @default {{ ucfirst($reserva->estado) }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-ticket-alt"></i>
                                <p>Aún no tienes reservas realizadas</p>
                                <a href="{{ url('/#circuito') }}" class="btn-action" style="display: inline-block; margin-top: 1rem;">
                                    <i class="fas fa-search"></i> Explorar Cholets
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Mis Calificaciones -->
            <div class="card" data-aos="fade-left" data-aos-delay="100" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2><i class="fas fa-star"></i> Mis Calificaciones</h2>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <div class="card-body">
                    <div class="reservas-list">
                        @forelse($ratings as $rating)
                            <div class="rating-item">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div style="margin-top: 5px; font-size: 0.85rem;">
                                    <i class="fas fa-building" style="color: #C9A84C;"></i> {{ $rating->cholet?->nombre ?? 'Cholet' }}
                                </div>
                                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4); margin-top: 2px;">
                                    <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($rating->created_at)->format('d/m/Y') }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-star"></i>
                                <p>No has calificado ningún cholet aún</p>
                                <p style="font-size: 0.75rem; margin-top: 0.5rem; color: rgba(255,255,255,0.3);">¡Comparte tu experiencia después de tu visita!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Próximas Reservas -->
            <div class="card" data-aos="fade-left" data-aos-delay="200" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h2><i class="fas fa-calendar-alt"></i> Próximas Visitas</h2>
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="card-body">
                    <div class="reservas-list">
                        @forelse($proximasReservas as $reserva)
                            <div class="reserva-item">
                                <div class="reserva-info">
                                    <h4>{{ $reserva->cholet?->nombre ?? 'Cholet' }}</h4>
                                    <p>
                                        <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
                                        @if($reserva->horario)
                                            <i class="fas fa-clock"></i> {{ $reserva->horario }}
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="reserva-estado estado-{{ $reserva->estado }}">
                                        @switch($reserva->estado)
                                            @case('pendiente') <i class="fas fa-hourglass-half"></i> Pendiente @break
                                            @case('pago_reportado') <i class="fas fa-clock"></i> Pago Reportado @break
                                            @case('confirmada') <i class="fas fa-check-circle"></i> Confirmada @break
                                            @default {{ ucfirst($reserva->estado) }}
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <p>No tienes reservas próximas</p>
                                <a href="{{ url('/#circuito') }}" class="btn-action" style="display: inline-block; margin-top: 1rem;">
                                    <i class="fas fa-search"></i> Explorar Cholets
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <p>
        <i class="fas fa-copyright"></i> 2026 <strong style="color: #C9A84C;">JACHA TOURS</strong> — Arquitectura neoandina · El Alto Bolivia
    </p>
</footer>

<!-- Modal Cambiar Contraseña -->
<div id="modalPass" class="modal">
    <div class="modal-content">
        <h3><i class="fas fa-key"></i> Cambiar Contraseña</h3>
        <form id="cambiarPassForm" onsubmit="event.preventDefault(); cambiarPassword();">
            <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" id="current_password" required>
            </div>
            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" id="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirmar nueva contraseña</label>
                <input type="password" id="new_password_confirmation" required>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-modal-confirm">Actualizar</button>
                <button type="button" class="btn-modal-cancel" onclick="cerrarModalPass()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cámara -->
<div id="webcamModal" class="modal">
    <div class="modal-content">
        <h3><i class="fas fa-camera"></i> Tomar Foto</h3>
        <div id="webcam"></div>
        <div class="webcam-buttons" style="margin-top: 1rem;">
            <button class="btn-modal-confirm" onclick="capturarFoto()">
                <i class="fas fa-camera"></i> Capturar
            </button>
            <button class="btn-modal-cancel" onclick="cerrarCamara()">
                <i class="fas fa-times"></i> Cancelar
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    // Función para volver atrás
    function goBack() {
        if (document.referrer && document.referrer !== "") {
            window.history.back();
        } else {
            window.location.href = '{{ url("/") }}';
        }
    }

    // Función para ir al inicio
    function goToHome() {
        window.location.href = '{{ url("/") }}';
    }

    // Control de audio del video
    const video = document.getElementById('bgVideo');
    let audioMuted = true;

    function toggleAudio() {
        const icon = document.getElementById('audioIcon');
        if (audioMuted) {
            video.muted = false;
            icon.classList.remove('fa-volume-mute');
            icon.classList.add('fa-volume-up');
            audioMuted = false;
            mostrarNotificacion('Audio activado', 'Ahora puedes escuchar el video', 'success');
        } else {
            video.muted = true;
            icon.classList.remove('fa-volume-up');
            icon.classList.add('fa-volume-mute');
            audioMuted = true;
            mostrarNotificacion('Audio desactivado', 'El video se reproduce en silencio', 'info');
        }
    }

    // Función para mostrar notificaciones
    function mostrarNotificacion(titulo, mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        let icon = 'fa-check-circle';
        let borderColor = '#10b981';
        if (tipo === 'error') { icon = 'fa-exclamation-circle'; borderColor = '#ef4444'; }
        if (tipo === 'info') { icon = 'fa-info-circle'; borderColor = '#3b82f6'; }
        toast.style.borderLeftColor = borderColor;
        toast.innerHTML = `<i class="fas ${icon}" style="font-size: 1.2rem;"></i>
            <div><strong>${titulo}</strong><br><small>${mensaje}</small></div>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.animation = 'slideOutRight 0.4s ease'; setTimeout(() => toast.remove(), 400); }, 3500);
    }

    // Actualizar perfil (solo teléfono)
    async function actualizarPerfil() {
        const telefono = document.getElementById('telefono').value;

        try {
            const response = await fetch('{{ route("perfil.actualizar") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ telefono })
            });

            const data = await response.json();
            if (data.success) {
                mostrarNotificacion('¡Éxito!', 'Teléfono actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                mostrarNotificacion('Error', data.message || 'No se pudo actualizar', 'error');
            }
        } catch (error) {
            mostrarNotificacion('Error', 'Ocurrió un problema al guardar', 'error');
        }
    }

    // Subir foto desde archivo
    async function subirFoto(input) {
        if (!input.files || !input.files[0]) return;
        
        const formData = new FormData();
        formData.append('foto', input.files[0]);
        
        try {
            const response = await fetch('{{ route("perfil.foto") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                mostrarNotificacion('¡Foto actualizada!', 'Tu foto de perfil ha sido cambiada', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarNotificacion('Error', data.message || 'No se pudo actualizar la foto', 'error');
            }
        } catch (error) {
            mostrarNotificacion('Error', 'Ocurrió un problema al subir la foto', 'error');
        }
    }

    // Función para la cámara
    let webcamActive = false;

    function abrirCamara() {
        const modal = document.getElementById('webcamModal');
        modal.classList.add('active');
        
        Webcam.set({
            width: 400,
            height: 400,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        
        Webcam.attach('#webcam');
        webcamActive = true;
    }

    function cerrarCamara() {
        const modal = document.getElementById('webcamModal');
        modal.classList.remove('active');
        if (webcamActive) {
            Webcam.reset();
            webcamActive = false;
        }
    }

    function capturarFoto() {
        Webcam.snap(function(data_uri) {
            fetch(data_uri)
                .then(res => res.blob())
                .then(blob => {
                    const formData = new FormData();
                    formData.append('foto', blob, 'webcam-photo.jpg');
                    
                    return fetch('{{ route("perfil.foto") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarNotificacion('¡Foto capturada!', 'Tu foto ha sido guardada correctamente', 'success');
                        cerrarCamara();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        mostrarNotificacion('Error', data.message || 'No se pudo guardar la foto', 'error');
                    }
                })
                .catch(error => {
                    mostrarNotificacion('Error', 'Ocurrió un problema al capturar la foto', 'error');
                });
        });
    }

    // Cambiar contraseña
    const modalPass = document.getElementById('modalPass');
    function abrirModalCambioPass() { modalPass.classList.add('active'); }
    function cerrarModalPass() { modalPass.classList.remove('active'); }

    async function cambiarPassword() {
        const current_password = document.getElementById('current_password').value;
        const new_password = document.getElementById('new_password').value;
        const new_password_confirmation = document.getElementById('new_password_confirmation').value;

        if (new_password !== new_password_confirmation) {
            mostrarNotificacion('Error', 'Las contraseñas nuevas no coinciden', 'error');
            return;
        }

        if (new_password.length < 6) {
            mostrarNotificacion('Error', 'La nueva contraseña debe tener al menos 6 caracteres', 'error');
            return;
        }

        try {
            const response = await fetch('{{ route("perfil.cambiar-password") }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ current_password, new_password, new_password_confirmation })
            });

            const data = await response.json();
            if (data.success) {
                mostrarNotificacion('¡Contraseña actualizada!', 'Tu contraseña ha sido cambiada exitosamente', 'success');
                cerrarModalPass();
                document.getElementById('cambiarPassForm').reset();
            } else {
                mostrarNotificacion('Error', data.message || 'No se pudo cambiar la contraseña', 'error');
            }
        } catch (error) {
            mostrarNotificacion('Error', 'Ocurrió un problema', 'error');
        }
    }

    window.onclick = function(event) {
        if (event.target === modalPass) cerrarModalPass();
        if (event.target === document.getElementById('webcamModal')) cerrarCamara();
    }

    // Gráfico
    const ctx = document.getElementById('reservasChart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($mesesLabels) !!},
                datasets: [{
                    label: 'Reservas',
                    data: {!! json_encode($reservasPorMes) !!},
                    borderColor: '#C9A84C',
                    backgroundColor: 'rgba(201, 168, 76, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#C9A84C',
                    pointBorderColor: '#000',
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { 
                        labels: { 
                            color: 'rgba(255,255,255,0.6)',
                            font: { size: 12 }
                        } 
                    }
                },
                scales: {
                    y: { 
                        grid: { color: 'rgba(255,255,255,0.05)' }, 
                        ticks: { color: 'rgba(255,255,255,0.5)', stepSize: 1 } 
                    },
                    x: { 
                        grid: { color: 'rgba(255,255,255,0.05)' }, 
                        ticks: { color: 'rgba(255,255,255,0.5)' } 
                    }
                }
            }
        });
    }
</script>
</body>
</html>