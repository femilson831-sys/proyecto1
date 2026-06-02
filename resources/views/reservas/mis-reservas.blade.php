<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Reservas | JACHA TOURS</title>  <!-- ✅ CORREGIDO: ya no usa $cholet -->

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

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            border-bottom: 1px solid rgba(201,168,76,0.2);
        }

        .header__logo img {
            height: 45px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .header__logo img:hover {
            transform: scale(1.05);
        }

        .btn-volver {
            background: #c9a84c;
            color: #000;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-volver:hover {
            background: #e4c46c;
            transform: translateY(-2px);
        }

        /* Container */
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: #c9a84c;
            transform: translateY(-3px);
            background: rgba(0,0,0,0.7);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #c9a84c;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #ccc;
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
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
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
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.08);
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
            background: rgba(0,0,0,0.75);
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
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .detalle {
            color: #ccc;
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
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .btn-cancelar {
            background: rgba(239,68,68,0.15);
            border: 1px solid #ef4444;
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
            background: rgba(201,168,76,0.15);
            border: 1px solid #c9a84c;
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

        /* Estado vacío */
        .empty {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            color: #ccc;
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
            background: #111;
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
        }

        .modal p {
            color: #aaa;
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
        }

        .modal-btn-cancel {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
        }

        /* Responsive */
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

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header__logo" onclick="window.location.href='{{ url('/') }}'">
        <img src="{{ asset('img/logo.png') }}" alt="JACHA TOURS">
    </div>
    <a href="{{ url('/') }}" class="btn-volver">
        <i class="fas fa-home"></i> Volver al Inicio
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
    @endphp

    <div class="stats-container" data-aos="fade-up" data-aos-delay="100">
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
        <button class="filter-btn active" data-filter="all">Todas</button>
        <button class="filter-btn" data-filter="pendiente">Pendientes</button>
        <button class="filter-btn" data-filter="pago_reportado">Pago reportado</button>
        <button class="filter-btn" data-filter="confirmada">Confirmadas</button>
        <button class="filter-btn" data-filter="cancelada">Canceladas</button>
    </div>

    @if($reservas->count() > 0)
        <div class="reservas-grid" id="reservasGrid">
            @foreach($reservas as $reserva)
                <div class="reserva-card" data-estado="{{ $reserva->estado }}" data-aos="zoom-in">
                    <div class="reserva-header">
                        <span class="codigo">
                            <i class="fas fa-ticket-alt"></i> {{ $reserva->codigo_reserva }}
                        </span>
                        <span class="estado estado-{{ $reserva->estado }}">
                            @switch($reserva->estado)
                                @case('pendiente') Pendiente @break
                                @case('pago_reportado') Pago Reportado @break
                                @case('confirmada') Confirmada @break
                                @case('cancelada') Cancelada @break
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
                            {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-clock"></i> Hora</strong>
                            {{ $reserva->hora_reserva ?? $reserva->horario->hora_inicio ?? 'No especificada' }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-ticket"></i> Experiencia</strong>
                            {{ $reserva->experiencia }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-users"></i> Personas</strong>
                            {{ $reserva->personas }}
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-dollar-sign"></i> Total</strong>
                            ${{ number_format($reserva->precio_total, 0, ',', '.') }} USD
                        </div>
                        <div class="detalle">
                            <strong><i class="fas fa-calendar-week"></i> Reservado</strong>
                            {{ $reserva->created_at->format('d/m/Y H:i') }}
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
                            <button class="btn-ver" disabled style="opacity:0.6; cursor:not-allowed;">
                                <i class="fas fa-clock"></i> Esperando confirmación
                            </button>
                        @elseif($reserva->estado === 'confirmada')
                            <button class="btn-ver" disabled style="opacity:0.6; cursor:not-allowed;">
                                <i class="fas fa-check-circle"></i> Reserva confirmada
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
            <p>No tienes reservas aún.</p>
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
            <button class="modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>
            <button class="modal-btn-confirm" id="confirmCancelBtn">Sí, cancelar</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true, offset: 50 });

    let reservaIdToCancel = null;

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
        if (reservaIdToCancel) {
            cancelarReserva(reservaIdToCancel);
        }
    });

    function cancelarReserva(id) {
        fetch(`/reservas/cancelar/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacion('Reserva cancelada', 'Tu reserva ha sido cancelada exitosamente', 'success');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                mostrarNotificacion('Error', data.message || 'No se pudo cancelar la reserva', 'error');
            }
            cerrarModal();
        })
        .catch(err => {
            mostrarNotificacion('Error', 'Ocurrió un error al cancelar la reserva', 'error');
            cerrarModal();
        });
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            cerrarModal();
        }
    });

    document.getElementById('cancelModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            cerrarModal();
        }
    });

    @if(session('success'))
        mostrarNotificacion('Éxito', '{{ session('success') }}', 'success');
    @endif
</script>

</body>
</html>