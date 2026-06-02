<?php

namespace App\Http\Controllers;

use App\Models\Cholet;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ReservaConfirmadaMail;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function index()
    {
        $totalCholets = Cholet::count();
        $totalReservas = Reserva::count();
        $totalUsers = User::count();
        $totalHorarios = Horario::count();
        
        // Obtener todos los cholets disponibles para el formulario
        $choletsDisponibles = Cholet::orderBy('nombre', 'asc')->get();
        
        // Horarios de los próximos 7 días
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

    /**
     * Listar cholets
     */
    public function cholets()
    {
        $cholets = Cholet::orderBy('created_at', 'desc')->get();
        return view('admin.cholets.index', compact('cholets'));
    }

    /**
     * Formulario crear cholet
     */
    public function createCholet()
    {
        return view('admin.cholets.create');
    }

    /**
     * Guardar cholet
     */
    public function storeCholet(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string|max:800',
            'categoria' => 'required|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $cholet = new Cholet();
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = basename($path);
        }

        $cholet->save();

        return redirect()->route('admin.cholets.index')
            ->with('success', '✅ Cholet creado correctamente');
    }

    /**
     * Formulario editar cholet
     */
    public function editCholet($id)
    {
        $cholet = Cholet::findOrFail($id);
        return view('admin.cholets.edit', compact('cholet'));
    }

    /**
     * Actualizar cholet
     */
    public function updateCholet(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string|max:800',
            'categoria' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $cholet = Cholet::findOrFail($id);
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($cholet->imagen) {
                $oldImagePath = storage_path('app/public/cholets/' . $cholet->imagen);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $path = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = basename($path);
        }

        $cholet->save();

        return redirect()->route('admin.cholets.index')
            ->with('success', '✅ Cholet actualizado correctamente');
    }

    /**
     * Eliminar cholet
     */
    public function destroyCholet($id)
    {
        $cholet = Cholet::findOrFail($id);
        
        // Eliminar imagen asociada
        if ($cholet->imagen) {
            $imagePath = storage_path('app/public/cholets/' . $cholet->imagen);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $cholet->delete();

        return redirect()->route('admin.cholets.index')
            ->with('success', '✅ Cholet eliminado correctamente');
    }

    /**
     * Listar reservas (con filtros)
     */
    public function reservas(Request $request)
    {
        $query = Reserva::with('user', 'cholet');
        
        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Filtrar por código de reserva
        if ($request->filled('codigo')) {
            $query->where('codigo_reserva', 'LIKE', '%' . $request->codigo . '%');
        }
        
        $reservas = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin.reservas.index', compact('reservas'));
    }

    /**
     * Listar usuarios
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Listar guías
     */
    public function guias()
    {
        // Filtrar usuarios con rol de guía
        $guias = User::where('rol', 'guia')->orWhere('role', 'guia')->get();
        return view('admin.guias.index', compact('guias'));
    }

    /**
     * Configuración del sistema
     */
    public function configuracion()
    {
        return view('admin.configuracion.index');
    }

    /**
     * CONFIRMAR RESERVA + ENVIAR EMAIL
     */
    public function confirmarReserva($id)
    {
        $reserva = Reserva::with('user', 'cholet')->findOrFail($id);

        // Actualizar estado
        $reserva->update(['estado' => 'confirmada']);

        // Enviar email de confirmación
        try {
            if ($reserva->user && $reserva->user->email) {
                Mail::to($reserva->user->email)->send(new ReservaConfirmadaMail($reserva));
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar email de confirmación: ' . $e->getMessage());
        }

        return redirect()->route('admin.reservas.index')
            ->with('success', '✅ Reserva confirmada. Se ha enviado un email al cliente.');
    }

    /**
     * CANCELAR RESERVA
     */
    public function cancelarReserva($id)
    {
        $reserva = Reserva::with('user', 'cholet', 'horario')->findOrFail($id);

        // Liberar cupos del horario
        if ($reserva->horario_id && $reserva->estado !== 'cancelada') {
            $horario = Horario::find($reserva->horario_id);
            if ($horario) {
                $horario->decrement('reservas_actuales', $reserva->personas);
            }
        }

        // Actualizar estado
        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('admin.reservas.index')
            ->with('success', '❌ Reserva cancelada correctamente.');
    }

    /**
     * COMPLETAR RESERVA
     */
    public function completarReserva($id)
    {
        $reserva = Reserva::with('user', 'cholet')->findOrFail($id);

        // Actualizar estado
        $reserva->update(['estado' => 'completada']);

        return redirect()->route('admin.reservas.index')
            ->with('success', '🏁 Reserva marcada como completada.');
    }
}