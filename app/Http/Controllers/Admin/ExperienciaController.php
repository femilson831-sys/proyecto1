<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'slug' => 'nullable|string|max:255|unique:experiencias,slug',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0', // Ahora es precio en Bolivianos
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'duracion_horas' => 'required|integer|min:1|max:24',
            'incluye' => 'nullable|string',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
        ]);

        // Generar slug si no se proporcionó
        $slug = $request->slug ?: Str::slug($request->nombre);
        
        // Verificar que el slug sea único
        $originalSlug = $slug;
        $counter = 1;
        while (Experiencia::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Experiencia::create([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio, // Precio en Bolivianos
            'icono' => $request->icono,
            'color' => $request->color,
            'duracion_horas' => $request->duracion_horas,
            'incluye' => $request->incluye,
            'activo' => $request->activo ?? 1,
            'orden' => $request->orden ?? 0,
        ]);

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
            'slug' => 'nullable|string|max:255|unique:experiencias,slug,' . $id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0', // Precio en Bolivianos
            'icono' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'duracion_horas' => 'required|integer|min:1|max:24',
            'incluye' => 'nullable|string',
            'activo' => 'boolean',
            'orden' => 'integer|min:0',
        ]);

        // Generar slug si no se proporcionó o si cambió el nombre
        $slug = $request->slug;
        if (empty($slug)) {
            $slug = Str::slug($request->nombre);
        }
        
        // Verificar que el slug sea único (excluyendo el actual)
        if ($slug !== $experiencia->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Experiencia::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $experiencia->update([
            'nombre' => $request->nombre,
            'slug' => $slug,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio, // Precio en Bolivianos
            'icono' => $request->icono,
            'color' => $request->color,
            'duracion_horas' => $request->duracion_horas,
            'incluye' => $request->incluye,
            'activo' => $request->activo ?? $experiencia->activo,
            'orden' => $request->orden ?? $experiencia->orden,
        ]);

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