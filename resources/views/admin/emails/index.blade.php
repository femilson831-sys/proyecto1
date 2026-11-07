@extends('admin.layouts.app')

@section('title', 'Correos')
@section('page-title', 'Envío de Correos')

@section('content')
<style>
    .emails-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Stats Grid Mejorado */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, var(--surface) 0%, var(--deep) 100%);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, var(--gold), var(--gold2));
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        border-color: var(--gold-dim);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .stat-icon {
        font-size: 2rem;
        color: var(--gold);
        margin-bottom: 0.5rem;
    }
    
    .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--gold2);
        font-family: 'Playfair Display', serif;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.7rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.3rem;
    }
    
    /* Email Cards */
    .email-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .email-card:hover {
        border-color: var(--gold-dim);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }
    
    .email-header {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, rgba(0,0,0,0.3), rgba(0,0,0,0.1));
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .email-header i {
        font-size: 1.3rem;
        color: var(--gold);
    }
    
    .email-header h3 {
        margin: 0;
        color: var(--gold2);
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .email-body {
        padding: 1.5rem;
    }
    
    /* Formulario Mejorado */
    .form-group {
        margin-bottom: 1.2rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--gold-d);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-group label i {
        margin-right: 0.3rem;
        font-size: 0.8rem;
    }
    
    .form-group input, 
    .form-group select, 
    .form-group textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        background: var(--deep);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .form-group input:focus, 
    .form-group select:focus, 
    .form-group textarea:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.1);
    }
    
    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    /* Botones */
    .btn-send {
        background: linear-gradient(135deg, #3d2e10, #7a5f28);
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .btn-send:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #7a5f28, #c9a84c);
        box-shadow: 0 5px 15px rgba(201, 168, 76, 0.3);
    }
    
    .btn-personal {
        background: linear-gradient(135deg, #2980b9, #3498db);
    }
    
    .btn-personal:hover {
        background: linear-gradient(135deg, #3498db, #5dade2);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
    }
    
    .btn-cancelacion {
        background: linear-gradient(135deg, #c0392b, #e74c3c);
    }
    
    .btn-cancelacion:hover {
        background: linear-gradient(135deg, #e74c3c, #ec7063);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
    }
    
    /* Alertas */
    .alert {
        background: linear-gradient(135deg, rgba(39, 174, 96, 0.1), rgba(39, 174, 96, 0.05));
        border: 1px solid #27ae60;
        border-radius: 12px;
        padding: 1rem 1.2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideIn 0.3s ease;
    }
    
    .alert i {
        font-size: 1.3rem;
        color: #27ae60;
    }
    
    .alert-error {
        background: linear-gradient(135deg, rgba(192, 57, 43, 0.1), rgba(192, 57, 43, 0.05));
        border-color: #c0392b;
    }
    
    .alert-error i {
        color: #e74c3c;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, rgba(201, 168, 76, 0.08), rgba(201, 168, 76, 0.03));
        border-left: 3px solid var(--gold);
        border-radius: 10px;
        padding: 0.8rem 1rem;
        margin-top: 1rem;
        font-size: 0.75rem;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-box i {
        color: var(--gold);
        font-size: 1rem;
    }
    
    hr {
        border: none;
        border-top: 1px solid var(--border);
        margin: 1rem 0;
    }
    
    /* Grid de 2 columnas para secciones */
    .two-columns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            gap: 1rem;
        }
        .two-columns {
            grid-template-columns: 1fr;
        }
        .email-header {
            flex-direction: column;
            text-align: center;
        }
        .stat-number {
            font-size: 1.8rem;
        }
    }
</style>

<div class="emails-container">
    @if(session('success'))
        <div class="alert">
            <i class="ti ti-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error">
            <i class="ti ti-alert-circle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    
    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="ti ti-users"></i>
            </div>
            <div class="stat-number">{{ $totalUsuarios }}</div>
            <div class="stat-label">Usuarios Registrados</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="ti ti-user-check"></i>
            </div>
            <div class="stat-number">{{ $totalClientes }}</div>
            <div class="stat-label">Clientes Activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="ti ti-calendar-check"></i>
            </div>
            <div class="stat-number">{{ $totalReservas }}</div>
            <div class="stat-label">Reservas Realizadas</div>
        </div>
    </div>
    
    <!-- SECCIONES DE 2 COLUMNAS -->
    <div class="two-columns">
        
        <!-- Envío Personalizado -->
        <div class="email-card">
            <div class="email-header">
                <i class="ti ti-user"></i>
                <h3>✉️ Correo Personalizado</h3>
            </div>
            <div class="email-body">
                <form method="POST" action="{{ route('admin.emails.enviar-personal') }}" class="email-form">
                    @csrf
                    <div class="form-group">
                        <label><i class="ti ti-mail"></i> Correo electrónico</label>
                        <input type="email" name="email_destino" placeholder="ejemplo@correo.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="ti ti-user"></i> Nombre del destinatario</label>
                        <input type="text" name="nombre_destino" placeholder="Nombre del cliente" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="ti ti-edit"></i> Asunto</label>
                        <input type="text" name="asunto_personal" placeholder="Asunto del correo" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="ti ti-message"></i> Mensaje personalizado</label>
                        <textarea name="mensaje_personal" placeholder="Escribe tu mensaje personalizado aquí..." required></textarea>
                    </div>
                    
                    <div class="info-box">
                        <i class="ti ti-info-circle"></i>
                        <span>Envía un correo personalizado a un destinatario específico.</span>
                    </div>
                    
                    <button type="submit" class="btn-send btn-personal">
                        <i class="ti ti-send"></i> Enviar correo personalizado
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Correo de Cancelación -->
        <div class="email-card">
            <div class="email-header">
                <i class="ti ti-circle-x"></i>
                <h3>📧 Correo de Cancelación</h3>
            </div>
            <div class="email-body">
                <form method="POST" action="{{ route('admin.emails.enviar-cancelacion') }}" class="email-form">
                    @csrf
                    <div class="form-group">
                        <label><i class="ti ti-mail"></i> Correo electrónico</label>
                        <input type="email" name="email_cancelacion" placeholder="ejemplo@correo.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="ti ti-user"></i> Nombre del cliente</label>
                        <input type="text" name="nombre_cancelacion" placeholder="Nombre del cliente" required>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="ti ti-barcode"></i> Código de reserva</label>
                        <input type="text" name="codigo_reserva" placeholder="Ej: JACHA-XXXXXX">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="ti ti-message"></i> Motivo de cancelación (opcional)</label>
                        <textarea name="motivo_cancelacion" placeholder="Explica el motivo de la cancelación..."></textarea>
                    </div>
                    
                    <div class="info-box">
                        <i class="ti ti-info-circle"></i>
                        <span>Envía un correo de cancelación con plantilla prediseñada.</span>
                    </div>
                    
                    <button type="submit" class="btn-send btn-cancelacion">
                        <i class="ti ti-send"></i> Enviar cancelación
                    </button>
                </form>
            </div>
        </div>
        
    </div>
    
</div>

<!-- ─── MODAL DE CONFIRMACIÓN PERSONALIZADO ─── -->
<div id="confirmModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon">✉️</div>
        <div class="modal-title">Confirmar envío</div>
        <div class="modal-message">
            ¿Estás seguro de que deseas enviar este correo electrónico?
            <br><br>
            Esta acción enviará el mensaje al destinatario especificado.
        </div>
        <div class="modal-buttons">
            <button id="modalCancel" class="modal-btn modal-btn-cancel">Cancelar</button>
            <button id="modalConfirm" class="modal-btn modal-btn-confirm">Enviar</button>
        </div>
    </div>
</div>

<style>
/* ─── MODAL PERSONALIZADO ─── */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(6px);
    z-index: 999999;
    justify-content: center;
    align-items: center;
}

.modal-overlay.active {
    display: flex;
}

.modal-box {
    background: #1a1a1a;
    border: 1px solid #c9a84c;
    border-radius: 20px;
    padding: 35px 30px 30px;
    max-width: 440px;
    width: 92%;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
    text-align: center;
    animation: modalIn 0.3s ease;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.modal-icon {
    font-size: 52px;
    margin-bottom: 12px;
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #e8cc80;
    margin-bottom: 8px;
    font-family: 'Playfair Display', serif;
}

.modal-message {
    font-size: 14px;
    color: #b0b0b0;
    margin-bottom: 22px;
    line-height: 1.7;
}

.modal-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.modal-btn {
    padding: 10px 28px;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    min-width: 100px;
}

.modal-btn-cancel {
    background: #2a2a2a;
    color: #ccc;
    border: 1px solid #444;
}

.modal-btn-cancel:hover {
    background: #333;
    color: #fff;
}

.modal-btn-confirm {
    background: linear-gradient(135deg, #c9a84c, #e8cc80);
    color: #0d0d0d;
    font-weight: 700;
}

.modal-btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(201, 168, 76, 0.35);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('confirmModal');
    const modalConfirm = document.getElementById('modalConfirm');
    const modalCancel = document.getElementById('modalCancel');
    
    let pendingForm = null;
    
    // Interceptar todos los formularios de envío de correos
    document.querySelectorAll('.email-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            pendingForm = this;
            modal.classList.add('active');
        });
    });
    
    // Confirmar envío
    modalConfirm.addEventListener('click', function() {
        modal.classList.remove('active');
        if (pendingForm) {
            // Desactivar la intercepción temporalmente
            const originalSubmit = pendingForm.submit;
            pendingForm.submit = function() {
                // Restaurar el submit original
                this.submit = originalSubmit;
                this.submit();
            };
            pendingForm.submit();
        }
        pendingForm = null;
    });
    
    // Cancelar
    modalCancel.addEventListener('click', function() {
        modal.classList.remove('active');
        pendingForm = null;
    });
    
    // Cerrar con clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            pendingForm = null;
        }
    });
});
</script>
@endsection