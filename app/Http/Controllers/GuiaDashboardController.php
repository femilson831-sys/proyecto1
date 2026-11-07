<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Horario;
use App\Models\Cholet;
use App\Models\Guia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuiaDashboardController extends Controller
{
    public function index()
    {
        // Obtener el guía desde la sesión
        $guia = Guia::find(session('guia_id'));
        
        if (!$guia) {
            return redirect()->route('guia.login')->with('error', 'Sesión no válida');
        }
        
        // Obtener reservas asignadas al guía
        $reservasAsignadas = Reserva::where('guia_id', $guia->id)
            ->whereIn('estado', ['confirmada', 'pendiente'])
            ->with(['cholet', 'user'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_reserva', 'asc')
            ->get();
        
        $proximasReservas = $reservasAsignadas->filter(function($reserva) {
            return Carbon::parse($reserva->fecha)->between(Carbon::today(), Carbon::today()->addDays(7));
        });
        
        $horarios = Horario::where('guia_id', $guia->id)
            ->where('fecha', '>=', Carbon::today())
            ->with(['cholet'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();
        
        $stats = [
            'total_reservas' => $reservasAsignadas->count(),
            'proximas_reservas' => $proximasReservas->count(),
            'horarios_mes' => $horarios->count(),
            'cholets_asignados' => $horarios->pluck('cholet_id')->unique()->count(),
        ];
        
        return view('guia.dashboard', compact('guia', 'proximasReservas', 'horarios', 'stats'));
    }
    
    /**
     * Mostrar calendario de horarios del guía
     */
    public function calendario()
    {
        $guia = Guia::find(session('guia_id'));
        
        if (!$guia) {
            return redirect()->route('guia.login')->with('error', 'Sesión no válida');
        }
        
        // Obtener horarios del guía
        $horarios = Horario::where('guia_id', $guia->id)
            ->where('fecha', '>=', Carbon::today()->subDays(30))
            ->where('fecha', '<=', Carbon::today()->addDays(60))
            ->with(['cholet'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_inicio', 'asc')
            ->get();
        
        // Agrupar por mes
        $horariosPorMes = $horarios->groupBy(function($item) {
            return Carbon::parse($item->fecha)->translatedFormat('F Y');
        });
        
        return view('guia.calendario', compact('horarios', 'horariosPorMes'));
    }
    
    /**
     * Mostrar lista de reservas del guía
     */
    public function reservas()
    {
        $guia = Guia::find(session('guia_id'));
        
        if (!$guia) {
            return redirect()->route('guia.login')->with('error', 'Sesión no válida');
        }
        
        $reservas = Reserva::where('guia_id', $guia->id)
            ->with(['cholet', 'user'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_reserva', 'desc')
            ->paginate(15);
        
        return view('guia.reservas', compact('reservas', 'guia'));
    }
    
    /**
     * Mostrar detalle de una reserva específica
     */
    public function reservaDetalle($id)
    {
        $guia = Guia::find(session('guia_id'));
        
        if (!$guia) {
            return redirect()->route('guia.login')->with('error', 'Sesión no válida');
        }
        
        $reserva = Reserva::where('id', $id)
            ->where('guia_id', $guia->id)
            ->with(['cholet', 'user'])
            ->firstOrFail();
        
        return view('guia.reserva-detalle', compact('reserva', 'guia'));
    }
}