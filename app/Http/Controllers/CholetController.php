<?php

namespace App\Http\Controllers;

use App\Models\Cholet;
use App\Models\CholetImagen;
use App\Models\CholetVideo;
use App\Models\Experiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CholetController extends Controller
{
    // =========================
    // MOSTRAR TODOS LOS CHOLETS
    // =========================
    public function index()
    {
        $cholets = Cholet::with('imagenes', 'videos')->get();
        return view('welcome', compact('cholets'));
    }

    // =========================
    // MOSTRAR DETALLE CHOLET
    // =========================
    public function show($id)
    {
        $cholet = Cholet::with('imagenes', 'videos')->findOrFail($id);
        
        $experiencias = Experiencia::where('activo', 1)
            ->orderBy('orden', 'asc')
            ->get();
        
        return view('cholets.show', compact('cholet', 'experiencias'));
    }

    // =========================
    // FORMULARIO CREAR
    // =========================
    public function create()
    {
        return view('cholets.create');
    }

    // =========================
    // GUARDAR CHOLET (CORREGIDO)
    // =========================
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'galeria' => 'nullable|array|max:10',
            'galeria.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'nuevos_videos' => 'nullable|array|max:5',
            'nuevos_videos.*' => 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm|max:102400',
        ]);

        // 1. Guardar cholet principal
        $cholet = new Cholet();
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        if ($request->hasFile('imagen')) {
            // ✅ CORREGIDO: Guardar ruta completa, NO basename()
            $imagePath = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = $imagePath;
        }

        $cholet->save();

        // 2. Guardar galería de imágenes
        if ($request->hasFile('galeria')) {
            $orden = 0;
            foreach ($request->file('galeria') as $imagen) {
                if ($imagen->isValid()) {
                    // ✅ CORREGIDO: Guardar ruta completa
                    $path = $imagen->store('cholets/galeria', 'public');
                    $cholet->imagenes()->create([
                        'imagen' => $path,
                        'orden' => $orden++
                    ]);
                }
            }
        }

        // 3. Guardar videos locales
        if ($request->hasFile('nuevos_videos')) {
            $orden = 0;
            foreach ($request->file('nuevos_videos') as $video) {
                if ($video->isValid()) {
                    // ✅ CORREGIDO: Guardar ruta completa
                    $videoPath = $video->store('cholets/videos', 'public');
                    $cholet->videos()->create([
                        'url' => $videoPath,
                        'titulo' => $video->getClientOriginalName(),
                        'tipo' => 'local',
                        'orden' => $orden++
                    ]);
                }
            }
        }
        
        return redirect()->route('cholets.index')
            ->with('success', 'Cholet creado correctamente con galería y videos');
    }

    // =========================
    // FORMULARIO EDITAR
    // =========================
    public function edit($id)
    {
        $cholet = Cholet::with('imagenes', 'videos')->findOrFail($id);
        return view('cholets.edit', compact('cholet'));
    }

    // =========================
    // ACTUALIZAR CHOLET (CORREGIDO)
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'galeria' => 'nullable|array|max:10',
            'galeria.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'nuevos_videos' => 'nullable|array|max:5',
            'nuevos_videos.*' => 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/webm|max:102400',
            'eliminar_imagenes' => 'nullable|array',
            'eliminar_imagenes.*' => 'exists:cholet_imagenes,id',
            'eliminar_videos' => 'nullable|array',
            'eliminar_videos.*' => 'exists:cholet_videos,id',
        ]);

        $cholet = Cholet::findOrFail($id);
        
        // Actualizar datos principales
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        // Actualizar imagen principal
        if ($request->hasFile('imagen')) {
            // ✅ CORREGIDO: Usar Storage::disk()
            if ($cholet->imagen && Storage::disk('public')->exists($cholet->imagen)) {
                Storage::disk('public')->delete($cholet->imagen);
            }
            
            $imagePath = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = $imagePath;
        }

        $cholet->save();

        // Eliminar imágenes de galería seleccionadas
        if ($request->has('eliminar_imagenes')) {
            foreach ($request->eliminar_imagenes as $imagenId) {
                $imagen = $cholet->imagenes()->find($imagenId);
                if ($imagen) {
                    // ✅ CORREGIDO: Usar Storage::disk()
                    if (Storage::disk('public')->exists($imagen->imagen)) {
                        Storage::disk('public')->delete($imagen->imagen);
                    }
                    $imagen->delete();
                }
            }
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('galeria')) {
            $maxOrden = $cholet->imagenes()->max('orden') ?? -1;
            $orden = $maxOrden + 1;
            
            foreach ($request->file('galeria') as $imagen) {
                if ($imagen->isValid()) {
                    $path = $imagen->store('cholets/galeria', 'public');
                    $cholet->imagenes()->create([
                        'imagen' => $path,
                        'orden' => $orden++
                    ]);
                }
            }
        }

        // Eliminar videos seleccionados
        if ($request->has('eliminar_videos')) {
            foreach ($request->eliminar_videos as $videoId) {
                $video = $cholet->videos()->find($videoId);
                if ($video && $video->tipo === 'local') {
                    // ✅ CORREGIDO: Usar Storage::disk()
                    if (Storage::disk('public')->exists($video->url)) {
                        Storage::disk('public')->delete($video->url);
                    }
                    $video->delete();
                }
            }
        }

        // Agregar nuevos videos
        if ($request->hasFile('nuevos_videos')) {
            $maxOrden = $cholet->videos()->max('orden') ?? -1;
            $orden = $maxOrden + 1;
            
            foreach ($request->file('nuevos_videos') as $video) {
                if ($video->isValid()) {
                    $videoPath = $video->store('cholets/videos', 'public');
                    $cholet->videos()->create([
                        'url' => $videoPath,
                        'titulo' => $video->getClientOriginalName(),
                        'tipo' => 'local',
                        'orden' => $orden++
                    ]);
                }
            }
        }

        return redirect()->route('cholets.index')
            ->with('success', 'Cholet actualizado correctamente');
    }

    // =========================
    // ELIMINAR CHOLET (CORREGIDO)
    // =========================
    public function destroy($id)
    {
        $cholet = Cholet::with('imagenes', 'videos')->findOrFail($id);
        
        // ✅ CORREGIDO: Usar Storage::disk() para eliminar imagen principal
        if ($cholet->imagen && Storage::disk('public')->exists($cholet->imagen)) {
            Storage::disk('public')->delete($cholet->imagen);
        }
        
        // ✅ CORREGIDO: Eliminar imágenes de galería
        foreach ($cholet->imagenes as $imagen) {
            if (Storage::disk('public')->exists($imagen->imagen)) {
                Storage::disk('public')->delete($imagen->imagen);
            }
        }
        
        // ✅ CORREGIDO: Eliminar videos locales
        foreach ($cholet->videos as $video) {
            if ($video->tipo === 'local' && Storage::disk('public')->exists($video->url)) {
                Storage::disk('public')->delete($video->url);
            }
        }
        
        $cholet->delete();

        return redirect()->route('cholets.index')
            ->with('success', 'Cholet eliminado correctamente');
    }
    
    // =========================
    // REORDENAR IMÁGENES DE GALERÍA (CORREGIDO)
    // =========================
    public function reordenarImagenes(Request $request, $id)
    {
        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'exists:cholet_imagenes,id',
        ]);
        
        // ✅ CORREGIDO: Verificar que la imagen pertenezca al cholet
        foreach ($request->orden as $index => $imagenId) {
            CholetImagen::where('id', $imagenId)
                ->where('cholet_id', $id)
                ->update(['orden' => $index]);
        }
        
        return response()->json(['success' => true]);
    }
}