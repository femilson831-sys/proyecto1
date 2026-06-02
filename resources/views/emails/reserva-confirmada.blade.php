<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva Confirmada - Jacha Tours</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0f0e0c;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #1a1814;
            border: 1px solid #2e2b25;
            border-radius: 12px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #c9a84c 0%, #e8c97a 100%);
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #0f0e0c;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #0f0e0c;
            opacity: 0.8;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #e8e0d0;
            margin-bottom: 20px;
        }
        .message {
            color: #c8bfae;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .code-box {
            background: #141210;
            border: 1px solid #c9a84c;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .code-box span {
            font-family: monospace;
            font-size: 20px;
            letter-spacing: 2px;
            color: #c9a84c;
            font-weight: bold;
        }
        .details {
            background: #141210;
            border: 1px solid #2e2b25;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #2e2b25;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #7a7060;
            font-size: 12px;
            text-transform: uppercase;
        }
        .detail-value {
            color: #c9a84c;
            font-weight: bold;
        }
        .total {
            font-size: 18px;
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #c9a84c;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #5a4f30;
            font-size: 11px;
            border-top: 1px solid #2e2b25;
        }
        .btn {
            display: inline-block;
            background: #c9a84c;
            color: #0f0e0c;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: bold;
        }
        .whatsapp {
            color: #25D366;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="container">
            <div class="header">
                <h1>✨ JACHA TOURS ✨</h1>
                <p>Experiencias únicas en El Alto</p>
            </div>

            <div class="content">
                <div class="greeting">
                    ¡Hola <strong>{{ $reserva->user->name ?? 'Viajero' }}</strong>!
                </div>

                <div class="message">
                    Tu reserva ha sido <strong style="color: #4ade80;">CONFIRMADA</strong> exitosamente. 
                    ¡Estamos emocionados de que vivas esta experiencia única!
                </div>

                <div class="code-box">
                    📋 <strong>Código de reserva:</strong><br>
                    <span>{{ $reserva->codigo_reserva }}</span>
                </div>

                <div class="details">
                    <div class="detail-row">
                        <span class="detail-label">🏠 Cholet</span>
                        <span class="detail-value">{{ $reserva->cholet->nombre ?? 'No especificado' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">📍 Ubicación</span>
                        <span class="detail-value">{{ $reserva->cholet->ubicacion ?? 'El Alto, Bolivia' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">📅 Fecha</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">⏰ Hora</span>
                        <span class="detail-value">{{ $reserva->hora_reserva ? \Carbon\Carbon::parse($reserva->hora_reserva)->format('h:i A') : '10:00 AM' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">🎫 Experiencia</span>
                        <span class="detail-value">{{ $reserva->experiencia }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">👥 Personas</span>
                        <span class="detail-value">{{ $reserva->personas }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">📞 Contacto</span>
                        <span class="detail-value">{{ $reserva->telefono }}</span>
                    </div>
                </div>

                <div class="total">
                    💰 Total pagado: <strong>${{ number_format($reserva->precio_total, 2) }} USD</strong>
                </div>

                <div style="text-align: center;">
                    <a href="{{ url('/mis-reservas') }}" class="btn">
                        📋 Ver mis reservas
                    </a>
                </div>

                <div style="margin-top: 25px; padding: 15px; background: #0f0e0c; border-radius: 8px;">
                    <p style="margin: 0 0 10px; color: #c9a84c; font-size: 12px;">📌 RECOMENDACIONES:</p>
                    <ul style="margin: 0; padding-left: 20px; color: #7a7060; font-size: 12px;">
                        <li>Llegar 15 minutos antes del horario</li>
                        <li>Llevar tu cédula de identidad</li>
                        <li>Mostrar este correo o el código al llegar</li>
                        <li>Para cambios o consultas, contáctanos al WhatsApp</li>
                    </ul>
                </div>
            </div>

            <div class="footer">
                <p>JACHA TOURS - Circuito de Cholets</p>
                <p>📞 <a href="https://wa.me/59167102033" class="whatsapp">+591 69375465</a></p>
                <p>&copy; {{ date('Y') }} Todos los derechos reservados</p>
            </div>
        </div>
    </div>
</body>
</html>