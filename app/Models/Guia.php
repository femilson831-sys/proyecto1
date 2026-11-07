<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guia extends Authenticatable
{
    use Notifiable;

    protected $table = 'guias';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'foto',
        'biografia',
        'idiomas',
        'anos_experiencia',
        'especialidad',
        'disponibilidad',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación con cholets
    public function cholets()
    {
        return $this->belongsToMany(Cholet::class, 'guia_cholet', 'guia_id', 'cholet_id')
                    ->withPivot('activo', 'fecha_asignacion', 'notas')
                    ->withTimestamps();
    }

    // Relación con reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    // Relación con horarios
    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}