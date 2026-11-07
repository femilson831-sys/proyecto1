@extends('admin.layouts.app')
@section('title', 'Editar Cholet')
@section('page-title', 'Editar Cholet')

@section('content')

<style>
    .form-wrap {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        animation: fadeUp .5s cubic-bezier(.22,1,.36,1) both;
    }

    @keyframes fadeUp {
        from { opacity:0; transform:translateY(16px); }
        to   { opacity:1; transform:none; }
    }

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

    .panel-head {
        padding: 1.3rem 1.6rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,.04);
        display: flex;
        align-items: center;
        gap: .7rem;
    }

    .panel-head-icon {
        width: 34px; height: 34px;
        border-radius: 3px;
        background: rgba(201,168,76,.1);
        border: 1px solid rgba(201,168,76,.2);
        display: flex; align-items: center; justify-content: center;
        color: #c9a84c;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .panel-title {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 600;
        color: #f0ead8;
        line-height: 1.1;
    }

    .panel-sub {
        font-size: .68rem;
        color: #6b5e3e;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-top: .1rem;
    }

    .panel-body { padding: 1.4rem 1.6rem 1.6rem; }

    .field { margin-bottom: 1.2rem; }
    .field:last-of-type { margin-bottom: 0; }

    .field label {
        display: flex;
        align-items: center;
        gap: .4rem;
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .13em;
        text-transform: uppercase;
        color: #7a5f28;
        margin-bottom: .45rem;
    }

    .field label i { font-size: .85rem; }

    .field input[type="text"],
    .field input[type="number"],
    .field select,
    .field textarea {
        width: 100%;
        background: rgba(255,255,255,.03);
        border: 1px solid #2a2010;
        border-radius: 3px;
        padding: .78rem 1rem;
        color: #f0ead8;
        font-family: 'Jost', sans-serif;
        font-size: .88rem;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        border-color: #7a5f28;
        background: rgba(201,168,76,.04);
        box-shadow: 0 0 0 3px rgba(201,168,76,.1);
    }

    .field select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a5f28' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }

    .field textarea {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
    }

    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    @media (max-width: 680px) {
        .field-row { grid-template-columns: 1fr; }
    }

    .field-error {
        font-size: .72rem;
        color: #f87171;
        margin-top: .3rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    .char-counter {
        font-size: .68rem;
        color: #4a3a1a;
        text-align: right;
        margin-top: .3rem;
    }
    .char-counter.warn { color: #d97706; }
    .char-counter.over { color: #f87171; }

    .img-upload {
        border: 1px dashed #2a2010;
        border-radius: 3px;
        padding: 2.2rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative;
        background: rgba(255,255,255,.02);
    }

    .img-upload:hover {
        border-color: #7a5f28;
        background: rgba(201,168,76,.03);
    }

    .img-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .img-upload-icon {
        font-size: 2.2rem;
        color: #3d2e10;
        margin-bottom: .7rem;
        transition: color .2s;
    }

    .img-upload:hover .img-upload-icon { color: #c9a84c; }

    .img-upload-text {
        font-size: .8rem;
        color: #6b5e3e;
        line-height: 1.5;
    }

    .img-upload-text strong {
        display: block;
        color: #9a8060;
        font-size: .85rem;
        margin-bottom: .25rem;
    }

    .current-image, .current-gallery-item {
        margin-bottom: 1rem;
        padding: 1rem;
        background: rgba(255,255,255,.02);
        border-radius: 3px;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .current-image img, .current-gallery-item img {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 3px;
        border: 1px solid #2a2010;
    }

    .btn-remove-image, .btn-remove-gallery-item {
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.2);
        color: #f87171;
        padding: .4rem .8rem;
        border-radius: 3px;
        font-size: .7rem;
        cursor: pointer;
        transition: all .2s;
    }

    .btn-remove-image:hover, .btn-remove-gallery-item:hover { background: rgba(239,68,68,.2); }

    .img-preview-wrap {
        margin-top: 1rem;
        display: none;
        position: relative;
    }

    .img-preview-wrap img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 3px;
        border: 1px solid #2a2010;
    }

    .img-preview-clear {
        position: absolute;
        top: .5rem; right: .5rem;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: rgba(0,0,0,.75);
        border: 1px solid rgba(255,255,255,.12);
        color: #f0ead8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        cursor: pointer;
        transition: background .2s;
    }

    .img-preview-clear:hover { background: rgba(192,57,43,.8); }

    /* Estilos para galería */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .gallery-item {
        position: relative;
        border: 1px solid #2a2010;
        border-radius: 4px;
        overflow: hidden;
        background: #0a0a0a;
        transition: all .2s;
    }

    .gallery-item:hover {
        border-color: #c9a84c;
        transform: translateY(-2px);
    }

    .gallery-item img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }

    .gallery-item-actions {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        display: flex;
        gap: 0.3rem;
    }

    .gallery-item-actions button {
        background: rgba(0,0,0,0.7);
        border: none;
        color: #fff;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .gallery-item-actions button:hover {
        transform: scale(1.05);
    }

    .gallery-checkbox {
        position: absolute;
        bottom: 0.5rem;
        left: 0.5rem;
    }

    .gallery-checkbox input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    /* Estilos para videos */
    .videos-list {
        margin-top: 1rem;
    }

    .video-item {
        background: rgba(201,168,76,0.05);
        border: 1px solid #2a2010;
        border-radius: 4px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }

    .video-item:hover {
        border-color: #c9a84c;
        background: rgba(201,168,76,0.08);
    }

    .video-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.8rem;
    }

    .video-info {
        flex: 1;
    }

    .video-nombre {
        font-size: 0.9rem;
        font-weight: 600;
        color: #f0ead8;
        margin-bottom: 0.2rem;
    }

    .video-url {
        font-family: monospace;
        font-size: 0.75rem;
        color: #c9a84c;
        word-break: break-all;
    }

    .video-titulo {
        font-size: 0.85rem;
        color: #f0ead8;
        margin-top: 0.2rem;
    }

    .video-tipo {
        font-size: 0.65rem;
        color: #6b5e3e;
        margin-top: 0.2rem;
    }

    .video-size {
        font-size: 0.7rem;
        color: #6b5e3e;
        margin-top: 0.2rem;
    }

    .video-checkbox {
        margin-left: 1rem;
    }

    .video-preview {
        margin-top: 0.8rem;
        border-radius: 4px;
        overflow: hidden;
        background: #000;
    }

    .video-preview video {
        width: 100%;
        max-height: 200px;
        cursor: pointer;
    }

    .video-upload {
        border: 1px dashed #2a2010;
        border-radius: 3px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative;
        background: rgba(255,255,255,.02);
        margin-bottom: 1rem;
    }

    .video-upload:hover {
        border-color: #7a5f28;
        background: rgba(201,168,76,.03);
    }

    .video-upload input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .video-upload-icon {
        font-size: 2rem;
        color: #3d2e10;
        margin-bottom: 0.5rem;
        transition: color .2s;
    }

    .video-upload:hover .video-upload-icon { color: #c9a84c; }

    .video-upload-text {
        font-size: 0.75rem;
        color: #6b5e3e;
    }

    .video-actions {
        display: flex;
        gap: 0.5rem;
    }

    .video-actions button {
        background: rgba(0,0,0,0.6);
        border: none;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .video-actions button:hover {
        transform: scale(1.05);
    }

    .btn-row {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .btn-save {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        padding: .88rem 1.5rem;
        background: linear-gradient(135deg, #b8962a, #e0c45a);
        color: #080808;
        border: none;
        border-radius: 3px;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 28px rgba(184,150,42,.38);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .88rem 1.3rem;
        background: transparent;
        color: #6b5e3e;
        border: 1px solid #2a2010;
        border-radius: 3px;
        text-decoration: none;
        cursor: pointer;
        transition: border-color .2s, color .2s;
    }

    .btn-cancel:hover {
        border-color: #3d2e10;
        color: #f0ead8;
    }

    .btn-horarios {
        background: rgba(167,139,250,.1);
        border: 1px solid rgba(167,139,250,.2);
        color: #a78bfa;
        padding: .5rem 1rem;
        border-radius: 3px;
        font-size: .7rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: all .2s;
    }

    .btn-horarios:hover {
        background: rgba(167,139,250,.2);
        color: #c4b5fd;
    }

    .horarios-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .horarios-preview {
        background: rgba(255,255,255,.02);
        border-radius: 3px;
        padding: .8rem;
        max-height: 200px;
        overflow-y: auto;
    }

    .horario-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .5rem 0;
        border-bottom: 1px solid rgba(255,255,255,.04);
        font-size: .75rem;
    }

    .horario-item:last-child { border-bottom: none; }

    .horario-fecha { color: #c9a84c; }
    .horario-hora { color: #8a7a5a; }
    .horario-cupos { color: #6ee7b7; }

    .empty-horarios {
        text-align: center;
        padding: 1rem;
        color: #6b5e3e;
        font-size: .75rem;
    }
    
    .info-note {
        background: rgba(201,168,76,0.05);
        border-left: 3px solid #c9a84c;
        padding: 0.8rem;
        margin-top: 1rem;
        font-size: 0.75rem;
        color: #9a8060;
    }
    
    .section-divider {
        margin: 1.5rem 0 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255,255,255,.05);
    }
</style>

<form action="{{ route('admin.cholets.update', $cholet->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-wrap">

        <!-- Panel 1: Información básica -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-building-estate"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Información básica</div>
                    <div class="panel-sub">Datos principales del cholet</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="field">
                    <label><i class="ti ti-forms"></i> Nombre del cholet</label>
                    <input type="text" name="nombre" placeholder="Ej. Cholet Los Andes"
                           value="{{ old('nombre', $cholet->nombre) }}" required>
                    @error('nombre')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    <div class="field" style="margin-bottom:0;">
                        <label><i class="ti ti-map-pin"></i> Ubicación</label>
                        <input type="text" name="ubicacion" placeholder="Ej. Zona Sur, La Paz"
                               value="{{ old('ubicacion', $cholet->ubicacion) }}" required>
                        @error('ubicacion')
                            <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field" style="margin-bottom:0;">
                        <label><i class="ti ti-tag"></i> Categoría</label>
                        <select name="categoria" required>
                            <option value="" disabled>Seleccionar…</option>
                            @foreach(['Lujo','Premium','Estándar','Económico'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria', $cholet->categoria) === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria')
                            <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel 2: Descripción -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-align-left"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Descripción</div>
                    <div class="panel-sub">Presenta el cholet a los clientes</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="field" style="margin-bottom:0;">
                    <label><i class="ti ti-pencil"></i> Descripción detallada</label>
                    <textarea name="descripcion" id="descArea"
                              placeholder="Describe las características..."
                              maxlength="800" required>{{ old('descripcion', $cholet->descripcion) }}</textarea>
                    <div class="char-counter" id="charCount">0 / 800</div>
                    @error('descripcion')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Panel 3: Imagen principal -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-photo"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Imagen principal</div>
                    <div class="panel-sub">Foto de portada del cholet</div>
                </div>
            </div>
            <div class="panel-body">

                @if($cholet->imagen)
                <div class="current-image" id="currentImageContainer">
                    <img src="{{ asset('storage/cholets/' . $cholet->imagen) }}" alt="{{ $cholet->nombre }}">
                    <div class="current-image-info">
                        <p style="font-size:0.7rem; color:#6b5e3e;">Imagen actual</p>
                        <button type="button" class="btn-remove-image" id="removeImageBtn">
                            <i class="ti ti-trash"></i> Eliminar imagen
                        </button>
                    </div>
                </div>
                @endif

                <div class="field" style="margin-bottom:0;">
                    <div class="img-upload" id="uploadZone">
                        <input type="file" name="imagen" accept="image/*" id="imgInput">
                        <div class="img-upload-icon"><i class="ti ti-cloud-upload"></i></div>
                        <div class="img-upload-text">
                            <strong>Haz clic o arrastra una imagen</strong>
                            JPG, PNG, WEBP — máximo 5 MB
                        </div>
                    </div>

                    <div class="img-preview-wrap" id="previewWrap">
                        <img id="previewImg" src="" alt="Vista previa">
                        <div class="img-preview-clear" id="clearImg" title="Quitar imagen">
                            <i class="ti ti-x"></i>
                        </div>
                    </div>

                    @error('imagen')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="remove_image" id="removeImage" value="0">
            </div>
        </div>

        <!-- Panel 4: Galería de imágenes -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-gallery"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Galería de imágenes</div>
                    <div class="panel-sub">Imágenes adicionales del cholet</div>
                </div>
            </div>
            <div class="panel-body">
                
                @if($cholet->imagenes && $cholet->imagenes->count() > 0)
                <div class="field">
                    <label><i class="ti ti-photo"></i> Imágenes actuales</label>
                    <div class="gallery-grid" id="existingGallery">
                        @foreach($cholet->imagenes as $imagen)
                        <div class="gallery-item" data-id="{{ $imagen->id }}">
                            <img src="{{ asset('storage/cholets/galeria/' . $imagen->imagen) }}" alt="Galería">
                            <div class="gallery-checkbox">
                                <input type="checkbox" name="eliminar_imagenes[]" value="{{ $imagen->id }}" id="img_{{ $imagen->id }}">
                                <label for="img_{{ $imagen->id }}" style="font-size:0.7rem;">Marcar para eliminar</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="info-note">
                        <i class="ti ti-info-circle"></i> Marca los checkboxes para eliminar imágenes existentes.
                    </div>
                </div>
                @endif
                
                <div class="field">
                    <label><i class="ti ti-cloud-upload"></i> Agregar nuevas imágenes</label>
                    <div class="img-upload" id="galleryUploadZone">
                        <input type="file" name="galeria[]" accept="image/*" id="galleryInput" multiple>
                        <div class="img-upload-icon"><i class="ti ti-cloud-upload"></i></div>
                        <div class="img-upload-text">
                            <strong>Selecciona múltiples imágenes</strong>
                            Puedes seleccionar varias imágenes a la vez<br>
                            JPG, PNG, WEBP — máximo 5 MB cada una
                        </div>
                    </div>
                    @error('galeria.*')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                
                <div id="newGalleryPreview" class="gallery-grid"></div>
            </div>
        </div>

        <!-- Panel 5: Videos LOCALES -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-video"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Videos del cholet</div>
                    <div class="panel-sub">Sube videos desde tu computadora (MP4, MOV, AVI)</div>
                </div>
            </div>
            <div class="panel-body">
                
                @if($cholet->videos && $cholet->videos->count() > 0)
                <div class="field">
                    <label><i class="ti ti-video"></i> Videos actuales</label>
                    <div class="videos-list">
                        @foreach($cholet->videos as $video)
                        <div class="video-item">
                            <div class="video-header">
                                <div class="video-info">
                                    <div class="video-nombre">{{ $video->titulo ?? 'Video sin título' }}</div>
                                    <div class="video-url">{{ $video->url }}</div>
                                    <div class="video-tipo">{{ $video->tipo === 'local' ? '📁 Video local' : ($video->tipo === 'youtube' ? '▶️ YouTube' : '🎬 Vimeo') }}</div>
                                </div>
                                <div class="video-checkbox">
                                    <input type="checkbox" name="eliminar_videos[]" value="{{ $video->id }}" id="video_{{ $video->id }}">
                                    <label for="video_{{ $video->id }}" style="font-size:0.7rem;">Eliminar</label>
                                </div>
                            </div>
                            @if($video->tipo === 'local')
                            <div class="video-preview">
                                <video controls preload="metadata">
                                    <source src="{{ asset('storage/videos/' . $video->url) }}" type="video/mp4">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <div class="info-note">
                        <i class="ti ti-info-circle"></i> Marca los checkboxes para eliminar videos existentes.
                    </div>
                </div>
                @endif
                
                <div class="field">
                    <label><i class="ti ti-cloud-upload"></i> Agregar nuevos videos</label>
                    <div class="video-upload" id="videoUploadZone">
                        <input type="file" name="nuevos_videos[]" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm" id="videoInput" multiple>
                        <div class="video-upload-icon"><i class="ti ti-cloud-upload"></i></div>
                        <div class="video-upload-text">
                            <strong>Sube uno o más videos</strong>
                            MP4, MOV, AVI, WEBM — máximo 100 MB cada uno
                        </div>
                    </div>
                    
                    <div id="newVideosList" class="videos-list"></div>
                    
                    @error('nuevos_videos.*')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                
                <div class="info-note">
                    <i class="ti ti-info-circle"></i> Formatos soportados: MP4, MOV, AVI, WEBM. Tamaño máximo por video: 100MB. Máximo 5 videos.
                </div>
            </div>
        </div>

        <!-- Panel 6: Horarios disponibles -->
        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-clock"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Horarios disponibles</div>
                    <div class="panel-sub">Gestiona los horarios de este cholet</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="horarios-header">
                    <span style="font-size: .7rem; color: #6b5e3e;">Próximos horarios</span>
                    <a href="{{ route('admin.horarios.index', $cholet->id) }}" class="btn-horarios">
                        <i class="ti ti-calendar-time"></i> Gestionar horarios
                    </a>
                </div>

                <div class="horarios-preview">
                    @php
                        $horariosRecientes = \App\Models\Horario::where('cholet_id', $cholet->id)
                            ->where('fecha', '>=', \Carbon\Carbon::today())
                            ->orderBy('fecha', 'asc')
                            ->orderBy('hora_inicio', 'asc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @forelse($horariosRecientes as $horario)
                    <div class="horario-item">
                        <div class="horario-fecha"><i class="ti ti-calendar"></i> {{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</div>
                        <div class="horario-hora"><i class="ti ti-clock"></i> {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('h:i A') }}</div>
                        <div class="horario-cupos"><i class="ti ti-users"></i> {{ $horario->capacidad_maxima - $horario->reservas_actuales }}/{{ $horario->capacidad_maxima }}</div>
                    </div>
                    @empty
                    <div class="empty-horarios">
                        <i class="ti ti-calendar-off"></i>
                        <p>No hay horarios creados. Haz clic en "Gestionar horarios" para agregar.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="btn-row">
            <a href="{{ route('admin.cholets.index') }}" class="btn-cancel">
                <i class="ti ti-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn-save">
                <i class="ti ti-device-floppy"></i> Actualizar Cholet
            </button>
        </div>

    </div>
</form>

<script>
// ==================== 1. CONTADOR DE DESCRIPCIÓN ====================
const descArea = document.getElementById('descArea');
const charCount = document.getElementById('charCount');
const MAX = 800;

function updateCounter() {
    const len = descArea.value.length;
    charCount.textContent = `${len} / ${MAX}`;
    charCount.className = 'char-counter' + (len >= MAX ? ' over' : len >= MAX * .85 ? ' warn' : '');
}

descArea.addEventListener('input', updateCounter);
updateCounter();

// ==================== 2. IMAGEN PRINCIPAL ====================
const imgInput = document.getElementById('imgInput');
const previewWrap = document.getElementById('previewWrap');
const previewImg = document.getElementById('previewImg');
const clearImg = document.getElementById('clearImg');
const uploadZone = document.getElementById('uploadZone');
const removeImageBtn = document.getElementById('removeImageBtn');
const removeImageInput = document.getElementById('removeImage');
const currentImageContainer = document.getElementById('currentImageContainer');

imgInput.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        alert('El archivo es demasiado grande. Máximo 5MB');
        this.value = '';
        return;
    }

    if (!file.type.match('image.*')) {
        alert('Solo se permiten archivos de imagen');
        this.value = '';
        return;
    }

    if (currentImageContainer) currentImageContainer.style.display = 'none';

    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewWrap.style.display = 'block';
        uploadZone.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

if (clearImg) {
    clearImg.addEventListener('click', function () {
        imgInput.value = '';
        previewImg.src = '';
        previewWrap.style.display = 'none';
        uploadZone.style.display = 'block';
        if (currentImageContainer) currentImageContainer.style.display = 'flex';
        removeImageInput.value = '0';
    });
}

if (removeImageBtn) {
    removeImageBtn.addEventListener('click', function () {
        if (confirm('¿Eliminar la imagen actual?')) {
            removeImageInput.value = '1';
            currentImageContainer.style.display = 'none';
        }
    });
}

// ==================== 3. GALERÍA DE IMÁGENES ====================
const galleryInput = document.getElementById('galleryInput');
const newGalleryPreview = document.getElementById('newGalleryPreview');
let newGalleryFiles = [];

galleryInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const existingCount = {{ $cholet->imagenes->count() ?? 0 }};
    
    if (newGalleryFiles.length + files.length + existingCount > 10) {
        alert('Máximo 10 imágenes en total (incluyendo las existentes)');
        return;
    }
    
    files.forEach(file => {
        if (file.size > 5 * 1024 * 1024) {
            alert(`El archivo ${file.name} es demasiado grande. Máximo 5MB`);
            return;
        }
        
        if (!file.type.match('image.*')) {
            alert(`El archivo ${file.name} no es una imagen válida`);
            return;
        }
        
        newGalleryFiles.push(file);
    });
    
    renderNewGalleryPreview();
});

function renderNewGalleryPreview() {
    newGalleryPreview.innerHTML = '';
    
    newGalleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const galleryItem = document.createElement('div');
            galleryItem.className = 'gallery-item';
            galleryItem.innerHTML = `
                <img src="${e.target.result}" alt="Nueva imagen ${index + 1}">
                <div class="gallery-item-actions">
                    <button type="button" class="btn-remove-new-gallery" data-index="${index}" style="background:rgba(239,68,68,0.8);">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            `;
            newGalleryPreview.appendChild(galleryItem);
        };
        reader.readAsDataURL(file);
    });
    
    document.querySelectorAll('.btn-remove-new-gallery').forEach(btn => {
        btn.addEventListener('click', () => {
            const index = parseInt(btn.dataset.index);
            newGalleryFiles.splice(index, 1);
            renderNewGalleryPreview();
            updateGalleryInput();
        });
    });
}

function updateGalleryInput() {
    const dataTransfer = new DataTransfer();
    newGalleryFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    galleryInput.files = dataTransfer.files;
}

// ==================== 4. VIDEOS LOCALES ====================
const videoInput = document.getElementById('videoInput');
const newVideosList = document.getElementById('newVideosList');
let newVideoFiles = [];

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function renderNewVideosList() {
    newVideosList.innerHTML = '';
    
    newVideoFiles.forEach((file, index) => {
        const url = URL.createObjectURL(file);
        const videoItem = document.createElement('div');
        videoItem.className = 'video-item';
        videoItem.innerHTML = `
            <div class="video-header">
                <div class="video-info">
                    <div class="video-nombre">${escapeHtml(file.name)}</div>
                    <div class="video-size">${formatFileSize(file.size)}</div>
                </div>
                <div class="video-actions">
                    <button type="button" class="btn-remove-new-video" data-index="${index}" style="background:rgba(239,68,68,.2); border:none; color:#f87171; padding:0.3rem 0.6rem; border-radius:3px; cursor:pointer;">
                        <i class="ti ti-trash"></i> Eliminar
                    </button>
                </div>
            </div>
            <div class="video-preview">
                <video controls preload="metadata">
                    <source src="${url}" type="${file.type}">
                    Tu navegador no soporta el elemento de video.
                </video>
            </div>
            <input type="hidden" name="nuevos_videos[]" value="${index}" data-file-index="${index}">
        `;
        newVideosList.appendChild(videoItem);
        
        videoItem.dataset.url = url;
    });
    
    document.querySelectorAll('.btn-remove-new-video').forEach(btn => {
        btn.addEventListener('click', () => {
            const index = parseInt(btn.dataset.index);
            const item = document.querySelector(`.video-item[data-index="${index}"]`);
            if (item && item.dataset.url) {
                URL.revokeObjectURL(item.dataset.url);
            }
            newVideoFiles.splice(index, 1);
            renderNewVideosList();
            updateVideoInput();
        });
    });
}

function updateVideoInput() {
    const dataTransfer = new DataTransfer();
    newVideoFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    videoInput.files = dataTransfer.files;
}

videoInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const existingCount = {{ $cholet->videos->count() ?? 0 }};
    
    if (newVideoFiles.length + files.length + existingCount > 5) {
        alert('Máximo 5 videos por cholet');
        return;
    }
    
    files.forEach(file => {
        const validTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'];
        if (!validTypes.includes(file.type)) {
            alert(`El archivo ${file.name} no es un formato de video válido. Formatos permitidos: MP4, MOV, AVI, WEBM`);
            return;
        }
        
        if (file.size > 100 * 1024 * 1024) {
            alert(`El archivo ${file.name} es demasiado grande. Máximo 100MB`);
            return;
        }
        
        newVideoFiles.push(file);
    });
    
    renderNewVideosList();
    updateVideoInput();
});

window.addEventListener('beforeunload', () => {
    document.querySelectorAll('.video-item').forEach(item => {
        if (item.dataset.url) {
            URL.revokeObjectURL(item.dataset.url);
        }
    });
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

@endsection