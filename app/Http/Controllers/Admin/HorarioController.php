<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cholet;
use App\Models\Horario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HorarioController extends Controller
{
    /**
     * Listar horarios de un cholet
     */
    public function index($choletId)
    {
        $cholet = Cholet::findOrFail($choletId);
        $horarios = Horario::where('cholet_id', $choletId)
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->paginate(20);
        
        return view('admin.horarios.index', compact('cholet', 'horarios'));
    }

    /**
     * Guardar un nuevo horario (individual)
     */
    public function store(Request $request, $choletId)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'capacidad_maxima' => 'required|integer|min:1|max:100',
        ]);

        // Verificar si ya existe
        $existe = Horario::where('cholet_id', $choletId)
            ->where('fecha', $request->fecha)
            ->where('hora_inicio', $request->hora_inicio)
            ->exists();

        if ($existe) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => '⚠️ Ya existe un horario para esta fecha y hora'
                ]);
            }
            return back()->with('error', '⚠️ Ya existe un horario para esta fecha y hora');
        }

        // Crear horario
        Horario::create([
            'cholet_id' => $choletId,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'capacidad_maxima' => $request->capacidad_maxima,
            'reservas_actuales' => 0,
            'disponible' => true,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => '✅ Horario creado correctamente'
            ]);
        }

        return redirect()->route('admin.horarios.index', $choletId)
            ->with('success', '✅ Horario creado correctamente');
    }

    /**
     * Crear múltiples horarios (varias fechas a la vez)
     */
    public function storeMultiple(Request $request, $choletId)
    {
        $request->validate([
            'fechas' => 'required|array',
            'fechas.*' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'capacidad_maxima' => 'required|integer|min:1|max:100',
        ]);

        $creados = 0;
        $errores = 0;
        $resultados = [];

        foreach ($request->fechas as $fecha) {
            // Verificar si ya existe
            $existe = Horario::where('cholet_id', $choletId)
                ->where('fecha', $fecha)
                ->where('hora_inicio', $request->hora_inicio)
                ->exists();

            if (!$existe) {
                Horario::create([
                    'cholet_id' => $choletId,
                    'fecha' => $fecha,
                    'hora_inicio' => $request->hora_inicio,
                    'hora_fin' => $request->hora_fin,
                    'capacidad_maxima' => $request->capacidad_maxima,
                    'reservas_actuales' => 0,
                    'disponible' => true,
                ]);
                $creados++;
                $resultados[] = ['fecha' => $fecha, 'status' => 'creado'];
            } else {
                $errores++;
                $resultados[] = ['fecha' => $fecha, 'status' => 'ya_existe'];
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'creados' => $creados,
                'errores' => $errores,
                'total' => count($request->fechas),
                'resultados' => $resultados
            ]);
        }

        return redirect()->route('admin.horarios.index', $choletId)
            ->with('success', "✅ Se crearon {$creados} horarios. {$errores} ya existían.");
    }

    /**
     * Generar horarios masivos por rango de fechas (con horarios predefinidos)
     */
    public function generarMasivo(Request $request, $choletId)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'horarios' => 'required|array',
            'horarios.*' => 'required',
            'capacidad_maxima' => 'required|integer|min:1',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        $creados = 0;
        $saltados = 0;

        while ($fechaInicio <= $fechaFin) {
            foreach ($request->horarios as $horaInicio) {
                // Calcular hora fin (3 horas después por defecto)
                $horaFin = date('H:i', strtotime($horaInicio . ' +3 hours'));
                
                // Verificar si ya existe
                $existe = Horario::where('cholet_id', $choletId)
                    ->where('fecha', $fechaInicio->format('Y-m-d'))
                    ->where('hora_inicio', $horaInicio)
                    ->exists();

                if (!$existe) {
                    Horario::create([
                        'cholet_id' => $choletId,
                        'fecha' => $fechaInicio->format('Y-m-d'),
                        'hora_inicio' => $horaInicio,
                        'hora_fin' => $horaFin,
                        'capacidad_maxima' => $request->capacidad_maxima,
                        'reservas_actuales' => 0,
                        'disponible' => true,
                    ]);
                    $creados++;
                } else {
                    $saltados++;
                }
            }
            $fechaInicio->addDay();
        }

        return redirect()->route('admin.horarios.index', $choletId)
            ->with('success', "✅ Se crearon {$creados} horarios. {$saltados} ya existían.");
    }

    /**
     * Eliminar un horario
     */
    public function destroy($choletId, $id)
    {
        $horario = Horario::findOrFail($id);
        
        // Verificar si tiene reservas
        if ($horario->reservas_actuales > 0) {
            $message = '❌ No se puede eliminar un horario con reservas activas';
            if (request()->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => $message
                ]);
            }
            return back()->with('error', $message);
        }
        
        $horario->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => '🗑️ Horario eliminado correctamente'
            ]);
        }
        
        return redirect()->route('admin.horarios.index', $choletId)
            ->with('success', '🗑️ Horario eliminado correctamente');
    }
}