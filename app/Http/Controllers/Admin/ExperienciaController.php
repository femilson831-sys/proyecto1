<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use Illuminate\Http\Request;

class ExperienciaController extends Controller
{
    /**
     * Listar experiencias
     */
    public function index()
    {
        $experiencias = Experiencia::orderBy('orden', 'asc')->get();
        return view('admin.experiencias.index', compact('experiencias'));
    }

    /**
     * Formulario crear experiencia
     */
    public function create()
    {
        return view('admin.experiencias.create');
    }

    /**
     * Guardar experiencia
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:experiencias,nombre',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_bs' => 'nullable|numeric|min:0',
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'duracion_horas' => 'required|integer|min:1|max:24',
            'incluye' => 'nullable|string',
            'orden' => 'integer|min:0',
        ]);

        Experiencia::create($request->all());

        return redirect()->route('admin.experiencias.index')
            ->with('success', '✅ Experiencia creada correctamente');
    }

    /**
     * Mostrar detalle de experiencia
     */
    public function show($id)
    {
        $experiencia = Experiencia::findOrFail($id);
        return view('admin.experiencias.show', compact('experiencia'));
    }

    /**
     * Formulario editar experiencia
     */
    public function edit($id)
    {
        $experiencia = Experiencia::findOrFail($id);
        return view('admin.experiencias.edit', compact('experiencia'));
    }

    /**
     * Actualizar experiencia
     */
    public function update(Request $request, $id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50|unique:experiencias,nombre,' . $id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'precio_bs' => 'nullable|numeric|min:0',
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'duracion_horas' => 'required|integer|min:1|max:24',
            'incluye' => 'nullable|string',
            'orden' => 'integer|min:0',
        ]);

        $experiencia->update($request->all());

        return redirect()->route('admin.experiencias.index')
            ->with('success', '✅ Experiencia actualizada correctamente');
    }

    /**
     * Eliminar experiencia
     */
    public function destroy($id)
    {
        $experiencia = Experiencia::findOrFail($id);
        
        // Verificar si tiene reservas asociadas
        if ($experiencia->reservas()->count() > 0) {
            return back()->with('error', '❌ No se puede eliminar una experiencia con reservas asociadas');
        }
        
        $experiencia->delete();

        return redirect()->route('admin.experiencias.index')
            ->with('success', '🗑️ Experiencia eliminada correctamente');
    }

    /**
     * Cambiar estado (activar/desactivar)
     */
    public function toggleEstado($id)
    {
        $experiencia = Experiencia::findOrFail($id);
        $experiencia->update(['activo' => !$experiencia->activo]);
        
        $estado = $experiencia->activo ? 'activada' : 'desactivada';
        return redirect()->route('admin.experiencias.index')
            ->with('success', "✅ Experiencia {$estado} correctamente");
    }
}