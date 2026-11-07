<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Guía') | Jacha Tours</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    
    <style>
        /* ==============================================
           VARIABLES Y RESET
        ============================================== */
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

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Jost', sans-serif;
            background: var(--black);
            color: var(--text);
            min-height: 100vh;
        }

        /* ==============================================
           HEADER PROFESIONAL
        ============================================== */
        .guia-header {
            background: linear-gradient(135deg, var(--surface) 0%, var(--deep) 100%);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon i {
            font-size: 1.4rem;
            color: var(--black);
        }

        .logo h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold2);
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        .logo p {
            font-size: 0.6rem;
            color: var(--muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.3rem 0.8rem 0.3rem 0.3rem;
            border-radius: 50px;
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--black);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gold2);
        }

        .user-role {
            font-size: 0.6rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logout-btn {
            background: var(--gold-dim);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 40px;
            color: white;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--danger);
            transform: translateY(-2px);
        }

        /* ==============================================
           MENÚ DE NAVEGACIÓN
        ============================================== */
        .guia-nav {
            background: rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid var(--border);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            gap: 0.5rem;
        }

        .nav-item {
            padding: 0.8rem 1.2rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-item i {
            font-size: 1.1rem;
        }

        .nav-item:hover {
            color: var(--gold);
            background: rgba(201, 168, 76, 0.05);
        }

        .nav-item.active {
            color: var(--gold2);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--gold), var(--gold2));
        }

        /* ==============================================
           CONTENIDO PRINCIPAL
        ============================================== */
        .guia-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeInUp 0.4s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==============================================
           TARJETAS Y COMPONENTES
        ============================================== */
        .page-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid var(--border);
        }

        .page-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--gold2);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .page-header p {
            color: var(--muted);
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.2rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.2rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, var(--gold), var(--gold2));
            border-radius: 3px 0 0 3px;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold-dim);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold2);
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.3rem;
        }

        /* ==============================================
           RESPONSIVE
        ============================================== */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
            }
            
            .user-info {
                width: 100%;
                justify-content: center;
            }
            
            .nav-container {
                overflow-x: auto;
                padding: 0 1rem;
            }
            
            .guia-main {
                padding: 1rem;
            }
            
            .stats-grid {
                gap: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- HEADER -->
    <header class="guia-header">
        <div class="header-container">
            <div class="logo">
                <div class="logo-icon">
                    <i class="ti ti-compass"></i>
                </div>
                <div>
                    <h2>Jacha Tours</h2>
                    <p>Panel de Guías</p>
                </div>
            </div>
            
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('guia_nombre') ?? 'G', 0, 1)) }}
                </div>
                <div class="user-details">
                    <span class="user-name">{{ session('guia_nombre') ?? 'Guía' }}</span>
                    <span class="user-role">Guía Turístico</span>
                </div>
                <form method="POST" action="{{ route('guia.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="ti ti-logout"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- MENÚ DE NAVEGACIÓN -->
    <nav class="guia-nav">
        <div class="nav-container">
            <a href="{{ route('guia.dashboard') }}" class="nav-item {{ request()->routeIs('guia.dashboard') ? 'active' : '' }}">
                <i class="ti ti-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('guia.calendario') }}" class="nav-item {{ request()->routeIs('guia.calendario') ? 'active' : '' }}">
                <i class="ti ti-calendar"></i> Calendario
            </a>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="guia-main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>