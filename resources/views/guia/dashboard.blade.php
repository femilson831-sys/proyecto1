<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Guía | Jacha Tours</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Jost', sans-serif;
            background: #080808;
            color: #f0ead8;
        }

        /* ==============================================
           HEADER PREMIUM
        ============================================== */
        .header {
            background: linear-gradient(135deg, #111111 0%, #0a0a0a 100%);
            border-bottom: 1px solid #2a2010;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #c9a84c, #e8cc80);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-icon i {
            font-size: 1.4rem;
            color: #080808;
        }
        
        .header h2 {
            font-family: 'Playfair Display', serif;
            color: #e8cc80;
            font-size: 1.2rem;
        }
        
        .header p {
            font-size: 0.65rem;
            color: #6b5e3e;
            margin-top: 0.2rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.3rem 0.8rem 0.3rem 0.3rem;
            border-radius: 50px;
            border: 1px solid #2a2010;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #c9a84c, #e8cc80);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: bold;
            color: #080808;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #e8cc80;
        }
        
        .user-role {
            font-size: 0.6rem;
            color: #6b5e3e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .logout-btn {
            background: #3d2e10;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 40px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
        }
        
        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        /* ==============================================
           MENÚ DE NAVEGACIÓN
        ============================================== */
        .nav-bar {
            background: rgba(0, 0, 0, 0.3);
            border-bottom: 1px solid #2a2010;
            padding: 0 2rem;
        }
        
        .nav-links {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 0.5rem;
        }
        
        .nav-link {
            padding: 0.8rem 1.2rem;
            color: #6b5e3e;
            text-decoration: none;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            position: relative;
        }
        
        .nav-link:hover {
            color: #c9a84c;
            background: rgba(201, 168, 76, 0.05);
        }
        
        .nav-link.active {
            color: #e8cc80;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #c9a84c, #e8cc80);
        }
        
        /* ==============================================
           CONTENEDOR PRINCIPAL
        ============================================== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeInUp 0.4s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==============================================
           WELCOME CARD PREMIUM
        ============================================== */
        .welcome-card {
            background: linear-gradient(135deg, #111111 0%, #0e0e0e 100%);
            border: 1px solid #2a2010;
            border-radius: 16px;
            padding: 1.8rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #c9a84c, #e8cc80);
        }
        
        .welcome-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(201,168,76,0.05) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .welcome-card h1 {
            color: #e8cc80;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
        }
        
        .welcome-card p {
            color: #6b5e3e;
            font-size: 0.85rem;
        }
        
        /* ==============================================
           INFO GRID
        ============================================== */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .info-item {
            background: #0e0e0e;
            border: 1px solid #2a2010;
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.3s;
        }
        
        .info-item:hover {
            border-color: #c9a84c;
            transform: translateY(-2px);
        }
        
        .info-icon {
            width: 44px;
            height: 44px;
            background: rgba(201, 168, 76, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #c9a84c;
        }
        
        .info-label {
            font-size: 0.7rem;
            color: #6b5e3e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 0.9rem;
            font-weight: 500;
            color: #f0ead8;
        }
        
        /* ==============================================
           STATS GRID
        ============================================== */
        .section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem 0 1rem;
            padding-left: 0.8rem;
            border-left: 3px solid #c9a84c;
        }
        
        .section-title h3 {
            font-size: 1rem;
            color: #e8cc80;
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .stat-card {
            background: #111111;
            border: 1px solid #2a2010;
            border-radius: 12px;
            padding: 1.2rem;
            text-align: center;
            transition: all 0.3s;
            position: relative;
        }
        
        .stat-card:hover {
            border-color: #c9a84c;
            transform: translateY(-3px);
        }
        
        .stat-icon {
            font-size: 2rem;
            color: #c9a84c;
            margin-bottom: 0.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #e8cc80;
            font-family: 'Playfair Display', serif;
        }
        
        .stat-label {
            font-size: 0.7rem;
            color: #6b5e3e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* ==============================================
           PRÓXIMAS RESERVAS
        ============================================== */
        .reservas-list {
            margin-top: 1rem;
        }
        
        .reserva-item {
            background: #0e0e0e;
            border: 1px solid #2a2010;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: all 0.3s;
        }
        
        .reserva-item:hover {
            border-color: #c9a84c;
        }
        
        .reserva-fecha {
            background: #3d2e10;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            color: #e8cc80;
        }
        
        .badge {
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .badge-confirmada {
            background: rgba(39, 174, 96, 0.2);
            color: #27ae60;
        }
        
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b5e3e;
        }
        
        /* ==============================================
           RESPONSIVE
        ============================================== */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            .header-left {
                flex-direction: column;
            }
            
            .user-info {
                width: 100%;
                justify-content: center;
            }
            
            .nav-bar {
                padding: 0 1rem;
                overflow-x: auto;
            }
            
            .container {
                padding: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .reserva-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo-icon">
                <i class="ti ti-compass"></i>
            </div>
            <div>
                <h2>Jacha Tours</h2>
                <p>Panel de Guías Turísticos</p>
            </div>
        </div>
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr($guia->nombre ?? 'G', 0, 1)) }}{{ strtoupper(substr($guia->apellido ?? 'U', 0, 1)) }}
            </div>
            <div class="user-details">
                <span class="user-name">{{ $guia->nombre }} {{ $guia->apellido }}</span>
                <span class="user-role">Guía Turístico</span>
            </div>
            <form method="POST" action="{{ route('guia.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="ti ti-logout"></i> Salir
                </button>
            </form>
        </div>
    </div>

    <!-- Menú de navegación -->
    <div class="nav-bar">
        <div class="nav-links">
            <a href="{{ route('guia.dashboard') }}" class="nav-link active">
                <i class="ti ti-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('guia.calendario') }}" class="nav-link">
                <i class="ti ti-calendar"></i> Calendario
            </a>
            <a href="{{ route('guia.reservas') }}" class="nav-link">
                <i class="ti ti-ticket"></i> Mis Reservas
            </a>
        </div>
    </div>

    <div class="container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h1>¡Bienvenido, {{ $guia->nombre }}!</h1>
            <p>Has iniciado sesión correctamente en el panel de guías de Jacha Tours. Aquí podrás gestionar tus horarios y ver tus reservas asignadas.</p>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-icon"><i class="ti ti-mail"></i></div>
                    <div>
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $guia->email }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="ti ti-phone"></i></div>
                    <div>
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">{{ $guia->telefono ?? 'No registrado' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="ti ti-star"></i></div>
                    <div>
                        <div class="info-label">Especialidad</div>
                        <div class="info-value">{{ $guia->especialidad ?? 'General' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon"><i class="ti ti-clock"></i></div>
                    <div>
                        <div class="info-label">Experiencia</div>
                        <div class="info-value">{{ $guia->anos_experiencia ?? 0 }} años</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="section-title">
            <i class="ti ti-chart-bar"></i>
            <h3>Resumen de Actividad</h3>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-ticket"></i></div>
                <div class="stat-number">{{ $stats['total_reservas'] ?? 0 }}</div>
                <div class="stat-label">Reservas Asignadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-calendar-event"></i></div>
                <div class="stat-number">{{ $stats['proximas_reservas'] ?? 0 }}</div>
                <div class="stat-label">Próximos Tours</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-clock"></i></div>
                <div class="stat-number">{{ $stats['horarios_mes'] ?? 0 }}</div>
                <div class="stat-label">Horarios Activos</div>
            </div>
        </div>

        <!-- Próximas Reservas -->
        @if(isset($proximasReservas) && $proximasReservas->count() > 0)
        <div class="section-title">
            <i class="ti ti-calendar"></i>
            <h3>Próximas Reservas</h3>
        </div>
        <div class="reservas-list">
            @foreach($proximasReservas as $reserva)
            <div class="reserva-item">
                <div>
                    <span class="reserva-fecha">
                        <i class="ti ti-calendar"></i> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
                    </span>
                    <span class="reserva-fecha" style="background: #2a2010;">
                        <i class="ti ti-clock"></i> {{ $reserva->hora_reserva ?? '10:00' }} hrs
                    </span>
                </div>
                <div>
                    <strong>{{ $reserva->cholet->nombre ?? 'Tour' }}</strong>
                    <span style="margin-left: 0.5rem;">
                        <i class="ti ti-users"></i> {{ $reserva->personas }} personas
                    </span>
                </div>
                <div>
                    <span class="badge badge-confirmada">
                        <i class="ti ti-check-circle"></i> Confirmada
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>