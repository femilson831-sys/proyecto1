<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CholetImagen extends Model
{
    protected $table = 'cholet_imagenes';
    
    protected $fillable = [
        'cholet_id',
        'imagen',
        'orden'
    ];
    
    /**
     * Relación inversa con Cholet
     */
    public function cholet()
    {
        return $this->belongsTo(Cholet::class);
    }
    
    /**
     * Obtener URL completa de la imagen
     */
    public function getUrlAttribute()
    {
        return $this->imagen ? asset('storage/cholets/galeria/' . $this->imagen) : null;
    }
    
    /**
     * Eliminar el archivo físico cuando se elimina el registro
     */
    protected static function booted()
    {
        static::deleting(function ($imagen) {
            if ($imagen->imagen) {
                $path = storage_path('app/public/cholets/galeria/' . $imagen->imagen);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        });
    }
}