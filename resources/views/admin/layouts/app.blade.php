<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — Jacha Tours</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Jost', sans-serif;
            background: var(--black);
            color: var(--text);
            min-height: 100vh;
        }

        /* LAYOUT */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
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

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: var(--black);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gold-dim);
            border-radius: 4px;
        }

        /* Línea dorada lateral derecha */
        .sidebar::after {
            content: '';
            position: absolute;
            top: 0; right: -1px; bottom: 0;
            width: 1px;
            background: linear-gradient(180deg, transparent, var(--gold-d) 30%, var(--gold-d) 70%, transparent);
            opacity: .5;
        }

        /* Logo */
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
        }

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

        /* Navegación */
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
            transition: background .2s, color .2s, border-color .2s;
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
        }

        .menu a.active i { opacity: 1; color: var(--gold); }

        /* Indicador activo */
        .menu a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 2px;
            background: var(--gold);
            border-radius: 0 2px 2px 0;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 1rem 1.4rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .75rem;
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
        }
        .sidebar-logout:hover { color: var(--danger); }

        /* MAIN CONTENT */
        .main {
            flex: 1;
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
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

        /* Page content */
        .page-content {
            flex: 1;
            padding: 1.8rem 2rem 2.5rem;
        }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--gold);
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.open {
                transform: translateX(0);
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
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--black); }
        ::-webkit-scrollbar-thumb {
            background: var(--border2);
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold-d); }
    </style>
</head>

<body>
<div class="layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

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

        {{-- Menú --}}
        <nav class="sidebar-nav">

            {{-- Principal --}}
            <div class="nav-section">
                <div class="nav-section-header" onclick="toggleSection(this)">
                    <span class="nav-section-label">Principal</span>
                    <i class="ti ti-chevron-right nav-section-icon"></i>
                </div>
                <div class="nav-section-content">
                    <div class="menu">
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="ti ti-layout-dashboard"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>

            {{-- Gestión --}}
            <div class="nav-section">
                <div class="nav-section-header" onclick="toggleSection(this)">
                    <span class="nav-section-label">Gestión</span>
                    <i class="ti ti-chevron-right nav-section-icon"></i>
                </div>
                <div class="nav-section-content">
                    <div class="menu">
                        <a href="{{ route('admin.cholets.index') }}" class="{{ request()->routeIs('admin.cholets.*') ? 'active' : '' }}">
                            <i class="ti ti-building-estate"></i> Cholets
                        </a>
                        <a href="{{ route('admin.reservas.index') }}" class="{{ request()->routeIs('admin.reservas.*') ? 'active' : '' }}">
                            <i class="ti ti-calendar-event"></i> Reservas
                        </a>
                        <a href="{{ route('admin.experiencias.index') }}" class="{{ request()->routeIs('admin.experiencias.*') ? 'active' : '' }}">
                            <i class="ti ti-ticket"></i> Experiencias
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="ti ti-users"></i> Usuarios
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sistema --}}
            <div class="nav-section">
                <div class="nav-section-header" onclick="toggleSection(this)">
                    <span class="nav-section-label">Sistema</span>
                    <i class="ti ti-chevron-right nav-section-icon"></i>
                </div>
                <div class="nav-section-content">
                    <div class="menu">
                        <a href="{{ route('admin.guias.index') }}" class="{{ request()->routeIs('admin.guias.*') ? 'active' : '' }}">
                            <i class="ti ti-map-pin"></i> Guías
                        </a>
                        <a href="{{ route('admin.configuracion.index') }}" class="{{ request()->routeIs('admin.configuracion.*') ? 'active' : '' }}">
                            <i class="ti ti-settings"></i> Configuración
                        </a>
                    </div>
                </div>
            </div>

        </nav>

        {{-- Footer del sidebar --}}
        <div class="sidebar-footer">
            @php
                $nombreAdmin = auth()->user()->nombre ?? auth()->user()->name ?? 'Admin';
                $inicial = strtoupper(substr($nombreAdmin, 0, 1));
            @endphp
            <div class="avatar">{{ $inicial }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $nombreAdmin }}</div>
                <div class="sidebar-user-role">Administrador</div>
            </div>
            <a href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="sidebar-logout" title="Cerrar sesión">
                <i class="ti ti-logout"></i>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>

    </aside>

    {{-- MAIN --}}
    <div class="main">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="ti ti-menu-2"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page-title', 'Panel')</div>
                    <div class="topbar-breadcrumb">
                        Admin <span>›</span> @yield('page-title', 'Panel')
                    </div>
                </div>
            </div>

            <div class="topbar-right">
                <div class="topbar-time">
                    <span id="topbar-date">—</span>
                    <strong id="topbar-clock">—</strong>
                </div>
                <div class="topbar-divider"></div>
                <div class="topbar-admin">
                    <div class="topbar-admin-avatar">{{ $inicial ?? 'A' }}</div>
                    <span class="topbar-admin-name">{{ $nombreAdmin ?? 'Admin' }}</span>
                </div>
            </div>
        </header>

        {{-- Contenido --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>

</div>

<script>
/* Reloj en topbar */
(function () {
    const dateEl  = document.getElementById('topbar-date');
    const clockEl = document.getElementById('topbar-clock');
    function update() {
        const now = new Date();
        dateEl.textContent  = now.toLocaleDateString('es-BO', { weekday:'short', day:'2-digit', month:'short' });
        clockEl.textContent = now.toLocaleTimeString('es-BO', { hour:'2-digit', minute:'2-digit' });
    }
    update();
    setInterval(update, 1000);
})();

/* Toggle secciones del sidebar */
function toggleSection(header) {
    const icon = header.querySelector('.nav-section-icon');
    const content = header.nextElementSibling;
    
    if (content.classList.contains('open')) {
        content.classList.remove('open');
        icon.classList.remove('open');
    } else {
        content.classList.add('open');
        icon.classList.add('open');
    }
    
    // Guardar estado en localStorage
    const sections = {};
    document.querySelectorAll('.nav-section-content').forEach((sec, index) => {
        sections[index] = sec.classList.contains('open');
    });
    localStorage.setItem('sidebarSections', JSON.stringify(sections));
}

// Restaurar estado guardado
function restoreSections() {
    const saved = localStorage.getItem('sidebarSections');
    if (saved) {
        const sections = JSON.parse(saved);
        document.querySelectorAll('.nav-section-content').forEach((sec, index) => {
            if (sections[index]) {
                sec.classList.add('open');
                sec.previousElementSibling.querySelector('.nav-section-icon').classList.add('open');
            }
        });
    } else {
        // Abrir sección Gestión por defecto
        const gestionSection = document.querySelectorAll('.nav-section-content')[1];
        if (gestionSection) {
            gestionSection.classList.add('open');
            gestionSection.previousElementSibling.querySelector('.nav-section-icon').classList.add('open');
        }
    }
}

// Abrir sección activa automáticamente
function openActiveSection() {
    const activeLink = document.querySelector('.menu a.active');
    if (activeLink) {
        const sectionContent = activeLink.closest('.nav-section-content');
        if (sectionContent && !sectionContent.classList.contains('open')) {
            sectionContent.classList.add('open');
            const icon = sectionContent.previousElementSibling.querySelector('.nav-section-icon');
            if (icon) icon.classList.add('open');
        }
    }
}

// Mobile menu
const mobileBtn = document.getElementById('mobileMenuBtn');
const sidebar = document.getElementById('sidebar');

if (mobileBtn) {
    mobileBtn.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });
}

// Cerrar sidebar al hacer clic fuera en mobile
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target) && !mobileBtn.contains(e.target)) {
            sidebar.classList.remove('open');
        }
    }
});

// Inicializar
restoreSections();
openActiveSection();
</script>

</body>
</html>