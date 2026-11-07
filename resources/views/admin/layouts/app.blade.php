<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Jacha Tours</title>

    {{-- Preconexión para fuentes más rápidas --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Estilos adicionales por página --}}
    @stack('styles')

    <style>
        /* ============================
           VARIABLES
           ============================ */
        :root {
            --black:    #080808;
            --deep:     #0e0e0e;
            --surface:  #111111;
            --sidebar:  #0a0a0a;
            --border:   #221a08;
            --border2:  #2a2010;
            --gold:     #c9a84c;
            --gold2:    #e8cc80;
            --gold-d:   #7a5f28;
            --gold-dim: #3d2e10;
            --text:     #f0ead8;
            --muted:    #6b5e3e;
            --danger:   #c0392b;
            --success:  #27ae60;
            --warning:  #f39c12;
            --info:     #3498db;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Jost', sans-serif;
            background: var(--black);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.5;
        }

        /* ============================
           BARRA DE PROGRESO DE SCROLL
           ============================ */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, var(--gold-d), var(--gold), var(--gold2));
            z-index: 9999;
            transition: width 0.1s linear;
            box-shadow: 0 0 8px rgba(201, 168, 76, 0.5);
        }

        /* ============================
           BOTÓN VOLVER ARRIBA
           ============================ */
        .scroll-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--surface);
            border: 1px solid var(--gold-d);
            color: var(--gold);
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 90;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-to-top:hover {
            background: var(--gold-dim);
            border-color: var(--gold);
            color: var(--gold2);
            box-shadow: 0 4px 16px rgba(201, 168, 76, 0.3);
        }

        .scroll-to-top:active {
            transform: scale(0.95);
        }

        /* ============================
           LAYOUT PRINCIPAL
           ============================ */
        .layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ============================
           OVERLAY PARA MOBILE
           ============================ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(2px);
            z-index: 99;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* ============================
           SIDEBAR
           ============================ */
        .sidebar {
            width: 280px;
            flex-shrink: 0;
            background: var(--sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: var(--gold-dim) var(--black);
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: var(--black); }
        .sidebar::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 4px; }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0; right: -1px; bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, transparent, var(--gold-d) 30%, var(--gold-d) 70%, transparent);
            opacity: .5;
            pointer-events: none;
        }

        .sidebar-logo {
            padding: 2rem 1.6rem 1.6rem;
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        .sidebar-logo::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 1.6rem; right: 1.6rem;
            height: 1px;
            background: linear-gradient(90deg, var(--gold-d), transparent);
        }

        .logo-img-wrap {
            display: flex;
            align-items: center;
            gap: .85rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        
        .logo-img-wrap:hover { opacity: 0.85; }

        .logo-img-wrap img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 10px rgba(201,168,76,.4));
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gold2);
            letter-spacing: .05em;
            line-height: 1.1;
        }

        .logo-sub {
            font-size: .62rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: .18em;
            text-transform: uppercase;
            margin-top: .15rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.4rem .9rem;
        }

        .nav-section {
            margin-bottom: 1rem;
        }

        .nav-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            padding: .5rem .7rem;
            border-radius: 4px;
            transition: background .2s;
            user-select: none;
        }

        .nav-section-header:hover {
            background: rgba(201,168,76,.05);
        }

        .nav-section-label {
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gold-dim);
        }

        .nav-section-icon {
            color: var(--gold-dim);
            font-size: .8rem;
            transition: transform 0.3s;
        }

        .nav-section-icon.open {
            transform: rotate(90deg);
        }

        .nav-section-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .nav-section-content.open {
            max-height: 500px;
        }

        .menu {
            padding-left: .5rem;
            margin-top: .3rem;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem .9rem;
            margin-bottom: 2px;
            border-radius: 3px;
            color: var(--muted);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            letter-spacing: .02em;
            transition: all .2s;
            border: 1px solid transparent;
            position: relative;
        }

        .menu a i {
            font-size: 1.1rem;
            flex-shrink: 0;
            opacity: .7;
            transition: opacity .2s, color .2s;
        }

        .menu a:hover {
            background: rgba(201,168,76,.06);
            color: var(--text);
            border-color: var(--border2);
        }

        .menu a:hover i { opacity: 1; color: var(--gold); }

        .menu a.active {
            background: rgba(201,168,76,.1);
            color: var(--gold2);
            border-color: var(--gold-dim);
            font-weight: 600;
        }

        .menu a.active i { opacity: 1; color: var(--gold); }

        .menu a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 2px;
            background: var(--gold);
            border-radius: 0 2px 2px 0;
        }

        .badge-count {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
            line-height: 1.2;
        }

        .sidebar-footer {
            padding: 1rem 1.4rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
            background: rgba(0,0,0,0.2);
        }

        .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
            border: 1px solid var(--gold-d);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: .85rem;
            color: var(--gold2);
            flex-shrink: 0;
        }

        .sidebar-user-info { flex: 1; min-width: 0; }
        
        .sidebar-user-name {
            font-size: .8rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar-user-role {
            font-size: .67rem;
            color: var(--gold-d);
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        
        .sidebar-logout {
            color: var(--muted);
            text-decoration: none;
            font-size: 1rem;
            transition: color .2s;
            flex-shrink: 0;
            padding: 0.3rem;
            border-radius: 4px;
        }
        
        .sidebar-logout:hover { 
            color: var(--danger);
            background: rgba(192, 57, 43, 0.1);
        }

        .main {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            flex-shrink: 0;
        }

        .topbar::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, var(--gold-d), transparent 60%);
            opacity: .5;
            pointer-events: none;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: .01em;
        }

        .topbar-breadcrumb {
            font-size: .72rem;
            color: var(--muted);
            letter-spacing: .08em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .topbar-breadcrumb span { color: var(--gold-d); }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar-time {
            font-size: .75rem;
            color: var(--muted);
            letter-spacing: .04em;
            text-align: right;
        }

        .topbar-time strong {
            display: block;
            color: var(--gold);
            font-size: .82rem;
            font-weight: 600;
        }

        .topbar-divider {
            width: 1px; height: 28px;
            background: var(--border2);
        }

        .topbar-admin {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .4rem .75rem;
            border-radius: 3px;
            border: 1px solid var(--border2);
            background: rgba(255,255,255,.02);
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .topbar-admin-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
            border: 1px solid var(--gold-d);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: .7rem;
            color: var(--gold2);
        }

        .topbar-admin-name {
            font-size: .8rem;
            font-weight: 500;
            color: var(--text);
        }

        .page-content {
            flex: 1;
            padding: 1.8rem 2rem 2.5rem;
            animation: fadeInUp 0.3s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gold);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.4rem;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(201,168,76,0.1);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0,0,0,0.5);
            }
            
            .sidebar-overlay {
                display: block;
            }
            
            .main {
                margin-left: 0;
            }
            .mobile-menu-btn {
                display: block;
            }
            .topbar {
                padding: 0 1rem;
            }
            .page-content {
                padding: 1.2rem 1rem 2rem;
            }
            
            .topbar-time {
                display: none;
            }
            .topbar-divider:last-of-type {
                display: none;
            }

            .scroll-to-top {
                width: 36px;
                height: 36px;
                bottom: 1.5rem;
                right: 1rem;
                font-size: 1rem;
            }
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--border2) var(--black);
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--black); }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold-d); }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>
</head>

<body>

{{-- BARRA DE PROGRESO DE SCROLL --}}
<div class="scroll-progress" id="scrollProgress"></div>

{{-- BOTÓN VOLVER ARRIBA --}}
<button class="scroll-to-top" id="scrollToTop" title="Volver arriba" aria-label="Volver arriba">
    <i class="ti ti-chevron-up" aria-hidden="true"></i>
</button>

<div class="layout">

    {{-- OVERLAY PARA MOBILE --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="sidebar" id="sidebar" aria-label="Menú principal">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="logo-img-wrap">
                <img src="{{ asset('img/logo.png') }}" alt="Jacha Tours">
                <div class="logo-text">
                    <span class="logo-name">Jacha Tours</span>
                    <span class="logo-sub">Panel Admin</span>
                </div>
            </a>
        </div>

        {{-- Menú de navegación --}}
        <nav class="sidebar-nav" aria-label="Navegación principal">
            
            {{-- Sección Principal --}}
            <div class="nav-section">
                <div class="nav-section-header" onclick="toggleSection(this)" aria-expanded="false" role="button" tabindex="0">
                    <span class="nav-section-label">Principal</span>
                    <i class="ti ti-chevron-right nav-section-icon" aria-hidden="true"></i>
                </div>
                <div class="nav-section-content">
                    <div class="menu">
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sección Gestión --}}
            <div class="nav-section">
                <div class="nav-section-header" onclick="toggleSection(this)" aria-expanded="false" role="button" tabindex="0">
                    <span class="nav-section-label">Gestión</span>
                    <i class="ti ti-chevron-right nav-section-icon" aria-hidden="true"></i>
                </div>
                <div class="nav-section-content">
                    <div class="menu">
                        <a href="{{ route('admin.cholets.index') }}" class="{{ request()->routeIs('admin.cholets.*') ? 'active' : '' }}">
                            <i class="ti ti-building-estate" aria-hidden="true"></i> Cholets
                        </a>
                        <a href="{{ route('admin.reservas.index') }}" class="{{ request()->routeIs('admin.reservas.*') ? 'active' : '' }}">
                            <i class="ti ti-calendar-event" aria-hidden="true"></i> Reservas
                        </a>
                        <a href="{{ route('admin.asignaciones.index') }}" class="{{ request()->routeIs('admin.asignaciones.*') ? 'active' : '' }}">
                            <i class="ti ti-user-check" aria-hidden="true"></i> Asignar Guías
                        </a>
                        <a href="{{ route('admin.experiencias.index') }}" class="{{ request()->routeIs('admin.experiencias.*') ? 'active' : '' }}">
                            <i class="ti ti-ticket" aria-hidden="true"></i> Experiencias
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sección Sistema --}}
            <div class="nav-section">
                <div class="nav-section-header" onclick="toggleSection(this)" aria-expanded="false" role="button" tabindex="0">
                    <span class="nav-section-label">Sistema</span>
                    <i class="ti ti-chevron-right nav-section-icon" aria-hidden="true"></i>
                </div>
                <div class="nav-section-content">
                    <div class="menu">
                        <a href="{{ route('admin.guias.index') }}" class="{{ request()->routeIs('admin.guias.*') ? 'active' : '' }}">
                            <i class="ti ti-map-pin" aria-hidden="true"></i> Guías
                        </a>
                        <a href="{{ route('admin.emails.index') }}" class="{{ request()->routeIs('admin.emails.*') ? 'active' : '' }}">
                            <i class="ti ti-mail" aria-hidden="true"></i> Gmails
                        </a>
                    </div>
                </div>
            </div>

        </nav>

        {{-- Footer del sidebar --}}
        <div class="sidebar-footer">
            @php
                $adminUser = auth()->guard('admin')->user();
                $nombreAdmin = $adminUser->nombre ?? $adminUser->name ?? 'Admin';
                $inicial = strtoupper(mb_substr($nombreAdmin, 0, 1));
            @endphp
            <div class="avatar" aria-hidden="true">{{ $inicial }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name" title="{{ $nombreAdmin }}">{{ $nombreAdmin }}</div>
                <div class="sidebar-user-role">Administrador</div>
            </div>
            <a href="#" 
               onclick="event.preventDefault(); confirmarLogout()" 
               class="sidebar-logout" 
               title="Cerrar sesión"
               aria-label="Cerrar sesión">
                <i class="ti ti-logout" aria-hidden="true"></i>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

    </aside>

    {{-- ==================== MAIN ==================== --}}
    <div class="main">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Abrir menú" aria-expanded="false">
                    <i class="ti ti-menu-2" aria-hidden="true"></i>
                </button>
                <div>
                    <h1 class="topbar-title">@yield('page-title', 'Panel')</h1>
                    <nav class="topbar-breadcrumb" aria-label="Breadcrumb">
                        Admin <span aria-hidden="true">›</span> @yield('page-title', 'Panel')
                    </nav>
                </div>
            </div>

            <div class="topbar-right">
                <div class="topbar-time" aria-live="polite" aria-atomic="true">
                    <span id="topbar-date">—</span>
                    <strong id="topbar-clock">—</strong>
                </div>
                <div class="topbar-divider" aria-hidden="true"></div>
                
                {{-- Info del admin --}}
                <div class="topbar-admin">
                    <div class="topbar-admin-avatar" aria-hidden="true">{{ $inicial ?? 'A' }}</div>
                    <span class="topbar-admin-name">{{ $nombreAdmin ?? 'Admin' }}</span>
                </div>
            </div>
        </header>

        {{-- Contenido de la página --}}
        <main class="page-content" id="mainContent">
            {{-- Mostrar mensajes flash --}}
            @if(session('success'))
                <div class="alert alert-success" style="background: var(--success); color: white; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; animation: fadeInUp 0.3s ease; display: flex; align-items: center; justify-content: space-between;">
                    <span>✓ {{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">&times;</button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error" style="background: var(--danger); color: white; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; animation: fadeInUp 0.3s ease; display: flex; align-items: center; justify-content: space-between;">
                    <span>✕ {{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>

</div>

{{-- Scripts --}}
<script>
(function() {
    'use strict';
    
    // RELOJ EN TOPBAR
    const dateEl = document.getElementById('topbar-date');
    const clockEl = document.getElementById('topbar-clock');
    
    function actualizarReloj() {
        if (!dateEl || !clockEl) return;
        const ahora = new Date();
        dateEl.textContent = ahora.toLocaleDateString('es-BO', { 
            weekday: 'short', 
            day: '2-digit', 
            month: 'short' 
        });
        clockEl.textContent = ahora.toLocaleTimeString('es-BO', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });
    }
    
    actualizarReloj();
    setInterval(actualizarReloj, 30000);

    // BARRA DE PROGRESO DE SCROLL
    const scrollProgress = document.getElementById('scrollProgress');
    
    function actualizarBarraProgreso() {
        if (!scrollProgress) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
        
        scrollProgress.style.width = scrollPercent + '%';
    }
    
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                actualizarBarraProgreso();
                actualizarBotonVolverArriba();
                ticking = false;
            });
            ticking = true;
        }
    });

    // BOTÓN VOLVER ARRIBA
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    function actualizarBotonVolverArriba() {
        if (!scrollToTopBtn) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 300) {
            scrollToTopBtn.classList.add('visible');
        } else {
            scrollToTopBtn.classList.remove('visible');
        }
    }
    
    if (scrollToTopBtn) {
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // TOGGLE DE SECCIONES DEL SIDEBAR
    const STORAGE_KEY = 'jacha_sidebar_sections';
    
    window.toggleSection = function(header) {
        const content = header.nextElementSibling;
        const isOpen = content.classList.contains('open');
        
        if (isOpen) {
            content.classList.remove('open');
            const icon = header.querySelector('.nav-section-icon');
            if (icon) icon.classList.remove('open');
            header.setAttribute('aria-expanded', 'false');
        } else {
            content.classList.add('open');
            const icon = header.querySelector('.nav-section-icon');
            if (icon) icon.classList.add('open');
            header.setAttribute('aria-expanded', 'true');
        }
        
        guardarEstado();
    };
    
    function guardarEstado() {
        const sections = {};
        document.querySelectorAll('.nav-section-content').forEach((sec, index) => {
            sections[index] = sec.classList.contains('open');
        });
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(sections));
        } catch(e) {}
    }
    
    function restaurarEstado() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const sections = JSON.parse(saved);
                document.querySelectorAll('.nav-section-content').forEach((sec, index) => {
                    if (sections[index]) {
                        sec.classList.add('open');
                        const icon = sec.previousElementSibling?.querySelector('.nav-section-icon');
                        if (icon) {
                            icon.classList.add('open');
                            sec.previousElementSibling?.setAttribute('aria-expanded', 'true');
                        }
                    }
                });
                return;
            }
        } catch(e) {}
        
        abrirSeccionActiva();
    }
    
    function abrirSeccionActiva() {
        const activeLink = document.querySelector('.menu a.active');
        if (activeLink) {
            const sectionContent = activeLink.closest('.nav-section-content');
            if (sectionContent && !sectionContent.classList.contains('open')) {
                sectionContent.classList.add('open');
                const icon = sectionContent.previousElementSibling?.querySelector('.nav-section-icon');
                if (icon) {
                    icon.classList.add('open');
                    sectionContent.previousElementSibling?.setAttribute('aria-expanded', 'true');
                }
                return;
            }
        }
        
        const gestionSection = document.querySelectorAll('.nav-section-content')[1];
        if (gestionSection && !gestionSection.classList.contains('open')) {
            gestionSection.classList.add('open');
            const icon = gestionSection.previousElementSibling?.querySelector('.nav-section-icon');
            if (icon) {
                icon.classList.add('open');
                gestionSection.previousElementSibling?.setAttribute('aria-expanded', 'true');
            }
        }
    }

    // MOBILE MENU
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    function abrirSidebar() {
        sidebar.classList.add('open');
        if (overlay) overlay.classList.add('active');
        if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    
    function cerrarSidebar() {
        sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
        if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    
    if (mobileBtn) {
        mobileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.contains('open') ? cerrarSidebar() : abrirSidebar();
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', cerrarSidebar);
    }
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            cerrarSidebar();
            if (mobileBtn) mobileBtn.focus();
        }
    });
    
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !mobileBtn?.contains(e.target)) {
                cerrarSidebar();
            }
        }
    });

    // LOGOUT CON CONFIRMACIÓN
    window.confirmarLogout = function() {
        if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
            document.getElementById('logout-form').submit();
        }
    };

    // INICIALIZAR
    function inicializar() {
        restaurarEstado();
        actualizarBarraProgreso();
        actualizarBotonVolverArriba();
    }
    
    inicializar();
    
})();
</script>

{{-- Scripts adicionales por página --}}
@stack('scripts')

</body>
</html>