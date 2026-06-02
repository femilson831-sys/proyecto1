<?php

namespace Database\Seeders;

use App\Models\Horario;
use App\Models\Cholet;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HorariosSeeder extends Seeder
{
    public function run(): void
    {
        $cholets = Cholet::all();
        
        if ($cholets->isEmpty()) {
            $this->command->info('⚠️ No hay cholets. Crea algunos cholets primero.');
            return;
        }
        
        $horariosCreados = 0;
        
        foreach ($cholets as $cholet) {
            // Próximos 15 días
            for ($i = 1; $i <= 15; $i++) {
                $fecha = Carbon::today()->addDays($i);
                
                // Horarios disponibles
                $horariosDelDia = [
                    ['09:00', '12:00'],
                    ['14:00', '17:00'],
                    ['19:00', '22:00'],
                ];
                
                foreach ($horariosDelDia as $hora) {
                    // Evitar duplicados
                    $existe = Horario::where('cholet_id', $cholet->id)
                        ->where('fecha', $fecha)
                        ->where('hora_inicio', $hora[0])
                        ->exists();
                    
                    if (!$existe) {
                        Horario::create([
                            'cholet_id' => $cholet->id,
                            'fecha' => $fecha,
                            'hora_inicio' => $hora[0],
                            'hora_fin' => $hora[1],
                            'capacidad_maxima' => 20,
                            'reservas_actuales' => 0,
                            'disponible' => true,
                        ]);
                        $horariosCreados++;
                    }
                }
            }
        }
        
        $this->command->info("✅ Creados {$horariosCreados} horarios disponibles.");
    }
}