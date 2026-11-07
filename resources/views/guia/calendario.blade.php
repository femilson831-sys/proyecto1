@extends('guia.layouts.app')

@section('title', 'Mi Calendario')
@section('content')

<style>
    /* ==============================================
       CALENDARIO PREMIUM
    ============================================== */
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .calendar-header h2 {
        color: var(--gold2);
        font-family: 'Playfair Display', serif;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .filtro-mes {
        display: flex;
        gap: 0.5rem;
        background: rgba(0, 0, 0, 0.3);
        padding: 0.3rem;
        border-radius: 40px;
        border: 1px solid var(--border);
    }
    
    .btn-mes {
        background: transparent;
        border: none;
        padding: 0.4rem 1rem;
        border-radius: 30px;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.8rem;
    }
    
    .btn-mes:hover {
        color: var(--gold);
    }
    
    .btn-mes.active {
        background: linear-gradient(135deg, var(--gold-dim), var(--gold-d));
        color: var(--black);
        font-weight: 600;
    }
    
    .mes-card {
        background: linear-gradient(135deg, var(--surface) 0%, var(--deep) 100%);
        border: 1px solid var(--border);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        animation: fadeInUp 0.4s ease;
    }
    
    .mes-card:hover {
        border-color: var(--gold-dim);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
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
    
    .mes-header {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.1));
        padding: 1rem 1.2rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .mes-header h3 {
        color: var(--gold2);
        margin: 0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    
    .total-horas {
        background: rgba(201, 168, 76, 0.1);
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.7rem;
        color: var(--gold);
    }
    
    .horarios-lista {
        padding: 0.5rem;
    }
    
    .horario-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.9rem 1rem;
        margin: 0.3rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .horario-item:hover {
        background: rgba(201, 168, 76, 0.05);
        transform: translateX(5px);
    }
    
    .horario-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .horario-dia {
        background: linear-gradient(135deg, var(--gold-dim), var(--gold-d));
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: bold;
        color: var(--black);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    
    .horario-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .horario-cholet {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--text);
    }
    
    .horario-tiempo {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--gold);
        font-size: 0.85rem;
    }
    
    .badge-estado {
        background: rgba(39, 174, 96, 0.15);
        border: 1px solid rgba(39, 174, 96, 0.3);
        padding: 0.2rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        color: #27ae60;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .empty-calendario {
        text-align: center;
        padding: 3rem;
        background: linear-gradient(135deg, var(--surface), var(--deep));
        border-radius: 16px;
        color: var(--muted);
        border: 1px solid var(--border);
    }
    
    .empty-calendario i {
        font-size: 3rem;
        color: var(--gold-dim);
        margin-bottom: 1rem;
        display: inline-block;
    }
    
    .empty-calendario p {
        margin-bottom: 0.5rem;
    }
    
    .empty-calendario small {
        font-size: 0.7rem;
        color: var(--border2);
    }
    
    /* Tooltip */
    .tooltip-info {
        position: relative;
        cursor: help;
    }
    
    .tooltip-info:hover::after {
        content: attr(data-info);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--gold-dim);
        color: var(--black);
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.7rem;
        white-space: nowrap;
        z-index: 10;
    }
    
    @media (max-width: 768px) {
        .calendar-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .horario-item {
            flex-direction: column;
            text-align: center;
        }
        
        .horario-left {
            justify-content: center;
        }
        
        .horario-info {
            justify-content: center;
        }
        
        .mes-header {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<div>
    <div class="calendar-header">
        <h2>
            <i class="ti ti-calendar"></i> 
            Mi Calendario de Trabajo
        </h2>
        
        <div class="filtro-mes">
            <button class="btn-mes active" data-mes="todos">Todos</button>
            <button class="btn-mes" data-mes="actual">Mes Actual</button>
            <button class="btn-mes" data-mes="proximo">Próximo Mes</button>
        </div>
    </div>
    
    <div id="calendarioContainer">
        @if($horariosPorMes->count() > 0)
            @foreach($horariosPorMes as $mes => $horariosMes)
                @php
                    $totalHoras = 0;
                    foreach($horariosMes as $h) {
                        $inicio = \Carbon\Carbon::parse($h->hora_inicio);
                        $fin = \Carbon\Carbon::parse($h->hora_fin);
                        $totalHoras += $inicio->diffInHours($fin);
                    }
                @endphp
                <div class="mes-card" data-mes-nombre="{{ $mes }}">
                    <div class="mes-header">
                        <h3>
                            <i class="ti ti-calendar"></i> 
                            {{ $mes }}
                        </h3>
                        <div class="total-horas tooltip-info" data-info="Total de horas trabajadas en el mes">
                            <i class="ti ti-clock"></i> {{ $totalHoras }} horas totales
                        </div>
                    </div>
                    <div class="horarios-lista">
                        @foreach($horariosMes as $horario)
                        <div class="horario-item">
                            <div class="horario-left">
                                <div class="horario-dia">
                                    <i class="ti ti-calendar"></i>
                                    {{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}
                                </div>
                                <div class="horario-info">
                                    <div class="horario-cholet">
                                        <i class="ti ti-building"></i>
                                        {{ $horario->cholet->nombre ?? 'Cholet' }}
                                    </div>
                                    <div class="horario-tiempo">
                                        <i class="ti ti-clock"></i>
                                        {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($horario->hora_fin)->format('g:i A') }}
                                    </div>
                                    <div>
                                        <span class="badge-estado">
                                            <i class="ti ti-check"></i> Programado
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                @php
                                    $capacidadActual = $horario->reservas_actuales ?? 0;
                                    $capacidadMaxima = $horario->capacidad_maxima ?? 20;
                                    $porcentaje = ($capacidadActual / $capacidadMaxima) * 100;
                                @endphp
                                <div class="tooltip-info" data-info="{{ $capacidadActual }}/{{ $capacidadMaxima }} reservas">
                                    <div style="display: flex; align-items: center; gap: 0.3rem;">
                                        <i class="ti ti-users"></i>
                                        <span style="font-size: 0.75rem;">{{ $capacidadActual }}/{{ $capacidadMaxima }}</span>
                                        <div style="width: 60px; height: 4px; background: var(--border); border-radius: 2px; overflow: hidden;">
                                            <div style="width: {{ $porcentaje }}%; height: 100%; background: var(--gold); border-radius: 2px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-calendario">
                <i class="ti ti-calendar-off"></i>
                <p>No tienes horarios programados</p>
                <small>Los horarios aparecerán aquí una vez que sean asignados por el administrador.</small>
            </div>
        @endif
    </div>
</div>

<script>
    // Filtro por mes
    const btnMes = document.querySelectorAll('.btn-mes');
    const mesCards = document.querySelectorAll('.mes-card');
    const mesesOrden = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const fechaActual = new Date();
    const mesActual = mesesOrden[fechaActual.getMonth()];
    const mesProximo = mesesOrden[(fechaActual.getMonth() + 1) % 12];
    
    function filtrarMes(filtro) {
        mesCards.forEach(card => {
            const mesNombre = card.getAttribute('data-mes-nombre');
            
            if (filtro === 'todos') {
                card.style.display = '';
            } else if (filtro === 'actual') {
                card.style.display = mesNombre === mesActual ? '' : 'none';
            } else if (filtro === 'proximo') {
                card.style.display = mesNombre === mesProximo ? '' : 'none';
            }
        });
        
        // Actualizar clases activas
        btnMes.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-mes') === filtro) {
                btn.classList.add('active');
            }
        });
    }
    
    btnMes.forEach(btn => {
        btn.addEventListener('click', () => {
            const filtro = btn.getAttribute('data-mes');
            filtrarMes(filtro);
        });
    });
    
    // Animación de entrada
    document.querySelectorAll('.horario-item').forEach((item, index) => {
        item.style.animation = `fadeInUp 0.3s ease ${index * 0.03}s both`;
    });
</script>

@endsection