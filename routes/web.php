<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/* ================= MODELOS ================= */
use App\Models\Cholet;
use App\Models\Reserva;
use App\Models\Rating;
use App\Models\Experiencia;

/* ================= CONTROLADORES ================= */
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\ExperienciaController;
use App\Http\Controllers\Admin\GuiaController;
use App\Http\Controllers\Admin\AsignacionGuiaController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\CholetController;
use App\Http\Controllers\GuiaDashboardController;
use App\Http\Controllers\GuiaAuthController;
use App\Http\Controllers\HomeController;

/* ================= HOME ================= */
Route::get('/', function () {
    $cholets = Cholet::all();
    $experiencias = Experiencia::where('activo', true)
        ->orderBy('orden', 'asc')
        ->get();
    return view('welcome', compact('cholets', 'experiencias'));
})->name('home');

/* ================= CHOLETS PÚBLICOS ================= */
Route::get('/cholets', [CholetController::class, 'index'])->name('cholets.index');
Route::get('/cholets/{cholet}', [CholetController::class, 'show'])->name('cholets.show');

/* ================= DASHBOARD USER ================= */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ================= API HORARIOS (pública) ================= */
Route::get('/api/horarios-disponibles', [ReservaController::class, 'getHorariosDisponibles'])->name('api.horarios');

/* ================= API EXPERIENCIAS (pública) ================= */
Route::get('/api/experiencias', [ReservaController::class, 'getExperiencias'])->name('api.experiencias');
Route::get('/api/experiencias/{id}', [ReservaController::class, 'getExperienciaDetalle'])->name('api.experiencia.detalle');

/* ================= RESERVAS (protegidas) ================= */
Route::middleware(['auth'])->group(function () {
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/exito/{id}', [ReservaController::class, 'exito'])->name('reservas.exito');
    Route::get('/mis-reservas', [ReservaController::class, 'misReservas'])->name('reservas.mis');
    Route::post('/reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
});

/* ================= PERFIL DE USUARIO ================= */
Route::middleware(['auth'])->group(function () {
    // Perfil principal personalizado
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');
    Route::put('/perfil/actualizar', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');
    Route::put('/perfil/cambiar-password', [PerfilController::class, 'cambiarPassword'])->name('perfil.cambiar-password');
    Route::post('/perfil/foto', [PerfilController::class, 'actualizarFoto'])->name('perfil.foto');
    
    // REDIRECCIÓN: /profile ahora va a /perfil
    Route::get('/profile', function() {
        return redirect()->route('perfil.index');
    })->name('profile.edit');
    
    // Estas rutas ya no se usan porque redirigimos a /perfil
    // Se mantienen comentadas por si acaso
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/* ================= ADMIN PANEL (GRUPO PRINCIPAL) ================= */
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // DASHBOARD
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // ================= RESERVAS =================
    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas.index');
    Route::put('/reservas/{id}/confirmar', [AdminController::class, 'confirmarReserva'])->name('reservas.confirmar');
    Route::put('/reservas/{id}/cancelar', [AdminController::class, 'cancelarReserva'])->name('reservas.cancelar');
    Route::put('/reservas/{id}/completar', [AdminController::class, 'completarReserva'])->name('reservas.completar');

    // ================= ASIGNAR GUÍA A RESERVA INDIVIDUAL =================
    Route::get('/reservas/{reserva}/asignar-guia', [AdminController::class, 'asignarGuiaForm'])->name('reservas.asignar-guia');
    Route::post('/reservas/{reserva}/asignar-guia', [AdminController::class, 'asignarGuia'])->name('reservas.asignar-guia.store');

    // ================= DESIGNAR GUÍA A HORARIOS (GRUPOS) =================
    Route::get('/designar-horarios', [AdminController::class, 'designarHorarios'])->name('designar-horarios');
    Route::post('/designar-horarios', [AdminController::class, 'designarHorariosStore'])->name('designar-horarios.store');

    // ================= ASIGNACIÓN DE GUÍAS (PANEL PRINCIPAL) =================
    Route::get('/asignaciones', [AsignacionGuiaController::class, 'index'])->name('asignaciones.index');
    Route::post('/asignaciones/asignar', [AsignacionGuiaController::class, 'asignar'])->name('asignaciones.asignar');
    Route::post('/asignaciones/asignar-masivo', [AsignacionGuiaController::class, 'asignarMasivo'])->name('asignaciones.asignar-masivo');
    Route::get('/asignaciones/horarios-disponibles/{guiaId}/{fecha}', [AsignacionGuiaController::class, 'horariosDisponibles'])->name('asignaciones.horarios-disponibles');

    // ================= CHOLETS (ADMIN) =================
    Route::get('/cholets', [AdminController::class, 'cholets'])->name('cholets.index');
    Route::get('/cholets/create', [AdminController::class, 'createCholet'])->name('cholets.create');
    Route::post('/cholets', [AdminController::class, 'storeCholet'])->name('cholets.store');
    Route::get('/cholets/{id}/edit', [AdminController::class, 'editCholet'])->name('cholets.edit');
    Route::put('/cholets/{id}', [AdminController::class, 'updateCholet'])->name('cholets.update');
    Route::delete('/cholets/{id}', [AdminController::class, 'destroyCholet'])->name('cholets.destroy');

    // ================= USUARIOS =================
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');

    // ================= GUÍAS =================
    Route::get('/guias', [GuiaController::class, 'index'])->name('guias.index');
    Route::get('/guias/create', [GuiaController::class, 'create'])->name('guias.create');
    Route::post('/guias', [GuiaController::class, 'store'])->name('guias.store');
    Route::get('/guias/{guia}', [GuiaController::class, 'show'])->name('guias.show');
    Route::get('/guias/{guia}/edit', [GuiaController::class, 'edit'])->name('guias.edit');
    Route::put('/guias/{guia}', [GuiaController::class, 'update'])->name('guias.update');
    Route::delete('/guias/{guia}', [GuiaController::class, 'destroy'])->name('guias.destroy');
    Route::get('/guias/{guia}/asignar-cholets', [GuiaController::class, 'asignarCholetsForm'])->name('guias.asignar-cholets');
    Route::post('/guias/{guia}/asignar-cholets', [GuiaController::class, 'asignarCholets'])->name('guias.asignar-cholets.store');

    // ================= CONFIGURACIÓN =================
    Route::get('/configuracion', [AdminController::class, 'configuracion'])->name('configuracion.index');
    Route::put('/configuracion', [AdminController::class, 'updateConfiguracion'])->name('configuracion.update');
    Route::post('/configuracion/limpiar-cache', [AdminController::class, 'limpiarCache'])->name('configuracion.limpiar-cache');
    
    // ================= HORARIOS =================
    Route::prefix('cholets/{choletId}/horarios')->name('horarios.')->group(function () {
        Route::get('/', [HorarioController::class, 'index'])->name('index');
        Route::post('/', [HorarioController::class, 'store'])->name('store');
        Route::post('/generar-masivo', [HorarioController::class, 'generarMasivo'])->name('generarMasivo');
        Route::delete('/{id}', [HorarioController::class, 'destroy'])->name('destroy');
    });
    
    // ================= EXPERIENCIAS =================
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
    
    // ================= CORREOS MASIVOS Y PERSONALIZADOS =================
    Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
    Route::post('/emails/enviar-todos', [EmailController::class, 'enviarATodos'])->name('emails.enviar-todos');
    Route::post('/emails/enviar-reservas', [EmailController::class, 'enviarConReservas'])->name('emails.enviar-reservas');
    Route::post('/emails/enviar-prueba', [EmailController::class, 'enviarPrueba'])->name('emails.enviar-prueba');
    Route::post('/emails/enviar-personal', [EmailController::class, 'enviarPersonalizado'])->name('emails.enviar-personal');
    Route::post('/emails/enviar-cancelacion', [EmailController::class, 'enviarCancelacion'])->name('emails.enviar-cancelacion');
    
});

/* ================= GUÍA AUTH (LOGIN EXCLUSIVO) ================= */
Route::get('/guia/login', [GuiaAuthController::class, 'showLoginForm'])->name('guia.login');
Route::post('/guia/login', [GuiaAuthController::class, 'login'])->name('guia.login.post');
Route::post('/guia/logout', [GuiaAuthController::class, 'logout'])->name('guia.logout');

/* ================= GUÍA DASHBOARD (PROTEGIDO CON MIDDLEWARE) ================= */
Route::middleware(['guia.auth'])->prefix('guia')->name('guia.')->group(function () {
    Route::get('/', [GuiaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/calendario', [GuiaDashboardController::class, 'calendario'])->name('calendario');
    Route::get('/reservas', [GuiaDashboardController::class, 'reservas'])->name('reservas');
    Route::get('/reserva/{id}', [GuiaDashboardController::class, 'reservaDetalle'])->name('reserva.detalle');
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