<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';
    
    protected $fillable = [
        'user_id',
        'cholet_id',
        'guia_id',
        'experiencia_id',
        'horario_id',
        'fecha',
        'hora_reserva',
        'experiencia',
        'personas',
        'telefono',
        'precio_unitario',
        'precio_total',
        'estado',
        'codigo_reserva',
        'notas'
    ];

    protected $casts = [
        'fecha' => 'date',
        'precio_unitario' => 'decimal:2',
        'precio_total' => 'decimal:2'
    ];

    // =========================
    // RELACIONES
    // =========================
    
    /**
     * Relación con el usuario que hizo la reserva
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el cholet
     */
    public function cholet()
    {
        return $this->belongsTo(Cholet::class);
    }

    /**
     * Relación con el guía asignado
     */
    public function guia()
    {
        return $this->belongsTo(Guia::class);
    }

    /**
     * Relación con la experiencia
     */
    public function experiencia()
    {
        return $this->belongsTo(Experiencia::class, 'experiencia_id');
    }

    /**
     * Relación con el horario
     */
    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    // =========================
    // ACCESORS
    // =========================
    
    /**
     * Obtener el nombre de la experiencia (con fallback)
     */
    public function getNombreExperienciaAttribute()
    {
        return $this->experiencia ? $this->experiencia->nombre : $this->experiencia;
    }

    /**
     * Obtener el precio formateado
     */
    public function getPrecioTotalFormateadoAttribute()
    {
        return 'Bs ' . number_format($this->precio_total, 2);
    }

    /**
     * Obtener el nombre del cliente
     */
    public function getNombreClienteAttribute()
    {
        return $this->user ? $this->user->nombre . ' ' . $this->user->apellido : 'Cliente';
    }

    /**
     * Obtener el email del cliente
     */
    public function getEmailClienteAttribute()
    {
        return $this->user ? $this->user->email : '';
    }

    /**
     * Obtener el teléfono del cliente (prioriza el de la reserva)
     */
    public function getTelefonoClienteAttribute()
    {
        return $this->telefono ?? ($this->user ? $this->user->telefono : '');
    }

    /**
     * Verificar si la reserva es cancelable
     */
    public function isCancelable()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si la reserva está confirmada
     */
    public function isConfirmada()
    {
        return $this->estado === 'confirmada';
    }

    /**
     * Verificar si la reserva está pendiente
     */
    public function isPendiente()
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si la reserva tiene guía asignado
     */
    public function tieneGuia()
    {
        return !is_null($this->guia_id);
    }

    /**
     * Obtener el nombre del guía
     */
    public function getNombreGuiaAttribute()
    {
        return $this->guia ? $this->guia->nombre . ' ' . $this->guia->apellido : 'No asignado';
    }

    // =========================
    // SCOPES
    // =========================
    
    /**
     * Scope para reservas activas (no canceladas ni completadas)
     */
    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['pendiente', 'confirmada', 'pago_reportado']);
    }

    /**
     * Scope para reservas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para reservas confirmadas
     */
    public function scopeConfirmadas($query)
    {
        return $query->where('estado', 'confirmada');
    }

    /**
     * Scope para reservas canceladas
     */
    public function scopeCanceladas($query)
    {
        return $query->where('estado', 'cancelada');
    }

    /**
     * Scope para reservas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope para reservas de un usuario específico
     */
    public function scopeDeUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para reservas en un rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para reservas sin guía asignado
     */
    public function scopeSinGuia($query)
    {
        return $query->whereNull('guia_id');
    }

    /**
     * Scope para reservas con guía asignado
     */
    public function scopeConGuia($query)
    {
        return $query->whereNotNull('guia_id');
    }

    /**
     * Scope para reservas de un guía específico
     */
    public function scopeDeGuia($query, $guiaId)
    {
        return $query->where('guia_id', $guiaId);
    }

    /**
     * Scope para reservas de un cholet específico
     */
    public function scopeDeCholet($query, $choletId)
    {
        return $query->where('cholet_id', $choletId);
    }

    /**
     * Scope para reservas de una fecha específica
     */
    public function scopeEnFecha($query, $fecha)
    {
        return $query->where('fecha', $fecha);
    }

    /**
     * Scope para reservas de un horario específico
     */
    public function scopeEnHora($query, $hora)
    {
        return $query->where('hora_reserva', $hora);
    }

    /**
     * Scope para reservas en una fecha y hora específica
     */
    public function scopeEnFechaHora($query, $fecha, $hora)
    {
        return $query->where('fecha', $fecha)->where('hora_reserva', $hora);
    }

    // =========================
    // MÉTODOS ADICIONALES
    // =========================

    /**
     * Generar código único de reserva
     */
    public static function generarCodigoReserva()
    {
        do {
            $codigo = 'JACHA-' . strtoupper(substr(uniqid(), -12));
        } while (self::where('codigo_reserva', $codigo)->exists());
        
        return $codigo;
    }

    /**
     * Calcular precio total basado en experiencia y personas
     */
    public function calcularPrecioTotal()
    {
        if ($this->experiencia) {
            $precioBase = $this->experiencia->precio;
        } elseif ($this->cholet) {
            $precioBase = 50;
        } else {
            $precioBase = 0;
        }
        
        $this->precio_unitario = $precioBase;
        $this->precio_total = $precioBase * $this->personas;
        
        return $this->precio_total;
    }

    /**
     * Obtener el estado en español
     */
    public function getEstadoTextoAttribute()
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'confirmada' => 'Confirmada',
            'cancelada' => 'Cancelada',
            'completada' => 'Completada',
            'pago_reportado' => 'Pago Reportado'
        ];
        
        return $estados[$this->estado] ?? ucfirst($this->estado);
    }

    /**
     * Obtener la clase CSS para el estado
     */
    public function getEstadoClassAttribute()
    {
        $classes = [
            'pendiente' => 'estado-pendiente',
            'confirmada' => 'estado-confirmada',
            'cancelada' => 'estado-cancelada',
            'completada' => 'estado-completada',
            'pago_reportado' => 'estado-pago-reportado'
        ];
        
        return $classes[$this->estado] ?? 'estado-desconocido';
    }

    /**
     * Obtener el icono para el estado
     */
    public function getEstadoIconoAttribute()
    {
        $iconos = [
            'pendiente' => 'ti ti-hourglass',
            'confirmada' => 'ti ti-check-circle',
            'cancelada' => 'ti ti-circle-x',
            'completada' => 'ti ti-circle-check',
            'pago_reportado' => 'ti ti-clock'
        ];
        
        return $iconos[$this->estado] ?? 'ti ti-info-circle';
    }
}