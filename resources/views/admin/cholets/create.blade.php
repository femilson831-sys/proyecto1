@extends('admin.layouts.app')
@section('title', 'Nuevo Cholet')
@section('page-title', 'Nuevo Cholet')

@section('content')

<style>
    .form-wrap {
        max-width: 860px;
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
        font-weight: 400;
        outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
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
        transition: color .2s;
    }
    .char-counter.warn { color: #d97706; }
    .char-counter.over  { color: #f87171; }

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
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem;
        cursor: pointer;
        transition: background .2s;
    }

    .img-preview-clear:hover { background: rgba(192,57,43,.8); }

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
        font-family: 'Jost', sans-serif;
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .12em;
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
        font-family: 'Jost', sans-serif;
        font-size: .82rem;
        font-weight: 500;
        letter-spacing: .06em;
        text-decoration: none;
        cursor: pointer;
        transition: border-color .2s, color .2s, background .2s;
    }

    .btn-cancel:hover {
        border-color: #3d2e10;
        color: #f0ead8;
        background: rgba(255,255,255,.03);
    }
</style>

<form action="{{ route('admin.cholets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-wrap">

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
                           value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    <div class="field" style="margin-bottom:0;">
                        <label><i class="ti ti-map-pin"></i> Ubicación</label>
                        <input type="text" name="ubicacion" placeholder="Ej. Zona Sur, La Paz"
                               value="{{ old('ubicacion') }}" required>
                        @error('ubicacion')
                            <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field" style="margin-bottom:0;">
                        <label><i class="ti ti-tag"></i> Categoría</label>
                        <select name="categoria" required>
                            <option value="" disabled {{ old('categoria') ? '' : 'selected' }}>Seleccionar…</option>
                            @foreach(['Lujo','Premium','Estándar','Económico'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria') === $cat ? 'selected' : '' }}>
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
                              placeholder="Describe las características, servicios y ambiente del cholet…"
                              maxlength="800" required>{{ old('descripcion') }}</textarea>
                    <div class="char-counter" id="charCount">0 / 800</div>
                    @error('descripcion')
                        <div class="field-error"><i class="ti ti-alert-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-icon"><i class="ti ti-photo"></i></div>
                <div class="panel-head-text">
                    <div class="panel-title">Imagen principal</div>
                    <div class="panel-sub">Foto de portada del cholet</div>
                </div>
            </div>
            <div class="panel-body">
                <div class="field" style="margin-bottom:0;">
                    <div class="img-upload" id="uploadZone">
                        <input type="file" name="imagen" accept="image/*" id="imgInput" required>
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
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('admin.cholets.index') }}" class="btn-cancel">
                <i class="ti ti-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn-save">
                <i class="ti ti-device-floppy"></i> Guardar Cholet
            </button>
        </div>

    </div>
</form>

<script>
const imgInput = document.getElementById('imgInput');
const previewWrap = document.getElementById('previewWrap');
const previewImg = document.getElementById('previewImg');
const clearImg = document.getElementById('clearImg');
const uploadZone = document.getElementById('uploadZone');

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

    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewWrap.style.display = 'block';
        uploadZone.style.display = 'none';
    };
    reader.readAsDataURL(file);
});

clearImg.addEventListener('click', function () {
    imgInput.value = '';
    previewImg.src = '';
    previewWrap.style.display = 'none';
    uploadZone.style.display = 'block';
});

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
</script>

@endsection