<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'precio',        // Ahora es precio en Bolivianos
        'icono',
        'color',
        'duracion_horas',
        'incluye',
        'activo',
        'orden'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
        'duracion_horas' => 'integer',
        'orden' => 'integer'
    ];

    /**
     * Relación con las reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'experiencia_id');
    }

    /**
     * Obtener el precio formateado
     */
    public function getPrecioFormateadoAttribute()
    {
        return 'Bs ' . number_format($this->precio, 2);
    }

    /**
     * Scope para experiencias activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', 1);
    }

    /**
     * Scope para ordenar
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden', 'asc');
    }
}