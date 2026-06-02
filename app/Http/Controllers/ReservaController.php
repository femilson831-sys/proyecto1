<?php

namespace App\Http\Controllers;

use App\Models\Cholet;
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
    public function show($id)
    {
        $cholet = Cholet::findOrFail($id);
        return view('cholets.show', compact('cholet'));
    }

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

    public function store(Request $request)
    {
        // Validar
        $request->validate([
            'cholet_id' => 'required|exists:cholets,id',
            'horario_id' => 'required|exists:horarios,id',
            'fecha' => 'required|date|after:today',
            'hora_reserva' => 'required',
            'experiencia' => 'required',
            'personas' => 'required|integer|min:1|max:20',
            'telefono' => 'required|string|min:8|max:20',
        ]);

        $precios = [
            'Blue' => 49,
            'Gold' => 89,
            'Platinum' => 149,
        ];

        // Verificar horario
        $horario = Horario::findOrFail($request->horario_id);
        
        if (!$horario->disponible) {
            return back()->with('error', '❌ Este horario ya no está disponible.');
        }

        $cuposDisponibles = $horario->capacidad_maxima - $horario->reservas_actuales;
        
        if ($cuposDisponibles < $request->personas) {
            return back()->with('error', "⚠️ Solo hay {$cuposDisponibles} cupos disponibles.");
        }

        // Calcular precio
        $precio_unitario = $precios[$request->experiencia] ?? 49;
        $precio_total = $precio_unitario * $request->personas;

        // Crear reserva
        $reserva = Reserva::create([
            'user_id' => Auth::id(),
            'cholet_id' => $request->cholet_id,
            'horario_id' => $request->horario_id,
            'fecha' => $request->fecha,
            'hora_reserva' => $request->hora_reserva,
            'experiencia' => $request->experiencia,
            'personas' => $request->personas,
            'telefono' => $request->telefono,
            'precio_unitario' => $precio_unitario,
            'precio_total' => $precio_total,
            'estado' => 'pendiente',
            'codigo_reserva' => 'JACHA-' . strtoupper(uniqid()),
        ]);

        // Actualizar cupos
        $horario->increment('reservas_actuales', $request->personas);

        // Enviar email
        try {
            Mail::to(Auth::user()->email)->send(new ReservaConfirmadaMail($reserva));
        } catch (\Exception $e) {
            Log::error('Error email: ' . $e->getMessage());
        }

        return redirect()->route('reservas.exito', $reserva->id)
            ->with('success', '✅ ¡Reserva creada!');
    }

    public function exito($id)
    {
        $reserva = Reserva::with(['cholet', 'horario'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('reservas.exito', compact('reserva'));
    }

    public function misReservas()
    {
        $reservas = Reserva::with(['cholet', 'horario'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservas.mis-reservas', compact('reservas'));
    }

    public function cancelar($id)
    {
        $reserva = Reserva::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($reserva->horario_id && $reserva->estado !== 'cancelada') {
            $horario = Horario::find($reserva->horario_id);
            if ($horario) {
                $horario->decrement('reservas_actuales', $reserva->personas);
            }
        }

        $reserva->update(['estado' => 'cancelada']);

        return response()->json(['success' => true]);
    }
}