<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $table = 'horarios';
    
    protected $fillable = [
        'cholet_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'capacidad_maxima',
        'reservas_actuales',
        'disponible'
    ];

    protected $casts = [
        'fecha' => 'date',
        'disponible' => 'boolean'
    ];

    public function cholet(): BelongsTo
    {
        return $this->belongsTo(Cholet::class);
    }

    public function cuposDisponibles(): int
    {
        return $this->capacidad_maxima - $this->reservas_actuales;
    }

    public function estaDisponible(): bool
    {
        return $this->disponible && $this->cuposDisponibles() > 0;
    }
}