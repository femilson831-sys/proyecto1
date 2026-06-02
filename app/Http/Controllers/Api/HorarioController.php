<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HorarioController extends Controller
{
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
                    'cupos_disponibles' => $horario->cuposDisponibles()
                ];
            });

        return response()->json($horarios);
    }
}