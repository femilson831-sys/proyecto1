<?php

namespace App\Http\Controllers;

use App\Models\Cholet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CholetController extends Controller
{
    // =========================
    // MOSTRAR TODOS LOS CHOLETS
    // =========================
    public function index()
    {
        $cholets = Cholet::all();
        return view('welcome', compact('cholets'));
    }

    // =========================
    // MOSTRAR DETALLE CHOLET
    // =========================
    public function show($id)
    {
        $cholet = Cholet::findOrFail($id);
        return view('cholets.show', compact('cholet'));
    }

    // =========================
    // FORMULARIO CREAR
    // =========================
    public function create()
    {
        return view('cholets.create');
    }

    // =========================
    // GUARDAR CHOLET (CORREGIDO CON CATEGORIA)
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:255',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cholet = new Cholet();
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        // Guardar la imagen
        if ($request->hasFile('imagen')) {
            $imagePath = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = basename($imagePath);
        }

        $cholet->save();

        return redirect()->route('cholets.index')
            ->with('success', 'Cholet creado correctamente');
    }

    // =========================
    // FORMULARIO EDITAR
    // =========================
    public function edit($id)
    {
        $cholet = Cholet::findOrFail($id);
        return view('cholets.edit', compact('cholet'));
    }

    // =========================
    // ACTUALIZAR CHOLET (CORREGIDO CON CATEGORIA)
    // =========================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cholet = Cholet::findOrFail($id);
        $cholet->nombre = $request->nombre;
        $cholet->ubicacion = $request->ubicacion;
        $cholet->descripcion = $request->descripcion;
        $cholet->categoria = $request->categoria;

        // Si se sube una nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($cholet->imagen) {
                $oldImagePath = storage_path('app/public/cholets/' . $cholet->imagen);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Guardar nueva imagen
            $imagePath = $request->file('imagen')->store('cholets', 'public');
            $cholet->imagen = basename($imagePath);
        }

        $cholet->save();

        return redirect()->route('cholets.index')
            ->with('success', 'Cholet actualizado correctamente');
    }

    // =========================
    // ELIMINAR CHOLET
    // =========================
    public function destroy($id)
    {
        $cholet = Cholet::findOrFail($id);
        
        // Eliminar la imagen física
        if ($cholet->imagen) {
            $imagePath = storage_path('app/public/cholets/' . $cholet->imagen);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $cholet->delete();

        return redirect()->route('cholets.index')
            ->with('success', 'Cholet eliminado correctamente');
    }
}