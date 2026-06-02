@extends('admin.layouts.app')
@section('title', 'Experiencias')
@section('page-title', 'Gestión de Experiencias')

@section('content')

<style>
    .page-wrap { animation: fadeUp .45s cubic-bezier(.22,1,.36,1) both; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }

    .top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.4rem;
        flex-wrap: wrap;
        gap: 1rem;
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
        text-decoration: none;
        transition: transform .2s;
    }

    .btn-primary:hover { transform: translateY(-1px); color: #080808; }

    .btn-edit {
        background: rgba(201,168,76,.1);
        border: 1px solid rgba(201,168,76,.2);
        color: #c9a84c;
        padding: .4rem .8rem;
        border-radius: 3px;
        font-size: .75rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }

    .btn-edit:hover { background: rgba(201,168,76,.2); }

    .btn-delete {
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.2);
        color: #f87171;
        padding: .4rem .8rem;
        border-radius: 3px;
        font-size: .75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }

    .btn-delete:hover { background: rgba(239,68,68,.2); }

    .btn-toggle {
        background: rgba(255,255,255,.05);
        border: 1px solid #2a2010;
        color: #c8bfa8;
        padding: .4rem .8rem;
        border-radius: 3px;
        font-size: .75rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        text-decoration: none;
    }

    .btn-toggle:hover { background: rgba(255,255,255,.1); }

    .panel {
        background: #111111;
        border: 1px solid #221a08;
        border-radius: 4px;
        overflow: hidden;
    }

    .panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, #c9a84c, transparent);
        opacity: .7;
    }

    .panel { position: relative; }

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

    .badge-inactive {
        background: rgba(239,68,68,.1);
        color: #f87171;
        padding: .2rem .6rem;
        border-radius: 20px;
        font-size: .7rem;
    }

    .precio {
        color: #c9a84c;
        font-weight: 600;
    }

    .acciones {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #4a3a1a;
    }

    .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; display: block; }
</style>

<div class="page-wrap">
    <div class="top-bar">
        <h3 style="margin:0; color:#e8e0d0;">Experiencias</h3>
        <a href="{{ route('admin.experiencias.create') }}" class="btn-primary">
            <i class="ti ti-plus"></i> Nueva Experiencia
        </a>
    </div>

    <div class="panel">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio USD</th>
                        <th>Precio Bs</th>
                        <th>Duración</th>
                        <th>Estado</th>
                        <th>Orden</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($experiencias as $exp)
                    <tr>
                        <td>{{ $exp->id }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($exp->icono)
                                    <i class="{{ $exp->icono }}" style="color:{{ $exp->color ?? '#c9a84c' }}"></i>
                                @endif
                                <strong style="color:{{ $exp->color ?? '#e8e0d0' }}">{{ $exp->nombre }}</strong>
                            </div>
                        </td>
                        <td>{{ Str::limit($exp->descripcion, 50) }}</td>
                        <td class="precio">${{ number_format($exp->precio, 2) }}</td>
                        <td>Bs {{ number_format($exp->precio_bs ?? $exp->precio * 6.96, 2) }}</td>
                        <td>{{ $exp->duracion_horas }} horas</td>
                        <td>
                            @if($exp->activo)
                                <span class="badge-active">✓ Activo</span>
                            @else
                                <span class="badge-inactive">✗ Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $exp->orden }}</td>
                        <td class="acciones">
                            <a href="{{ route('admin.experiencias.toggle', $exp->id) }}" class="btn-toggle" title="{{ $exp->activo ? 'Desactivar' : 'Activar' }}">
                                <i class="ti ti-{{ $exp->activo ? 'eye-off' : 'eye' }}"></i>
                            </a>
                            <a href="{{ route('admin.experiencias.edit', $exp->id) }}" class="btn-edit">
                                <i class="ti ti-edit"></i> Editar
                            </a>
                            <form action="{{ route('admin.experiencias.destroy', $exp->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar {{ $exp->nombre }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="ti ti-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="ti ti-ticket"></i>
                                <p>No hay experiencias creadas</p>
                                <a href="{{ route('admin.experiencias.create') }}" class="btn-primary" style="margin-top:1rem;">
                                    Crear primera experiencia
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection