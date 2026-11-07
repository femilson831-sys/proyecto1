@extends('admin.layouts.app')

@section('title', 'Detalles del Guía')
@section('page-title', 'Detalles del Guía')

@section('content')
<div class="guia-detail-container">
    <div class="detail-header">
        <div class="detail-actions">
            <a href="{{ route('admin.guias.index') }}" class="btn-back">
                <i class="ti ti-arrow-left"></i> Volver a la lista
            </a>
            <div class="action-buttons">
                <a href="{{ route('admin.guias.edit', $guia) }}" class="btn-edit">
                    <i class="ti ti-edit"></i> Editar
                </a>
                <button onclick="eliminarGuia({{ $guia->id }}, '{{ addslashes($guia->nombre_completo) }}')" class="btn-delete">
                    <i class="ti ti-trash"></i> Eliminar
                </button>
            </div>
        </div>
        <form id="delete-form-{{ $guia->id }}" action="{{ route('admin.guias.destroy', $guia) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="detail-grid">
        {{-- Columna izquierda: Foto e información básica --}}
        <div class="detail-left">
            <div class="photo-card">
                @if($guia->foto)
                    <img src="{{ $guia->foto }}" alt="{{ $guia->nombre_completo }}" class="guia-photo">
                @else
                    <div class="photo-placeholder">
                        <i class="ti ti-user-circle"></i>
                        <span>{{ strtoupper(substr($guia->nombre, 0, 1)) }}{{ strtoupper(substr($guia->apellido, 0, 1)) }}</span>
                    </div>
                @endif
            </div>

            <div class="info-card">
                <h3 class="card-title">
                    <i class="ti ti-info-circle"></i> Información de Contacto
                </h3>
                <div class="info-item">
                    <div class="info-label">
                        <i class="ti ti-mail"></i> Email:
                    </div>
                    <div class="info-value">{{ $guia->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="ti ti-phone"></i> Teléfono:
                    </div>
                    <div class="info-value">{{ $guia->telefono ?? 'No registrado' }}</div>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Información detallada --}}
        <div class="detail-right">
            <div class="info-card">
                <h3 class="card-title">
                    <i class="ti ti-user"></i> Información Personal
                </h3>
                <div class="info-item">
                    <div class="info-label">Nombre completo:</div>
                    <div class="info-value">{{ $guia->nombre_completo }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Especialidad:</div>
                    <div class="info-value">{{ $guia->especialidad ?? 'General' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Años de experiencia:</div>
                    <div class="info-value">{{ $guia->anos_experiencia ?? 0 }} años</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Idiomas:</div>
                    <div class="info-value">
                        @if($guia->idiomas)
                            @php
                                $idiomas = is_array($guia->idiomas) ? $guia->idiomas : json_decode($guia->idiomas, true);
                            @endphp
                            @if(is_array($idiomas))
                                @foreach($idiomas as $idioma)
                                    <span class="badge-idioma">{{ $idioma }}</span>
                                @endforeach
                            @else
                                {{ $guia->idiomas }}
                            @endif
                        @else
                            No especificado
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-card">
                <h3 class="card-title">
                    <i class="ti ti-briefcase"></i> Estado Laboral
                </h3>
                <div class="info-item">
                    <div class="info-label">Disponibilidad:</div>
                    <div class="info-value">
                        @php
                            $estadoClass = match($guia->disponibilidad) {
                                'disponible' => 'success',
                                'ocupado' => 'danger',
                                'descanso' => 'warning',
                                default => 'secondary'
                            };
                        @endphp
                        <span class="estado-badge estado-{{ $estadoClass }}">
                            {{ ucfirst($guia->disponibilidad) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Estado de cuenta:</div>
                    <div class="info-value">
                        <span class="estado-badge estado-{{ $guia->estado == 'activo' ? 'success' : 'danger' }}">
                            {{ ucfirst($guia->estado ?? 'activo') }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de registro:</div>
                    <div class="info-value">{{ $guia->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            @if($guia->cholets->count() > 0)
            <div class="info-card">
                <h3 class="card-title">
                    <i class="ti ti-building-estate"></i> Cholets Asignados
                </h3>
                <div class="cholets-list">
                    @foreach($guia->cholets as $cholet)
                    <div class="cholet-item">
                        <div class="cholet-info">
                            <i class="ti ti-building"></i>
                            <span>{{ $cholet->nombre }}</span>
                        </div>
                        <div class="cholet-location">
                            <i class="ti ti-map-pin"></i>
                            {{ $cholet->ubicacion }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($guia->biografia)
            <div class="info-card">
                <h3 class="card-title">
                    <i class="ti ti-notes"></i> Biografía
                </h3>
                <div class="biografia">
                    {{ $guia->biografia }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.guia-detail-container {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.detail-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border);
    background: rgba(0,0,0,0.2);
}

.detail-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    text-decoration: none;
    transition: all 0.2s;
}

.btn-back:hover {
    border-color: var(--gold-d);
    transform: translateX(-2px);
}

.action-buttons {
    display: flex;
    gap: 0.8rem;
}

.btn-edit, .btn-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    font-size: 0.85rem;
}

.btn-edit {
    background: var(--gold-dim);
    color: var(--text);
}

.btn-edit:hover {
    background: var(--gold-d);
    transform: translateY(-1px);
}

.btn-delete {
    background: rgba(192, 57, 43, 0.2);
    color: #e74c3c;
}

.btn-delete:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-1px);
}

.detail-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.5rem;
    padding: 1.5rem;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}

.detail-left {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.photo-card {
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.guia-photo {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--gold-d);
}

.photo-placeholder {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    background: linear-gradient(135deg, var(--gold-dim), var(--gold-d));
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.photo-placeholder i {
    font-size: 3rem;
    color: var(--black);
}

.photo-placeholder span {
    font-size: 1.2rem;
    font-weight: bold;
    color: var(--black);
}

.info-card {
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.2rem;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gold);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0;
    border-bottom: 1px solid var(--border2);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: var(--muted);
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    color: var(--text);
    font-weight: 500;
    font-size: 0.85rem;
}

.estado-badge {
    padding: 0.2rem 0.6rem;
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

.badge-idioma {
    display: inline-block;
    background: rgba(201,168,76,0.15);
    padding: 0.2rem 0.6rem;
    border-radius: 15px;
    font-size: 0.7rem;
    color: var(--gold2);
    margin: 0.2rem;
}

.cholets-list {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.cholet-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem;
    background: rgba(0,0,0,0.2);
    border-radius: 8px;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.cholet-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gold2);
}

.cholet-location {
    font-size: 0.7rem;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.biografia {
    line-height: 1.6;
    color: var(--text);
    font-size: 0.85rem;
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