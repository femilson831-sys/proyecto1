@extends('admin.layouts.app')
@section('title', 'Cholets')
@section('page-title', 'Cholets')

@section('content')

<style>
    /* ══ LAYOUT ══ */
    .page-wrap {
        animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(12px); }
        to   { opacity:1; transform:none; }
    }

    /* ══ TOP BAR ══ */
    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.4rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .top-bar-left {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .record-count {
        font-size: .75rem;
        color: #6b5e3e;
        background: rgba(201,168,76,.07);
        border: 1px solid rgba(201,168,76,.15);
        border-radius: 20px;
        padding: .2rem .75rem;
        letter-spacing: .05em;
    }

    .btn-new {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .72rem 1.3rem;
        background: linear-gradient(135deg, #b8962a, #e0c45a);
        color: #080808;
        border: none;
        border-radius: 3px;
        font-family: 'Jost', sans-serif;
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        text-decoration: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(184,150,42,.25);
        transition: transform .2s, box-shadow .2s;
        white-space: nowrap;
    }

    .btn-new::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 60%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.22), transparent);
        transition: left .4s ease;
    }

    .btn-new:hover::before { left: 160%; }
    .btn-new:hover {
        transform: translateY(-1px);
        box-shadow: 0 7px 22px rgba(184,150,42,.34);
        color: #080808;
        text-decoration: none;
    }

    /* ══ SEARCH ══ */
    .search-wrap {
        position: relative;
        max-width: 280px;
    }

    .search-wrap i {
        position: absolute;
        left: .85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #4a3a1a;
        font-size: 1rem;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        background: rgba(255,255,255,.03);
        border: 1px solid #2a2010;
        border-radius: 3px;
        padding: .62rem .9rem .62rem 2.4rem;
        color: #f0ead8;
        font-family: 'Jost', sans-serif;
        font-size: .84rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .search-input::placeholder { color: #3d2e10; }

    .search-input:focus {
        border-color: #7a5f28;
        box-shadow: 0 0 0 3px rgba(201,168,76,.1);
    }

    /* ══ PANEL / TABLE ══ */
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

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Jost', sans-serif;
        font-size: .86rem;
    }

    .data-table thead tr {
        border-bottom: 1px solid #1e1608;
    }

    .data-table th {
        padding: .85rem 1.2rem;
        text-align: left;
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: #5a4820;
        white-space: nowrap;
        user-select: none;
    }

    .data-table th.th-id   { width: 60px; }
    .data-table th.th-img  { width: 72px; }
    .data-table th.th-act  { width: 220px; text-align: right; }

    .data-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,.03);
        transition: background .15s;
    }

    .data-table tbody tr:last-child { border-bottom: none; }

    .data-table tbody tr:hover { background: rgba(201,168,76,.03); }

    .data-table td {
        padding: .95rem 1.2rem;
        color: #c8bfa8;
        vertical-align: middle;
    }

    /* Thumbnail */
    .thumb {
        width: 46px;
        height: 34px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid #2a2010;
        display: block;
    }

    .thumb-placeholder {
        width: 46px;
        height: 34px;
        border-radius: 2px;
        border: 1px dashed #2a2010;
        background: rgba(255,255,255,.02);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3d2e10;
        font-size: .95rem;
    }

    /* Nombre */
    .cell-name {
        font-weight: 500;
        color: #e8dfc8;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    /* Ubicación */
    .cell-location {
        display: flex;
        align-items: center;
        gap: .4rem;
        color: #8a7a5a;
        font-size: .83rem;
    }

    .cell-location i { font-size: .9rem; color: #5a4820; }

    /* Badge de categoría */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: .22rem .7rem;
        border-radius: 2px;
        white-space: nowrap;
    }

    .badge-lujo    { background: rgba(201,168,76,.12); color: #e0c45a; border: 1px solid rgba(201,168,76,.2); }
    .badge-premium { background: rgba(167,139,250,.1);  color: #c4b5fd; border: 1px solid rgba(167,139,250,.2); }
    .badge-estandar{ background: rgba(56,189,248,.1);   color: #7dd3fc; border: 1px solid rgba(56,189,248,.2); }
    .badge-economico{ background: rgba(52,211,153,.1);  color: #6ee7b7; border: 1px solid rgba(52,211,153,.2); }

    /* Acciones */
    .actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .45rem;
        flex-wrap: wrap;
    }

    /* Botón Horarios */
    .btn-horarios {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .38rem .8rem;
        background: rgba(167,139,250,.1);
        color: #a78bfa;
        border: 1px solid rgba(167,139,250,.2);
        border-radius: 2px;
        font-family: 'Jost', sans-serif;
        font-size: .74rem;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s, border-color .18s, color .18s;
    }

    .btn-horarios:hover {
        background: rgba(167,139,250,.18);
        border-color: rgba(167,139,250,.35);
        color: #c4b5fd;
        text-decoration: none;
    }

    /* Botón Editar */
    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .38rem .8rem;
        background: rgba(201,168,76,.07);
        color: #c9a84c;
        border: 1px solid rgba(201,168,76,.18);
        border-radius: 2px;
        font-family: 'Jost', sans-serif;
        font-size: .74rem;
        font-weight: 600;
        letter-spacing: .07em;
        text-transform: uppercase;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s, border-color .18s, color .18s;
    }

    .btn-edit:hover {
        background: rgba(201,168,76,.15);
        border-color: rgba(201,168,76,.35);
        color: #e0c45a;
        text-decoration: none;
    }

    /* Botón Eliminar */
    .btn-del {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .38rem .8rem;
        background: rgba(239,68,68,.06);
        color: #f87171;
        border: 1px solid rgba(239,68,68,.15);
        border-radius: 2px;
        font-family: 'Jost', sans-serif;
        font-size: .74rem;
        font-weight: 600;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .18s, border-color .18s;
    }

    .btn-del:hover {
        background: rgba(239,68,68,.14);
        border-color: rgba(239,68,68,.3);
    }

    /* ══ EMPTY STATE ══ */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: #4a3a1a;
    }

    .empty-state i { font-size: 2.8rem; margin-bottom: 1rem; display: block; }
    .empty-state p { font-size: .9rem; margin: .3rem 0; }
    .empty-state .em-title { color: #7a6040; font-size: 1rem; font-weight: 600; }

    /* ══ PAGINACIÓN ══ */
    .pagination-wrap {
        display: flex;
        justify-content: flex-end;
        padding: 1rem 1.2rem;
        border-top: 1px solid rgba(255,255,255,.04);
    }

    /* ══ CONFIRM MODAL ══ */
    .confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .confirm-overlay.active { display: flex; }

    .confirm-box {
        background: #111111;
        border: 1px solid #2a2010;
        border-radius: 4px;
        padding: 2rem;
        max-width: 360px;
        width: 100%;
        text-align: center;
        position: relative;
    }

    .confirm-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, #ef4444, transparent);
    }

    .confirm-icon {
        font-size: 2.4rem;
        color: #f87171;
        margin-bottom: .8rem;
    }

    .confirm-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        color: #f0ead8;
        margin-bottom: .5rem;
    }

    .confirm-sub {
        font-size: .82rem;
        color: #6b5e3e;
        line-height: 1.6;
        margin-bottom: 1.6rem;
    }

    .confirm-name {
        color: #c9a84c;
        font-weight: 600;
    }

    .confirm-actions {
        display: flex;
        gap: .6rem;
    }

    .confirm-cancel {
        flex: 1;
        padding: .7rem;
        background: transparent;
        color: #6b5e3e;
        border: 1px solid #2a2010;
        border-radius: 3px;
        font-family: 'Jost', sans-serif;
        font-size: .8rem;
        cursor: pointer;
        transition: color .2s, border-color .2s;
    }

    .confirm-cancel:hover { color: #f0ead8; border-color: #3d2e10; }

    .confirm-delete {
        flex: 1;
        padding: .7rem;
        background: rgba(239,68,68,.12);
        color: #f87171;
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 3px;
        font-family: 'Jost', sans-serif;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }

    .confirm-delete:hover {
        background: rgba(239,68,68,.22);
        border-color: rgba(239,68,68,.4);
    }
</style>

{{-- Confirm delete overlay --}}
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="ti ti-trash-x"></i></div>
        <div class="confirm-title">¿Eliminar cholet?</div>
        <div class="confirm-sub">Esta acción no se puede deshacer. Se eliminará permanentemente <span class="confirm-name" id="confirmName"></span>.</div>
        <div class="confirm-actions">
            <button class="confirm-cancel" onclick="closeConfirm()">Cancelar</button>
            <button class="confirm-delete" id="confirmBtn">Eliminar</button>
        </div>
    </div>
</div>

<div class="page-wrap">

    <div class="top-bar">
        <div class="top-bar-left">
            <div class="search-wrap">
                <i class="ti ti-search"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Buscar cholet…">
            </div>
            <span class="record-count" id="recordCount">{{ $cholets->count() }} registros</span>
        </div>
        <a href="{{ route('admin.cholets.create') }}" class="btn-new">
            <i class="ti ti-plus"></i> Nuevo Cholet
        </a>
    </div>

    <div class="panel">
        <table class="data-table" id="choletTable">
            <thead>
                <tr>
                    <th class="th-id">#</th>
                    <th class="th-img"></th>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>Categoría</th>
                    <th class="th-act">Acciones</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($cholets as $c)
                <tr data-search="{{ strtolower($c->nombre . ' ' . $c->ubicacion . ' ' . $c->categoria) }}">
                    <td style="color:#4a3a1a; font-size:.8rem;">{{ $c->id }}</td>
                    <td>
                        @if($c->imagen)
                            <img src="{{ asset('storage/cholets/' . $c->imagen) }}" alt="{{ $c->nombre }}" class="thumb">
                        @else
                            <div class="thumb-placeholder"><i class="ti ti-photo-off"></i></div>
                        @endif
                    </td>
                    <td>
                        <div class="cell-name">{{ $c->nombre }}</div>
                    </td>
                    <td>
                        <div class="cell-location">
                            <i class="ti ti-map-pin"></i>
                            {{ $c->ubicacion }}
                        </div>
                    </td>
                    <td>
                        @php
                            $badgeClass = match(strtolower($c->categoria)) {
                                'lujo'     => 'badge-lujo',
                                'premium'  => 'badge-premium',
                                'estándar', 'estandar' => 'badge-estandar',
                                'económico','economico' => 'badge-economico',
                                default    => 'badge-estandar',
                            };
                            $badgeIcon = match(strtolower($c->categoria)) {
                                'lujo'     => 'ti-crown',
                                'premium'  => 'ti-diamond',
                                'estándar', 'estandar' => 'ti-building',
                                'económico','economico' => 'ti-home',
                                default    => 'ti-building',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            <i class="ti {{ $badgeIcon }}"></i>
                            {{ $c->categoria }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            {{-- BOTÓN HORARIOS --}}
                            <a href="{{ route('admin.horarios.index', $c->id) }}" class="btn-horarios" title="Gestionar horarios">
                                <i class="ti ti-clock"></i> Horarios
                            </a>
                            
                            {{-- BOTÓN EDITAR --}}
                            <a href="{{ route('admin.cholets.edit', $c->id) }}" class="btn-edit">
                                <i class="ti ti-edit"></i> Editar
                            </a>
                            
                            {{-- BOTÓN ELIMINAR --}}
                            <button class="btn-del" onclick="openConfirm({{ $c->id }}, '{{ addslashes($c->nombre) }}')">
                                <i class="ti ti-trash"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="ti ti-building-off"></i>
                            <p class="em-title">Sin cholets registrados</p>
                            <p>Agrega el primer cholet con el botón "Nuevo Cholet".</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Si usas ->paginate() en el controlador, descomenta esto:
        @if($cholets->hasPages())
        <div class="pagination-wrap">
            {{ $cholets->links() }}
        </div>
        @endif
        --}}
    </div>

</div>

{{-- Hidden forms for deletion --}}
@foreach($cholets as $c)
<form id="deleteForm{{ $c->id }}" action="{{ route('admin.cholets.destroy', $c->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endforeach

<script>
/* ── Búsqueda en tabla ── */
const searchInput = document.getElementById('searchInput');
const rows        = document.querySelectorAll('#tableBody tr[data-search]');
const recordCount = document.getElementById('recordCount');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        rows.forEach(row => {
            const match = row.dataset.search.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        recordCount.textContent = visible + ' registro' + (visible !== 1 ? 's' : '');
    });
}

/* ── Modal de confirmación ── */
let pendingId = null;

function openConfirm(id, name) {
    pendingId = id;
    document.getElementById('confirmName').textContent = name;
    document.getElementById('confirmOverlay').classList.add('active');
}

function closeConfirm() {
    pendingId = null;
    document.getElementById('confirmOverlay').classList.remove('active');
}

const confirmBtn = document.getElementById('confirmBtn');
if (confirmBtn) {
    confirmBtn.addEventListener('click', function () {
        if (pendingId) {
            document.getElementById('deleteForm' + pendingId).submit();
        }
    });
}

const confirmOverlay = document.getElementById('confirmOverlay');
if (confirmOverlay) {
    confirmOverlay.addEventListener('click', function (e) {
        if (e.target === this) closeConfirm();
    });
}

/* ── Tecla ESC para cerrar modal ── */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && confirmOverlay.classList.contains('active')) {
        closeConfirm();
    }
});
</script>

@endsection