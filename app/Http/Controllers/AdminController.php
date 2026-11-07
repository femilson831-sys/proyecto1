<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Cholet;
use App\Models\CholetImagen;
use App\Models\CholetVideo;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Guia;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservaConfirmadaMail;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalCholets = Cholet::count();
        $totalReservas = Reserva::count();
        $totalUsers = User::count();
        $totalHorarios = Horario::count();
        
        $choletsDisponibles = Cholet::orderBy('nombre', 'asc')->get();
        
        $horariosRecientes = Horario::with('cholet')
            ->where('fecha', '>=', Carbon::today())
            ->where('fecha', '<=', Carbon::today()->addDays(7))
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->limit(10)
            ->get();
        
        $reservasRecientes = Reserva::with('user', 'cholet')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalCholets', 
            'totalReservas', 
            'totalUsers', 
            'totalHorarios',
            'horariosRecientes',
            'reservasRecientes',
            'choletsDisponibles'
        ));
    }

    public function cholets()
    {
        $cholets = Cholet::with('imagenes', 'videos')->orderBy('created_at', 'desc')->get();
        return view('admin.cholets.index', compact('cholets'));
    }

    public function createCholet()
    {
        return view('admin.cholets.create');
    }

    public function storeCholet(Request $request)
    {
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

        $cholet = new Cholet();
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = basename($imagePath);
        }

        $cholet->save();

        if ($request->hasFile('galeria')) {
            $orden = 0;
            foreach ($request->file('galeria') as $imagen) {
                if ($imagen->isValid()) {
                    $path = $imagen->store('cholets/galeria', 'public');
                    $cholet->imagenes()->create([
                        'imagen' => basename($path),
                        'orden' => $orden++
                    ]);
                }
            }
        }

        if ($request->hasFile('nuevos_videos')) {
            $orden = 0;
            foreach ($request->file('nuevos_videos') as $video) {
                if ($video->isValid()) {
                    $videoPath = $video->store('videos', 'public');
                    $cholet->videos()->create([
                        'url' => basename($videoPath),
                        'titulo' => $video->getClientOriginalName(),
                        'tipo' => 'local',
                        'orden' => $orden++
                    ]);
                }
            }
        }

        return redirect()->route('admin.cholets.index')
            ->with('success', '✅ Cholet creado correctamente');
    }

    public function editCholet($id)
    {
        $cholet = Cholet::with('imagenes', 'videos')->findOrFail($id);
        return view('admin.cholets.edit', compact('cholet'));
    }

    public function updateCholet(Request $request, $id)
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
        
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        if ($request->hasFile('imagen')) {
            if ($cholet->imagen) {
                $oldImagePath = storage_path('app/public/cholets/' . $cholet->imagen);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $imagePath = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = basename($imagePath);
        }

        $cholet->save();

        if ($request->has('eliminar_imagenes')) {
            foreach ($request->eliminar_imagenes as $imagenId) {
                $imagen = $cholet->imagenes()->find($imagenId);
                if ($imagen) {
                    $imagePath = storage_path('app/public/cholets/galeria/' . $imagen->imagen);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $imagen->delete();
                }
            }
        }

        if ($request->hasFile('galeria')) {
            $maxOrden = $cholet->imagenes()->max('orden') ?? -1;
            $orden = $maxOrden + 1;
            
            foreach ($request->file('galeria') as $imagen) {
                if ($imagen->isValid()) {
                    $path = $imagen->store('cholets/galeria', 'public');
                    $cholet->imagenes()->create([
                        'imagen' => basename($path),
                        'orden' => $orden++
                    ]);
                }
            }
        }

        if ($request->has('eliminar_videos')) {
            foreach ($request->eliminar_videos as $videoId) {
                $video = $cholet->videos()->find($videoId);
                if ($video && $video->tipo === 'local') {
                    $videoPath = storage_path('app/public/videos/' . $video->url);
                    if (file_exists($videoPath)) {
                        unlink($videoPath);
                    }
                }
                if ($video) {
                    $video->delete();
                }
            }
        }

        if ($request->hasFile('nuevos_videos')) {
            $maxOrden = $cholet->videos()->max('orden') ?? -1;
            $orden = $maxOrden + 1;
            
            foreach ($request->file('nuevos_videos') as $video) {
                if ($video->isValid()) {
                    $videoPath = $video->store('videos', 'public');
                    $cholet->videos()->create([
                        'url' => basename($videoPath),
                        'titulo' => $video->getClientOriginalName(),
                        'tipo' => 'local',
                        'orden' => $orden++
                    ]);
                }
            }
        }

        return redirect()->route('admin.cholets.index')
            ->with('success', '✅ Cholet actualizado correctamente');
    }

    public function destroyCholet($id)
    {
        $cholet = Cholet::with('imagenes', 'videos')->findOrFail($id);
        
        if ($cholet->imagen) {
            $imagePath = storage_path('app/public/cholets/' . $cholet->imagen);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        foreach ($cholet->imagenes as $imagen) {
            $imagePath = storage_path('app/public/cholets/galeria/' . $imagen->imagen);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        foreach ($cholet->videos as $video) {
            if ($video->tipo === 'local') {
                $videoPath = storage_path('app/public/videos/' . $video->url);
                if (file_exists($videoPath)) {
                    unlink($videoPath);
                }
            }
        }
        
        $cholet->delete();

        return redirect()->route('admin.cholets.index')
            ->with('success', '✅ Cholet eliminado correctamente');
    }

    public function reservas(Request $request)
    {
        $query = Reserva::with('user', 'cholet', 'guia');
        
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->filled('codigo')) {
            $query->where('codigo_reserva', 'LIKE', '%' . $request->codigo . '%');
        }
        
        $reservas = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.reservas.index', compact('reservas'));
    }

    public function confirmarReserva($id)
    {
        $reserva = Reserva::with('user', 'cholet')->findOrFail($id);
        $reserva->update(['estado' => 'confirmada']);

        try {
            if ($reserva->user && $reserva->user->email) {
                Mail::to($reserva->user->email)->send(new ReservaConfirmadaMail($reserva));
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar email de confirmación: ' . $e->getMessage());
        }

        return redirect()->route('admin.reservas.index')
            ->with('success', '✅ Reserva confirmada');
    }

    public function cancelarReserva($id)
    {
        $reserva = Reserva::with('user', 'cholet', 'horario')->findOrFail($id);

        if ($reserva->horario_id && $reserva->estado !== 'cancelada') {
            $horario = Horario::find($reserva->horario_id);
            if ($horario) {
                $horario->decrement('reservas_actuales', $reserva->personas);
            }
        }

        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('admin.reservas.index')
            ->with('success', '❌ Reserva cancelada');
    }

    public function completarReserva($id)
    {
        $reserva = Reserva::with('user', 'cholet')->findOrFail($id);
        $reserva->update(['estado' => 'completada']);

        return redirect()->route('admin.reservas.index')
            ->with('success', '🏁 Reserva completada');
    }

    // ================= ASIGNAR GUÍA A RESERVA INDIVIDUAL =================

    public function asignarGuiaForm(Reserva $reserva)
    {
        $guias = Guia::where('estado', 'activo')
                     ->orderByRaw("FIELD(disponibilidad, 'disponible', 'descanso', 'ocupado')")
                     ->orderBy('nombre')
                     ->get();
        
        return view('admin.reservas.asignar-guia', compact('reserva', 'guias'));
    }

    public function asignarGuia(Request $request, Reserva $reserva)
    {
        $request->validate([
            'guia_id' => 'required|exists:guias,id',
        ]);
        
        $guia = Guia::find($request->guia_id);
        
        $reserva->update([
            'guia_id' => $request->guia_id,
            'estado' => 'confirmada'
        ]);
        
        return redirect()->route('admin.reservas.index')
                         ->with('success', '✅ Guía ' . $guia->nombre_completo . ' asignado a la reserva ' . $reserva->codigo_reserva);
    }

    // ================= DESIGNAR GUÍA A HORARIOS (GRUPOS DE RESERVAS) =================

    /**
     * Vista para designar guías a horarios
     */
    public function designarHorarios(Request $request)
    {
        $cholets = Cholet::orderBy('nombre')->get();
        $guias = Guia::where('estado', 'activo')->orderBy('nombre')->get();
        
        $query = Horario::with(['cholet', 'reservas.user', 'guia'])
            ->where('fecha', '>=', now()->startOfDay());
        
        if ($request->cholet_id) {
            $query->where('cholet_id', $request->cholet_id);
        }
        
        if ($request->fecha) {
            $query->where('fecha', $request->fecha);
        }
        
        $horarios = $query->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();
        
        // Calcular estadísticas para cada horario
        foreach ($horarios as $horario) {
            $reservas = $horario->reservas;
            $horario->reservas_count = $reservas->count();
            $horario->total_personas = $reservas->sum('personas');
            $horario->reservas = $reservas;
        }
        
        // Filtrar por asignación
        if ($request->asignado == 'sin_asignar') {
            $horarios = $horarios->filter(fn($h) => is_null($h->guia_id));
        } elseif ($request->asignado == 'asignado') {
            $horarios = $horarios->filter(fn($h) => !is_null($h->guia_id));
        }
        
        return view('admin.designar_horarios.asignar-guia', compact('horarios', 'cholets', 'guias'));
    }

    /**
     * Guardar asignación de guía a horario
     */
    public function designarHorariosStore(Request $request)
    {
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'guia_id' => 'required|exists:guias,id',
            'notas' => 'nullable|string'
        ]);
        
        $horario = Horario::find($request->horario_id);
        $guia = Guia::find($request->guia_id);
        
        $horario->update([
            'guia_id' => $request->guia_id,
            'notas_guia' => $request->notas
        ]);
        
        // También actualizar las reservas de ese horario
        Reserva::where('horario_id', $horario->id)
            ->update(['guia_id' => $request->guia_id]);
        
        return response()->json([
            'success' => true,
            'message' => "Guía {$guia->nombre_completo} asignado al horario"
        ]);
    }

    // ================= FIN MÉTODOS =================

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function configuracion()
    {
        return view('admin.configuracion.index');
    }
}