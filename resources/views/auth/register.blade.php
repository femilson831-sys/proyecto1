<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro | JACHA TOURS</title>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html{
            scroll-behavior:smooth;
        }

        body.register-page{

            min-height:100vh;

            background:#050505;

            font-family:'Inter',sans-serif;

            position:relative;

            overflow-y:auto;
            overflow-x:hidden;

            padding:40px 20px;

            display:flex;
            justify-content:center;
            align-items:flex-start;

        }

        /* VIDEO */

        .bg-video{

            position:fixed;
            inset:0;

            width:100%;
            height:100%;

            object-fit:cover;

            opacity:.35;

            z-index:0;

        }

        /* OVERLAY */

        .overlay{

            position:fixed;
            inset:0;

            background:rgba(0,0,0,.72);

            backdrop-filter:blur(4px);

            z-index:1;

        }

        /* CARD */

        .register-card{

            position:relative;
            z-index:10;

            width:100%;
            max-width:520px;

            background:rgba(255,255,255,.05);

            border:1px solid rgba(201,168,76,.25);

            padding:45px 35px;

            border-radius:22px;

            backdrop-filter:blur(10px);

            box-shadow:0 0 40px rgba(0,0,0,.45);

            margin:40px 0;

        }

        .top-line{

            width:100%;
            height:2px;

            background:linear-gradient(to right,transparent,#C9A84C,transparent);

            margin-bottom:30px;

        }

        /* LOGO */

        .brand{

            text-align:center;

            margin-bottom:30px;

        }

        .brand img{

            width:90px;

            margin-bottom:15px;

        }

        .brand h1{

            color:white;

            font-size:2.2rem;

            letter-spacing:4px;

            font-family:'Cormorant Garamond',serif;

        }

        .brand p{

            color:#C9A84C;

            font-size:.8rem;

            letter-spacing:3px;

            margin-top:6px;

            text-transform:uppercase;

        }

        /* ERRORES */

        .errors{

            background:rgba(220,38,38,.1);

            border:1px solid rgba(220,38,38,.35);

            padding:14px;

            margin-bottom:22px;

            border-radius:10px;

        }

        .errors ul{
            list-style:none;
        }

        .errors li{

            color:#ff8b8b;

            font-size:.85rem;

            margin:5px 0;

        }

        /* FIELD */

        .field{
            margin-bottom:18px;
        }

        .field label{

            display:block;

            margin-bottom:8px;

            color:rgba(255,255,255,.68);

            font-size:.75rem;

            text-transform:uppercase;

            letter-spacing:2px;

            font-weight:600;

        }

        .field input{

            width:100%;

            padding:15px;

            border:none;
            outline:none;

            border-radius:12px;

            background:rgba(255,255,255,.06);

            border:1px solid rgba(255,255,255,.08);

            color:white;

            font-size:.95rem;

            transition:.3s;

        }

        .field input:focus{

            border-color:#C9A84C;

            box-shadow:0 0 0 3px rgba(201,168,76,.12);

        }

        .field input::placeholder{
            color:rgba(255,255,255,.25);
        }

        /* BUTTON */

        .btn-register{

            width:100%;

            padding:16px;

            border:none;

            border-radius:12px;

            background:#C9A84C;

            color:black;

            font-weight:800;

            letter-spacing:2px;

            cursor:pointer;

            margin-top:12px;

            transition:.3s;

            font-size:.9rem;

        }

        .btn-register:hover{

            background:#e7c35c;

            transform:translateY(-2px);

            box-shadow:0 12px 25px rgba(201,168,76,.25);

        }

        /* LOGIN */

        .login-link{

            text-align:center;

            margin-top:28px;

            color:rgba(255,255,255,.5);

            font-size:.92rem;

        }

        .login-link a{

            color:#C9A84C;

            text-decoration:none;

            font-weight:700;

        }

        .login-link a:hover{
            color:#e7c35c;
        }

        /* SCROLL */

        body.register-page::-webkit-scrollbar{
            width:10px;
        }

        body.register-page::-webkit-scrollbar-track{
            background:#050505;
        }

        body.register-page::-webkit-scrollbar-thumb{
            background:#C9A84C;
            border-radius:20px;
        }

        /* RESPONSIVE */

        @media(max-width:600px){

            body.register-page{
                padding:20px 14px;
            }

            .register-card{

                padding:35px 22px;

                margin:20px 0;

            }

            .brand h1{
                font-size:1.8rem;
            }

        }

    </style>

</head>

<body class="register-page">

    <!-- VIDEO -->
    <video class="bg-video" autoplay muted loop playsinline>
        <source src="{{ asset('video/cholets.mp4') }}" type="video/mp4">
    </video>

    <!-- OVERLAY -->
    <div class="overlay"></div>

    <!-- CARD -->
    <div class="register-card">

        <div class="top-line"></div>

        <!-- LOGO -->
        <div class="brand">

            <img src="{{ asset('img/logo.png') }}" alt="JACHA TOURS">

            <h1>JACHA TOURS</h1>

            <p>Registro de viajeros</p>

        </div>

        <!-- ERRORES -->
        @if ($errors->any())

            <div class="errors">

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <!-- FORMULARIO -->
        <form method="POST" action="{{ route('register') }}">

            @csrf

            <!-- NOMBRE -->
            <div class="field">

                <label>Nombres</label>

                <input type="text"
                       name="nombre"
                       value="{{ old('nombre') }}"
                       placeholder="Ingresa tus nombres"
                       required>

            </div>

            <!-- APELLIDO -->
            <div class="field">

                <label>Apellidos</label>

                <input type="text"
                       name="apellido"
                       value="{{ old('apellido') }}"
                       placeholder="Ingresa tus apellidos"
                       required>

            </div>

            <!-- TELEFONO -->
            <div class="field">

                <label>Teléfono</label>

                <input type="text"
                       name="telefono"
                       value="{{ old('telefono') }}"
                       placeholder="77777777">

            </div>

            <!-- EMAIL -->
            <div class="field">

                <label>Correo electrónico</label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="correo@gmail.com"
                       required>

            </div>

            <!-- PASSWORD -->
            <div class="field">

                <label>Contraseña</label>

                <input type="password"
                       name="password"
                       placeholder="Mínimo 8 caracteres"
                       required>

            </div>

            <!-- CONFIRMAR -->
            <div class="field">

                <label>Confirmar contraseña</label>

                <input type="password"
                       name="password_confirmation"
                       placeholder="Repite tu contraseña"
                       required>

            </div>

            <!-- BOTON -->
            <button type="submit" class="btn-register">

                <i class="fas fa-user-plus"></i>

                CREAR CUENTA

            </button>

        </form>

        <!-- LOGIN -->
        <div class="login-link">

            ¿Ya tienes cuenta?

            <a href="{{ route('login') }}">
                Iniciar sesión
            </a>

        </div>

    </div>

</body>
</html>