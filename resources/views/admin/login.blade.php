<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Acceso Seguro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
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

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            60% { transform: translateX(-3px); }
            80% { transform: translateX(3px); }
        }

        .attempts-badge {
            background: rgba(192,57,43,.08);
            border: 1px solid rgba(192,57,43,.22);
            border-radius: 3px;
            color: var(--danger-lt);
            font-size: .8rem;
            padding: .45rem .9rem;
            text-align: center;
            margin-bottom: 1.2rem;
            letter-spacing: .02em;
        }

        /* ── Dots ── */
        .dots-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            margin-bottom: 1.6rem;
        }

        .dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--border);
            border: 1px solid #3a2e18;
            transition: background .3s, transform .3s, box-shadow .3s;
        }

        .dot.used {
            background: var(--danger);
            border-color: var(--danger);
            box-shadow: 0 0 6px rgba(192,57,43,.5);
            transform: scale(1.2);
        }

        .dot.active {
            background: var(--gold);
            border-color: var(--gold);
            box-shadow: 0 0 8px rgba(201,168,76,.6);
            transform: scale(1.3);
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

        .field-wrap input:disabled {
            opacity: .4;
            cursor: not-allowed;
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

        .btn-login:hover:not(:disabled)::before { left: 160%; }
        .btn-login:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(184,150,42,.4), 0 1px 0 rgba(255,255,255,.15) inset;
        }
        .btn-login:active:not(:disabled) { transform: translateY(0); }

        .btn-login:disabled {
            opacity: .35;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* ── Countdown ── */
        .countdown-wrap {
            margin-top: 1.4rem;
            text-align: center;
            display: none;
        }

        .countdown-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 76px; height: 76px;
            margin-bottom: .7rem;
        }

        .countdown-ring svg {
            position: absolute;
            inset: 0;
            transform: rotate(-90deg);
        }

        .ring-bg { fill: none; stroke: #1e1a10; stroke-width: 3.5; }
        .ring-fg {
            fill: none;
            stroke: url(#goldGrad);
            stroke-width: 3.5;
            stroke-linecap: round;
            transition: stroke-dashoffset .9s linear;
        }

        .countdown-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gold);
            position: relative;
            z-index: 1;
        }

        .countdown-label {
            font-size: .78rem;
            color: var(--muted);
            letter-spacing: .04em;
        }

        .countdown-label strong { color: var(--gold-dim); }
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
            <img src="/img/logo.png" alt="Logo" id="logo">
        </div>

        <!-- Divider con texto -->
        <div class="gold-rule">
            <span></span>
            <em>Panel Administrativo</em>
            <span></span>
        </div>

        <h3>Acceso Seguro</h3>
        <p class="subtitle">Credenciales requeridas</p>

        <!-- Dots: 3 intentos -->
        <div class="dots-row" id="dotsRow">
            <div class="dot active" id="dot0"></div>
            <div class="dot"        id="dot1"></div>
            <div class="dot"        id="dot2"></div>
        </div>

        <!-- Error del servidor (Laravel) -->
        @if($errors->any())
        <div class="alert-custom" id="serverAlert">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><circle cx="12" cy="16" r=".5" fill="currentColor"/></svg>
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Badge de intentos restantes (JS) -->
        <div class="attempts-badge" id="attemptsBadge" style="display:none"></div>

        <!-- Formulario -->
        <form method="POST" action="/admin/login" id="loginForm">
            @csrf

            <div class="field-wrap">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email"
                       placeholder="admin@dominio.com"
                       value="{{ old('email') }}" required>
            </div>

            <div class="field-wrap">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">Ingresar</button>
        </form>

        <!-- Cuenta regresiva (bloqueo) -->
        <div class="countdown-wrap" id="countdownWrap">
            <div class="countdown-ring">
                <svg viewBox="0 0 76 76">
                    <defs>
                        <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%"   stop-color="#b8962a"/>
                            <stop offset="100%" stop-color="#e0c45a"/>
                        </linearGradient>
                    </defs>
                    <circle class="ring-bg" cx="38" cy="38" r="32"/>
                    <circle class="ring-fg" cx="38" cy="38" r="32"
                            stroke-dasharray="201.1"
                            stroke-dashoffset="0"
                            id="ringFg"/>
                </svg>
                <span class="countdown-num" id="countdownNum">180</span>
            </div>
            <div class="countdown-label">
                Vuelve a intentarlo en <strong id="countdownText">3:00</strong>
            </div>
        </div>

    </div>
</div>

<script>
(function () {
    const MAX_ATTEMPTS  = 3;
    const LOCKOUT_SECS  = 180;
    const STORE_KEY     = 'adm_lock';
    const CIRCUMFERENCE = 201.1;

    const form          = document.getElementById('loginForm');
    const btn           = document.getElementById('loginBtn');
    const emailInput    = document.getElementById('email');
    const passInput     = document.getElementById('password');
    const badge         = document.getElementById('attemptsBadge');
    const countdownWrap = document.getElementById('countdownWrap');
    const countdownNum  = document.getElementById('countdownNum');
    const countdownText = document.getElementById('countdownText');
    const ringFg        = document.getElementById('ringFg');

    function updateDots(used) {
        for (let i = 0; i < MAX_ATTEMPTS; i++) {
            const d = document.getElementById('dot' + i);
            d.classList.remove('used', 'active');
            if (i < used)        d.classList.add('used');
            else if (i === used) d.classList.add('active');
        }
    }

    function getState() {
        try { return JSON.parse(localStorage.getItem(STORE_KEY)) || {}; }
        catch { return {}; }
    }
    function setState(obj) {
        localStorage.setItem(STORE_KEY, JSON.stringify(obj));
    }

    let timer = null;

    function startLockout(remainingSecs) {
        setFormDisabled(true);
        countdownWrap.style.display = 'block';

        function tick() {
            if (remainingSecs <= 0) {
                clearInterval(timer);
                unlockForm();
                return;
            }
            const m = Math.floor(remainingSecs / 60);
            const s = remainingSecs % 60;
            countdownNum.textContent  = remainingSecs;
            countdownText.textContent = m + ':' + String(s).padStart(2, '0');
            ringFg.style.strokeDashoffset = CIRCUMFERENCE * (1 - remainingSecs / LOCKOUT_SECS);
            remainingSecs--;
        }
        tick();
        timer = setInterval(tick, 1000);
    }

    function unlockForm() {
        setState({ attempts: 0 });
        updateDots(0);
        setFormDisabled(false);
        countdownWrap.style.display = 'none';
        badge.style.display = 'none';
        ringFg.style.strokeDashoffset = 0;
    }

    function setFormDisabled(val) {
        emailInput.disabled = val;
        passInput.disabled  = val;
        btn.disabled        = val;
    }

    function checkLockout() {
        const state = getState();
        if (state.lockedUntil) {
            const left = Math.ceil((state.lockedUntil - Date.now()) / 1000);
            if (left > 0) {
                updateDots(MAX_ATTEMPTS);
                startLockout(left);
                return true;
            } else {
                setState({ attempts: 0 });
            }
        }
        updateDots(state.attempts || 0);
        return false;
    }

    function detectServerError() {
        const serverAlert = document.getElementById('serverAlert');
        if (!serverAlert) return;

        const state    = getState();
        const attempts = (state.attempts || 0) + 1;

        if (attempts >= MAX_ATTEMPTS) {
            const lockedUntil = Date.now() + LOCKOUT_SECS * 1000;
            setState({ attempts, lockedUntil });
            updateDots(attempts);
            startLockout(LOCKOUT_SECS);
        } else {
            setState({ attempts });
            updateDots(attempts);
            const left = MAX_ATTEMPTS - attempts;
            badge.textContent = `⚠  Te queda${left === 1 ? '' : 'n'} ${left} intento${left === 1 ? '' : 's'} antes de bloquearte 3 minutos.`;
            badge.style.display = 'block';
        }
    }

    const locked = checkLockout();
    if (!locked) detectServerError();

    form.addEventListener('submit', function (e) {
        if (btn.disabled) e.preventDefault();
    });
})();
</script>

</body>
</html>