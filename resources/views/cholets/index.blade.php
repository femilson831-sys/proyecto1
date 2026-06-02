<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>JACHA TOURS</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>

        body{
            margin:0;
            font-family:Arial;
            background:#0f0f0f;
            color:white;
        }

        header{
            padding:30px;
            text-align:center;
            background:black;
        }

        header h1{
            color:#c9a84c;
            font-size:3rem;
        }

        .contenedor{
            width:90%;
            margin:auto;
            padding:40px 0;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
            gap:30px;
        }

        .card{
            background:#1b1b1b;
            border-radius:20px;
            overflow:hidden;
            transition:.3s;
        }

        .card:hover{
            transform:translateY(-5px);
        }

        .card img{
            width:100%;
            height:260px;
            object-fit:cover;
        }

        .contenido{
            padding:20px;
        }

        .contenido h2{
            margin-bottom:10px;
            color:#c9a84c;
        }

        .ubicacion{
            margin-bottom:20px;
            color:#ccc;
        }

        .btn{
            display:inline-block;
            background:#c9a84c;
            color:black;
            padding:14px 22px;
            border-radius:12px;
            text-decoration:none;
            font-weight:bold;
        }

        .btn:hover{
            opacity:.9;
        }

    </style>

</head>

<body>

<header>

    <h1>
        <i class="fas fa-crown"></i>
        JACHA TOURS
    </h1>

</header>

<div class="contenedor">

    <div class="grid">

        @foreach($cholets as $cholet)

        <div class="card">

            <img src="{{ asset('img/' . $cholet->imagen) }}">

            <div class="contenido">

                <h2>{{ $cholet->nombre }}</h2>

                <div class="ubicacion">

                    <i class="fas fa-location-dot"></i>

                    {{ $cholet->ubicacion }}

                </div>

                <a href="{{ route('cholets.show', $cholet->id) }}"
                   class="btn">

                    Ver Experiencia

                </a>

            </div>

        </div>

        @endforeach

    </div>

</div>

</body>
</html>