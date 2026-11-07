<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Reservas | JACHA TOURS</title>

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
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.85), rgba(0,0,0,0.85)), url('{{ asset("img/image.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* ===== HEADER ===== */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: transparent;  /* Fondo transparente */
            backdrop-filter: none;
            z-index: 1000;
            border-bottom: 1px solid rgba(201,168,76,0.15);
            transition: all 0.4s ease;
        }

        /* Efecto al hacer scroll */
        .header.scrolled {
            background: rgba(10,10,10,0.92);
            backdrop-filter: blur(12px);
            padding: 0.8rem 2rem;
            border-bottom: 1px solid rgba(201,168,76,0.2);
        }

        /* ===== LOGO MÁS GRANDE Y TRANSPARENTE ===== */
        .header__logo img {
            height: 80px;
            width: auto;
            cursor: pointer;
            transition: transform 0.4s ease, filter 0.4s ease;
            filter: drop-shadow(0 0 20px rgba(201,168,76,0.12));
            /* El logo debe ser PNG con fondo transparente */
        }

        .header__logo img:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 0 35px rgba(201,168,76,0.25));
        }

        .btn-volver {
            background: rgba(201,168,76,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(201,168,76,0.3);
            color: #c9a84c;
            padding: 0.7rem 1.8rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-volver:hover {
            background: #c9a84c;
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(201,168,76,0.3);
        }

        /* ===== RESPONSIVE LOGO ===== */
        @media (max-width: 768px) {
            .header {
                padding: 0.8rem 1rem;
                background: rgba(10,10,10,0.92);
                backdrop-filter: blur(10px);
            }
            
            .header__logo img {
                height: 55px;
            }
            
            .btn-volver {
                padding: 0.5rem 1.2rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .header__logo img {
                height: 45px;
            }
            
            .btn-volver span {
                display: none;
            }
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 2rem 2rem;
        }

        h1 {
            color: #c9a84c;
            margin-bottom: 2rem;
            font-size: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Estadísticas */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: #c9a84c;
            transform: translateY(-3px);
            background: rgba(0,0,0,0.65);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #c9a84c;
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            margin-top: 0.3rem;
        }

        /* Filtros */
        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.85rem;
        }

        .filter-btn.active {
            background: #c9a84c;
            color: #000;
            border-color: #c9a84c;
        }

        .filter-btn:hover:not(.active) {
            border-color: #c9a84c;
            color: #c9a84c;
        }

        /* Grid de reservas */
        .reservas-grid {
            display: grid;
            gap: 1.5rem;
        }

        .reserva-card {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s ease;
            animation: fadeInUp 0.5s ease;
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

        .reserva-card:hover {
            border-color: #c9a84c;
            transform: translateY(-3px);
            background: rgba(0,0,0,0.7);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .reserva-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .codigo {
            font-size: 1.1rem;
            font-weight: 700;
            color: #c9a84c;
            font-family: monospace;
            letter-spacing: 1px;
        }

        .estado {
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .estado-pendiente { background: #f59e0b; color: #000; }
        .estado-pago_reportado { background: #8b5cf6; color: #fff; }
        .estado-confirmada { background: #10b981; color: #fff; }
        .estado-cancelada { background: #ef4444; color: #fff; }

        .reserva-titulo {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #fff;
        }

        .reserva-detalles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .detalle {
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
        }

        .detalle strong {
            color: #fff;
            display: block;
            margin-bottom: 0.3rem;
            font-size: 0.85rem;
        }

        .detalle i {
            color: #c9a84c;
            width: 20px;
        }

        /* Botones de acción */
        .reserva-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            flex-wrap: wrap;
        }

        .btn-cancelar {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.4);
            color: #ef4444;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancelar:hover {
            background: #ef4444;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-ver {
            background: rgba(201,168,76,0.12);
            border: 1px solid rgba(201,168,76,0.3);
            color: #c9a84c;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-ver:hover {
            background: #c9a84c;
            color: #000;
            transform: translateY(-2px);
        }

        .btn-pagar {
            background: #10b981;
            border: none;
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-pagar:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn-disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Estado vacío */
        .empty {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            color: rgba(255,255,255,0.7);
        }

        .empty i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #c9a84c;
            opacity: 0.5;
        }

        /* Toast */
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(26,26,26,0.95);
            backdrop-filter: blur(10px);
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

        /* Modal de confirmación */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal {
            background: rgba(17,17,17,0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            text-align: center;
            border: 1px solid rgba(201,168,76,0.2);
            transform: scale(0.9);
            transition: transform 0.3s;
        }

        .modal-overlay.active .modal {
            transform: scale(1);
        }

        .modal i {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }

        .modal h3 {
            margin-bottom: 1rem;
            color: #fff;
        }

        .modal p {
            color: rgba(255,255,255,0.6);
            margin-bottom: 1.5rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn-confirm {
            background: #ef4444;
            border: none;
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .modal-btn-confirm:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .modal-btn-cancel {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .modal-btn-cancel:hover {
            background: rgba(255,255,255,0.05);
            transform: translateY(-2px);
        }

        /* Loader */
        .loader {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #c9a84c;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 80px 1rem 1rem;
            }

            .reserva-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .reserva-detalles {
                grid-template-columns: 1fr;
            }

            .reserva-actions {
                flex-direction: column;
            }

            .reserva-actions .btn-pagar,
            .reserva-actions .btn-cancelar,
            .reserva-actions .btn-ver {
                width: 100%;
                justify-content: center;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filters {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<header class="header" id="header">
    <div class="header__logo" onclick="window.location.href='{{ url('/') }}'">
        <img src="{{ asset('img/logo.blanco.amarillo.png') }}" alt="JACHA TOURS">
    </div>
    <a href="{{ url('/') }}" class="btn-volver">
        <i class="fas fa-home"></i> <span>Volver al Inicio</span>
    </a>
</header>

<div class="container">
    <h1 data-aos="fade-up">
        <i class="fas fa-calendar-alt"></i> 
        Mis Reservas
    </h1>

    <!-- Estadísticas -->
    @php
        $pendientes = $reservas->where('estado', 'pendiente')->count();
        $pagoReportado = $reservas->where('estado', 'pago_reportado')->count();
        $confirmadas = $reservas->where('estado', 'confirmada')->count();
        $canceladas = $reservas->where('estado', 'cancelada')->count();
        $total = $reservas->count();
    @endphp

    <div class="stats-container" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total reservas</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $pendientes }}</div>
            <div class="stat-label">Pendientes de pago</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $pagoReportado }}</div>
            <div class="stat-label">Pago reportado</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $confirmadas }}</div>
            <div class="stat-label">Confirmadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $canceladas }}</div>
            <div class="stat-label">Canceladas</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters" data-aos="fade-up" data-aos-delay="200">
        <button class="filter-btn active" data-filter="all">Todas ({{ $total }})</button>
        <button class="filter-btn" data-filter="pendiente">Pendientes ({{ $pendientes }})</button>
        <button class="filter-btn" data-filter="pago_reportado">Pago reportado ({{ $pagoReportado }})</button>
        <button class="filter-btn" data-filter="confirmada">Confirmadas ({{ $confirmadas }})</button>
        <button class="filter-btn" data-filter="cancelada">Canceladas ({{ $canceladas }})</button>
    </div>

    @if($reservas->count() > 0)
        <div class="reservas-grid" id="reservasGrid">
            @foreach($reservas as $reserva)
                <div class="reserva-card" data-estado="{{ $reserva->estado }}" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 50 }}">
                    <div class="reserva-header">
                        <span class="codigo">
                            <i class="fas fa-ticket-alt"></i> {{ $reserva->codigo_reserva }}
                        </span>
                        <span class="estado estado-{{ $reserva->estado }}">
                            @switch($reserva->estado)
                                @case('pendiente') <i class="fas fa-hourglass-half"></i> Pendiente @break
                                @case('pago_reportado') <i class="fas fa-clock"></i> Pago Reportado @break
                                @case('confirmada') <i class="fas fa-check-circle"></i> Confirmada @break
                                @case('cancelada') <i class="fas fa-times-circle"></i> Cancelada @break
                                @default {{ ucfirst($reserva->estado) }}
                            @endswitch
                        </span>
                    </div>

                    <div class="reserva-titulo">
                        {{ $reserva->cholet->nombre ?? 'Cholet no disponible' }}
                    </div>

                    <div class="reserva-detalles">
                        <div class="detalle">
                            <strong><i class="fas fa-calendar"></i> Fecha</strong>
                            {{ \Carbon\Carbon::parse($reserva->fecha)->translatedFormat('l, d \\d\\e F \\d\\e Y') }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-clock"></i> Hora</strong>
                            {{ $reserva->hora_reserva ?? ($reserva->horario->hora_inicio ?? 'No especificada') }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-ticket"></i> Experiencia</strong>
                            {{ $reserva->experienciaRelacion->nombre ?? $reserva->experiencia }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-users"></i> Personas</strong>
                            {{ $reserva->personas }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-money-bill-wave"></i> Precio unitario</strong>
                            Bs {{ number_format($reserva->precio_unitario, 2) }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-receipt"></i> Total</strong>
                            Bs {{ number_format($reserva->precio_total, 2) }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-calendar-week"></i> Reservado</strong>
                            {{ $reserva->created_at->translatedFormat('d/m/Y H:i') }}
                        </div>
                    </div>

                    <div class="reserva-actions">
                        @if($reserva->estado === 'pendiente')
                            <a href="{{ route('reservas.exito', $reserva->id) }}" class="btn-pagar">
                                <i class="fas fa-credit-card"></i> Completar pago
                            </a>
                            <button class="btn-cancelar" onclick="confirmarCancelacion({{ $reserva->id }})">
                                <i class="fas fa-times-circle"></i> Cancelar reserva
                            </button>
                        @elseif($reserva->estado === 'pago_reportado')
                            <button class="btn-ver btn-disabled" disabled>
                                <i class="fas fa-clock"></i> Esperando confirmación
                            </button>
                        @elseif($reserva->estado === 'confirmada')
                            <button class="btn-ver btn-disabled" disabled>
                                <i class="fas fa-check-circle"></i> Reserva confirmada
                            </button>
                        @elseif($reserva->estado === 'cancelada')
                            <button class="btn-ver btn-disabled" disabled>
                                <i class="fas fa-ban"></i> Reserva cancelada
                            </button>
                        @endif

                        <a href="{{ route('cholets.show', $reserva->cholet->id) }}" class="btn-ver">
                            <i class="fas fa-eye"></i> Ver cholet
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty" data-aos="fade-up">
            <i class="fas fa-calendar-times"></i>
            <h3 style="color: #fff; margin-bottom: 0.5rem;">No tienes reservas aún</h3>
            <p>¡Descubre los increíbles cholets de El Alto y vive una experiencia única!</p>
            <a href="{{ url('/#circuito') }}" class="btn-pagar" style="display: inline-block; margin-top: 1rem; background:#c9a84c; color:#000;">
                <i class="fas fa-search"></i> Explorar Cholets
            </a>
        </div>
    @endif
</div>

<!-- Modal de confirmación -->
<div class="modal-overlay" id="cancelModal">
    <div class="modal">
        <i class="fas fa-exclamation-triangle"></i>
        <h3>¿Cancelar reserva?</h3>
        <p>Esta acción no se puede deshacer. ¿Estás seguro de que deseas cancelar esta reserva?</p>
        <div class="modal-buttons">
            <button class="modal-btn-cancel" onclick="cerrarModal()">
                <i class="fas fa-arrow-left"></i> No, mantener
            </button>
            <button class="modal-btn-confirm" id="confirmCancelBtn">
                <i class="fas fa-check"></i> Sí, cancelar
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true, offset: 50 });

    // ===== HEADER SCROLL =====
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    let reservaIdToCancel = null;
    let isLoading = false;

    // Filtros
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            const cards = document.querySelectorAll('.reserva-card');

            cards.forEach(card => {
                if (filter === 'all' || card.dataset.estado === filter) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.5s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Cancelar reserva
    function confirmarCancelacion(id) {
        reservaIdToCancel = id;
        document.getElementById('cancelModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal() {
        document.getElementById('cancelModal').classList.remove('active');
        document.body.style.overflow = '';
        reservaIdToCancel = null;
    }

    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        if (reservaIdToCancel && !isLoading) {
            cancelarReserva(reservaIdToCancel);
        }
    });

    async function cancelarReserva(id) {
        isLoading = true;
        const confirmBtn = document.getElementById('confirmCancelBtn');
        const originalText = confirmBtn.innerHTML;
        confirmBtn.innerHTML = '<span class="loader"></span> Cancelando...';
        confirmBtn.disabled = true;

        try {
            const response = await fetch(`/reservas/${id}/cancelar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                mostrarNotificacion('✅ Reserva cancelada', 'Tu reserva ha sido cancelada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                mostrarNotificacion('❌ Error', data.message || 'No se pudo cancelar la reserva', 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            mostrarNotificacion('❌ Error', 'Ocurrió un error al cancelar la reserva', 'error');
        } finally {
            isLoading = false;
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
            cerrarModal();
        }
    }

    // Toast notification
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

    // Cerrar modal con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('cancelModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            cerrarModal();
        }
    });

    // Mostrar mensajes de sesión
    @if(session('success'))
        mostrarNotificacion('✅ Éxito', '{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        mostrarNotificacion('❌ Error', '{{ session('error') }}', 'error');
    @endif

    @if(session('warning'))
        mostrarNotificacion('⚠️ Atención', '{{ session('warning') }}', 'warning');
    @endif

    // Animación de entrada para las cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.reserva-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
</script>
</body>
</html>