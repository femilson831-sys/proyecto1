@extends('admin.layouts.app')

@section('title', 'Gestión de Guías')
@section('page-title', 'Guías Turísticos')

@section('content')
<div class="guias-container">
    {{-- Header con acciones --}}
    <div class="page-header-actions">
        <div class="search-filters">
            <form method="GET" class="search-form">
                <div class="search-box">
                    <i class="ti ti-search"></i>
                    <input type="text" 
                           name="search" 
                           placeholder="Buscar por nombre, email o teléfono..." 
                           value="{{ request('search') }}">
                </div>
                
                <select name="tiene_cholets" class="filter-select">
                    <option value="">Todos los guías</option>
                    <option value="si" {{ request('tiene_cholets') == 'si' ? 'selected' : '' }}>Con cholets asignados</option>
                    <option value="no" {{ request('tiene_cholets') == 'no' ? 'selected' : '' }}>Sin cholets asignados</option>
                </select>
                
                <select name="disponibilidad" class="filter-select">
                    <option value="">Todos los estados</option>
                    <option value="disponible" {{ request('disponibilidad') == 'disponible' ? 'selected' : '' }}>✅ Disponible</option>
                    <option value="ocupado" {{ request('disponibilidad') == 'ocupado' ? 'selected' : '' }}>🔴 Ocupado</option>
                    <option value="descanso" {{ request('disponibilidad') == 'descanso' ? 'selected' : '' }}>⏸ Descanso</option>
                </select>
                
                <button type="submit" class="btn-filter">
                    <i class="ti ti-filter"></i> Filtrar
                </button>
                
                @if(request('search') || request('tiene_cholets') || request('disponibilidad'))
                    <a href="{{ route('admin.guias.index') }}" class="btn-clear">
                        <i class="ti ti-x"></i> Limpiar filtros
                    </a>
                @endif
            </form>
        </div>
        
        <a href="{{ route('admin.guias.create') }}" class="btn-primary">
            <i class="ti ti-plus"></i> Nuevo Guía
        </a>
    </div>

    {{-- Tabla de guías --}}
    <div class="table-responsive">
        <table class="guias-table">
            <thead>
                <tr>
                    <th width="60">Foto</th>
                    <th>Nombre completo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Especialidad</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th width="100">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guias as $guia)
                <tr>
                    <td class="avatar-cell" data-label="Foto">
                        @if($guia->foto)
                            <img src="{{ $guia->foto }}" alt="{{ $guia->nombre_completo }}" class="avatar-img">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($guia->nombre, 0, 1)) }}{{ strtoupper(substr($guia->apellido, 0, 1)) }}
                            </div>
                        @endif
                     </td>
                     <td data-label="Nombre">
                        <div class="guia-name">{{ $guia->nombre_completo }}</div>
                        <div class="guia-role">Guía Turístico</div>
                      </td>
                     <td data-label="Email">{{ $guia->email }}</td>
                     <td data-label="Teléfono">{{ $guia->telefono ?? '—' }}</td>
                     <td data-label="Especialidad">
                        @if($guia->especialidad)
                            <span class="badge-especialidad">
                                <i class="ti ti-star"></i>
                                {{ $guia->especialidad }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                      </td>
                     <td data-label="Estado">
                        @php
                            $estadoClass = match($guia->disponibilidad) {
                                'disponible' => 'success',
                                'ocupado' => 'danger',
                                'descanso' => 'warning',
                                default => 'secondary'
                            };
                            $estadoIcon = match($guia->disponibilidad) {
                                'disponible' => 'circle-check',
                                'ocupado' => 'circle-x',
                                'descanso' => 'clock',
                                default => 'question'
                            };
                        @endphp
                        <span class="estado-badge estado-{{ $estadoClass }}">
                            <i class="ti ti-{{ $estadoIcon }}"></i> {{ ucfirst($guia->disponibilidad) }}
                        </span>
                      </td>
                     <td data-label="Registro">
                        <div class="fecha-registro">
                            {{ $guia->created_at->format('d/m/Y') }}
                        </div>
                      </td>
                    <td class="actions-cell" data-label="Acciones">
                        <div class="action-buttons">
                            <a href="{{ route('admin.guias.show', $guia) }}" class="btn-icon" title="Ver detalles">
                                <i class="ti ti-eye"></i>
                            </a>
                            <a href="{{ route('admin.guias.edit', $guia) }}" class="btn-icon" title="Editar">
                                <i class="ti ti-edit"></i>
                            </a>
                            <button onclick="eliminarGuia({{ $guia->id }}, '{{ addslashes($guia->nombre_completo) }}')"
                                    class="btn-icon delete" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                        
                        <form id="delete-form-{{ $guia->id }}" 
                              action="{{ route('admin.guias.destroy', $guia) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                      </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="empty-state">
                        <i class="ti ti-users-off"></i>
                        <p>No hay guías registrados</p>
                        <a href="{{ route('admin.guias.create') }}" class="btn-primary-sm">
                            <i class="ti ti-plus"></i> Crear primer guía
                        </a>
                      </td>
                  </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($guias->hasPages())
    <div class="pagination-container">
        {{ $guias->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<style>
.guias-container {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.page-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
    gap: 1rem;
}

.search-filters {
    flex: 1;
}

.search-form {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 250px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold-dim);
}

.search-box input {
    width: 100%;
    padding: 0.6rem 0.6rem 0.6rem 2.2rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    font-size: 0.9rem;
}

.search-box input:focus {
    outline: none;
    border-color: var(--gold-d);
}

.filter-select {
    padding: 0.6rem 1rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    cursor: pointer;
}

.btn-filter, .btn-clear {
    padding: 0.6rem 1.2rem;
    background: var(--gold-dim);
    border: none;
    border-radius: 6px;
    color: var(--text);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-clear {
    background: var(--deep);
    border: 1px solid var(--border);
}

.btn-filter:hover, .btn-clear:hover {
    opacity: 0.8;
    transform: translateY(-1px);
}

.btn-primary {
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    color: var(--text);
    padding: 0.6rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-primary:hover {
    opacity: 0.8;
    transform: translateY(-1px);
}

.btn-primary-sm {
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    color: var(--text);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
}

.table-responsive {
    overflow-x: auto;
}

.guias-table {
    width: 100%;
    border-collapse: collapse;
}

.guias-table thead th {
    text-align: left;
    padding: 1rem;
    background: rgba(201,168,76,0.05);
    color: var(--gold);
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.guias-table tbody td {
    padding: 1rem;
    border-top: 1px solid var(--border);
}

.avatar-cell {
    width: 60px;
}

.avatar-img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--gold-d);
}

.avatar-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: var(--black);
    font-size: 1rem;
}

.guia-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text);
}

.guia-role {
    font-size: 0.7rem;
    color: var(--gold-d);
    letter-spacing: 0.05em;
}

.badge-especialidad {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(201,168,76,0.1);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    color: var(--gold2);
}

.text-muted {
    color: var(--muted);
}

.estado-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
}

.estado-success {
    background: rgba(39, 174, 96, 0.2);
    color: #27ae60;
}

.estado-danger {
    background: rgba(192, 57, 43, 0.2);
    color: #e74c3c;
}

.estado-warning {
    background: rgba(241, 196, 15, 0.2);
    color: #f1c40f;
}

.fecha-registro {
    font-size: 0.85rem;
    color: var(--muted);
}

.actions-cell {
    white-space: nowrap;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--gold);
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}

.btn-icon:hover {
    background: var(--gold-dim);
    color: var(--text);
    border-color: var(--gold-d);
    transform: translateY(-2px);
}

.btn-icon.delete:hover {
    background: var(--danger);
    border-color: var(--danger);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem !important;
}

.empty-state i {
    font-size: 4rem;
    color: var(--muted);
    margin-bottom: 1rem;
    display: block;
}

.empty-state p {
    color: var(--muted);
    margin-bottom: 1rem;
}

.pagination-container {
    padding: 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: center;
}

.pagination-container nav {
    display: inline-block;
}

.pagination-container .pagination {
    display: flex;
    gap: 0.3rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.pagination-container .page-link {
    padding: 0.5rem 0.75rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 4px;
    color: var(--text);
    text-decoration: none;
    transition: all 0.2s;
}

.pagination-container .page-link:hover {
    background: var(--gold-dim);
    border-color: var(--gold-d);
}

.pagination-container .active .page-link {
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    border-color: var(--gold);
    color: var(--black);
}

@media (max-width: 768px) {
    .page-header-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-form {
        flex-direction: column;
    }
    
    .search-box {
        width: 100%;
    }
    
    .filter-select, .btn-filter, .btn-clear {
        width: 100%;
        justify-content: center;
    }
    
    .guias-table thead {
        display: none;
    }
    
    .guias-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
    }
    
    .guias-table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: none;
        padding: 0.75rem;
    }
    
    .guias-table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--gold);
        font-size: 0.75rem;
        min-width: 100px;
    }
    
    .avatar-cell {
        justify-content: flex-start;
    }
    
    .action-buttons {
        justify-content: flex-end;
    }
}
</style>

<script>
function eliminarGuia(id, nombre) {
    if (confirm(`¿Estás seguro de eliminar al guía "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
        document.getElementById(`delete-form-${id}`).submit();
    }
}
</script>
@endsection