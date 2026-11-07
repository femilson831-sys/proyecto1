<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Mostrar formulario de envío de correos
     */
    public function index()
    {
        $totalUsuarios = User::count();
        $totalClientes = User::where('rol', 'cliente')->count();
        $totalReservas = Reserva::count();
        
        return view('admin.emails.index', compact('totalUsuarios', 'totalClientes', 'totalReservas'));
    }
    
    /**
     * Enviar correo personalizado a un destinatario específico
     */
    public function enviarPersonalizado(Request $request)
    {
        $request->validate([
            'email_destino' => 'required|email',
            'nombre_destino' => 'required|string|max:255',
            'asunto_personal' => 'required|string|max:255',
            'mensaje_personal' => 'required|string'
        ]);
        
        try {
            Mail::send('emails.correo-personalizado', [
                'nombre' => $request->nombre_destino,
                'mensaje' => $request->mensaje_personal,
                'asunto' => $request->asunto_personal
            ], function ($message) use ($request) {
                $message->to($request->email_destino)
                        ->subject($request->asunto_personal);
            });
            
            return redirect()->route('admin.emails.index')
                ->with('success', "✅ Correo personalizado enviado a {$request->email_destino}");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.emails.index')
                ->with('error', '❌ Error al enviar: ' . $e->getMessage());
        }
    }
    
    /**
     * Enviar correo de cancelación
     */
    public function enviarCancelacion(Request $request)
    {
        $request->validate([
            'email_cancelacion' => 'required|email',
            'nombre_cancelacion' => 'required|string|max:255',
            'codigo_reserva' => 'nullable|string',
            'motivo_cancelacion' => 'nullable|string'
        ]);
        
        try {
            Mail::send('emails.correo-cancelacion', [
                'nombre' => $request->nombre_cancelacion,
                'codigo_reserva' => $request->codigo_reserva ?? 'No especificado',
                'motivo' => $request->motivo_cancelacion ?? 'No se especificó un motivo',
                'fecha' => now()->format('d/m/Y H:i')
            ], function ($message) use ($request) {
                $message->to($request->email_cancelacion)
                        ->subject('Cancelación de reserva - Jacha Tours');
            });
            
            return redirect()->route('admin.emails.index')
                ->with('success', "✅ Correo de cancelación enviado a {$request->email_cancelacion}");
                
        } catch (\Exception $e) {
            return redirect()->route('admin.emails.index')
                ->with('error', '❌ Error al enviar: ' . $e->getMessage());
        }
    }
    
    /**
     * Métodos eliminados porque ya no se usan:
     * - enviarATodos()
     * - enviarConReservas()
     * - enviarPrueba()
     */
}