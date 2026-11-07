@extends('admin.layouts.app')

@section('title', 'Asignar Guías')
@section('page-title', 'Asignar Guías a Reservas Confirmadas')

@section('content')

<style>
    /* ── Layout general ──────────────────────────────────────── */
    .pg { animation: fadeUp .4s cubic-bezier(.22,1,.36,1) both; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:none; } }

    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .page-heading {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #e8e0d0;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .page-heading i { color: #c9a84c; font-size: 1.1rem; }

    /* ── Search ──────────────────────────────────────────────── */
    .search-wrap {
        position: relative;
    }

    .search-wrap i {
        position: absolute;
        left: .7rem;
        top: 50%;
        transform: translateY(-50%);
        color: #5a4820;
        font-size: .85rem;
        pointer-events: none;
    }

    .search-input {
        background: #0e0e0e;
        border: 1px solid #2a2010;
        border-radius: 4px;
        padding: .5rem .8rem .5rem 2rem;
        color: #c8bfa8;
        font-size: .75rem;
        min-width: 260px;
        transition: border-color .2s;
    }

    .search-input::placeholder { color: #3d2e10; }
    .search-input:focus { outline: none; border-color: #c9a84c; }

    /* ── Info banner ─────────────────────────────────────────── */
    .info-banner {
        background: rgba(39,174,96,.08);
        border: 1px solid rgba(39,174,96,.2);
        border-left: 3px solid #27ae60;
        border-radius: 4px;
        padding: .75rem 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: .7rem;
    }

    .info-banner i { color: #27ae60; font-size: 1rem; margin-top: .05rem; flex-shrink: 0; }
    .info-banner p { margin: 0; font-size: .75rem; color: #a09070; line-height: 1.5; }
    .info-banner strong { color: #c8bfa8; font-weight: 600; }

    /* ── Stat cards ──────────────────────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: .8rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #111;
        border: 1px solid #1e1608;
        border-radius: 4px;
        padding: 1rem 1.1rem;
        position: relative;
        overflow: hidden;
        transition: border-color .2s, transform .2s;
    }

    .stat-card:hover { border-color: #3d2e10; transform: translateY(-1px); }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, #c9a84c44, transparent);
    }

    .stat-icon {
        font-size: 1.4rem;
        color: #3d2e10;
        margin-bottom: .5rem;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #e8cc80;
        line-height: 1;
        font-family: 'Playfair Display', serif;
    }

    .stat-label {
        font-size: .65rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #5a4820;
        margin-top: .25rem;
    }

    /* ── Main panel ──────────────────────────────────────────── */
    .panel {
        background: #111;
        border: 1px solid #1e1608;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .panel-header {
        padding: .8rem 1.2rem;
        border-bottom: 1px solid #181208;
        font-size: .7rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #5a4820;
        background: rgba(0,0,0,.15);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .panel-header i { color: #c9a84c; }

    /* ── Grupo de horario ────────────────────────────────────── */
    .grupo {
        border-bottom: 1px solid #151005;
    }

    .grupo:last-child { border-bottom: none; }

    .grupo-header {
        padding: .9rem 1.2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        cursor: pointer;
        transition: background .15s;
        user-select: none;
    }

    .grupo-header:hover { background: rgba(201,168,76,.04); }

    .grupo-left {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .chevron {
        color: #5a4820;
        font-size: .85rem;
        transition: transform .2s;
        flex-shrink: 0;
    }

    .chevron.open { transform: rotate(90deg); }

    /* Tags de info del grupo */
    .tag {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .6rem;
        border-radius: 3px;
        font-size: .68rem;
        font-weight: 500;
    }

    .tag-cholet { background: rgba(201,168,76,.1); color: #c9a84c; }
    .tag-date   { background: rgba(255,255,255,.04); color: #9a8a6a; }
    .tag-time   { background: rgba(255,255,255,.04); color: #9a8a6a; }
    .tag-count  { background: #1e1608; color: #c9a84c; border-radius: 20px; }

    /* Lado derecho del grupo: selector + botón */
    .grupo-right {
        display: flex;
        align-items: center;
        gap: .6rem;
        flex-wrap: wrap;
    }

    .guia-select-wrap {
        position: relative;
    }

    .guia-select-wrap .sel-icon {
        position: absolute;
        left: .6rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: .8rem;
        color: #5a4820;
        pointer-events: none;
    }

    .guia-select {
        background: #0d0d0d;
        border: 1px solid #2a2010;
        border-radius: 3px;
        padding: .5rem .8rem .5rem 2rem;
        color: #c8bfa8;
        font-size: .73rem;
        min-width: 210px;
        appearance: none;
        cursor: pointer;
        transition: border-color .2s;
    }

    .guia-select:focus { outline: none; border-color: #c9a84c; }

    option:disabled { color: #3d2e10; }

    .btn-assign {
        background: #1e1608;
        border: 1px solid #3d2e10;
        color: #c9a84c;
        padding: .5rem .9rem;
        border-radius: 3px;
        font-size: .73rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: background .2s, transform .2s, border-color .2s;
        white-space: nowrap;
    }

    .btn-assign:hover:not(:disabled) {
        background: #3d2e10;
        border-color: #7a5f28;
        transform: translateY(-1px);
    }

    .btn-assign:disabled { opacity: .5; cursor: not-allowed; }

    /* ── Lista de reservas ───────────────────────────────────── */
    .reservas-body {
        border-top: 1px solid #151005;
        background: rgba(0,0,0,.12);
    }

    .reserva-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .7rem 1.2rem .7rem 3.5rem;
        border-bottom: 1px solid #0f0b04;
        gap: .8rem;
        flex-wrap: wrap;
        transition: background .15s;
    }

    .reserva-row:last-child { border-bottom: none; }
    .reserva-row:hover { background: rgba(201,168,76,.02); }

    .reserva-left {
        display: flex;
        align-items: center;
        gap: .8rem;
        flex-wrap: wrap;
    }

    .avatar {
        width: 30px;
        height: 30px;
        background: #1e1608;
        border: 1px solid #3d2e10;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .65rem;
        font-weight: 700;
        color: #c9a84c;
        flex-shrink: 0;
    }

    .cliente-name {
        font-size: .8rem;
        font-weight: 500;
        color: #d8cfc0;
    }

    .cliente-email {
        font-size: .65rem;
        color: #5a4820;
        margin-top: .1rem;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        font-size: .65rem;
        padding: .15rem .5rem;
        border-radius: 20px;
    }

    .pill-personas { background: rgba(201,168,76,.1); color: #a07a30; }
    .pill-codigo   { background: rgba(0,0,0,.3); color: #5a4820; font-family: monospace; }
    .pill-ok       { background: rgba(74,222,128,.08); color: #6ee7b7; }

    /* ── Empty state ─────────────────────────────────────────── */
    .empty {
        padding: 3rem;
        text-align: center;
        color: #3d2e10;
    }

    .empty i { font-size: 2rem; display: block; margin-bottom: .8rem; }
    .empty p { font-size: .8rem; margin: .3rem 0 0; }
    .empty small { font-size: .68rem; color: #2a1e08; }

    /* ── Modal ───────────────────────────────────────────────── */
    .modal-wrap {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.75);
        backdrop-filter: blur(3px);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal-wrap.open { display: flex; }

    .modal-box {
        background: #131313;
        border: 1px solid #3d2e10;
        border-top: 2px solid #c9a84c;
        border-radius: 6px;
        max-width: 440px;
        width: 92%;
        padding: 1.8rem 1.5rem 1.5rem;
        text-align: center;
        animation: popIn .25s cubic-bezier(.22,1,.36,1);
    }

    @keyframes popIn { from { opacity:0; transform:scale(.96) translateY(-8px); } to { opacity:1; transform:none; } }

    .modal-ico { font-size: 2.2rem; color: #c9a84c; margin-bottom: .8rem; }
    .modal-title { font-size: 1rem; color: #e8cc80; font-weight: 600; margin-bottom: .5rem; }
    .modal-body { font-size: .8rem; color: #9a8a6a; line-height: 1.6; margin-bottom: 1.2rem; }
    .modal-body strong { color: #c8bfa8; }

    .modal-detail {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #1e1608;
        border: 1px solid #3d2e10;
        border-radius: 4px;
        padding: .4rem .8rem;
        font-size: .7rem;
        color: #c9a84c;
        margin-top: .6rem;
    }

    .modal-btns { display: flex; gap: .8rem; justify-content: center; }

    .mbtn {
        padding: .55rem 1.2rem;
        border-radius: 4px;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
    }

    .mbtn-cancel { background: rgba(255,255,255,.07); color: #7a6a4a; }
    .mbtn-cancel:hover { background: rgba(255,255,255,.12); }
    .mbtn-confirm { background: #3d2e10; color: #e8cc80; border: 1px solid #7a5f28; }
    .mbtn-confirm:hover { background: #7a5f28; transform: translateY(-1px); }

    /* ── Toast ───────────────────────────────────────────────── */
    #toast-container {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 3000;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .toast {
        padding: .65rem 1rem;
        border-radius: 4px;
        font-size: .78rem;
        font-weight: 500;
        color: #fff;
        display: flex;
        align-items: center;
        gap: .5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,.5);
        animation: toastIn .3s ease;
        transition: opacity .3s;
    }

    @keyframes toastIn { from { opacity:0; transform:translateX(30px); } to { opacity:1; transform:none; } }

    .toast-ok  { background: #1a4a2a; border: 1px solid #27ae60; }
    .toast-err { background: #4a1a1a; border: 1px solid #c0392b; }

    /* ── Responsive ──────────────────────────────────────────── */
    @media (max-width: 768px) {
        .stats-row { grid-template-columns: 1fr 1fr; }
        .grupo-header { flex-direction: column; align-items: stretch; }
        .grupo-right { justify-content: flex-end; }
        .reserva-row { padding-left: 1.2rem; }
    }
</style>

<div id="toast-container"></div>

<div class="pg">

    <!-- Top bar -->
    <div class="top-bar">
        <h3 class="page-heading">
            <i class="ti ti-user-check"></i> Asignar Guías
        </h3>
        <div class="search-wrap">
            <i class="ti ti-search"></i>
            <input id="searchInput" type="text" class="search-input" placeholder="Buscar por cliente, email o código…">
        </div>
    </div>

    <!-- Info banner -->
    <div class="info-banner">
        <i class="ti ti-info-circle"></i>
        <p>Solo se muestran reservas <strong>confirmadas</strong>. Para asignar un guía a un grupo, selecciona el guía y haz clic en <strong>"Asignar a todas"</strong> — el guía quedará asignado a todas las reservas de ese horario y cholet.</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-calendar-check"></i></div>
            <div class="stat-number">{{ $reservasPendientes->count() }}</div>
            <div class="stat-label">Reservas sin guía</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-users"></i></div>
            <div class="stat-number">{{ $guias->count() }}</div>
            <div class="stat-label">Guías activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="ti ti-circle-check"></i></div>
            <div class="stat-number">{{ $guias->where('disponibilidad', 'disponible')->count() }}</div>
            <div class="stat-label">Guías disponibles</div>
        </div>
    </div>

    <!-- Panel principal -->
    <div class="panel">
        <div class="panel-header">
            <i class="ti ti-clock"></i>
            Grupos por horario y cholet
        </div>

        @if($reservasAgrupadas->count() == 0)
            <div class="empty">
                <i class="ti ti-check-circle"></i>
                <p>No hay reservas confirmadas pendientes de asignar guía</p>
                <small>Confirma las reservas desde la sección "Reservas" primero</small>
            </div>
        @else
            @foreach($reservasAgrupadas as $key => $grupo)
                @php
                    $parts      = explode('|', $key);
                    $fecha      = $parts[0];
                    $hora       = $parts[1];
                    $cholet_id  = $parts[2];
                    $cholet     = $grupo->first()->cholet;
                    $totalPers  = $grupo->sum('personas');
                    $slug       = Str::slug($key);
                    $isFirst    = $loop->first;
                @endphp

                <div class="grupo"
                     data-fecha="{{ $fecha }}"
                     data-hora="{{ $hora }}"
                     data-cholet="{{ $cholet_id }}">

                    <!-- Header del grupo -->
                    <div class="grupo-header" onclick="toggleGrupo(this)">

                        <div class="grupo-left">
                            <i class="ti ti-chevron-right chevron {{ $isFirst ? 'open' : '' }}"></i>

                            <span class="tag tag-cholet">
                                <i class="ti ti-building"></i>
                                {{ $cholet->nombre ?? 'Tour' }}
                            </span>
                            <span class="tag tag-date">
                                <i class="ti ti-calendar"></i>
                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                            </span>
                            <span class="tag tag-time">
                                <i class="ti ti-clock"></i>
                                {{ $hora }} hrs
                            </span>
                            <span class="tag tag-count">
                                <i class="ti ti-users"></i>
                                {{ $grupo->count() }} reserva{{ $grupo->count() != 1 ? 's' : '' }}
                                &nbsp;·&nbsp; {{ $totalPers }} personas
                            </span>
                        </div>

                        <div class="grupo-right" onclick="event.stopPropagation()">
                            <div class="guia-select-wrap">
                                <i class="ti ti-user sel-icon"></i>
                                <select id="guia_{{ $slug }}" class="guia-select">
                                    <option value="">Seleccionar guía…</option>
                                    @foreach($guias as $guia)
                                        @php $ocupado = $guia->disponibilidad == 'ocupado'; @endphp
                                        <option value="{{ $guia->id }}" {{ $ocupado ? 'disabled' : '' }}>
                                            {{ $guia->nombre }} {{ $guia->apellido ?? '' }}
                                            @if($guia->especialidad) — {{ $guia->especialidad }} @endif
                                            @if($ocupado) (ocupado) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn-assign"
                                    onclick="abrirModal('{{ $fecha }}', '{{ $hora }}', {{ $cholet_id }}, {{ $grupo->count() }}, '{{ $slug }}')">
                                <i class="ti ti-user-plus"></i>
                                Asignar a todas
                            </button>
                        </div>

                    </div>

                    <!-- Lista de reservas -->
                    <div class="reservas-body" style="{{ $isFirst ? '' : 'display:none;' }}">
                        @foreach($grupo as $reserva)
                            <div class="reserva-row"
                                 data-cliente="{{ strtolower($reserva->user->nombre ?? $reserva->user->name ?? '') }}"
                                 data-email="{{ strtolower($reserva->user->email ?? '') }}"
                                 data-codigo="{{ strtolower($reserva->codigo_reserva ?? '') }}">

                                <div class="reserva-left">
                                    <div class="avatar">
                                        {{ strtoupper(substr($reserva->user->nombre ?? $reserva->user->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="cliente-name">
                                            {{ $reserva->user->nombre ?? $reserva->user->name ?? 'Cliente' }}
                                        </div>
                                        <div class="cliente-email">
                                            {{ $reserva->user->email ?? '' }}
                                        </div>
                                    </div>
                                    <span class="pill pill-personas">
                                        <i class="ti ti-users"></i> {{ $reserva->personas }} personas
                                    </span>
                                    <span class="pill pill-codigo">
                                        {{ $reserva->codigo_reserva ?? 'N/A' }}
                                    </span>
                                </div>

                                <span class="pill pill-ok">
                                    <i class="ti ti-check"></i> Confirmada
                                </span>

                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        @endif
    </div>

</div><!-- .pg -->

<!-- Modal de confirmación -->
<div id="confirmModal" class="modal-wrap">
    <div class="modal-box">
        <div class="modal-ico"><i class="ti ti-user-check"></i></div>
        <div class="modal-title">Confirmar asignación</div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-btns">
            <button class="mbtn mbtn-cancel" onclick="cerrarModal()">
                <i class="ti ti-x"></i> Cancelar
            </button>
            <button class="mbtn mbtn-confirm" onclick="confirmarAsignacion()">
                <i class="ti ti-check"></i> Confirmar
            </button>
        </div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
let pending = null;

/* ── Toggle grupo ─────────────────────────────────── */
function toggleGrupo(header) {
    const body    = header.nextElementSibling;
    const chevron = header.querySelector('.chevron');
    const open    = body.style.display !== 'none' && body.style.display !== '';
    body.style.display = open ? 'none' : '';
    chevron?.classList.toggle('open', !open);
}

/* ── Toast ────────────────────────────────────────── */
function toast(msg, type = 'ok') {
    const el = document.createElement('div');
    el.className = `toast toast-${type}`;
    el.innerHTML = `<i class="ti ti-${type === 'ok' ? 'check-circle' : 'alert-circle'}"></i><span>${msg}</span>`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 300); }, 4000);
}

/* ── Modal ────────────────────────────────────────── */
function abrirModal(fecha, hora, choletId, total, slug) {
    const sel = document.getElementById(`guia_${slug}`);
    if (!sel?.value) { toast('Selecciona un guía para continuar', 'err'); return; }

    const nombre = sel.options[sel.selectedIndex]?.text.split('—')[0].trim() || 'Guía seleccionado';
    pending = { fecha, hora, choletId, guiaId: sel.value, total, slug };

    document.getElementById('modalBody').innerHTML = `
        <strong>${nombre}</strong> será asignado a <strong>${total} reserva${total != 1 ? 's' : ''}</strong>
        del mismo horario y cholet.<br><br>
        <div class="modal-detail">
            <i class="ti ti-calendar"></i> ${fecha}
            &ensp;·&ensp;
            <i class="ti ti-clock"></i> ${hora} hrs
        </div>
    `;
    document.getElementById('confirmModal').classList.add('open');
}

function cerrarModal() {
    document.getElementById('confirmModal').classList.remove('open');
    pending = null;
}

/* ── Confirmar asignación ─────────────────────────── */
async function confirmarAsignacion() {
    if (!pending) return;
    cerrarModal();

    const { fecha, hora, choletId, guiaId, slug } = pending;
    const btn = document.querySelector(
        `.grupo[data-fecha="${fecha}"][data-hora="${hora}"][data-cholet="${choletId}"] .btn-assign`
    );
    const orig = btn?.innerHTML;
    if (btn) { btn.innerHTML = '<i class="ti ti-loader"></i> Asignando…'; btn.disabled = true; }

    try {
        const res  = await fetch('{{ route("admin.asignaciones.asignar-masivo") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body:    JSON.stringify({ guia_id: guiaId, fecha, hora, cholet_id: choletId })
        });
        const data = await res.json();

        if (data.success) {
            toast(data.msg, 'ok');
            setTimeout(() => location.reload(), 1600);
        } else {
            toast(data.msg || 'Error al asignar', 'err');
            if (btn) { btn.innerHTML = orig; btn.disabled = false; }
        }
    } catch {
        toast('Error de conexión', 'err');
        if (btn) { btn.innerHTML = orig; btn.disabled = false; }
    }
}

/* ── Búsqueda en tiempo real ──────────────────────── */
document.getElementById('searchInput')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.grupo').forEach(grupo => {
        let hits = 0;
        grupo.querySelectorAll('.reserva-row').forEach(row => {
            const match = !q
                || row.dataset.cliente?.includes(q)
                || row.dataset.email?.includes(q)
                || row.dataset.codigo?.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) hits++;
        });

        if (!q) { grupo.style.display = ''; return; }
        grupo.style.display = hits ? '' : 'none';

        // Si hay hits, abrir el cuerpo automáticamente
        if (hits) {
            const body    = grupo.querySelector('.reservas-body');
            const chevron = grupo.querySelector('.chevron');
            if (body)    body.style.display = '';
            if (chevron) chevron.classList.add('open');
        }
    });
});

/* ── Cerrar modal con Escape ──────────────────────── */
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });

/* ── Abrir primer grupo al cargar ─────────────────── */
(function () {
    const first = document.querySelector('.grupo');
    if (!first) return;
    const body    = first.querySelector('.reservas-body');
    const chevron = first.querySelector('.chevron');
    if (body)    body.style.display = '';
    if (chevron) chevron.classList.add('open');
})();
</script>

@endsection