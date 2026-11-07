<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CholetVideo extends Model
{
    protected $table = 'cholet_videos';
    
    protected $fillable = [
        'cholet_id',
        'url',
        'titulo',
        'tipo',
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
     * Obtener URL completa del video (para videos locales)
     */
    public function getVideoUrlAttribute()
    {
        if ($this->tipo === 'local') {
            return asset('storage/videos/' . $this->url);
        }
        return $this->url;
    }
    
    /**
     * Obtener URL embebida para iframe (para YouTube/Vimeo)
     * Para videos locales, retorna la URL del archivo
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->tipo === 'local') {
            return $this->video_url;
        } elseif ($this->tipo === 'youtube') {
            return $this->getYouTubeEmbedUrl();
        } elseif ($this->tipo === 'vimeo') {
            return $this->getVimeoEmbedUrl();
        }
        return $this->url;
    }
    
    /**
     * Extraer ID de YouTube y generar URL embebida
     */
    private function getYouTubeEmbedUrl()
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]+)/',
            '/(?:youtu\.be\/)([a-zA-Z0-9_-]+)/',
            '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->url, $matches)) {
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        return $this->url;
    }
    
    /**
     * Extraer ID de Vimeo y generar URL embebida
     */
    private function getVimeoEmbedUrl()
    {
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $this->url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }
        return $this->url;
    }
    
    /**
     * Obtener miniatura del video
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->tipo === 'youtube') {
            return $this->getYouTubeThumbnail();
        } elseif ($this->tipo === 'vimeo') {
            return $this->getVimeoThumbnail();
        } elseif ($this->tipo === 'local') {
            return $this->getLocalThumbnail();
        }
        return null;
    }
    
    /**
     * Obtener miniatura de YouTube
     */
    private function getYouTubeThumbnail()
    {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $this->url, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/maxresdefault.jpg';
        }
        return null;
    }
    
    /**
     * Obtener miniatura de Vimeo
     */
    private function getVimeoThumbnail()
    {
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $this->url, $matches)) {
            // Usar la API de Vimeo para obtener la miniatura
            return "https://vumbnail.com/{$matches[1]}.jpg";
        }
        return null;
    }
    
    /**
     * Obtener miniatura de video local (primer frame)
     */
    private function getLocalThumbnail()
    {
        // Para videos locales, puedes generar una miniatura usando FFmpeg
        // Por ahora retornamos un placeholder o null
        return asset('img/video-placeholder.jpg');
    }
    
    /**
     * Verificar si es un video local
     */
    public function isLocal()
    {
        return $this->tipo === 'local';
    }
    
    /**
     * Verificar si es un video de YouTube
     */
    public function isYouTube()
    {
        return $this->tipo === 'youtube';
    }
    
    /**
     * Verificar si es un video de Vimeo
     */
    public function isVimeo()
    {
        return $this->tipo === 'vimeo';
    }
}