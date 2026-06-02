<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/* ================= MODELOS ================= */
use App\Models\Cholet;
use App\Models\Reserva;
use App\Models\Rating;

/* ================= CONTROLADORES ================= */
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\ExperienciaController;
use App\Http\Controllers\CholetController;  // ✅ AGREGADO - Controlador para Cholets

/* ================= HOME ================= */
Route::get('/', function () {
    $cholets = Cholet::all();
    return view('welcome', compact('cholets'));
});

/* ================= DETALLE CHOLET ================= */

Route::get('/cholets/{id}', [CholetController::class, 'show'])->name('cholets.show');

/* ================= DASHBOARD USER ================= */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ================= API HORARIOS (pública) ================= */
Route::get('/api/horarios-disponibles', [\App\Http\Controllers\Api\HorarioController::class, 'getHorariosDisponibles']);

/* ================= RESERVAS (protegidas) ================= */
Route::middleware(['auth'])->group(function () {
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/exito/{id}', [ReservaController::class, 'exito'])->name('reservas.exito');
    Route::get('/mis-reservas', [ReservaController::class, 'misReservas'])->name('reservas.mis-reservas');
    Route::post('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
});

/* ================= PERFIL ================= */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ================= RATING ================= */
Route::middleware(['auth'])->group(function () {
    Route::post('/rating/store', function (Request $request) {
        $request->validate([
            'cholet_id' => 'required|exists:cholets,id',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'cholet_id' => $request->cholet_id],
            ['rating' => $request->rating]
        );

        $average = Rating::where('cholet_id', $request->cholet_id)->avg('rating');

        return response()->json([
            'success' => true,
            'average' => $average
        ]);
    })->name('rating.store');
});

/* ================= CONTACTO ================= */
Route::post('/contacto/enviar', function (Request $request) {
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email',
        'telefono' => 'nullable|string',
        'mensaje' => 'required|string|min:10'
    ]);

    return back()->with('success', 'Mensaje enviado correctamente');
})->name('contacto.enviar');

/* ================= ADMIN AUTH ================= */
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/* ================= ADMIN PANEL (GRUPO PRINCIPAL) ================= */
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // DASHBOARD
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // ================= RESERVAS =================
    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas.index');
    Route::put('/reservas/{id}/confirmar', [AdminController::class, 'confirmarReserva'])->name('reservas.confirmar');
    Route::put('/reservas/{id}/cancelar', [AdminController::class, 'cancelarReserva'])->name('reservas.cancelar');
    Route::put('/reservas/{id}/completar', [AdminController::class, 'completarReserva'])->name('reservas.completar');

    // ================= CHOLETS =================
    Route::get('/cholets', [AdminController::class, 'cholets'])->name('cholets.index');
    Route::get('/cholets/create', [AdminController::class, 'createCholet'])->name('cholets.create');
    Route::post('/cholets', [AdminController::class, 'storeCholet'])->name('cholets.store');
    Route::get('/cholets/{id}/edit', [AdminController::class, 'editCholet'])->name('cholets.edit');
    Route::put('/cholets/{id}', [AdminController::class, 'updateCholet'])->name('cholets.update');
    Route::delete('/cholets/{id}', [AdminController::class, 'destroyCholet'])->name('cholets.destroy');

    // ================= USUARIOS =================
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');

    // ================= GUÍAS =================
    Route::get('/guias', [AdminController::class, 'guias'])->name('guias.index');

    // ================= CONFIGURACIÓN =================
    Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('configuracion.index');
    
    // ================= HORARIOS =================
    Route::prefix('cholets/{choletId}/horarios')->name('horarios.')->group(function () {
        Route::get('/', [HorarioController::class, 'index'])->name('index');
        Route::post('/', [HorarioController::class, 'store'])->name('store');
        Route::post('/generar-masivo', [HorarioController::class, 'generarMasivo'])->name('generarMasivo');
        Route::delete('/{id}', [HorarioController::class, 'destroy'])->name('destroy');
    });
});

/* ================= EXPERIENCIAS ADMIN ================= */
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('experiencias', ExperienciaController::class)->names([
        'index' => 'experiencias.index',
        'create' => 'experiencias.create',
        'store' => 'experiencias.store',
        'show' => 'experiencias.show',
        'edit' => 'experiencias.edit',
        'update' => 'experiencias.update',
        'destroy' => 'experiencias.destroy',
    ]);
    Route::get('/experiencias/{id}/toggle', [ExperienciaController::class, 'toggleEstado'])->name('experiencias.toggle');
});

/* ================= GUÍA DASHBOARD ================= */
Route::middleware(['auth', 'role:guia'])->group(function () {
    Route::get('/guia', function () {
        $reservas = \App\Models\Reserva::with('cholet', 'user')->get();
        return view('guia.dashboard', compact('reservas'));
    })->name('guia.dashboard');
});

/* ================= TEST EMAIL ================= */
Route::get('/test-mail', function () {
    try {
        Mail::raw('Correo de prueba desde Jacha Tours', function ($message) {
            $message->to('jachatours936@gmail.com')
                    ->subject('Prueba Laravel');
        });
        return '✅ Correo enviado correctamente';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
})->middleware(['auth:admin']);

/* ================= AUTH USUARIO ================= */
require __DIR__.'/auth.php';