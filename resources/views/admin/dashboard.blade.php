@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<style>
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.4rem;
    }
    @media (max-width: 1100px) { .kpi-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 560px)  { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
        background: #111111;
        border: 1px solid #221a08;
        border-radius: 4px;
        padding: 1.5rem 1.4rem 1.3rem;
        position: relative;
        overflow: hidden;
        transition: transform .25s, box-shadow .25s, border-color .25s;
        animation: kpiIn .5s cubic-bezier(.22,1,.36,1) both;
        cursor: default;
    }
    .kpi-card:nth-child(1){animation-delay:.04s}
    .kpi-card:nth-child(2){animation-delay:.10s}
    .kpi-card:nth-child(3){animation-delay:.16s}
    .kpi-card:nth-child(4){animation-delay:.22s}

    @keyframes kpiIn {
        from { opacity:0; transform: translateY(16px); }
        to   { opacity:1; transform: none; }
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        border-color: #3d2e10;
        box-shadow: 0 16px 48px rgba(0,0,0,.7), 0 0 0 1px rgba(201,168,76,.09) inset;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: var(--kc, #c9a84c);
        opacity: .8;
    }

    .kpi-ghost {
        position: absolute;
        right: -6px; bottom: -14px;
        font-family: 'Playfair Display', serif;
        font-size: 5.5rem;
        font-weight: 700;
        color: rgba(255,255,255,.025);
        line-height: 1;
        user-select: none;
        pointer-events: none;
    }

    .kpi-icon {
        width: 38px; height: 38px;
        border-radius: 3px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.06);
        margin-bottom: 1rem;
        font-size: 1.2rem;
        color: var(--kc, #c9a84c);
    }

    .kpi-label {
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #6b5e3e;
        margin-bottom: .3rem;
    }

    .kpi-value {
        font-family: 'Playfair Display', serif;
        font-size: 2.3rem;
        font-weight: 700;
        color: #f0ead8;
        line-height: 1;
        margin-bottom: .5rem;
    }

    .kpi-note {
        font-size: .73rem;
        color: #6b5e3e;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .dash-bottom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
        animation: kpiIn .5s .28s cubic-bezier(.22,1,.36,1) both;
    }
    @media (max-width: 900px) { .dash-bottom { grid-template-columns: 1fr; } }

    .panel {
        background: #111111;
        border: 1px solid #221a08;
        border-radius: 4px;
        padding: 1.6rem;
        position: relative;
    }

    .panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--pb, #c9a84c), transparent);
        opacity: .55;
    }

    .panel-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: #f0ead8;
        margin-bottom: .2rem;
    }

    .panel-sub {
        font-size: .7rem;
        color: #6b5e3e;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 1.4rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 11px;
        color: #6b5e3e;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input, .form-group select {
        width: 100%;
        background: #141210;
        border: 1px solid #2e2b25;
        border-radius: 6px;
        padding: 10px 12px;
        color: #e8e0d0;
        font-size: 13px;
        transition: all 0.2s;
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: #c9a84c;
        box-shadow: 0 0 0 2px rgba(201,168,76,.1);
    }

    .btn-primary {
        background: #c9a84c;
        color: #0f0e0c;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background: #e8c97a;
        transform: translateY(-2px);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .horarios-list {
        max-height: 280px;
        overflow-y: auto;
    }
    .horarios-list::-webkit-scrollbar {
        width: 4px;
    }
    .horarios-list::-webkit-scrollbar-track {
        background: #1a1a1a;
    }
    .horarios-list::-webkit-scrollbar-thumb {
        background: #c9a84c;
        border-radius: 4px;
    }
    .horario-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
        transition: background .2s;
    }
    .horario-item:hover {
        background: rgba(255,255,255,.02);
        padding-left: 8px;
    }
    .horario-item:last-child { border-bottom: none; }
    .horario-info { flex: 1; }
    .horario-cholet {
        font-size: .85rem;
        font-weight: 600;
        color: #c9a84c;
        margin-bottom: .2rem;
    }
    .horario-detalle {
        font-size: .7rem;
        color: #6b5e3e;
    }
    .horario-cupos { text-align: right; }
    .cupos-total { font-size: .85rem; font-weight: 600; color: #f0ead8; }
    .cupos-disponibles { font-size: .7rem; color: #34d399; }
    .cupos-lleno { color: #f87171; }
    .badge-horario {
        background: rgba(201,168,76,.15);
        border: 1px solid rgba(201,168,76,.3);
        border-radius: 20px;
        padding: .2rem .7rem;
        font-size: .65rem;
        color: #c9a84c;
        margin-left: 8px;
    }

    .stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .85rem 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
        gap: 1rem;
    }
    .stat-row:last-child { border-bottom: none; }
    .stat-left {
        display: flex;
        align-items: center;
        gap: .7rem;
        flex: 1;
        min-width: 0;
    }
    .stat-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .stat-info { flex: 1; min-width: 0; }
    .stat-name {
        font-size: .84rem;
        font-weight: 500;
        color: #f0ead8;
        margin-bottom: .3rem;
    }
    .bar-track {
        height: 3px;
        background: rgba(255,255,255,.05);
        border-radius: 2px;
        overflow: hidden;
    }
    .bar-fill {
        height: 100%;
        border-radius: 2px;
        transition: width 1.3s cubic-bezier(.22,1,.36,1);
    }
    .stat-right { text-align: right; flex-shrink: 0; }
    .stat-val {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: #f0ead8;
    }
    .stat-pct {
        font-size: .68rem;
        color: #6b5e3e;
        margin-top: .1rem;
    }
    .btn-ver-mas {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 1rem;
        font-size: .7rem;
        color: #c9a84c;
        text-decoration: none;
        transition: color .2s, gap .2s;
    }
    .btn-ver-mas:hover { 
        color: #e8c97a;
        gap: 8px;
    }
    
    .btn-gestion {
        background: #2e2b25;
        border: 1px solid #c9a84c;
        color: #c9a84c;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-gestion:hover {
        background: #c9a84c;
        color: #0f0e0c;
        transform: translateY(-2px);
    }

    .alert-warning {
        background: rgba(201,168,76,.1);
        border: 1px solid rgba(201,168,76,.3);
        border-radius: 6px;
        padding: 1rem;
        color: #c9a84c;
        text-align: center;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #6b5e3e;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: inline-block;
    }
</style>

<div class="kpi-grid">
    <div class="kpi-card" style="--kc:#c9a84c;">
        <div class="kpi-icon"><i class="ti ti-users"></i></div>
        <div class="kpi-label">Usuarios</div>
        <div class="kpi-value" id="cnt-u">0</div>
        <div class="kpi-note">registrados en el sistema</div>
        <div class="kpi-ghost">{{ number_format($totalUsers) }}</div>
    </div>

    <div class="kpi-card" style="--kc:#38bdf8;">
        <div class="kpi-icon"><i class="ti ti-building-estate"></i></div>
        <div class="kpi-label">Cholets</div>
        <div class="kpi-value" id="cnt-c">0</div>
        <div class="kpi-note">propiedades activas</div>
        <div class="kpi-ghost">{{ number_format($totalCholets) }}</div>
    </div>

    <div class="kpi-card" style="--kc:#34d399;">
        <div class="kpi-icon"><i class="ti ti-calendar-event"></i></div>
        <div class="kpi-label">Reservas</div>
        <div class="kpi-value" id="cnt-r">0</div>
        <div class="kpi-note">reservas totales</div>
        <div class="kpi-ghost">{{ number_format($totalReservas) }}</div>
    </div>

    <div class="kpi-card" style="--kc:#a78bfa;">
        <div class="kpi-icon"><i class="ti ti-clock"></i></div>
        <div class="kpi-label">Horarios activos</div>
        <div class="kpi-value" id="cnt-h">0</div>
        <div class="kpi-note">slots disponibles</div>
        <div class="kpi-ghost">{{ number_format($totalHorarios ?? 0) }}</div>
    </div>
</div>

<div class="dash-bottom">
    <div class="panel" style="--pb:#c9a84c;">
        <div class="panel-title">Actividad del sistema</div>
        <div class="panel-sub">Distribución de registros</div>
        <canvas id="dashChart" height="130"></canvas>
    </div>

    <div class="panel" style="--pb:#7a5f28;">
        <div class="panel-title">Resumen</div>
        <div class="panel-sub">Vista proporcional</div>
        @php
            $total = max(1, $totalUsers + $totalCholets + $totalReservas + ($totalHorarios ?? 0));
            $stats = [
                ['Usuarios', $totalUsers, '#c9a84c'],
                ['Cholets',  $totalCholets, '#38bdf8'],
                ['Reservas', $totalReservas, '#34d399'],
                ['Horarios', $totalHorarios ?? 0, '#a78bfa'],
            ];
        @endphp
        @foreach($stats as [$name, $val, $hex])
        @php $pct = $total > 0 ? round($val / $total * 100) : 0; @endphp
        <div class="stat-row">
            <div class="stat-left">
                <div class="stat-dot" style="background:{{ $hex }};box-shadow:0 0 6px {{ $hex }}55;"></div>
                <div class="stat-info">
                    <div class="stat-name">{{ $name }}</div>
                    <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%;background:{{ $hex }};"></div></div>
                </div>
            </div>
            <div class="stat-right">
                <div class="stat-val">{{ number_format($val) }}</div>
                <div class="stat-pct">{{ $pct }}%</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- FORMULARIO PARA CREAR HORARIO RÁPIDO --}}
<div class="panel" style="--pb:#c9a84c; margin-top: 1rem;">
    <div class="panel-title">➕ Crear nuevo horario</div>
    <div class="panel-sub">Selecciona un cholet y configura su disponibilidad</div>
    
    @if(isset($choletsDisponibles) && $choletsDisponibles->count() > 0)
        <form action="{{ route('admin.horarios.store', ['choletId' => $choletsDisponibles->first()->id]) }}" method="POST" id="formHorarioRapido">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>🏠 CHOlet</label>
                    <select name="cholet_id" id="choletSelect" required>
                        <option value="">Seleccionar cholet</option>
                        @foreach($choletsDisponibles as $cholet)
                            <option value="{{ $cholet->id }}" {{ $loop->first ? 'selected' : '' }}>
                                {{ $cholet->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>📅 FECHA</label>
                    <input type="date" name="fecha" id="fechaHorario" min="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label>⏰ HORARIO</label>
                    <select name="hora_inicio" id="horaInicio" required>
                        <option value="09:00">🌅 09:00 AM - 12:00 PM</option>
                        <option value="14:00">☀️ 02:00 PM - 05:00 PM</option>
                        <option value="19:00">🌙 07:00 PM - 10:00 PM</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>👥 CAPACIDAD MÁXIMA</label>
                    <input type="number" name="capacidad_maxima" id="capacidadMaxima" value="20" min="1" max="50" required>
                    <small style="color: #6b5e3e; font-size: 10px;">Min: 1 | Max: 50 personas</small>
                </div>
            </div>
            
            <input type="hidden" name="hora_fin" id="horaFin" value="12:00">
            
            <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="submit" class="btn-primary" id="btnCrearHorario">
                    ✅ Crear Horario
                </button>
                <a href="{{ route('admin.cholets.index') }}" class="btn-gestion">
                    📋 Gestionar horarios
                </a>
            </div>
        </form>
    @else
        <div class="alert-warning">
            <i class="ti ti-alert-circle"></i>
            ⚠️ No hay cholets disponibles. 
            <a href="{{ route('admin.cholets.create') }}" style="color: #c9a84c; text-decoration: underline;">
                Crea un cholet primero
            </a>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Actualizar hora fin automáticamente según hora inicio
        const horaInicioSelect = document.getElementById('horaInicio');
        const horaFinInput = document.getElementById('horaFin');
        
        if (horaInicioSelect && horaFinInput) {
            horaInicioSelect.addEventListener('change', function() {
                const hora = this.value;
                let horaFin = '12:00';
                if (hora === '09:00') horaFin = '12:00';
                if (hora === '14:00') horaFin = '17:00';
                if (hora === '19:00') horaFin = '22:00';
                horaFinInput.value = horaFin;
            });
        }
        
        // Actualizar acción del formulario cuando cambia el cholet
        const choletSelect = document.getElementById('choletSelect');
        const formHorario = document.getElementById('formHorarioRapido');
        
        if (choletSelect && formHorario) {
            choletSelect.addEventListener('change', function() {
                const choletId = this.value;
                if (choletId) {
                    const url = "{{ url('/admin/cholets') }}/" + choletId + "/horarios";
                    formHorario.action = url;
                }
            });
        }
        
        // Validar fecha antes de enviar
        const fechaInput = document.getElementById('fechaHorario');
        if (fechaInput) {
            fechaInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate < today) {
                    alert('⚠️ No puedes seleccionar fechas pasadas');
                    this.value = '';
                }
            });
        }
        
        // Validar capacidad máxima
        const capacidadInput = document.getElementById('capacidadMaxima');
        if (capacidadInput) {
            capacidadInput.addEventListener('input', function() {
                let value = parseInt(this.value);
                if (value < 1) this.value = 1;
                if (value > 50) this.value = 50;
            });
        }
        
        // Prevenir envío múltiple del formulario
        const btnSubmit = document.getElementById('btnCrearHorario');
        if (btnSubmit && formHorario) {
            formHorario.addEventListener('submit', function() {
                btnSubmit.disabled = true;
                btnSubmit.textContent = '⏳ Creando...';
                setTimeout(() => {
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = '✅ Crear Horario';
                }, 3000);
            });
        }
    });
</script>

{{-- SECCIÓN DE HORARIOS RECIENTES --}}
<div class="panel" style="--pb:#c9a84c; margin-top: 1rem;">
    <div class="panel-title">📅 Horarios disponibles (próximos 7 días)</div>
    <div class="panel-sub">Gestión de cupos y disponibilidad</div>
    
    <div class="horarios-list">
        @forelse($horariosRecientes ?? [] as $horario)
        <div class="horario-item">
            <div class="horario-info">
                <div class="horario-cholet">
                    {{ $horario->cholet->nombre ?? 'Cholet sin nombre' }}
                    <span class="badge-horario">
                        {{ $horario->hora_inicio ? \Carbon\Carbon::parse($horario->hora_inicio)->format('h:i A') : 'N/A' }}
                    </span>
                </div>
                <div class="horario-detalle">
                    📅 {{ $horario->fecha ? \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') : 'Fecha no disponible' }}
                    @if($horario->hora_inicio && $horario->hora_fin)
                        • 🕐 {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('h:i A') }}
                    @endif
                </div>
            </div>
            <div class="horario-cupos">
                <div class="cupos-total">Cap: {{ $horario->capacidad_maxima ?? 0 }}</div>
                <div class="cupos-disponibles {{ isset($horario->capacidad_disponible) && $horario->capacidad_disponible <= 0 ? 'cupos-lleno' : '' }}">
                    {{ $horario->capacidad_disponible ?? $horario->capacidad_maxima ?? 0 }} cupos libres
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="ti ti-calendar-off"></i>
            <p>No hay horarios creados para los próximos 7 días</p>
            @if(isset($choletsDisponibles) && $choletsDisponibles->count() > 0)
                <p style="font-size: 12px; margin-top: 10px;">Usa el formulario de arriba para crear tu primer horario</p>
            @else
                <p style="font-size: 12px; margin-top: 10px;">
                    <a href="{{ route('admin.cholets.create') }}" style="color: #c9a84c;">
                        Crea un cholet primero
                    </a>
                </p>
            @endif
        </div>
        @endforelse
    </div>
    
    @if(($horariosRecientes ?? collect())->count() > 0)
    <div style="text-align: right; margin-top: 15px;">
        <a href="{{ route('admin.cholets.index') }}" class="btn-ver-mas">
            Ver todos los horarios →
        </a>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Función para animar contadores
    function animCount(id, target) {
        const el = document.getElementById(id);
        if (!el) return;
        
        if (!target || target === 0) {
            el.textContent = '0';
            return;
        }
        
        const dur = 900;
        const start = performance.now();
        
        function step(now) {
            const p = Math.min((now - start) / dur, 1);
            const easing = 1 - Math.pow(1 - p, 3);
            const current = Math.round(easing * target);
            el.textContent = current.toLocaleString('es-BO');
            
            if (p < 1) {
                requestAnimationFrame(step);
            }
        }
        
        requestAnimationFrame(step);
    }
    
    // Iniciar animaciones cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', () => {
        animCount('cnt-u', {{ $totalUsers ?? 0 }});
        animCount('cnt-c', {{ $totalCholets ?? 0 }});
        animCount('cnt-r', {{ $totalReservas ?? 0 }});
        animCount('cnt-h', {{ $totalHorarios ?? 0 }});
    });
    
    // Configuración de Chart.js
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = '#6b5e3e';
        Chart.defaults.font.family = "'Jost', sans-serif";
        Chart.defaults.font.size = 12;
        
        const ctx = document.getElementById('dashChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Usuarios', 'Cholets', 'Reservas', 'Horarios'],
                    datasets: [{
                        label: 'Total',
                        data: [
                            {{ $totalUsers ?? 0 }}, 
                            {{ $totalCholets ?? 0 }}, 
                            {{ $totalReservas ?? 0 }}, 
                            {{ $totalHorarios ?? 0 }}
                        ],
                        backgroundColor: [
                            'rgba(201,168,76,.7)',
                            'rgba(56,189,248,.7)',
                            'rgba(52,211,153,.7)',
                            'rgba(167,139,250,.7)'
                        ],
                        borderColor: ['#c9a84c', '#38bdf8', '#34d399', '#a78bfa'],
                        borderWidth: 1,
                        borderRadius: 3,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#141414',
                            borderColor: '#2a2010',
                            borderWidth: 1,
                            titleColor: '#c9a84c',
                            bodyColor: '#f0ead8',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return '  ' + context.parsed.y.toLocaleString('es-BO');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,.04)' },
                            ticks: { color: '#6b5e3e' },
                            border: { color: '#221a08' }
                        },
                        y: {
                            grid: { color: 'rgba(255,255,255,.04)' },
                            ticks: { 
                                color: '#6b5e3e', 
                                callback: function(val) {
                                    return val.toLocaleString('es-BO');
                                }
                            },
                            border: { color: '#221a08' },
                            beginAtZero: true
                        }
                    },
                    animation: { 
                        duration: 1000, 
                        easing: 'easeOutCubic' 
                    }
                }
            });
        }
    }
</script>

@endsection