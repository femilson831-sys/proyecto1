<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guías — Acceso Exclusivo | Jacha Tours</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --black:    #080808;
            --deep:     #0e0e0e;
            --surface:  #141414;
            --border:   #2a2318;
            --gold:     #c9a84c;
            --gold2:    #e8cc80;
            --gold-dim: #7a5f28;
            --text:     #f0ead8;
            --muted:    #6b5e3e;
            --danger:   #c0392b;
            --danger-lt:#e8857c;
            --success:  #27ae60;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: var(--black);
            font-family: 'Jost', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* ── Fondo: viñeta dorada suave ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 50% 50%, rgba(201,168,76,.07) 0%, transparent 65%),
                radial-gradient(ellipse 40% 30% at 15% 85%, rgba(201,168,76,.05) 0%, transparent 50%),
                radial-gradient(ellipse 35% 25% at 85% 10%, rgba(201,168,76,.04) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Patrón de rombo sutil ── */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                repeating-linear-gradient(45deg,  rgba(201,168,76,.018) 0px, rgba(201,168,76,.018) 1px, transparent 1px, transparent 28px),
                repeating-linear-gradient(-45deg, rgba(201,168,76,.018) 0px, rgba(201,168,76,.018) 1px, transparent 1px, transparent 28px);
            pointer-events: none;
            z-index: 0;
        }

        /* ── Wrapper ── */
        .login-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 1.2rem;
            animation: fadeUp .7s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: none; }
        }

        /* ── Card ── */
        .login-card {
            background: var(--surface);
            border: 1px solid #2e2410;
            border-radius: 4px;
            padding: 3rem 2.8rem 2.6rem;
            position: relative;
            box-shadow:
                0 0 0 1px rgba(201,168,76,.08) inset,
                0 40px 100px rgba(0,0,0,.85),
                0 0 80px rgba(201,168,76,.05);
        }

        /* Línea dorada superior */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 10%; right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            border-radius: 0 0 2px 2px;
        }

        /* Esquinas decorativas */
        .corner {
            position: absolute;
            width: 18px; height: 18px;
            border-color: var(--gold-dim);
            border-style: solid;
            opacity: .6;
        }
        .corner.tl { top: 10px; left: 10px;  border-width: 1px 0 0 1px; }
        .corner.tr { top: 10px; right: 10px; border-width: 1px 1px 0 0; }
        .corner.bl { bottom: 10px; left: 10px;  border-width: 0 0 1px 1px; }
        .corner.br { bottom: 10px; right: 10px; border-width: 0 1px 1px 0; }

        /* ── Logo ── */
        .logo-wrap {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-wrap img {
            height: 68px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 16px rgba(201,168,76,.35)) brightness(1.05);
            transition: filter .35s;
        }

        .logo-wrap img:hover {
            filter: drop-shadow(0 4px 28px rgba(201,168,76,.6)) brightness(1.12);
        }

        /* ── Divider ── */
        .gold-rule {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.8rem;
        }
        .gold-rule span {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }
        .gold-rule em {
            font-style: normal;
            font-family: 'Playfair Display', serif;
            font-size: .72rem;
            letter-spacing: .22em;
            color: var(--gold-dim);
            text-transform: uppercase;
            white-space: nowrap;
        }

        /* ── Heading ── */
        h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.45rem;
            color: var(--text);
            text-align: center;
            letter-spacing: .02em;
            margin-bottom: .35rem;
            line-height: 1.2;
        }

        .subtitle {
            text-align: center;
            font-size: .78rem;
            font-weight: 400;
            color: var(--muted);
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 1.8rem;
        }

        /* ── Alert ── */
        .alert-custom {
            background: rgba(192,57,43,.1);
            border: 1px solid rgba(192,57,43,.3);
            border-left: 3px solid var(--danger);
            border-radius: 3px;
            color: var(--danger-lt);
            font-size: .845rem;
            padding: .65rem 1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: .55rem;
            animation: shake .4s ease;
        }

        .alert-success-custom {
            background: rgba(39,174,96,.1);
            border: 1px solid rgba(39,174,96,.3);
            border-left: 3px solid var(--success);
            color: #6ee7b7;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            60% { transform: translateX(-3px); }
            80% { transform: translateX(3px); }
        }

        /* ── Inputs ── */
        .field-wrap {
            margin-bottom: 1.1rem;
        }

        .field-wrap label {
            display: block;
            font-size: .7rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: .45rem;
        }

        .field-wrap label i {
            margin-right: 0.3rem;
        }

        .field-wrap input {
            width: 100%;
            background: rgba(255,255,255,.025);
            border: 1px solid #2a2010;
            border-radius: 3px;
            padding: .82rem 1.1rem;
            color: var(--text);
            font-family: 'Jost', sans-serif;
            font-size: .92rem;
            font-weight: 400;
            transition: border-color .25s, box-shadow .25s, background .25s;
            outline: none;
            letter-spacing: .02em;
        }

        .field-wrap input::placeholder { color: #3d3020; }

        .field-wrap input:focus {
            border-color: var(--gold-dim);
            background: rgba(201,168,76,.04);
            box-shadow: 0 0 0 3px rgba(201,168,76,.1);
        }

        /* ── Botón ── */
        .btn-login {
            width: 100%;
            padding: .88rem;
            margin-top: .6rem;
            background: linear-gradient(135deg, #b8962a 0%, #e0c45a 45%, #b8962a 100%);
            border: none;
            border-radius: 3px;
            color: #0e0e0e;
            font-family: 'Jost', sans-serif;
            font-weight: 600;
            font-size: .88rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: opacity .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 24px rgba(184,150,42,.28), 0 1px 0 rgba(255,255,255,.15) inset;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent);
            transition: left .5s ease;
        }

        .btn-login:hover::before { left: 160%; }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(184,150,42,.4), 0 1px 0 rgba(255,255,255,.15) inset;
        }
        .btn-login:active { transform: translateY(0); }

        /* ── Footer ── */
        .footer-links {
            text-align: center;
            margin-top: 1.8rem;
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 0.5px;
        }

        .footer-links a {
            color: var(--gold-dim);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--gold);
        }

        hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1rem 0;
        }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-card">
        <!-- Esquinas decorativas -->
        <div class="corner tl"></div>
        <div class="corner tr"></div>
        <div class="corner bl"></div>
        <div class="corner br"></div>

        <!-- Logo -->
        <div class="logo-wrap">
            <img src="{{ asset('img/logo.png') }}" alt="Jacha Tours">
        </div>

        <!-- Divider con texto -->
        <div class="gold-rule">
            <span></span>
            <em>Acceso Guías</em>
            <span></span>
        </div>

        <h3>Área Exclusiva</h3>
        <p class="subtitle">Ingrese sus credenciales</p>

        <!-- Errores del servidor -->
        @if(session('error'))
        <div class="alert-custom">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <circle cx="12" cy="16" r=".5" fill="currentColor"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert-custom">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <circle cx="12" cy="16" r=".5" fill="currentColor"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('guia.login.post') }}">
            @csrf

            <div class="field-wrap">
                <label for="email"><i class="ti ti-mail"></i> Correo electrónico</label>
                <input type="email" id="email" name="email"
                       placeholder="guia@ejemplo.com"
                       value="{{ old('email') }}" required autofocus>
            </div>

            <div class="field-wrap">
                <label for="password"><i class="ti ti-lock"></i> Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">
                <i class="ti ti-login"></i> Ingresar
            </button>
        </form>

        <hr>

        <div class="footer-links">
            <p>© {{ date('Y') }} Jacha Tours - Todos los derechos reservados</p>
            <p style="margin-top: 0.3rem;">
                ¿Eres administrador? <a href="{{ route('admin.login') }}">Accede aquí</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>