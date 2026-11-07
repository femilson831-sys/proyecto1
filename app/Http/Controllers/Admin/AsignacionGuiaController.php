<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Guia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionGuiaController extends Controller
{
    public function index()
    {
        // Solo reservas CONFIRMADAS y sin guía
        $reservasPendientes = Reserva::whereNull('guia_id')
            ->where('estado', 'confirmada')
            ->with(['cholet', 'user'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_reserva', 'asc')
            ->get();
        
        $guias = Guia::where('estado', 'activo')->get();
        
        // Agrupar por fecha|hora|cholet_id
        $reservasAgrupadas = $reservasPendientes->groupBy(function($item) {
            return $item->fecha . '|' . ($item->hora_reserva ?? '10:00') . '|' . $item->cholet_id;
        });
        
        return view('admin.asignaciones.index', compact('guias', 'reservasPendientes', 'reservasAgrupadas'));
    }
    
    public function asignarMasivo(Request $request)
    {
        $request->validate([
            'guia_id' => 'required|exists:guias,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'cholet_id' => 'required|exists:cholets,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $guia = Guia::findOrFail($request->guia_id);
            
            $reservas = Reserva::whereNull('guia_id')
                ->where('estado', 'confirmada')
                ->where('fecha', $request->fecha)
                ->where('hora_reserva', $request->hora)
                ->where('cholet_id', $request->cholet_id)
                ->get();
            
            if ($reservas->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'msg' => 'No hay reservas confirmadas para este horario'
                ]);
            }
            
            $asignadas = 0;
            $clientes = [];
            
            foreach ($reservas as $reserva) {
                $reserva->guia_id = $guia->id;
                $reserva->save();
                $asignadas++;
                $clientes[] = $reserva->user->nombre ?? 'Cliente';
            }
            
            $guia->disponibilidad = 'ocupado';
            $guia->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'msg' => "✓ Guía {$guia->nombre} asignado a {$asignadas} reserva(s)"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'msg' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}