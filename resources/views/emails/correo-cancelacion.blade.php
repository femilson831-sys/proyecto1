<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cancelación de reserva - Jacha Tours</title>
    <style>
        /* Reset */
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.13);
        }

        /* ─── HEADER ─── */
        .header {
            background: linear-gradient(160deg, #0d0d0d 0%, #1c1c1c 60%, #2a200a 100%);
            padding: 36px 30px 28px;
            text-align: center;
            position: relative;
        }

        /* Línea roja indicando cancelación */
        .header::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, transparent, #e74c3c, #ff6b6b, #e74c3c, transparent);
            position: absolute;
            top: 0; left: 0; right: 0;
        }

        .logo-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a84c 0%, #e8cc80 100%);
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 18px rgba(201,168,76,0.35);
            overflow: hidden;
            border: 3px solid #e8cc80;
            padding: 6px;
        }

        .logo-wrapper img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            display: block;
            border-radius: 50%;
        }

        .header h1 {
            color: #e8cc80;
            margin: 0 0 4px;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header p {
            color: #a0956e;
            margin: 0;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ─── CONTENT ─── */
        .content {
            padding: 36px 32px 28px;
        }

        .content h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .cancelacion-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #e74c3c;
            border-radius: 12px;
            padding: 22px 24px;
            margin: 20px 0;
            line-height: 1.8;
        }

        .cancelacion-box strong {
            color: #e74c3c;
        }

        .codigo {
            background: #1c1c1c;
            color: #e8cc80;
            padding: 12px;
            border-radius: 8px;
            font-family: monospace;
            text-align: center;
            font-size: 18px;
            letter-spacing: 2px;
            margin: 10px 0;
            border: 1px solid #c9a84c;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #c9a84c 0%, #e8cc80 100%);
            color: #0d0d0d !important;
            padding: 13px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 16px rgba(201,168,76,0.35);
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        /* ─── REDES SOCIALES ─── */
        .social-section {
            text-align: center;
            padding: 10px 0 20px;
        }

        .social-icons {
            display: inline-block;
        }

        .social-icon {
            display: inline-block;
            margin: 0 6px;
            transition: transform 0.2s ease;
        }

        .social-icon:hover {
            transform: scale(1.1);
        }

        .social-icon img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: block;
        }

        /* ─── FOOTER ─── */
        .footer {
            background: #f5f3ef;
            border-top: 1px solid #e8e2d8;
            padding: 22px 30px;
            text-align: center;
        }

        .footer-contact {
            font-size: 13px;
            color: #7a6f5a;
            margin: 0 0 8px;
        }

        .footer-contact a {
            color: #b8993a;
            text-decoration: none;
        }

        .footer-legal {
            font-size: 11px;
            color: #aaa498;
            margin: 0;
        }

        /* ─── RESPONSIVE ─── */
        @media screen and (max-width: 620px) {
            .container { border-radius: 0; box-shadow: none; }
            .content { padding: 28px 20px 22px; }
            .footer { padding: 18px 20px; }
            .header { padding: 28px 20px 22px; }
            .logo-wrapper {
                width: 80px;
                height: 80px;
            }
            .logo-wrapper img {
                width: 60px;
                height: 60px;
            }
            .social-icon img {
                width: 28px;
                height: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <div class="logo-wrapper">
                <!-- ✅ CORREGIDO: logo.png en lugar de logon.png -->
                <img src="{{ $message->embed(public_path('img/logo.png')) }}" alt="Jacha Tours" width="80" height="80">
            </div>
            <h1>Jacha Tours</h1>
            <p>Cancelación de reserva</p>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <h2>Estimado/a <strong>{{ $nombre ?? 'Cliente' }}</strong>,</h2>

            <p>Lamentamos informarte que tu reserva ha sido <strong style="color: #e74c3c;">CANCELADA</strong>.</p>

            <div class="cancelacion-box">
                @if(isset($codigo_reserva) && $codigo_reserva != 'No especificado' && $codigo_reserva)
                <div class="codigo">
                    📌 Código de reserva: {{ $codigo_reserva }}
                </div>
                @endif

                <p><strong>📅 Fecha de cancelación:</strong> {{ $fecha ?? now()->format('d/m/Y H:i') }}</p>

                <p><strong>📝 Motivo:</strong> {{ $motivo ?? 'No se especificó un motivo' }}</p>
            </div>

            <p>Si tienes alguna consulta o deseas realizar una nueva reserva, no dudes en contactarnos.</p>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ url('/') }}" class="btn">🌍 Hacer una nueva reserva</a>
            </div>
        </div>

        <!-- REDES SOCIALES -->
        <div class="social-section">
            <div class="social-icons">
                <!-- Facebook -->
                <a href="https://www.facebook.com/share/1L4gjr4JXd/" class="social-icon" target="_blank" rel="noopener noreferrer">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg" alt="Facebook" style="background: #1877F2; border-radius: 50%; padding: 6px;">
                </a>
                <!-- Instagram -->
                <a href="https://www.instagram.com/jachatoursbolivia?igsh=cHBnN2NhZTNuYXl2" class="social-icon" target="_blank" rel="noopener noreferrer">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/instagram.svg" alt="Instagram" style="background: #E4405F; border-radius: 50%; padding: 6px;">
                </a>
                <!-- TikTok -->
                <a href="https://www.tiktok.com/@user7332490373917?_r=1&_t=ZS-9756PEr9pW0" class="social-icon" target="_blank" rel="noopener noreferrer">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/tiktok.svg" alt="TikTok" style="background: #000000; border-radius: 50%; padding: 6px;">
                </a>
                <!-- YouTube -->
                <a href="https://www.youtube.com/c/jachatours" class="social-icon" target="_blank" rel="noopener noreferrer">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/youtube.svg" alt="YouTube" style="background: #FF0000; border-radius: 50%; padding: 6px;">
                </a>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p class="footer-contact">
                📞 <a href="tel:+59169375465">+591 69375465</a>
                <span style="margin: 0 6px; opacity: 0.5;">·</span>
                ✉️ <a href="mailto:jachatours936@gmail.com">jachatours936@gmail.com</a>
            </p>
            <p class="footer-legal">
                &copy; {{ date('Y') }} Jacha Tours — Todos los derechos reservados.
                <br>La Paz, Bolivia
            </p>
        </div>

    </div>
</body>
</html>