@extends('admin.layouts.app')
@section('title', 'Editar Experiencia')
@section('page-title', 'Editar Experiencia')

@section('content')

<style>
    .form-wrap { max-width: 800px; margin: 0 auto; }
    .panel {
        background: #111111;
        border: 1px solid #221a08;
        border-radius: 4px;
        position: relative;
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
    .panel-body { padding: 1.8rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label {
        display: block;
        font-size: .7rem;
        color: #7a5f28;
        margin-bottom: .4rem;
        text-transform: uppercase;
        letter-spacing: .08em;
    }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        background: #1a1a1a;
        border: 1px solid #2a2010;
        border-radius: 4px;
        padding: .75rem 1rem;
        color: #f0ead8;
        font-size: .9rem;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: #c9a84c;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }
    .btn-submit {
        background: linear-gradient(135deg, #b8962a, #e0c45a);
        color: #080808;
        border: none;
        padding: .8rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .btn-cancel {
        background: transparent;
        border: 1px solid #2a2010;
        color: #6b5e3e;
        padding: .8rem 1.5rem;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        text-decoration: none;
    }
    .btn-cancel:hover { border-color: #7a5f28; color: #f0ead8; }
    .help-text { font-size: .7rem; color: #6b5e3e; margin-top: .3rem; }
    textarea { resize: vertical; min-height: 100px; }
</style>

<div class="form-wrap">
    <div class="panel">
        <div class="panel-body">
            <form action="{{ route('admin.experiencias.update', $experiencia->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>📛 NOMBRE DE LA EXPERIENCIA</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $experiencia->nombre) }}" required>
                </div>

                <div class="form-group">
                    <label>📝 DESCRIPCIÓN</label>
                    <textarea name="descripcion">{{ old('descripcion', $experiencia->descripcion) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>💰 PRECIO (USD)</label>
                        <input type="number" name="precio" step="0.01" value="{{ old('precio', $experiencia->precio) }}" required>
                    </div>
                    <div class="form-group">
                        <label>💰 PRECIO (Bs)</label>
                        <input type="number" name="precio_bs" step="0.01" value="{{ old('precio_bs', $experiencia->precio_bs) }}">
                        <div class="help-text">Opcional: si está vacío se calcula automáticamente</div>
                    </div>
                </div>

                <div class="form-row-3">
                    <div class="form-group">
                        <label>🎨 ICONO (FontAwesome)</label>
                        <input type="text" name="icono" value="{{ old('icono', $experiencia->icono) }}" placeholder="fas fa-star">
                    </div>
                    <div class="form-group">
                        <label>🎨 COLOR</label>
                        <input type="color" name="color" value="{{ old('color', $experiencia->color ?? '#c9a84c') }}">
                    </div>
                    <div class="form-group">
                        <label>⏰ DURACIÓN (horas)</label>
                        <input type="number" name="duracion_horas" value="{{ old('duracion_horas', $experiencia->duracion_horas) }}" min="1" max="24" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>📋 QUÉ INCLUYE</label>
                    <textarea name="incluye">{{ old('incluye', $experiencia->incluye) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>🔢 ORDEN</label>
                        <input type="number" name="orden" value="{{ old('orden', $experiencia->orden) }}" min="0">
                    </div>
                    <div class="form-group">
                        <label>✅ ACTIVO</label>
                        <select name="activo">
                            <option value="1" {{ $experiencia->activo ? 'selected' : '' }}>Sí, activo</option>
                            <option value="0" {{ !$experiencia->activo ? 'selected' : '' }}>No, inactivo</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn-submit">
                        <i class="ti ti-device-floppy"></i> Actualizar Experiencia
                    </button>
                    <a href="{{ route('admin.experiencias.index') }}" class="btn-cancel">
                        <i class="ti ti-arrow-left"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection