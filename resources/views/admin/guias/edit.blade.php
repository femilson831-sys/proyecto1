@extends('admin.layouts.app')

@section('title', 'Editar Guía')
@section('page-title', 'Editar Guía')

@section('content')
<div class="gc-wrap">

    {{-- HEADER --}}
    <div class="gc-header">
        <div class="gc-header-left">
            <div class="gc-icon-badge">
                <i class="ti ti-edit"></i>
            </div>
            <div>
                <h1 class="gc-title">Editar Guía Turístico</h1>
                <p class="gc-subtitle">Modifica los datos del guía: {{ $guia->nombre_completo }}</p>
            </div>
        </div>
        <a href="{{ route('admin.guias.index') }}" class="gc-btn-back">
            <i class="ti ti-arrow-left"></i> Volver
        </a>
    </div>

    {{-- STEPS NAV --}}
    <div class="gc-steps">
        <div class="gc-step active" data-step="1">
            <span class="gc-step-num">1</span>
            <span class="gc-step-label">Datos personales</span>
        </div>
        <div class="gc-step-line"></div>
        <div class="gc-step" data-step="2">
            <span class="gc-step-num">2</span>
            <span class="gc-step-label">Perfil profesional</span>
        </div>
        <div class="gc-step-line"></div>
        <div class="gc-step" data-step="3">
            <span class="gc-step-num">3</span>
            <span class="gc-step-label">Foto</span>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.guias.update', $guia) }}" enctype="multipart/form-data" id="guiaForm">
        @csrf
        @method('PUT')

        {{-- PASO 1: DATOS PERSONALES --}}
        <div class="gc-section" id="step-1">
            <div class="gc-section-title">
                <i class="ti ti-id-badge"></i> Datos personales
            </div>

            <div class="gc-grid">
                <div class="gc-field">
                    <label>Nombre <span class="gc-req">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre', $guia->nombre) }}" placeholder="Ej: Juan" required>
                    @error('nombre') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                <div class="gc-field">
                    <label>Apellido <span class="gc-req">*</span></label>
                    <input type="text" name="apellido" value="{{ old('apellido', $guia->apellido) }}" placeholder="Ej: Pérez" required>
                    @error('apellido') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                <div class="gc-field">
                    <label>Email <span class="gc-req">*</span></label>
                    <div class="gc-input-icon">
                        <i class="ti ti-mail"></i>
                        <input type="email" name="email" value="{{ old('email', $guia->email) }}" placeholder="ejemplo@correo.com" required>
                    </div>
                    @error('email') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                <div class="gc-field">
                    <label>Teléfono</label>
                    <div class="gc-input-icon">
                        <i class="ti ti-phone"></i>
                        <input type="text" name="telefono" value="{{ old('telefono', $guia->telefono) }}" placeholder="+591 7XX XXX XX">
                    </div>
                    @error('telefono') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                <div class="gc-field">
                    <label>Nueva Contraseña <small class="gc-hint">(dejar en blanco para no cambiar)</small></label>
                    <div class="gc-input-pwd">
                        <input type="password" name="password" id="password" placeholder="Mínimo 6 caracteres">
                        <button type="button" onclick="togglePwd('password')" class="gc-toggle-pwd">
                            <i class="ti ti-eye" id="ico-password"></i>
                        </button>
                    </div>
                    @error('password') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                <div class="gc-field">
                    <label>Confirmar contraseña</label>
                    <div class="gc-input-pwd">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repite la contraseña">
                        <button type="button" onclick="togglePwd('password_confirmation')" class="gc-toggle-pwd">
                            <i class="ti ti-eye" id="ico-password_confirmation"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- PASO 2: PERFIL PROFESIONAL --}}
        <div class="gc-section" id="step-2">
            <div class="gc-section-title">
                <i class="ti ti-briefcase"></i> Perfil profesional
            </div>

            <div class="gc-grid">
                <div class="gc-field">
                    <label>Años de experiencia</label>
                    <div class="gc-input-icon">
                        <i class="ti ti-star"></i>
                        <input type="number" name="anos_experiencia" value="{{ old('anos_experiencia', $guia->anos_experiencia) }}" min="0" max="50">
                    </div>
                    @error('anos_experiencia') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                <div class="gc-field">
                    <label>Disponibilidad</label>
                    <select name="disponibilidad" class="gc-select">
                        <option value="disponible" {{ old('disponibilidad', $guia->disponibilidad) == 'disponible' ? 'selected' : '' }}>✅ Disponible</option>
                        <option value="ocupado"    {{ old('disponibilidad', $guia->disponibilidad) == 'ocupado'    ? 'selected' : '' }}>🔴 Ocupado</option>
                        <option value="descanso"   {{ old('disponibilidad', $guia->disponibilidad) == 'descanso'   ? 'selected' : '' }}>⏸ En descanso</option>
                    </select>
                    @error('disponibilidad') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                {{-- IDIOMAS --}}
                <div class="gc-field gc-full">
                    <label>Idiomas <small class="gc-hint">— Selecciona todos los que habla</small></label>

                    <div class="gc-idiomas-filter">
                        @foreach(['Todos','Europa','Asia','Medio Oriente','Nórdicos','Originarias'] as $cat)
                            <button type="button" class="gc-filter-btn {{ $cat === 'Todos' ? 'active' : '' }}" data-cat="{{ $cat }}">{{ $cat }}</button>
                        @endforeach
                    </div>

                    <div class="gc-idiomas-grid" id="idiomasGrid">
                        @php
                        $idiomas = [
                            ['val'=>'Español',        'flag'=>'🇪🇸','cat'=>'Europa'],
                            ['val'=>'Inglés',         'flag'=>'🇬🇧','cat'=>'Europa'],
                            ['val'=>'Portugués',      'flag'=>'🇵🇹','cat'=>'Europa'],
                            ['val'=>'Francés',        'flag'=>'🇫🇷','cat'=>'Europa'],
                            ['val'=>'Alemán',         'flag'=>'🇩🇪','cat'=>'Europa'],
                            ['val'=>'Italiano',       'flag'=>'🇮🇹','cat'=>'Europa'],
                            ['val'=>'Ruso',           'flag'=>'🇷🇺','cat'=>'Europa'],
                            ['val'=>'Neerlandés',     'flag'=>'🇳🇱','cat'=>'Europa'],
                            ['val'=>'Chino Mandarín', 'flag'=>'🇨🇳','cat'=>'Asia'],
                            ['val'=>'Japonés',        'flag'=>'🇯🇵','cat'=>'Asia'],
                            ['val'=>'Coreano',        'flag'=>'🇰🇷','cat'=>'Asia'],
                            ['val'=>'Hindi',          'flag'=>'🇮🇳','cat'=>'Asia'],
                            ['val'=>'Árabe',          'flag'=>'🇸🇦','cat'=>'Medio Oriente'],
                            ['val'=>'Hebreo',         'flag'=>'🇮🇱','cat'=>'Medio Oriente'],
                            ['val'=>'Sueco',          'flag'=>'🇸🇪','cat'=>'Nórdicos'],
                            ['val'=>'Danés',          'flag'=>'🇩🇰','cat'=>'Nórdicos'],
                            ['val'=>'Noruego',        'flag'=>'🇳🇴','cat'=>'Nórdicos'],
                            ['val'=>'Finlandés',      'flag'=>'🇫🇮','cat'=>'Nórdicos'],
                            ['val'=>'Quechua',        'flag'=>'🏔️','cat'=>'Originarias'],
                            ['val'=>'Aymara',         'flag'=>'🌄','cat'=>'Originarias'],
                            ['val'=>'Guaraní',        'flag'=>'🌿','cat'=>'Originarias'],
                            ['val'=>'Náhuatl',        'flag'=>'🦅','cat'=>'Originarias'],
                            ['val'=>'Mapudungun',     'flag'=>'🗿','cat'=>'Originarias'],
                        ];
                        $idiomasGuardados = is_array($guia->idiomas) ? $guia->idiomas : [];
                        @endphp

                        @foreach($idiomas as $id)
                        <label class="gc-idioma-card" data-cat="{{ $id['cat'] }}">
                            <input type="checkbox" name="idiomas[]" value="{{ $id['val'] }}"
                                   {{ in_array($id['val'], old('idiomas', $idiomasGuardados)) ? 'checked' : '' }}>
                            <div class="gc-idioma-inner">
                                <span class="gc-idioma-flag">{{ $id['flag'] }}</span>
                                <span class="gc-idioma-name">{{ $id['val'] }}</span>
                                <i class="ti ti-check gc-idioma-check"></i>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    <div class="gc-idiomas-selected" id="idiomasResumen">
                        <span class="gc-resumen-label">Seleccionados:</span>
                        <span id="idiomasCount">0</span>
                    </div>

                    @error('idiomas') <span class="gc-error">{{ $message }}</span> @enderror
                </div>

                {{-- BIOGRAFIA --}}
                <div class="gc-field gc-full">
                    <label>Biografía</label>
                    <textarea name="biografia" rows="4" class="gc-textarea"
                              placeholder="Describe la experiencia, habilidades y características del guía...">{{ old('biografia', $guia->biografia) }}</textarea>
                    <div class="gc-char-counter">
                        <span id="bioCount">0</span> / 1000 caracteres
                    </div>
                    @error('biografia') <span class="gc-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- PASO 3: FOTO --}}
        <div class="gc-section" id="step-3">
            <div class="gc-section-title">
                <i class="ti ti-camera"></i> Foto de perfil
            </div>

            <div class="gc-photo-wrap">
                {{-- Avatar preview --}}
                <div class="gc-avatar-preview" id="avatarPreview">
                    <div class="gc-avatar-placeholder" id="avatarPlaceholder" style="{{ $guia->foto ? 'display:none' : '' }}">
                        <i class="ti ti-user"></i>
                    </div>
                    <img id="previewImg" src="{{ $guia->foto ? asset($guia->foto) : '#' }}" alt="Vista previa" style="{{ $guia->foto ? '' : 'display:none' }}">
                    <button type="button" class="gc-avatar-remove" id="btnRemove" onclick="eliminarFoto()" style="{{ $guia->foto ? '' : 'display:none' }}">
                        <i class="ti ti-x"></i>
                    </button>
                </div>

                {{-- Opciones --}}
                <div class="gc-photo-options">
                    <button type="button" class="gc-photo-btn" id="btnUpload" onclick="setPhotoMode('upload')">
                        <i class="ti ti-upload"></i>
                        <span>Subir archivo</span>
                        <small>JPG, PNG · máx. 2MB</small>
                    </button>
                    <button type="button" class="gc-photo-btn" id="btnCamera" onclick="setPhotoMode('camera')">
                        <i class="ti ti-camera"></i>
                        <span>Cámara</span>
                        <small>Captura en vivo</small>
                    </button>
                    @if($guia->foto)
                    <button type="button" class="gc-photo-btn" onclick="eliminarFotoPermanente()">
                        <i class="ti ti-trash"></i>
                        <span>Eliminar foto actual</span>
                        <small>Quitar imagen</small>
                    </button>
                    @endif
                </div>

                {{-- Upload panel --}}
                <div class="gc-upload-panel" id="uploadPanel" style="display:none">
                    <input type="file" name="foto" id="fotoInput"
                           accept="image/jpeg,image/png,image/jpg"
                           onchange="previewImage(this)" style="display:none">
                    <label for="fotoInput" class="gc-upload-drop" id="uploadDrop">
                        <i class="ti ti-cloud-upload"></i>
                        <span>Arrastra una imagen o haz clic</span>
                        <small>JPG · PNG · máx. 2 MB</small>
                    </label>
                </div>

                {{-- Camera panel --}}
                <div class="gc-camera-panel" id="cameraPanel" style="display:none">
                    <video id="video" autoplay playsinline></video>
                    <canvas id="canvas" style="display:none"></canvas>
                    <div class="gc-camera-actions">
                        <button type="button" class="gc-btn-capture" id="btnCapture" onclick="capturarFoto()">
                            <i class="ti ti-camera"></i> Capturar
                        </button>
                        <button type="button" class="gc-btn-retake" id="btnRetake" onclick="reiniciarCamara()" style="display:none">
                            <i class="ti ti-refresh"></i> Reintentar
                        </button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="foto_base64" id="fotoBase64">
            <input type="hidden" name="eliminar_foto" id="eliminarFoto" value="0">
            @error('foto') <span class="gc-error">{{ $message }}</span> @enderror
        </div>

        {{-- ACTIONS --}}
        <div class="gc-actions">
            <a href="{{ route('admin.guias.index') }}" class="gc-btn-cancel">
                <i class="ti ti-x"></i> Cancelar
            </a>
            <button type="submit" class="gc-btn-save">
                <i class="ti ti-device-floppy"></i> Actualizar Guía
            </button>
        </div>

    </form>
</div>

{{-- ══════════════════════════════════════════ STYLES ══════════════════════════════════════════ --}}
<style>
/* ── Wrap ── */
.gc-wrap {
    max-width: 900px;
    margin: 0 auto;
}

/* ── Header ── */
.gc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.gc-header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.gc-icon-badge {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    color: var(--black);
    flex-shrink: 0;
}

.gc-title {
    font-size: 1.25rem;
    color: var(--gold);
    margin: 0 0 0.2rem;
}

.gc-subtitle {
    font-size: 0.8rem;
    color: var(--muted);
    margin: 0;
}

.gc-btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 6px;
    color: var(--text);
    text-decoration: none;
    font-size: 0.85rem;
    transition: border-color 0.2s, background 0.2s;
}

.gc-btn-back:hover {
    border-color: var(--gold-d);
    background: rgba(201,168,76,0.06);
}

/* ── Steps ── */
.gc-steps {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 1.75rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1rem 1.5rem;
}

.gc-step {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    flex-shrink: 0;
}

.gc-step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--border);
    border: 2px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--muted);
    transition: all 0.3s;
}

.gc-step-label {
    font-size: 0.8rem;
    color: var(--muted);
    font-weight: 500;
    transition: color 0.3s;
}

.gc-step.active .gc-step-num {
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    border-color: var(--gold);
    color: var(--black);
}

.gc-step.active .gc-step-label {
    color: var(--gold);
}

.gc-step-line {
    flex: 1;
    height: 1px;
    background: var(--border);
    margin: 0 1rem;
}

/* ── Sections ── */
.gc-section {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    transition: border-color 0.2s;
}

.gc-section:hover {
    border-color: rgba(201,168,76,0.25);
}

.gc-section-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--gold);
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border);
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

/* ── Grid ── */
.gc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.25rem;
}

.gc-field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.gc-field.gc-full {
    grid-column: 1 / -1;
}

.gc-field label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--gold-dim);
    letter-spacing: 0.02em;
}

.gc-req {
    color: var(--danger);
}

.gc-hint {
    font-size: 0.7rem;
    color: var(--muted);
    font-weight: 400;
}

/* ── Inputs ── */
.gc-field input:not([type="checkbox"]),
.gc-select,
.gc-textarea {
    padding: 0.65rem 1rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 7px;
    color: var(--text);
    font-size: 0.88rem;
    width: 100%;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
}

.gc-field input:not([type="checkbox"]):focus,
.gc-select:focus,
.gc-textarea:focus {
    outline: none;
    border-color: var(--gold-d);
    box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
}

.gc-textarea {
    resize: vertical;
    min-height: 100px;
}

.gc-char-counter {
    text-align: right;
    font-size: 0.7rem;
    color: var(--muted);
    margin-top: 0.25rem;
}

/* Input with icon */
.gc-input-icon {
    position: relative;
}

.gc-input-icon i {
    position: absolute;
    left: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gold-dim);
    font-size: 0.9rem;
    pointer-events: none;
}

.gc-input-icon input {
    padding-left: 2.4rem !important;
}

/* Password input */
.gc-input-pwd {
    position: relative;
}

.gc-input-pwd input {
    padding-right: 2.8rem;
}

.gc-toggle-pwd {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--gold-dim);
    cursor: pointer;
    padding: 0.25rem;
    font-size: 0.95rem;
    transition: color 0.2s;
}

.gc-toggle-pwd:hover {
    color: var(--gold);
}

/* ── Idiomas ── */
.gc-idiomas-filter {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 0.75rem;
}

.gc-filter-btn {
    padding: 0.3rem 0.85rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 20px;
    color: var(--muted);
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
}

.gc-filter-btn.active,
.gc-filter-btn:hover {
    border-color: var(--gold-d);
    color: var(--gold);
    background: rgba(201,168,76,0.07);
}

.gc-idiomas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.6rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 1rem;
    max-height: 280px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--gold-dim) var(--black);
}

.gc-idiomas-grid::-webkit-scrollbar { width: 5px; }
.gc-idiomas-grid::-webkit-scrollbar-track { background: var(--black); }
.gc-idiomas-grid::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 3px; }

.gc-idioma-card {
    cursor: pointer;
    transition: transform 0.15s;
}

.gc-idioma-card:hover {
    transform: translateY(-1px);
}

.gc-idioma-card input {
    display: none;
}

.gc-idioma-inner {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: rgba(0,0,0,0.3);
    border: 1px solid var(--border);
    border-radius: 7px;
    transition: all 0.18s;
    position: relative;
}

.gc-idioma-check {
    display: none;
    font-size: 0.7rem;
    color: var(--black);
    margin-left: auto;
}

.gc-idioma-card input:checked + .gc-idioma-inner {
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    border-color: var(--gold);
}

.gc-idioma-card input:checked + .gc-idioma-inner .gc-idioma-name {
    color: var(--black);
    font-weight: 600;
}

.gc-idioma-card input:checked + .gc-idioma-inner .gc-idioma-check {
    display: block;
}

.gc-idioma-card:hover .gc-idioma-inner {
    border-color: var(--gold-d);
    background: rgba(201,168,76,0.08);
}

.gc-idioma-flag {
    font-size: 1.1rem;
    flex-shrink: 0;
}

.gc-idioma-name {
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--text);
}

.gc-idiomas-selected {
    margin-top: 0.6rem;
    font-size: 0.75rem;
    color: var(--muted);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.gc-resumen-label {
    color: var(--gold-dim);
    font-weight: 600;
}

#idiomasCount {
    color: var(--gold);
    font-weight: 700;
}

/* ── Photo section ── */
.gc-photo-wrap {
    display: grid;
    grid-template-columns: 130px 1fr;
    grid-template-rows: auto auto;
    gap: 1.25rem;
    align-items: start;
}

.gc-avatar-preview {
    grid-row: 1 / 3;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid var(--gold-d);
    background: var(--deep);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.gc-avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--border);
    width: 100%;
    height: 100%;
}

.gc-avatar-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

.gc-avatar-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--danger);
    border: none;
    color: white;
    font-size: 0.7rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.2s;
}

.gc-avatar-remove:hover {
    opacity: 0.8;
}

.gc-photo-options {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.gc-photo-btn {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.15rem;
    padding: 0.75rem 1.1rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 9px;
    color: var(--text);
    cursor: pointer;
    transition: all 0.2s;
    min-width: 130px;
    text-align: left;
}

.gc-photo-btn i {
    font-size: 1.2rem;
    color: var(--gold-dim);
    margin-bottom: 0.1rem;
}

.gc-photo-btn span {
    font-size: 0.82rem;
    font-weight: 600;
}

.gc-photo-btn small {
    font-size: 0.7rem;
    color: var(--muted);
}

.gc-photo-btn.active,
.gc-photo-btn:hover {
    border-color: var(--gold-d);
    background: rgba(201,168,76,0.06);
}

.gc-photo-btn.active i {
    color: var(--gold);
}

/* Upload drop */
.gc-upload-panel {
    grid-column: 2;
}

.gc-upload-drop {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 1.75rem 1rem;
    border: 2px dashed var(--border);
    border-radius: 9px;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    text-align: center;
    background: var(--deep);
}

.gc-upload-drop:hover {
    border-color: var(--gold-d);
    background: rgba(201,168,76,0.03);
}

.gc-upload-drop i {
    font-size: 1.8rem;
    color: var(--gold-dim);
}

.gc-upload-drop span {
    font-size: 0.85rem;
    color: var(--text);
}

.gc-upload-drop small {
    font-size: 0.72rem;
    color: var(--muted);
}

/* Camera panel */
.gc-camera-panel {
    grid-column: 2;
    text-align: center;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 1rem;
}

.gc-camera-panel video {
    width: 100%;
    max-width: 360px;
    border-radius: 7px;
    border: 1px solid var(--border);
}

.gc-camera-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    margin-top: 0.75rem;
    flex-wrap: wrap;
}

.gc-btn-capture,
.gc-btn-retake {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1.1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s;
    border: none;
}

.gc-btn-capture {
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    color: var(--black);
    font-weight: 600;
}

.gc-btn-retake {
    background: var(--deep);
    border: 1px solid var(--border);
    color: var(--text);
}

.gc-btn-capture:hover,
.gc-btn-retake:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}

/* ── Error ── */
.gc-error {
    font-size: 0.72rem;
    color: var(--danger);
}

/* ── Actions ── */
.gc-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding-top: 1rem;
}

.gc-btn-save {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.75rem;
    background: linear-gradient(135deg, var(--gold-d), var(--gold-dim));
    border: none;
    border-radius: 7px;
    color: var(--black);
    font-weight: 700;
    font-size: 0.88rem;
    cursor: pointer;
    transition: all 0.2s;
    letter-spacing: 0.02em;
}

.gc-btn-cancel {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.7rem 1.35rem;
    background: var(--deep);
    border: 1px solid var(--border);
    border-radius: 7px;
    color: var(--muted);
    font-size: 0.88rem;
    text-decoration: none;
    transition: all 0.2s;
}

.gc-btn-save:hover {
    opacity: 0.88;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,168,76,0.25);
}

.gc-btn-cancel:hover {
    border-color: var(--gold-d);
    color: var(--text);
}

/* ── Responsive ── */
@media (max-width: 640px) {
    .gc-steps {
        padding: 0.75rem 1rem;
    }
    .gc-step-label {
        display: none;
    }
    .gc-photo-wrap {
        grid-template-columns: 1fr;
    }
    .gc-avatar-preview {
        grid-row: auto;
        margin: 0 auto;
    }
    .gc-upload-panel,
    .gc-camera-panel {
        grid-column: 1;
    }
}
</style>

{{-- ══════════════════════════════════════════ SCRIPTS ══════════════════════════════════════════ --}}
<script>
/* ── Password toggle ── */
function togglePwd(id) {
    const f = document.getElementById(id);
    const ico = document.getElementById('ico-' + id);
    if (f && ico) {
        if (f.type === 'password') {
            f.type = 'text';
            ico.className = 'ti ti-eye-off';
        } else {
            f.type = 'password';
            ico.className = 'ti ti-eye';
        }
    }
}

/* ── Idiomas: filter tabs ── */
document.querySelectorAll('.gc-filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.gc-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.cat;
        document.querySelectorAll('.gc-idioma-card').forEach(card => {
            card.style.display = (cat === 'Todos' || card.dataset.cat === cat) ? '' : 'none';
        });
    });
});

/* ── Idiomas: count selected ── */
function updateIdiomasCount() {
    const n = document.querySelectorAll('.gc-idiomas-grid input:checked').length;
    const counter = document.getElementById('idiomasCount');
    if (counter) counter.textContent = n;
}
document.querySelectorAll('.gc-idiomas-grid input').forEach(cb => {
    cb.addEventListener('change', updateIdiomasCount);
});
updateIdiomasCount();

/* ── Biografía char counter ── */
const bioTA = document.querySelector('textarea[name="biografia"]');
const bioCount = document.getElementById('bioCount');
if (bioTA && bioCount) {
    function updateBioCount() { bioCount.textContent = bioTA.value.length; }
    bioTA.addEventListener('input', updateBioCount);
    updateBioCount();
}

/* ── Steps indicator (scroll-based) ── */
function updateSteps() {
    const sections = [
        document.getElementById('step-1'),
        document.getElementById('step-2'),
        document.getElementById('step-3'),
    ];
    const steps = document.querySelectorAll('.gc-step');
    const scrollY = window.scrollY + window.innerHeight * 0.4;
    let active = 0;
    sections.forEach((s, i) => {
        if (s && s.getBoundingClientRect().top + window.scrollY <= scrollY) active = i;
    });
    steps.forEach((s, i) => {
        s.classList.toggle('active', i === active);
    });
}
window.addEventListener('scroll', updateSteps, { passive: true });

/* ── Photo mode ── */
let stream = null;

function setPhotoMode(mode) {
    const uploadBtn = document.getElementById('btnUpload');
    const cameraBtn = document.getElementById('btnCamera');
    const uploadPanel = document.getElementById('uploadPanel');
    const cameraPanel = document.getElementById('cameraPanel');
    
    if (uploadBtn) uploadBtn.classList.toggle('active', mode === 'upload');
    if (cameraBtn) cameraBtn.classList.toggle('active', mode === 'camera');
    
    if (uploadPanel) uploadPanel.style.display = mode === 'upload' ? '' : 'none';
    if (cameraPanel) cameraPanel.style.display = mode === 'camera' ? '' : 'none';

    if (mode !== 'camera' && stream) {
        stream.getTracks().forEach(t => t.stop());
        stream = null;
    }
    if (mode === 'camera') initCamera();
}

function initCamera() {
    if (!navigator.mediaDevices?.getUserMedia) {
        alert('Tu navegador no soporta acceso a la cámara.');
        return;
    }
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(s => {
            stream = s;
            const v = document.getElementById('video');
            if (v) {
                v.srcObject = s;
                v.style.display = '';
                v.play();
            }
            const captureBtn = document.getElementById('btnCapture');
            const retakeBtn = document.getElementById('btnRetake');
            if (captureBtn) captureBtn.style.display = 'inline-flex';
            if (retakeBtn) retakeBtn.style.display = 'none';
        })
        .catch(() => alert('No se pudo acceder a la cámara. Verifica los permisos.'));
}

function capturarFoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    if (!video || !canvas) return;
    
    const ctx = canvas.getContext('2d');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    const data = canvas.toDataURL('image/jpeg', 0.85);
    document.getElementById('fotoBase64').value = data;
    setPreview(data);
    if (stream) stream.getTracks().forEach(t => t.stop());
    video.style.display = 'none';
    const captureBtn = document.getElementById('btnCapture');
    const retakeBtn = document.getElementById('btnRetake');
    if (captureBtn) captureBtn.style.display = 'none';
    if (retakeBtn) retakeBtn.style.display = 'inline-flex';
}

function reiniciarCamara() {
    clearPreview();
    document.getElementById('fotoBase64').value = '';
    initCamera();
}

function previewImage(input) {
    if (!input.files?.[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('fotoBase64').value = '';
        setPreview(e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
}

function setPreview(src) {
    const img = document.getElementById('previewImg');
    const placeholder = document.getElementById('avatarPlaceholder');
    const removeBtn = document.getElementById('btnRemove');
    if (img) {
        img.src = src;
        img.style.display = '';
    }
    if (placeholder) placeholder.style.display = 'none';
    if (removeBtn) removeBtn.style.display = '';
}

function clearPreview() {
    const img = document.getElementById('previewImg');
    const placeholder = document.getElementById('avatarPlaceholder');
    const removeBtn = document.getElementById('btnRemove');
    if (img) {
        img.src = '#';
        img.style.display = 'none';
    }
    if (placeholder) placeholder.style.display = '';
    if (removeBtn) removeBtn.style.display = 'none';
}

function eliminarFoto() {
    clearPreview();
    document.getElementById('fotoBase64').value = '';
    document.getElementById('fotoInput').value = '';
    document.getElementById('eliminarFoto').value = '0';
}

function eliminarFotoPermanente() {
    if (confirm('¿Eliminar la foto actual del guía?')) {
        clearPreview();
        document.getElementById('fotoBase64').value = '';
        document.getElementById('fotoInput').value = '';
        document.getElementById('eliminarFoto').value = '1';
    }
}

/* ── Drag & drop upload ── */
const uploadDrop = document.getElementById('uploadDrop');
if (uploadDrop) {
    uploadDrop.addEventListener('dragover', e => {
        e.preventDefault();
        uploadDrop.style.borderColor = 'var(--gold)';
    });
    uploadDrop.addEventListener('dragleave', () => {
        uploadDrop.style.borderColor = '';
    });
    uploadDrop.addEventListener('drop', e => {
        e.preventDefault();
        uploadDrop.style.borderColor = '';
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const input = document.getElementById('fotoInput');
            if (input) {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                previewImage(input);
            }
        }
    });
}

/* ── Inicializar contador de idiomas al cargar ── */
document.addEventListener('DOMContentLoaded', function() {
    updateIdiomasCount();
});
</script>
@endsection