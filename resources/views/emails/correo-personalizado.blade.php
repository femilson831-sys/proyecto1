<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $asunto ?? 'Mensaje de Jacha Tours' }}</title>
    <style>
        /* Reset */
        *, *::before, *::after { box-sizing: border-box; }
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }

        body {
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #2c2c2c;
            background-color: #eae8e3;
            margin: 0;
            padding: 0;
        }

        /* Wrapper */
        .email-wrapper {
            width: 100%;
            background-color: #eae8e3;
            padding: 32px 16px;
        }

        /* Card container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.13);
        }

        /* ─── HEADER ─── */
        .header {
            background: linear-gradient(160deg, #0d0d0d 0%, #1c1c1c 60%, #2a200a 100%);
            padding: 36px 30px 28px;
            text-align: center;
            position: relative;
        }

        /* Gold accent line at top */
        .header::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, transparent, #c9a84c, #e8cc80, #c9a84c, transparent);
            position: absolute;
            top: 0; left: 0; right: 0;
        }

        /* LOGO - MÁS GRANDE Y VISIBLE */
        .logo-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c9a84c 0%, #e8cc80 100%);
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 30px rgba(201,168,76,0.5);
            overflow: hidden;
            border: 3px solid #e8cc80;
            padding: 8px;
        }

        .logo-wrapper img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            display: block;
            border-radius: 50%;
        }

        /* Fallback icon when no logo image */
        .logo-fallback {
            font-size: 50px;
            line-height: 1;
        }

        .header-brand {
            color: #e8cc80;
            margin: 0 0 4px;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-tagline {
            color: #a0956e;
            margin: 0;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* ─── BODY ─── */
        .content {
            padding: 36px 32px 28px;
        }

        .greeting {
            font-size: 17px;
            color: #1c1c1c;
            margin: 0 0 20px;
        }

        .greeting strong {
            color: #0d0d0d;
        }

        /* Message block */
        .mensaje {
            background: #faf8f4;
            border: 1px solid #ede9df;
            border-left: 4px solid #c9a84c;
            border-radius: 12px;
            padding: 22px 24px;
            margin: 0 0 28px;
            font-size: 15px;
            line-height: 1.85;
            color: #3a3a3a;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #ddd6c8, transparent);
            margin: 0 0 28px;
        }

        /* CTA */
        .cta-wrapper {
            text-align: center;
            margin-bottom: 8px;
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201,168,76,0.45);
        }

        /* ─── SOCIAL MEDIA SECTION ─── */
        .social-section {
            text-align: center;
            padding: 10px 0 15px;
        }

        .social-title {
            font-size: 13px;
            color: #7a6f5a;
            margin-bottom: 12px;
            font-weight: 400;
            letter-spacing: 1px;
        }

        .social-icons {
            display: inline-block;
            margin: 0 auto;
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
            letter-spacing: 0.2px;
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

        .footer-separator {
            display: inline-block;
            margin: 0 6px;
            opacity: 0.5;
        }

        /* ─── RESPONSIVE ─── */
        @media screen and (max-width: 620px) {
            .email-wrapper { padding: 0; }
            .container { border-radius: 0; box-shadow: none; }
            .content { padding: 28px 20px 22px; }
            .footer { padding: 18px 20px; }
            .header { padding: 28px 20px 22px; }
            .logo-wrapper {
                width: 90px;
                height: 90px;
            }
            .logo-wrapper img {
                width: 70px;
                height: 70px;
            }
            .logo-fallback {
                font-size: 36px;
            }
            .social-icon img {
                width: 28px;
                height: 28px;
            }
        }

        @media (prefers-color-scheme: dark) {
            body, .email-wrapper { background-color: #1a1a1a !important; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">

            {{-- ─── HEADER ─── --}}
            <div class="header">
                <div class="logo-wrapper">
                    @if(file_exists(public_path('img/logo.png')))
                        <img src="{{ $message->embed(public_path('img/logo.png')) }}"
                             alt="Jacha Tours"
                             width="100"
                             height="100">
                    @else
                        <span class="logo-fallback">🏔️</span>
                    @endif
                </div>
                <h1 class="header-brand">Jacha Tours</h1>
                <p class="header-tagline">Experiencias únicas en Bolivia</p>
            </div>

            {{-- ─── CONTENT ─── --}}
            <div class="content">

                <p class="greeting">
                    Estimado/a <strong>{{ $nombre ?? 'Cliente' }}</strong>,
                </p>

                <div class="mensaje">
                    {!! nl2br(e($mensaje ?? 'Gracias por comunicarte con nosotros. Estamos aquí para ayudarte a descubrir lo mejor de Bolivia.')) !!}
                </div>

                <div class="divider"></div>

                <div class="cta-wrapper">
                    <a href="{{ url('/') }}" class="btn">
                        🌍&nbsp; Visitar nuestro sitio web
                    </a>
                </div>

            </div>

            {{-- ─── SOCIAL MEDIA ─── --}}
            <div class="social-section">
                <p class="social-title">Síguenos en redes sociales</p>
                <div class="social-icons">

                    {{-- FACEBOOK --}}
                    <a href="https://www.facebook.com/share/1L4gjr4JXd/" class="social-icon" target="_blank" rel="noopener noreferrer">
                        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg"
                             alt="Facebook"
                             width="32"
                             height="32"
                             style="background: #1877F2; border-radius: 50%; padding: 6px;">
                    </a>

                    {{-- INSTAGRAM --}}
                    <a href="https://www.instagram.com/jachatoursbolivia?igsh=cHBnN2NhZTNuYXl2" class="social-icon" target="_blank" rel="noopener noreferrer">
                        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/instagram.svg"
                             alt="Instagram"
                             width="32"
                             height="32"
                             style="background: #E4405F; border-radius: 50%; padding: 6px;">
                    </a>

                    {{-- TIKTOK --}}
                    <a href="https://www.tiktok.com/@user7332490373917?_r=1&_t=ZS-9756PEr9pW0" class="social-icon" target="_blank" rel="noopener noreferrer">
                        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/tiktok.svg"
                             alt="TikTok"
                             width="32"
                             height="32"
                             style="background: #000000; border-radius: 50%; padding: 6px;">
                    </a>

                    {{-- YOUTUBE --}}
                    <a href="https://www.youtube.com/c/jachatours" class="social-icon" target="_blank" rel="noopener noreferrer">
                        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/youtube.svg"
                             alt="YouTube"
                             width="32"
                             height="32"
                             style="background: #FF0000; border-radius: 50%; padding: 6px;">
                    </a>

                </div>
            </div>

            {{-- ─── FOOTER ─── --}}
            <div class="footer">
                <p class="footer-contact">
                    📞 <a href="tel:+59169375465">+591 69375465</a>
                    <span class="footer-separator">·</span>
                    ✉️ <a href="mailto:jachatours936@gmail.com">jachatours936@gmail.com</a>
                </p>
                <p class="footer-legal">
                    &copy; {{ date('Y') }} Jacha Tours — Todos los derechos reservados.
                    <br>La Paz, Bolivia
                </p>
            </div>

        </div>
    </div>
</body>
</html>