<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Reserva;
use App\Models\Rating;
use App\Models\User;
use Carbon\Carbon;

class PerfilController extends Controller
{
    /**
     * Mostrar página de perfil del usuario
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Reservas del usuario (todas)
        $todasReservas = Reserva::where('user_id', $user->id)->get();
        $totalReservas = $todasReservas->count();
        $reservasConfirmadas = $todasReservas->where('estado', 'confirmada')->count();
        
        // Historial de reservas (últimas 10)
        $historialReservas = Reserva::with('cholet')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Próximas reservas
        $proximasReservas = Reserva::with('cholet')
            ->where('user_id', $user->id)
            ->whereIn('estado', ['pendiente', 'pago_reportado', 'confirmada'])
            ->where('fecha', '>=', Carbon::today())
            ->orderBy('fecha', 'asc')
            ->take(5)
            ->get();
        
        // Ratings del usuario
        $ratings = Rating::with('cholet')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $promedioPuntuacion = $ratings->avg('rating') ?? 0;
        $diasComoCliente = Carbon::parse($user->created_at)->diffInDays(Carbon::now());
        
        // Datos para gráfico (últimos 6 meses)
        $mesesLabels = [];
        $reservasPorMes = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $mesesLabels[] = $mes->format('M Y');
            $reservasPorMes[] = Reserva::where('user_id', $user->id)
                ->whereYear('created_at', $mes->year)
                ->whereMonth('created_at', $mes->month)
                ->count();
        }
        
        return view('perfil.index', compact(
            'totalReservas',
            'reservasConfirmadas',
            'historialReservas',
            'proximasReservas',
            'ratings',
            'promedioPuntuacion',
            'diasComoCliente',
            'mesesLabels',
            'reservasPorMes'
        ));
    }
    
    /**
     * Actualizar datos del perfil
     */
    public function actualizar(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
        ]);
        
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->save(); // Este método SÍ existe en Laravel
        
        return response()->json(['success' => true, 'message' => 'Perfil actualizado correctamente']);
    }
    
    /**
     * Cambiar contraseña del usuario
     */
    public function cambiarPassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'La contraseña actual es incorrecta'], 422);
        }
        
        $user->password = Hash::make($request->new_password);
        $user->save(); // Este método SÍ existe en Laravel
        
        return response()->json(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
    }
    
    /**
     * Actualizar foto de perfil
     */
    public function actualizarFoto(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Eliminar foto anterior si existe
        if ($user->foto && file_exists(public_path($user->foto))) {
            unlink(public_path($user->foto));
        }
        
        // Guardar nueva foto
        $file = $request->file('foto');
        $nombreArchivo = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        
        // Crear directorio si no existe
        if (!file_exists(public_path('uploads/users'))) {
            mkdir(public_path('uploads/users'), 0777, true);
        }
        
        $file->move(public_path('uploads/users'), $nombreArchivo);
        $user->foto = '/uploads/users/' . $nombreArchivo;
        $user->save(); // Este método SÍ existe en Laravel
        
        return response()->json([
            'success' => true, 
            'message' => 'Foto actualizada correctamente',
            'foto_url' => asset($user->foto)
        ]);
    }
}