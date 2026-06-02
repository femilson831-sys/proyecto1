<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experiencia extends Model
{
    protected $table = 'experiencias';
    
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',
        'precio_bs',
        'icono',
        'color',
        'duracion_horas',
        'incluye',
        'activo',
        'orden'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_bs' => 'decimal:2',
        'activo' => 'boolean',
        'duracion_horas' => 'integer'
    ];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'experiencia_id');
    }

    // Mutador para crear slug automáticamente
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = $value;
        $this->attributes['slug'] = strtolower(str_replace(' ', '-', $value));
    }

    // Scope para experiencias activas
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Obtener precio en Bolivianos (tipo de cambio 6.96)
    public function getPrecioBsAttribute($value)
    {
        return $value ?? $this->precio * 6.96;
    }
}