<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reserva Confirmada | JACHA TOURS</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e4c46c;
            --gold-dark: #a8893a;
            --dark: #0a0a0a;
            --dark-card: #111111;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .container {
            max-width: 650px;
            width: 100%;
            margin: 0 auto;
            background: var(--dark-card);
            border-radius: 28px;
            border: 1px solid rgba(201,168,76,0.2);
            overflow: hidden;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success-header {
            background: linear-gradient(135deg, #0a2e1f, #064e3b);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }
        
        .success-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
            animation: scaleIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .success-icon i { font-size: 2.8rem; color: white; }
        .success-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--gold);
            font-weight: 600;
        }
        .success-header p { color: rgba(255,255,255,0.7); font-size: 0.9rem; }
        
        .reservation-code {
            background: rgba(201,168,76,0.08);
            padding: 1.2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .code-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--gold);
            font-weight: 500;
        }
        .code-value {
            font-size: 1.6rem;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            margin-top: 0.3rem;
            color: var(--gold-light);
        }
        
        .details { padding: 1.8rem; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .detail-label {
            color: #999;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }
        .detail-label i { color: var(--gold); width: 22px; font-size: 1rem; }
        .detail-value { font-weight: 500; color: #fff; }
        
        .total-row {
            background: rgba(201,168,76,0.08);
            margin: 1rem -1.8rem -1.8rem -1.8rem;
            padding: 1rem 1.8rem;
            border-radius: 16px;
            margin-top: 1rem;
        }
        .total-row .detail-label { font-weight: 700; color: var(--gold); }
        .total-row .detail-label i { color: var(--gold); }
        .total-row .detail-value { font-size: 1.3rem; color: var(--gold); font-weight: 800; }
        
        .payment-section {
            padding: 1.8rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.2);
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gold);
        }
        .payment-options { display: flex; gap: 1rem; margin-bottom: 1.5rem; }
        .payment-btn {
            flex: 1;
            background: rgba(255,255,255,0.03);
            border: 2px solid rgba(255,255,255,0.08);
            padding: 1rem;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            color: #fff;
        }
        .payment-btn.active {
            border-color: var(--gold);
            background: rgba(201,168,76,0.1);
        }
        .payment-btn i { font-size: 1.5rem; display: block; margin-bottom: 8px; }
        .payment-btn small { font-size: 0.7rem; color: #999; display: block; margin-top: 5px; }
        
        .qr-container {
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 20px;
            display: none;
            animation: fadeSlide 0.4s ease;
        }
        .qr-container.active { display: block; }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .qr-container img {
            width: 220px;
            height: 220px;
            margin: 0 auto;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .account-container {
            background: rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 1.2rem;
            display: none;
        }
        .account-container.active { display: block; animation: fadeSlide 0.4s ease; }
        .account-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-wrap: wrap;
            gap: 10px;
        }
        .account-label { color: var(--gold); font-weight: 500; font-size: 0.85rem; }
        .account-value {
            font-family: monospace;
            font-size: 0.9rem;
            word-break: break-all;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .copy-btn {
            background: var(--gold);
            color: #000;
            border: none;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 600;
        }
        .copy-btn:hover { background: var(--gold-light); transform: scale(1.05); }
        
        .btn-pay {
            width: 100%;
            background: linear-gradient(135deg, #075e54, #128c7e);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 1.5rem;
        }
        .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(18,140,126,0.3); }
        .btn-cancel {
            width: 100%;
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid var(--danger);
            padding: 0.9rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
        }
        .btn-cancel:hover { background: var(--danger); color: white; transform: translateY(-2px); }
        
        .payment-status {
            margin: 1rem 1.8rem;
            padding: 1rem;
            border-radius: 16px;
            text-align: center;
            display: none;
            animation: fadeSlide 0.4s ease;
        }
        .payment-status.success { display: block; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: var(--success); }
        .payment-status.cancelled { display: block; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--danger); }
        .payment-status.pending { display: block; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: var(--warning); }
        
        .actions {
            display: flex;
            gap: 1rem;
            padding: 1.5rem 1.8rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            background: rgba(0,0,0,0.3);
        }
        .btn-secondary {
            flex: 1;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.8rem;
            border-radius: 50px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            font-weight: 500;
        }
        .btn-secondary:hover { border-color: var(--gold); color: var(--gold); transform: translateY(-2px); }
        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            text-decoration: none;
            padding: 0.8rem;
            border-radius: 50px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(201,168,76,0.3); }
        
        .toast-notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1a1a1a;
            border-left: 4px solid var(--success);
            padding: 1rem 1.5rem;
            border-radius: 12px;
            z-index: 9999;
            animation: slideInRight 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        @media (max-width: 550px) {
            body { padding: 1rem; }
            .payment-options { flex-direction: column; }
            .actions { flex-direction: column; }
            .account-row { flex-direction: column; align-items: flex-start; }
            .account-value { text-align: left; justify-content: flex-start; }
            .success-header { padding: 1.8rem 1.5rem; }
            .details { padding: 1.2rem; }
            .total-row { margin: 1rem -1.2rem -1.2rem -1.2rem; padding: 1rem 1.2rem; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="success-header">
        <div class="success-icon"><i class="fas fa-check"></i></div>
        <h1>¡Reserva Creada!</h1>
        <p>Tu reserva está pendiente de confirmación. Realiza el pago para asegurar tu experiencia.</p>
    </div>
    
    <div class="reservation-code">
        <div class="code-label"><i class="fas fa-ticket-alt"></i> CÓDIGO DE RESERVA</div>
        <div class="code-value">{{ $reserva->codigo_reserva }}</div>
    </div>
    
    <div class="details">
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-building"></i> Cholet:</div>
            <div class="detail-value">{{ $reserva->cholet->nombre }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-calendar"></i> Fecha:</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</div>
        </div>
        @if($reserva->hora_reserva)
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-clock"></i> Hora:</div>
            <div class="detail-value">{{ \Carbon\Carbon::parse($reserva->hora_reserva)->format('h:i A') }}</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-crown"></i> Experiencia:</div>
            <div class="detail-value">{{ $reserva->experiencia }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label"><i class="fas fa-users"></i> Personas:</div>
            <div class="detail-value">{{ $reserva->personas }}</div>
        </div>
        <div class="detail-row total-row">
            <div class="detail-label"><i class="fas fa-dollar-sign"></i> Total a pagar:</div>
            <div class="detail-value">
                @php
                    $precios = ['Blue' => 49, 'Gold' => 89, 'Platinum' => 149];
                    $totalUSD = ($precios[$reserva->experiencia] ?? 49) * $reserva->personas;
                    $totalBOB = $totalUSD * 6.96;
                @endphp
                Bs {{ number_format($totalBOB, 0) }}
                <span style="font-size: 0.75rem; color: #999;">(USD ${{ number_format($totalUSD, 2) }})</span>
            </div>
        </div>
    </div>
    
    <div id="payment-status" class="payment-status" style="display: none;"></div>
    
    <div id="payment-section">
        <div class="payment-section">
            <div class="section-title"><i class="fas fa-credit-card"></i> Selecciona método de pago</div>
            
            <div class="payment-options">
                <div class="payment-btn active" data-method="qr"><i class="fas fa-qrcode"></i> Código QR<small>Escanear y pagar</small></div>
                <div class="payment-btn" data-method="account"><i class="fas fa-building-columns"></i> Datos Bancarios<small>Transferencia</small></div>
            </div>
            
            <div id="qr-container" class="qr-container active">
                <img src="{{ asset('img/qr.png') }}" alt="Código QR para pago JACHA TOURS">
                <p style="margin-top: 1rem; color: #666; font-size: 0.8rem;">
                    <i class="fas fa-mobile-alt"></i> Escanea el QR con tu banca móvil<br>
                    <strong style="color: var(--gold);">Referencia: {{ $reserva->codigo_reserva }}</strong>
                </p>
            </div>
            
            <div id="account-container" class="account-container">
                <div class="account-row">
                    <span class="account-label"><i class="fas fa-university"></i> Banco:</span>
                    <div class="account-value">BNB (Banco Nacional de Bolivia)<button class="copy-btn" onclick="copiarTexto('BNB')">Copiar</button></div>
                </div>
                <div class="account-row">
                    <span class="account-label"><i class="fas fa-credit-card"></i> Número de Cuenta:</span>
                    <div class="account-value">1505626474<button class="copy-btn" onclick="copiarTexto('1505626474')">Copiar</button></div>
                </div>
                <div class="account-row">
                    <span class="account-label"><i class="fas fa-user"></i> Titular:</span>
                    <div class="account-value">EMILSON EDSON FLORES CHAMBI<button class="copy-btn" onclick="copiarTexto('EMILSON EDSON FLORES CHAMBI')">Copiar</button></div>
                </div>
                <div class="account-row">
                    <span class="account-label"><i class="fas fa-hashtag"></i> Referencia:</span>
                    <div class="account-value"><strong style="color: var(--gold);">{{ $reserva->codigo_reserva }}</strong><button class="copy-btn" onclick="copiarTexto('{{ $reserva->codigo_reserva }}')">Copiar</button></div>
                </div>
                <div class="account-row">
                    <span class="account-label"><i class="fas fa-dollar-sign"></i> Monto a pagar:</span>
                    <div class="account-value"><strong style="color: var(--gold); font-size: 1.1rem;">Bs {{ number_format($totalBOB, 0) }}</strong><button class="copy-btn" onclick="copiarTexto('{{ number_format($totalBOB, 0) }}')">Copiar</button></div>
                </div>
                <div style="background: rgba(201,168,76,0.08); padding: 0.8rem; border-radius: 12px; margin-top: 1rem;">
                    <p style="font-size: 0.7rem; color: #999; text-align: center;"><i class="fas fa-info-circle"></i> Importante: Usa el código de reserva como referencia de pago</p>
                </div>
            </div>
            
            <button class="btn-pay" onclick="enviarWhatsApp()"><i class="fab fa-whatsapp"></i> Ya realicé el pago</button>
            <button class="btn-cancel" onclick="cancelarReserva()"><i class="fas fa-times-circle"></i> Cancelar Reserva</button>
        </div>
    </div>
    
    <div class="actions">
        <a href="{{ url('/') }}" class="btn-secondary"><i class="fas fa-home"></i> Volver al Inicio</a>
        <a href="{{ route('reservas.mis-reservas') }}" class="btn-primary"><i class="fas fa-calendar-alt"></i> Mis Reservas</a>
    </div>
</div>

<script>
    let selectedMethod = 'qr';
    
    document.querySelectorAll('.payment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selectedMethod = this.dataset.method;
            
            if (selectedMethod === 'qr') {
                document.getElementById('qr-container').classList.add('active');
                document.getElementById('account-container').classList.remove('active');
            } else {
                document.getElementById('qr-container').classList.remove('active');
                document.getElementById('account-container').classList.add('active');
            }
        });
    });
    
    function copiarTexto(texto) {
        navigator.clipboard.writeText(texto).then(() => {
            mostrarNotificacion('¡Copiado!', `${texto} copiado al portapapeles`, 'success');
        }).catch(() => {
            mostrarNotificacion('Error', 'No se pudo copiar el texto', 'error');
        });
    }
    
    function mostrarNotificacion(titulo, mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        let icon = 'fa-check-circle';
        let borderColor = '#10b981';
        if (tipo === 'error') { icon = 'fa-exclamation-circle'; borderColor = '#ef4444'; }
        else if (tipo === 'warning') { icon = 'fa-exclamation-triangle'; borderColor = '#f59e0b'; }
        toast.style.borderLeftColor = borderColor;
        toast.innerHTML = `<i class="fas ${icon}" style="font-size: 1.2rem;"></i><div><strong style="display: block; font-size: 0.85rem;">${titulo}</strong><span style="font-size: 0.75rem;">${mensaje}</span></div>`;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.animation = 'slideOutRight 0.4s ease'; setTimeout(() => toast.remove(), 400); }, 3500);
    }
    
    const adminPhone = "59169375465";
    
    function enviarWhatsApp() {
        const precios = {'Blue': 49, 'Gold': 89, 'Platinum': 149};
        const totalUSD = (precios["{{ $reserva->experiencia }}"] || 49) * {{ $reserva->personas }};
        const totalBOB = totalUSD * 6.96;
        
        const mensaje = `🏛️ *NUEVO PAGO REPORTADO* 🏛️
        
┌─────────────────────────────────
│ *CÓDIGO:* {{ $reserva->codigo_reserva }}
│ *CLIENTE:* {{ auth()->user()->nombre ?? 'Cliente' }}
│ *EMAIL:* {{ auth()->user()->email ?? 'No disponible' }}
│ *TELÉFONO:* {{ $reserva->telefono ?? 'No registrado' }}
├─────────────────────────────────
│ *CHOLET:* {{ addslashes($reserva->cholet->nombre) }}
│ *FECHA:* {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}
│ *EXPERIENCIA:* {{ $reserva->experiencia }}
│ *PERSONAS:* {{ $reserva->personas }}
│ *TOTAL:* Bs ${totalBOB} (USD $${totalUSD})
└─────────────────────────────────

✅ *El cliente reporta haber realizado el pago*
📌 *Verificar transferencia en BNB - Cuenta: 1505626474*
🔑 *Referencia: {{ $reserva->codigo_reserva }}*

---
📱 JACHA TOURS - Sistema de Reservas`;
        
        const mensajeCodificado = encodeURIComponent(mensaje);
        window.open(`https://wa.me/${adminPhone}?text=${mensajeCodificado}`, '_blank');
        
        document.getElementById('payment-section').style.display = 'none';
        const statusDiv = document.getElementById('payment-status');
        statusDiv.style.display = 'block';
        statusDiv.className = 'payment-status pending';
        statusDiv.innerHTML = `<i class="fas fa-clock" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i><strong>¡Pago reportado!</strong><br>Hemos notificado al administrador. En breve recibirás la confirmación.`;
        mostrarNotificacion('¡Gracias por tu pago!', 'Hemos notificado al administrador', 'success');
    }
    
    function cancelarReserva() {
        if (confirm('¿Estás seguro de que deseas cancelar esta reserva? Esta acción no se puede deshacer.')) {
            fetch('{{ route("reservas.cancelar", $reserva->id) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('payment-section').style.display = 'none';
                    const statusDiv = document.getElementById('payment-status');
                    statusDiv.style.display = 'block';
                    statusDiv.className = 'payment-status cancelled';
                    statusDiv.innerHTML = `<i class="fas fa-times-circle" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i><strong>Reserva Cancelada</strong><br>Tu reserva ha sido cancelada exitosamente.`;
                    mostrarNotificacion('Reserva cancelada', 'Tu reserva ha sido cancelada', 'warning');
                    setTimeout(() => { window.location.href = '{{ url("/") }}'; }, 3000);
                }
            })
            .catch(err => { mostrarNotificacion('Error', 'No se pudo cancelar la reserva', 'error'); });
        }
    }
    
    @if(session('success'))
        mostrarNotificacion('Éxito', '{{ session('success') }}', 'success');
    @endif
</script>
</body>
</html>