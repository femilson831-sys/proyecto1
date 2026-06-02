@extends('admin.layouts.app')
@section('title', 'Horarios - ' . $cholet->nombre)
@section('page-title', 'Gestión de Horarios: ' . $cholet->nombre)

@section('content')

<style>
    .page-wrap { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }

    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.4rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1rem;
        background: transparent;
        color: #6b5e3e;
        border: 1px solid #2a2010;
        border-radius: 3px;
        text-decoration: none;
        font-size: .8rem;
        transition: all .2s;
    }

    .btn-back:hover {
        border-color: #7a5f28;
        color: #f0ead8;
    }

    .btn-primary {
        background: linear-gradient(135deg, #b8962a, #e0c45a);
        color: #080808;
        border: none;
        padding: .6rem 1.2rem;
        border-radius: 3px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: transform .2s;
    }

    .btn-primary:hover { transform: translateY(-1px); }

    .btn-secondary {
        background: rgba(255,255,255,.05);
        border: 1px solid #2a2010;
        color: #c8bfa8;
        padding: .6rem 1.2rem;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: all .2s;
    }

    .btn-secondary:hover { background: rgba(255,255,255,.1); }

    .btn-danger {
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.2);
        color: #f87171;
        padding: .4rem .8rem;
        border-radius: 3px;
        font-size: .75rem;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-danger:hover { background: rgba(239,68,68,.2); }

    .panel {
        background: #111111;
        border: 1px solid #221a08;
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    .panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, #c9a84c, transparent);
        opacity: .7;
    }

    .panel-head {
        padding: 1rem 1.6rem;
        border-bottom: 1px solid rgba(255,255,255,.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .panel-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 600;
        color: #f0ead8;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        padding: .85rem 1.2rem;
        text-align: left;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #5a4820;
        border-bottom: 1px solid #1e1608;
    }

    .data-table td {
        padding: .85rem 1.2rem;
        color: #c8bfa8;
        border-bottom: 1px solid rgba(255,255,255,.03);
    }

    .data-table tr:last-child td { border-bottom: none; }
    .data-table tr:hover { background: rgba(201,168,76,.03); }

    .badge-active {
        background: rgba(74,222,128,.1);
        color: #6ee7b7;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .7rem;
    }

    .badge-full {
        background: rgba(239,68,68,.1);
        color: #f87171;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .7rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #4a3a1a;
    }

    .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; display: block; }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.85);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active { display: flex; }

    .modal-box {
        background: #111111;
        border: 1px solid #2a2010;
        border-radius: 4px;
        padding: 1.8rem;
        max-width: 550px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #f0ead8;
        margin-bottom: 1.2rem;
        border-bottom: 1px solid rgba(255,255,255,.1);
        padding-bottom: .5rem;
    }

    .form-group { margin-bottom: 1rem; }
    .form-group label {
        display: block;
        font-size: .7rem;
        color: #7a5f28;
        margin-bottom: .3rem;
        text-transform: uppercase;
        letter-spacing: .08em;
    }
    .form-group input, .form-group select {
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #2a2010;
        border-radius: 3px;
        padding: .7rem;
        color: #f0ead8;
        font-size: .9rem;
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: #c9a84c;
    }

    .modal-actions {
        display: flex;
        gap: .5rem;
        margin-top: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .time-input {
        font-family: monospace;
        font-size: 1rem;
    }

    .dias-semana {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: .5rem;
        margin-bottom: 1rem;
    }

    .dia-btn {
        background: #1a1a1a;
        border: 1px solid #2a2010;
        border-radius: 3px;
        padding: .6rem .3rem;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        font-size: .75rem;
        color: #8a7a5a;
    }

    .dia-btn.selected {
        background: rgba(201,168,76,.2);
        border-color: #c9a84c;
        color: #c9a84c;
    }

    .dia-btn:hover {
        background: rgba(201,168,76,.1);
        border-color: #7a5f28;
    }

    .rangos-container {
        background: #1a1a1a;
        border-radius: 4px;
        padding: .8rem;
        margin-bottom: 1rem;
        max-height: 250px;
        overflow-y: auto;
    }

    .rango-item {
        display: flex;
        gap: .5rem;
        margin-bottom: .5rem;
        align-items: center;
    }

    .rango-item input {
        flex: 1;
        background: #111;
        border: 1px solid #2a2010;
        border-radius: 3px;
        padding: .5rem;
        color: #f0ead8;
    }

    .btn-remove-rango {
        background: rgba(239,68,68,.1);
        border: none;
        color: #f87171;
        cursor: pointer;
        padding: .3rem .6rem;
        border-radius: 3px;
    }

    .btn-add-rango {
        background: rgba(201,168,76,.1);
        border: 1px solid rgba(201,168,76,.2);
        color: #c9a84c;
        padding: .4rem .8rem;
        border-radius: 3px;
        font-size: .7rem;
        cursor: pointer;
        margin-top: .5rem;
    }

    .info-text {
        font-size: .7rem;
        color: #6b5e3e;
        margin-top: .5rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }
</style>

<div class="page-wrap">

    <div class="top-bar">
        <a href="{{ route('admin.cholets.edit', $cholet->id) }}" class="btn-back">
            <i class="ti ti-arrow-left"></i> Volver al Cholet
        </a>
        <div style="display: flex; gap: .5rem;">
            <button class="btn-primary" onclick="openModal()">
                <i class="ti ti-plus"></i> Agregar Horario
            </button>
            <button class="btn-secondary" onclick="openRecurrente()">
                <i class="ti ti-calendar-repeat"></i> Horarios Recurrentes
            </button>
            <button class="btn-secondary" onclick="openRangoFechas()">
                <i class="ti ti-calendar-range"></i> Rango de Fechas
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">
                <i class="ti ti-clock"></i> Horarios de {{ $cholet->nombre }}
            </div>
            <span style="font-size: .7rem; color: #6b5e3e;">{{ $horarios->total() }} horarios registrados</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Capacidad</th>
                        <th>Reservados</th>
                        <th>Disponibles</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($horarios as $horario)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($horario->hora_fin)->format('h:i A') }}</td>
                        <td>{{ $horario->capacidad_maxima }}</td>
                        <td style="color: #c9a84c;">{{ $horario->reservas_actuales }}</td>
                        <td>{{ $horario->capacidad_maxima - $horario->reservas_actuales }}</td>
                        <td>
                            @php $disponibles = $horario->capacidad_maxima - $horario->reservas_actuales; @endphp
                            @if($disponibles > 0)
                                <span class="badge-active">✓ Disponible</span>
                            @else
                                <span class="badge-full">✗ Completado</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.horarios.destroy', [$cholet->id, $horario->id]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Eliminar este horario?')"
                                  style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger" title="Eliminar">
                                    <i class="ti ti-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="ti ti-calendar-off"></i>
                                <p>No hay horarios creados para este cholet.</p>
                                <button class="btn-primary" onclick="openModal()" style="margin-top: 1rem;">
                                    <i class="ti ti-plus"></i> Crear primer horario
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($horarios->hasPages())
        <div style="padding: 1rem; border-top: 1px solid rgba(255,255,255,.04); text-align: right;">
            {{ $horarios->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL AGREGAR HORARIO SIMPLE --}}
<div class="modal-overlay" id="modalHorario">
    <div class="modal-box">
        <div class="modal-title">➕ Agregar Horario</div>
        <form action="{{ route('admin.horarios.store', $cholet->id) }}" method="POST" id="formSimple">
            @csrf
            <div class="form-group">
                <label>📅 FECHA</label>
                <input type="date" name="fecha" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>⏰ HORA INICIO</label>
                    <input type="time" name="hora_inicio" class="time-input" step="60" required>
                </div>
                <div class="form-group">
                    <label>⏰ HORA FIN</label>
                    <input type="time" name="hora_fin" class="time-input" step="60" required>
                </div>
            </div>
            <div class="form-group">
                <label>👥 CAPACIDAD MÁXIMA</label>
                <input type="number" name="capacidad_maxima" value="20" min="1" max="100" required>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-primary">Guardar Horario</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HORARIOS RECURRENTES --}}
<div class="modal-overlay" id="modalRecurrente">
    <div class="modal-box">
        <div class="modal-title">🔄 Horarios Recurrentes</div>
        <div class="form-group">
            <label>📅 DÍAS DE LA SEMANA</label>
            <div class="dias-semana" id="diasSemana">
                <div class="dia-btn" data-dia="1">LUN</div>
                <div class="dia-btn" data-dia="2">MAR</div>
                <div class="dia-btn" data-dia="3">MIÉ</div>
                <div class="dia-btn" data-dia="4">JUE</div>
                <div class="dia-btn" data-dia="5">VIE</div>
                <div class="dia-btn" data-dia="6">SÁB</div>
                <div class="dia-btn" data-dia="7">DOM</div>
            </div>
        </div>
        <div class="form-group">
            <label>📅 FECHA INICIO</label>
            <input type="date" id="fechaInicioRec" min="{{ date('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>📅 FECHA FIN (opcional)</label>
            <input type="date" id="fechaFinRec">
            <div class="info-text">Si no seleccionas fecha fin, se generarán para los próximos 3 meses</div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>⏰ HORA INICIO</label>
                <input type="time" id="horaInicioRec" class="time-input" step="60" required>
            </div>
            <div class="form-group">
                <label>⏰ HORA FIN</label>
                <input type="time" id="horaFinRec" class="time-input" step="60" required>
            </div>
        </div>
        <div class="form-group">
            <label>👥 CAPACIDAD MÁXIMA</label>
            <input type="number" id="capacidadRec" value="20" min="1" max="100" required>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-primary" onclick="generarRecurrentes()">Generar Horarios</button>
            <button type="button" class="btn-secondary" onclick="closeRecurrente()">Cancelar</button>
        </div>
    </div>
</div>

{{-- MODAL RANGO DE FECHAS --}}
<div class="modal-overlay" id="modalRangoFechas">
    <div class="modal-box">
        <div class="modal-title">📅 Rango de Fechas</div>
        
        <div class="form-group">
            <label>📅 FECHA INICIO</label>
            <input type="date" id="rangoFechaInicio" class="form-input" min="{{ date('Y-m-d') }}" required>
        </div>

        <div class="form-group">
            <label>📅 FECHA FIN</label>
            <input type="date" id="rangoFechaFin" class="form-input" required>
        </div>

        <div class="form-group">
            <label>⏰ HORARIOS A GENERAR</label>
            <div id="rangosContainer" class="rangos-container">
                <div class="rango-item">
                    <input type="time" class="rango-hora-inicio" value="09:00" step="60" required>
                    <input type="time" class="rango-hora-fin" value="12:00" step="60" required>
                    <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
                </div>
                <div class="rango-item">
                    <input type="time" class="rango-hora-inicio" value="14:00" step="60" required>
                    <input type="time" class="rango-hora-fin" value="17:00" step="60" required>
                    <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
                </div>
                <div class="rango-item">
                    <input type="time" class="rango-hora-inicio" value="19:00" step="60" required>
                    <input type="time" class="rango-hora-fin" value="22:00" step="60" required>
                    <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
                </div>
            </div>
            <button type="button" class="btn-add-rango" onclick="agregarRango()">
                <i class="ti ti-plus"></i> Agregar otro horario
            </button>
            <div class="info-text">
                <i class="ti ti-info-circle"></i> Puedes agregar múltiples horarios por día
            </div>
        </div>

        <div class="form-group">
            <label>👥 CAPACIDAD MÁXIMA (para todos)</label>
            <input type="number" id="rangoCapacidad" value="20" min="1" max="100" required>
        </div>

        <div class="modal-actions">
            <button type="button" class="btn-primary" id="btnGenerarRango" onclick="generarPorRango()">
                <i class="ti ti-device-floppy"></i> Generar Horarios
            </button>
            <button type="button" class="btn-secondary" onclick="closeRangoFechas()">Cancelar</button>
        </div>
    </div>
</div>

<script>
// Variables
let diasSeleccionados = [];

// Funciones de modales
function openModal() { document.getElementById('modalHorario').classList.add('active'); }
function closeModal() { document.getElementById('modalHorario').classList.remove('active'); }

function openRecurrente() { 
    document.getElementById('modalRecurrente').classList.add('active'); 
    diasSeleccionados = [];
    document.querySelectorAll('#diasSemana .dia-btn').forEach(btn => btn.classList.remove('selected'));
}
function closeRecurrente() { document.getElementById('modalRecurrente').classList.remove('active'); }

function openRangoFechas() { 
    // Establecer fechas por defecto
    const hoy = new Date();
    const manana = new Date(hoy);
    manana.setDate(hoy.getDate() + 1);
    const treintaDias = new Date(hoy);
    treintaDias.setDate(hoy.getDate() + 30);
    
    document.getElementById('rangoFechaInicio').value = manana.toISOString().split('T')[0];
    document.getElementById('rangoFechaFin').value = treintaDias.toISOString().split('T')[0];
    document.getElementById('rangoCapacidad').value = '20';
    
    // Resetear rangos
    const container = document.getElementById('rangosContainer');
    container.innerHTML = `
        <div class="rango-item">
            <input type="time" class="rango-hora-inicio" value="09:00" step="60" required>
            <input type="time" class="rango-hora-fin" value="12:00" step="60" required>
            <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
        </div>
        <div class="rango-item">
            <input type="time" class="rango-hora-inicio" value="14:00" step="60" required>
            <input type="time" class="rango-hora-fin" value="17:00" step="60" required>
            <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
        </div>
        <div class="rango-item">
            <input type="time" class="rango-hora-inicio" value="19:00" step="60" required>
            <input type="time" class="rango-hora-fin" value="22:00" step="60" required>
            <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
        </div>
    `;
    
    document.getElementById('modalRangoFechas').classList.add('active');
}
function closeRangoFechas() { document.getElementById('modalRangoFechas').classList.remove('active'); }

// Selección de días
document.querySelectorAll('#diasSemana .dia-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const dia = parseInt(this.dataset.dia);
        if (diasSeleccionados.includes(dia)) {
            diasSeleccionados = diasSeleccionados.filter(d => d !== dia);
            this.classList.remove('selected');
        } else {
            diasSeleccionados.push(dia);
            this.classList.add('selected');
        }
    });
});

// Rangos dinámicos
function agregarRango() {
    const container = document.getElementById('rangosContainer');
    const nuevoRango = document.createElement('div');
    nuevoRango.className = 'rango-item';
    nuevoRango.innerHTML = `
        <input type="time" class="rango-hora-inicio" value="09:00" step="60" required>
        <input type="time" class="rango-hora-fin" value="12:00" step="60" required>
        <button type="button" class="btn-remove-rango" onclick="eliminarRango(this)">✕</button>
    `;
    container.appendChild(nuevoRango);
}

function eliminarRango(btn) {
    const container = document.getElementById('rangosContainer');
    if (container.children.length > 1) {
        btn.parentElement.remove();
    } else {
        alert('⚠️ Debe haber al menos un horario');
    }
}

// Generar horarios recurrentes
async function generarRecurrentes() {
    if (diasSeleccionados.length === 0) {
        alert('❌ Selecciona al menos un día de la semana');
        return;
    }

    const fechaInicio = document.getElementById('fechaInicioRec').value;
    let fechaFin = document.getElementById('fechaFinRec').value;
    const horaInicio = document.getElementById('horaInicioRec').value;
    const horaFin = document.getElementById('horaFinRec').value;
    const capacidad = document.getElementById('capacidadRec').value;

    if (!fechaInicio) {
        alert('❌ Selecciona una fecha de inicio');
        return;
    }

    if (!fechaFin) {
        const tresMeses = new Date(fechaInicio);
        tresMeses.setMonth(tresMeses.getMonth() + 3);
        fechaFin = tresMeses.toISOString().split('T')[0];
    }

    if (!horaInicio || !horaFin) {
        alert('❌ Selecciona hora de inicio y fin');
        return;
    }

    if (horaInicio >= horaFin) {
        alert('❌ La hora fin debe ser mayor a la hora inicio');
        return;
    }

    const fechas = [];
    let currentDate = new Date(fechaInicio);
    const endDate = new Date(fechaFin);
    
    while (currentDate <= endDate) {
        let diaSemana = currentDate.getDay();
        let diaNumerico = diaSemana === 0 ? 7 : diaSemana;
        if (diasSeleccionados.includes(diaNumerico)) {
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const day = String(currentDate.getDate()).padStart(2, '0');
            fechas.push(`${year}-${month}-${day}`);
        }
        currentDate.setDate(currentDate.getDate() + 1);
    }

    if (fechas.length === 0) {
        alert('❌ No hay fechas que coincidan con los días seleccionados');
        return;
    }

    const response = await fetch('{{ url("admin/cholets") }}/{{ $cholet->id }}/horarios/multiple', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            fechas: fechas,
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            capacidad_maxima: capacidad
        })
    });

    const data = await response.json();
    alert(`📊 RESULTADO:\n✅ Creados: ${data.creados}\n❌ Errores: ${data.errores}\n📅 Total fechas: ${fechas.length}`);
    
    if (data.creados > 0) {
        window.location.reload();
    }
}

// Generar por rango de fechas
async function generarPorRango() {
    const btn = document.getElementById('btnGenerarRango');
    const textoOriginal = btn.innerHTML;
    btn.innerHTML = '<i class="ti ti-loader"></i> Generando...';
    btn.disabled = true;
    
    try {
        const fechaInicio = document.getElementById('rangoFechaInicio').value;
        const fechaFin = document.getElementById('rangoFechaFin').value;
        const capacidad = document.getElementById('rangoCapacidad').value;

        if (!fechaInicio || !fechaFin) {
            alert('❌ Selecciona fecha de inicio y fin');
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
            return;
        }

        if (fechaInicio > fechaFin) {
            alert('❌ La fecha fin debe ser posterior a la fecha inicio');
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
            return;
        }

        const rangos = [];
        document.querySelectorAll('#rangosContainer .rango-item').forEach(item => {
            const horaInicio = item.querySelector('.rango-hora-inicio').value;
            const horaFin = item.querySelector('.rango-hora-fin').value;
            if (horaInicio && horaFin) {
                rangos.push({ hora_inicio: horaInicio, hora_fin: horaFin });
            }
        });

        if (rangos.length === 0) {
            alert('❌ Agrega al menos un horario');
            btn.innerHTML = textoOriginal;
            btn.disabled = false;
            return;
        }

        // Generar todas las fechas del rango
        const fechas = [];
        let currentDate = new Date(fechaInicio);
        const endDate = new Date(fechaFin);
        
        while (currentDate <= endDate) {
            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const day = String(currentDate.getDate()).padStart(2, '0');
            fechas.push(`${year}-${month}-${day}`);
            currentDate.setDate(currentDate.getDate() + 1);
        }

        let totalCreados = 0;
        let totalErrores = 0;
        const erroresDetalle = [];

        for (const fecha of fechas) {
            for (const rango of rangos) {
                try {
                    const response = await fetch('{{ url("admin/cholets") }}/{{ $cholet->id }}/horarios', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            fecha: fecha,
                            hora_inicio: rango.hora_inicio,
                            hora_fin: rango.hora_fin,
                            capacidad_maxima: capacidad
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        totalCreados++;
                    } else {
                        totalErrores++;
                        if (erroresDetalle.length < 5) {
                            erroresDetalle.push(`${fecha} ${rango.hora_inicio}-${rango.hora_fin}: ${data.message}`);
                        }
                    }
                } catch (error) {
                    totalErrores++;
                    if (erroresDetalle.length < 5) {
                        erroresDetalle.push(`${fecha} ${rango.hora_inicio}-${rango.hora_fin}: Error de conexión`);
                    }
                }
            }
        }

        let mensaje = `📊 RESULTADO:\n✅ Creados: ${totalCreados}\n❌ Errores: ${totalErrores}\n📅 Fechas: ${fechas.length}\n⏰ Horarios/día: ${rangos.length}\n📈 Total intentos: ${fechas.length * rangos.length}`;
        
        if (erroresDetalle.length > 0) {
            mensaje += `\n\n⚠️ Algunos errores:\n${erroresDetalle.join('\n')}`;
        }
        
        alert(mensaje);
        
        if (totalCreados > 0) {
            window.location.reload();
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Ocurrió un error inesperado');
    } finally {
        btn.innerHTML = textoOriginal;
        btn.disabled = false;
    }
}

// Cerrar modales con ESC
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('active');
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
    }
});
</script>

@endsection