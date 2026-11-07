<?php

namespace App\Http\Controllers;

use App\Models\Cholet;
use App\Models\Experiencia;
use App\Models\Reserva;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaConfirmadaMail;
use Carbon\Carbon;

class ReservaController extends Controller
{
    // Mostrar detalle del cholet con experiencias disponibles
    public function show($id)
    {
        $cholet = Cholet::with('imagenes', 'videos')->findOrFail($id);
        $experiencias = Experiencia::where('activo', 1)
            ->orderBy('orden')
            ->orderBy('precio')
            ->get();
        
        return view('cholets.show', compact('cholet', 'experiencias'));
    }

    // Obtener horarios disponibles vía API
    public function getHorariosDisponibles(Request $request)
    {
        $request->validate([
            'cholet_id' => 'required|exists:cholets,id',
            'fecha' => 'required|date|after:today'
        ]);

        $horarios = Horario::where('cholet_id', $request->cholet_id)
            ->where('fecha', $request->fecha)
            ->where('disponible', true)
            ->whereRaw('capacidad_maxima > reservas_actuales')
            ->orderBy('hora_inicio')
            ->get()
            ->map(function($horario) {
                return [
                    'id' => $horario->id,
                    'hora_inicio' => Carbon::parse($horario->hora_inicio)->format('H:i'),
                    'hora_fin' => Carbon::parse($horario->hora_fin)->format('H:i'),
                    'cupos_disponibles' => $horario->capacidad_maxima - $horario->reservas_actuales
                ];
            });

        return response()->json($horarios);
    }

    // Crear nueva reserva
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'cholet_id' => 'required|exists:cholets,id',
            'experiencia_id' => 'required|exists:experiencias,id',
            'horario_id' => 'required|exists:horarios,id',
            'fecha' => 'required|date|after:today',
            'hora_reserva' => 'required',
            'personas' => 'required|integer|min:1|max:20',
            'telefono' => 'required|string|min:8|max:20',
        ]);

        try {
            // Obtener la experiencia de la base de datos
            $experiencia = Experiencia::findOrFail($request->experiencia_id);
            
            // Verificar horario
            $horario = Horario::findOrFail($request->horario_id);
            
            if (!$horario->disponible) {
                return back()->with('error', '❌ Este horario ya no está disponible.');
            }

            $cuposDisponibles = $horario->capacidad_maxima - $horario->reservas_actuales;
            
            if ($cuposDisponibles < $request->personas) {
                return back()->with('error', "⚠️ Solo hay {$cuposDisponibles} cupos disponibles.");
            }

            // Calcular precio usando la experiencia de la BD
            $precio_unitario = $experiencia->precio;
            $precio_total = $precio_unitario * $request->personas;

            // Generar código de reserva único
            $codigo_reserva = 'JACHA-' . strtoupper(uniqid());
            
            // Verificar que no exista el código
            while (Reserva::where('codigo_reserva', $codigo_reserva)->exists()) {
                $codigo_reserva = 'JACHA-' . strtoupper(uniqid());
            }

            // Crear reserva
            $reserva = Reserva::create([
                'user_id' => Auth::id(),
                'cholet_id' => $request->cholet_id,
                'experiencia_id' => $experiencia->id,
                'horario_id' => $request->horario_id,
                'fecha' => $request->fecha,
                'hora_reserva' => $request->hora_reserva,
                'experiencia' => $experiencia->nombre,
                'personas' => $request->personas,
                'telefono' => $request->telefono,
                'precio_unitario' => $precio_unitario,
                'precio_total' => $precio_total,
                'estado' => 'pendiente',
                'codigo_reserva' => $codigo_reserva,
            ]);

            // Actualizar cupos del horario
            $horario->increment('reservas_actuales', $request->personas);

            // Enviar email de confirmación
            try {
                Mail::to(Auth::user()->email)->send(new ReservaConfirmadaMail($reserva));
            } catch (\Exception $e) {
                Log::error('Error al enviar email: ' . $e->getMessage());
            }

            return redirect()->route('reservas.exito', $reserva->id)
                ->with('success', '✅ ¡Reserva creada exitosamente!');

        } catch (\Exception $e) {
            Log::error('Error al crear reserva: ' . $e->getMessage());
            return back()->with('error', '❌ Ocurrió un error al procesar tu reserva. Por favor, intenta nuevamente.');
        }
    }

    // Página de éxito después de reservar
    public function exito($id)
    {
        // ✅ CORREGIDO: Usar 'experiencia' directamente
        $reserva = Reserva::with(['cholet', 'horario', 'experiencia'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('reservas.exito', compact('reserva'));
    }

    // Mostrar todas las reservas del usuario
    public function misReservas()
    {
        // ✅ CORREGIDO: Usar 'experiencia' directamente
        $reservas = Reserva::with(['cholet', 'horario', 'experiencia'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservas.mis-reservas', compact('reservas'));
    }

    // Cancelar una reserva
    public function cancelar($id)
    {
        try {
            $reserva = Reserva::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Verificar si se puede cancelar
            if ($reserva->estado !== 'pendiente') {
                return response()->json([
                    'success' => false, 
                    'message' => 'Solo se pueden cancelar reservas en estado pendiente'
                ], 400);
            }

            // Verificar si la fecha de la reserva ya pasó
            if (Carbon::parse($reserva->fecha)->isPast()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'No se pueden cancelar reservas con fecha pasada'
                ], 400);
            }

            // Liberar cupos del horario
            if ($reserva->horario_id) {
                $horario = Horario::find($reserva->horario_id);
                if ($horario) {
                    $horario->decrement('reservas_actuales', $reserva->personas);
                }
            }

            // Actualizar estado
            $reserva->update(['estado' => 'cancelada']);

            return response()->json([
                'success' => true,
                'message' => 'Reserva cancelada exitosamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al cancelar reserva: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la reserva'
            ], 500);
        }
    }

    // API: Obtener todas las experiencias (para AJAX)
    public function getExperiencias(Request $request)
    {
        $query = Experiencia::where('activo', 1)
            ->orderBy('orden')
            ->orderBy('precio');
        
        // Filtrar por cholet si es necesario
        if ($request->has('cholet_id')) {
            // Si hay relación entre experiencias y cholets
            // $query->where('cholet_id', $request->cholet_id);
        }
        
        $experiencias = $query->get();
        
        return response()->json($experiencias);
    }

    // API: Obtener detalle de una experiencia específica
    public function getExperienciaDetalle($id)
    {
        $experiencia = Experiencia::findOrFail($id);
        
        return response()->json([
            'id' => $experiencia->id,
            'nombre' => $experiencia->nombre,
            'slug' => $experiencia->slug,
            'descripcion' => $experiencia->descripcion,
            'precio' => $experiencia->precio,
            'precio_bs' => $experiencia->precio_bs,
            'duracion_horas' => $experiencia->duracion_horas,
            'incluye' => $experiencia->incluye ? json_decode($experiencia->incluye) : [],
            'icono' => $experiencia->icono,
            'color' => $experiencia->color,
        ]);
    }

    // Verificar disponibilidad de fecha y experiencia
    public function verificarDisponibilidad(Request $request)
    {
        $request->validate([
            'cholet_id' => 'required|exists:cholets,id',
            'fecha' => 'required|date|after:today',
            'experiencia_id' => 'required|exists:experiencias,id',
            'personas' => 'required|integer|min:1'
        ]);

        $reservasExistentes = Reserva::where('cholet_id', $request->cholet_id)
            ->where('fecha', $request->fecha)
            ->where('experiencia_id', $request->experiencia_id)
            ->whereIn('estado', ['pendiente', 'confirmada', 'pago_reportado'])
            ->sum('personas');

        // Límite máximo por día (puedes ajustarlo o sacarlo de configuración)
        $limiteDiario = 50;
        $disponible = $reservasExistentes + $request->personas <= $limiteDiario;

        return response()->json([
            'disponible' => $disponible,
            'cupos_disponibles' => max(0, $limiteDiario - $reservasExistentes),
            'reservas_actuales' => $reservasExistentes
        ]);
    }

    // Obtener resumen de reservas para el dashboard
    public function resumenReservas()
    {
        $totalReservas = Reserva::where('user_id', Auth::id())->count();
        $totalGastado = Reserva::where('user_id', Auth::id())
            ->where('estado', 'confirmada')
            ->sum('precio_total');
        
        $proximasReservas = Reserva::where('user_id', Auth::id())
            ->where('fecha', '>=', Carbon::today())
            ->whereIn('estado', ['pendiente', 'confirmada', 'pago_reportado'])
            ->orderBy('fecha')
            ->limit(5)
            ->get();

        return response()->json([
            'total_reservas' => $totalReservas,
            'total_gastado' => $totalGastado,
            'proximas_reservas' => $proximasReservas
        ]);
    }
}