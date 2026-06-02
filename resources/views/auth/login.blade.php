<x-guest-layout>

<div class="login-page">

    <!-- VIDEO FONDO -->
    <video autoplay muted loop playsinline class="login-video">
        <source src="{{ asset('video/cholets.mp4') }}" type="video/mp4">
    </video>

    <!-- OVERLAY -->
    <div class="login-overlay"></div>

    <!-- CARD -->
    <div class="login-card">

        <!-- LÍNEA SUPERIOR DORADA -->
        <div class="login-card__top-bar"></div>

        <!-- MARCA -->
        <div class="login-brand">

            <div class="login-brand__icon">
                <img src="{{ asset('img/logo.png') }}" alt="JACHA TOURS">
            </div>

            <span class="login-brand__name">JACHA TOURS</span>
            <span class="login-brand__sub">El Alto · Bolivia</span>

        </div>

        <!-- SESSION STATUS -->
        <x-auth-session-status class="login-alert" :status="session('status')" />

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}">

            @csrf

            <!-- EMAIL -->
            <div class="login-field">

                <label class="login-label">
                    Correo Electrónico
                </label>

                <input type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    class="login-input @error('email') login-input--error @enderror"
                    placeholder="correo@gmail.com"
                    value="{{ old('email') }}">

                @error('email')
                    <span class="login-error">{{ $message }}</span>
                @enderror

            </div>

            <!-- PASSWORD -->
            <div class="login-field">

                <label class="login-label">
                    Contraseña
                </label>

                <input type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="login-input @error('password') login-input--error @enderror"
                    placeholder="••••••••">

                @error('password')
                    <span class="login-error">{{ $message }}</span>
                @enderror

            </div>

            <!-- REMEMBER + FORGOT -->
            <div class="login-row">

                <label class="login-remember">
                    <input type="checkbox" name="remember">
                    Recordarme
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="login-forgot">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif

            </div>

            <!-- SUBMIT -->
            <button type="submit" class="login-submit">
                Iniciar Sesión
            </button>

        </form>

        <!-- DIVIDER -->
        <div class="login-divider"><span>o</span></div>

        <!-- REGISTER -->
        <p class="login-register">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}">Registrarse</a>
        </p>

    </div>

</div>

<!-- ===========================
     ESTILOS INLINE
     (puedes moverlos a styles.css)
     =========================== -->
<style>

/* PAGE */
.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #070707;
    position: relative;
    overflow: hidden;
    padding: 2rem;
}

/* VIDEO */
.login-video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.35;
}

/* OVERLAY */
.login-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(4px);
}

/* CARD */
.login-card {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 420px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(201,168,76,0.25);
    padding: 0 2.5rem 2.5rem;
}

/* LÍNEA SUPERIOR */
.login-card__top-bar {
    height: 2px;
    background: linear-gradient(90deg, transparent, #C9A84C, transparent);
    margin-bottom: 2.5rem;
}

/* MARCA */
.login-brand {
    text-align: center;
    margin-bottom: 2rem;
}

.login-brand__icon {
    width: 72px;
    height: 72px;
    border: 1px solid rgba(201,168,76,0.35);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    overflow: hidden;
    padding: 8px;
}

.login-brand__icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.login-brand__name {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: 0.2em;
    font-family: 'Cormorant Garamond', serif;
}

.login-brand__sub {
    display: block;
    font-size: 0.65rem;
    color: rgba(255,255,255,0.35);
    letter-spacing: 0.4em;
    text-transform: uppercase;
    margin-top: 4px;
}

/* ALERT */
.login-alert {
    background: rgba(201,168,76,0.08);
    border: 1px solid rgba(201,168,76,0.3);
    color: #C9A84C;
    font-size: 0.8rem;
    padding: 0.6rem 1rem;
    margin-bottom: 1.2rem;
    letter-spacing: 0.04em;
}

/* FIELD */
.login-field {
    margin-bottom: 1.2rem;
}

.login-label {
    display: block;
    font-size: 0.68rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.45);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.login-input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    padding: 0.78rem 1rem;
    font-size: 0.9rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    border-radius: 2px;
    transition: border-color 0.3s;
}

.login-input:focus {
    border-color: #C9A84C;
}

.login-input::placeholder {
    color: rgba(255,255,255,0.18);
}

.login-input--error {
    border-color: rgba(220,38,38,0.6);
}

.login-error {
    display: block;
    font-size: 0.72rem;
    color: #f87171;
    margin-top: 0.35rem;
    letter-spacing: 0.04em;
}

/* REMEMBER / FORGOT */
.login-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.8rem;
}

.login-remember {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.78rem;
    color: rgba(255,255,255,0.4);
    cursor: pointer;
}

.login-remember input[type="checkbox"] {
    accent-color: #C9A84C;
    width: 14px;
    height: 14px;
    cursor: pointer;
}

.login-forgot {
    font-size: 0.75rem;
    color: #C9A84C;
    text-decoration: none;
    letter-spacing: 0.04em;
    transition: color 0.3s;
}

.login-forgot:hover {
    color: #E8C96D;
}

/* SUBMIT */
.login-submit {
    width: 100%;
    padding: 0.9rem;
    background: #C9A84C;
    color: #070707;
    font-weight: 700;
    font-size: 0.78rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    border: none;
    border-radius: 2px;
    cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: background 0.3s, transform 0.2s;
}

.login-submit:hover {
    background: #E8C96D;
    transform: translateY(-1px);
}

.login-submit:active {
    transform: translateY(0);
}

/* DIVIDER */
.login-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1.5rem 0;
    opacity: 0.3;
}

.login-divider::before,
.login-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #C9A84C;
}

.login-divider span {
    font-size: 0.65rem;
    color: #C9A84C;
    letter-spacing: 0.2em;
    text-transform: uppercase;
}

/* REGISTER */
.login-register {
    text-align: center;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.35);
}

.login-register a {
    color: #C9A84C;
    text-decoration: none;
    font-weight: 600;
    margin-left: 4px;
    transition: color 0.3s;
}

.login-register a:hover {
    color: #E8C96D;
}

/* RESPONSIVE */
@media (max-width: 480px) {
    .login-card { padding: 0 1.5rem 2rem; }
    .login-row  { flex-direction: column; align-items: flex-start; gap: 0.8rem; }
}

</style>

</x-guest-layout>