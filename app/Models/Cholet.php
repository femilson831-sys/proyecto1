<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cholet extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',
        'descripcion',
        'imagen',
        'categoria'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
}