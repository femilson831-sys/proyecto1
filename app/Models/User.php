<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\VerifyEmailCustom;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos permitidos
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'foto',
        'rol',
        'password',
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    /**
     * Enviar correo de verificación personalizado
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailCustom);
    }

    /**
     * Relación con reservas
     */
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    /**
     * ============================================
     * NUEVAS RELACIONES PARA EL SISTEMA DE GUÍAS
     * ============================================
     */

    /**
     * Relación con cholets asignados (solo para guías)
     * Un guía puede atender múltiples cholets
     */
    public function choletsAsignados()
    {
        return $this->belongsToMany(Cholet::class, 'guia_cholet', 'guia_id', 'cholet_id')
                    ->withPivot('activo', 'fecha_asignacion', 'notas')
                    ->withTimestamps();
    }

    /**
     * Obtener solo los cholets activos asignados a este guía
     */
    public function choletsActivos()
    {
        return $this->choletsAsignados()->wherePivot('activo', true);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    /**
     * Verificar si el usuario es guía
     */
    public function isGuia()
    {
        return $this->rol === 'guia';
    }

    /**
     * Verificar si el usuario es cliente
     */
    public function isCliente()
    {
        return $this->rol === 'cliente';
    }

    /**
     * Scope para obtener solo guías
     */
    public function scopeGuias($query)
    {
        return $query->where('rol', 'guia');
    }

    /**
     * Scope para obtener solo admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('rol', 'admin');
    }

    /**
     * Scope para obtener solo clientes
     */
    public function scopeClientes($query)
    {
        return $query->where('rol', 'cliente');
    }

    /**
     * Obtener la URL de la foto de perfil
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path($this->foto))) {
            return $this->foto;
        }
        
        // Avatar por defecto con iniciales
        return 'https://ui-avatars.com/api/?background=c9a84c&color=fff&name=' . urlencode($this->nombre_completo);
    }

    /**
     * Contar cuántos cholets tiene asignados
     */
    public function getTotalCholetsAsignadosAttribute()
    {
        return $this->choletsAsignados()->count();
    }
}