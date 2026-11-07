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

    // =========================
    // RELACIONES EXISTENTES
    // =========================
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // =========================
    // NUEVAS RELACIONES PARA MULTIMEDIA
    // =========================
    
    /**
     * Relación con imágenes adicionales (galería)
     */
    public function imagenes()
    {
        return $this->hasMany(CholetImagen::class)->orderBy('orden');
    }

    /**
     * Relación con videos
     */
    public function videos()
    {
        return $this->hasMany(CholetVideo::class)->orderBy('orden');
    }

    // =========================
    // NUEVA RELACIÓN PARA GUÍAS
    // =========================
    
    /**
     * Relación con guías asignados a este cholet
     * Un cholet puede tener múltiples guías asignados
     */
    public function guias()
    {
        return $this->belongsToMany(User::class, 'guia_cholet', 'cholet_id', 'guia_id')
                    ->withPivot('activo', 'fecha_asignacion', 'notas')
                    ->withTimestamps();
    }

    /**
     * Obtener solo los guías activos de este cholet
     */
    public function guiasActivos()
    {
        return $this->guias()->wherePivot('activo', true);
    }

    /**
     * Verificar si un guía específico está asignado a este cholet
     */
    public function tieneGuiaAsignado($guiaId)
    {
        return $this->guias()->where('user_id', $guiaId)->exists();
    }

    /**
     * Obtener todos los guías con sus detalles para mostrar
     */
    public function getGuiasListaAttribute()
    {
        return $this->guias->map(function($guia) {
            return [
                'id' => $guia->id,
                'nombre' => $guia->nombre_completo,
                'email' => $guia->email,
                'foto' => $guia->foto_url,
                'fecha_asignacion' => $guia->pivot->fecha_asignacion,
                'activo' => $guia->pivot->activo
            ];
        });
    }

    // =========================
    // ACCESORS (ATRIBUTOS DINÁMICOS)
    // =========================
    
    /**
     * Obtener URL completa de la imagen principal
     */
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/cholets/' . $this->imagen) : null;
    }

    /**
     * Obtener URLs de todas las imágenes de galería
     */
    public function getGaleriaUrlsAttribute()
    {
        return $this->imagenes->map(function($imagen) {
            return asset('storage/cholets/galeria/' . $imagen->imagen);
        });
    }

    /**
     * Obtener URLs de videos embebidos (para iframes)
     */
    public function getVideosEmbedUrlsAttribute()
    {
        return $this->videos->map(function($video) {
            return $video->embed_url;
        });
    }

    /**
     * Obtener promedio de calificaciones
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Obtener número total de calificaciones
     */
    public function getRatingsCountAttribute()
    {
        return $this->ratings()->count();
    }

    /**
     * Obtener primera imagen de galería (útil para thumbnails)
     */
    public function getFirstGaleriaImageAttribute()
    {
        $primeraImagen = $this->imagenes()->first();
        return $primeraImagen ? asset('storage/cholets/galeria/' . $primeraImagen->imagen) : $this->imagen_url;
    }

    /**
     * Contar cuántos guías tiene asignados este cholet
     */
    public function getTotalGuiasAsignadosAttribute()
    {
        return $this->guias()->count();
    }

    // =========================
    // MUTATORS (PARA MODIFICAR DATOS ANTES DE GUARDAR)
    // =========================
    
    /**
     * Asegurar que la categoría tenga primera letra mayúscula
     */
    public function setCategoriaAttribute($value)
    {
        $this->attributes['categoria'] = ucfirst(strtolower($value));
    }

    // =========================
    // MÉTODOS ÚTILES
    // =========================
    
    /**
     * Verificar si el cholet tiene galería de imágenes
     */
    public function hasGaleria()
    {
        return $this->imagenes()->count() > 0;
    }

    /**
     * Verificar si el cholet tiene videos
     */
    public function hasVideos()
    {
        return $this->videos()->count() > 0;
    }

    /**
     * Obtener número total de imágenes (principal + galería)
     */
    public function getTotalImagenesCountAttribute()
    {
        return 1 + $this->imagenes()->count(); // +1 por la imagen principal
    }

    /**
     * Verificar si el cholet tiene al menos un guía asignado
     */
    public function tieneGuiasAsignados()
    {
        return $this->guias()->exists();
    }

    /**
     * Obtener nombres de los guías asignados como string
     */
    public function getNombresGuiasAttribute()
    {
        return $this->guias->pluck('nombre_completo')->implode(', ');
    }
}