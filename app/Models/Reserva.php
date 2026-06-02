<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'user_id',
        'cholet_id',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cholet()
    {
        return $this->belongsTo(Cholet::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
}