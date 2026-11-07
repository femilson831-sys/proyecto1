<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horario extends Model
{
    protected $table = 'horarios';
    
    protected $fillable = [
        'cholet_id',
        'guia_id',           // ← NUEVO
        'fecha',
        'hora_inicio',
        'hora_fin',
        'capacidad_maxima',
        'reservas_actuales',
        'disponible',
        'notas_guia'         // ← NUEVO
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
        'disponible' => 'boolean'
    ];

    // Relación con cholet
    public function cholet(): BelongsTo
    {
        return $this->belongsTo(Cholet::class);
    }

    // Relación con guía (NUEVA)
    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class, 'guia_id');
    }

    // Relación con reservas (NUEVA)
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }

    public function cuposDisponibles(): int
    {
        return $this->capacidad_maxima - $this->reservas_actuales;
    }

    public function estaDisponible(): bool
    {
        return $this->disponible && $this->cuposDisponibles() > 0;
    }

    // Verificar si tiene guía asignado (NUEVO)
    public function tieneGuiaAsignado(): bool
    {
        return !is_null($this->guia_id);
    }

    // Obtener hora formateada (NUEVO)
    public function getHoraInicioFormateadaAttribute(): string
    {
        return $this->hora_inicio ? $this->hora_inicio->format('H:i') : '';
    }

    public function getHoraFinFormateadaAttribute(): string
    {
        return $this->hora_fin ? $this->hora_fin->format('H:i') : '';
    }

    // Obtener fecha formateada (NUEVO)
    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }

    // Obtener el día de la semana (NUEVO)
    public function getDiaSemanaAttribute(): string
    {
        return $this->fecha ? $this->fecha->translatedFormat('l') : '';
    }

    // Total de personas de todas las reservas de este horario (NUEVO)
    public function getTotalPersonasAttribute(): int
    {
        return $this->reservas()->sum('personas');
    }

    // Total de reservas (NUEVO)
    public function getTotalReservasAttribute(): int
    {
        return $this->reservas()->count();
    }
}