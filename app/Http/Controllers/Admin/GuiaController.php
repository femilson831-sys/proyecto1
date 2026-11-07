<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use App\Models\Cholet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GuiaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Guia::query();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('tiene_cholets')) {
            if ($request->tiene_cholets == 'si') {
                $query->has('cholets');
            } elseif ($request->tiene_cholets == 'no') {
                $query->doesntHave('cholets');
            }
        }
        
        $guias = $query->withCount('cholets')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);
        
        return view('admin.guias.index', compact('guias'));
    }

    public function create()
    {
        return view('admin.guias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:guias,email',
            'password' => 'required|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'foto_base64' => 'nullable|string',
            'biografia' => 'nullable|string',
            'idiomas' => 'nullable|array',
            'anos_experiencia' => 'nullable|integer|min:0',
            'especialidad' => 'nullable|string',
            'disponibilidad' => 'nullable|in:disponible,ocupado,descanso',
            'estado' => 'nullable|in:activo,inactivo'
        ]);
        
        $data = $request->only([
            'nombre', 'apellido', 'email', 'telefono', 
            'biografia', 'anos_experiencia', 'especialidad',
            'disponibilidad', 'estado'
        ]);
        
        // Encriptar contraseña
        $data['password'] = bcrypt($request->password);
        
        // Convertir idiomas de array a JSON
        if ($request->has('idiomas') && is_array($request->idiomas)) {
            $data['idiomas'] = json_encode($request->idiomas, JSON_UNESCAPED_UNICODE);
        } elseif ($request->has('idiomas') && is_string($request->idiomas)) {
            $idiomasArray = explode(',', $request->idiomas);
            $data['idiomas'] = json_encode(array_map('trim', $idiomasArray), JSON_UNESCAPED_UNICODE);
        } else {
            $data['idiomas'] = json_encode([]);
        }
        
        // Valores por defecto si no vienen
        $data['disponibilidad'] = $data['disponibilidad'] ?? 'disponible';
        $data['estado'] = $data['estado'] ?? 'activo';
        
        // Procesar foto desde archivo
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('guias', 'public');
            $data['foto'] = '/storage/' . $path;
        }
        // Procesar foto desde cámara (base64)
        elseif ($request->filled('foto_base64') && $request->foto_base64 != 'null') {
            $fotoPath = $this->guardarImagenBase64($request->foto_base64);
            if ($fotoPath) {
                $data['foto'] = $fotoPath;
            }
        }
        
        Guia::create($data);
        
        return redirect()->route('admin.guias.index')
                         ->with('success', '✅ Guía creado exitosamente');
    }

    public function show(Guia $guia)
    {
        $guia->load('cholets');
        return view('admin.guias.show', compact('guia'));
    }

    public function edit(Guia $guia)
    {
        // Decodificar idiomas para el formulario
        if ($guia->idiomas) {
            $guia->idiomas_array = json_decode($guia->idiomas, true) ?? [];
        } else {
            $guia->idiomas_array = [];
        }
        
        return view('admin.guias.edit', compact('guia'));
    }

    public function update(Request $request, Guia $guia)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:guias,email,' . $guia->id,
            'password' => 'nullable|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'foto_base64' => 'nullable|string',
            'eliminar_foto' => 'nullable|boolean',
            'biografia' => 'nullable|string',
            'idiomas' => 'nullable|array',
            'anos_experiencia' => 'nullable|integer|min:0',
            'especialidad' => 'nullable|string',
            'disponibilidad' => 'nullable|in:disponible,ocupado,descanso',
            'estado' => 'nullable|in:activo,inactivo'
        ]);
        
        $data = $request->only([
            'nombre', 'apellido', 'email', 'telefono', 
            'biografia', 'anos_experiencia', 'especialidad',
            'disponibilidad', 'estado'
        ]);
        
        // Actualizar contraseña solo si se proporcionó
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        
        // Convertir idiomas de array a JSON
        if ($request->has('idiomas') && is_array($request->idiomas)) {
            $data['idiomas'] = json_encode($request->idiomas, JSON_UNESCAPED_UNICODE);
        } elseif ($request->has('idiomas') && is_string($request->idiomas)) {
            $idiomasArray = explode(',', $request->idiomas);
            $data['idiomas'] = json_encode(array_map('trim', $idiomasArray), JSON_UNESCAPED_UNICODE);
        } else {
            $data['idiomas'] = json_encode([]);
        }
        
        // Eliminar foto actual si se solicitó
        if ($request->eliminar_foto == '1' || $request->eliminar_foto === true) {
            if ($guia->foto) {
                $this->eliminarFoto($guia->foto);
            }
            $data['foto'] = null;
        }
        
        // Procesar nueva foto desde archivo
        if ($request->hasFile('foto')) {
            if ($guia->foto) {
                $this->eliminarFoto($guia->foto);
            }
            $path = $request->file('foto')->store('guias', 'public');
            $data['foto'] = '/storage/' . $path;
        }
        // Procesar nueva foto desde cámara
        elseif ($request->filled('foto_base64') && $request->foto_base64 != 'null') {
            if ($guia->foto) {
                $this->eliminarFoto($guia->foto);
            }
            $fotoPath = $this->guardarImagenBase64($request->foto_base64);
            if ($fotoPath) {
                $data['foto'] = $fotoPath;
            }
        }
        
        $guia->update($data);
        
        return redirect()->route('admin.guias.index')
                         ->with('success', '✅ Guía actualizado exitosamente');
    }

    public function destroy(Guia $guia)
    {
        if ($guia->foto) {
            $this->eliminarFoto($guia->foto);
        }
        
        $guia->cholets()->detach();
        $guia->delete();
        
        return redirect()->route('admin.guias.index')
                         ->with('success', '✅ Guía eliminado exitosamente');
    }

    public function asignarCholetsForm(Guia $guia)
    {
        $cholets = Cholet::orderBy('nombre')->get();
        $choletsAsignados = $guia->cholets->pluck('id')->toArray();
        
        return view('admin.guias.asignar-cholets', compact('guia', 'cholets', 'choletsAsignados'));
    }

    public function asignarCholets(Request $request, Guia $guia)
    {
        $request->validate([
            'cholets' => 'array',
            'cholets.*' => 'exists:cholets,id',
        ]);
        
        $syncData = [];
        foreach ($request->cholets ?? [] as $choletId) {
            $syncData[$choletId] = [
                'activo' => true,
                'fecha_asignacion' => now()->toDateString(),
            ];
        }
        
        $guia->cholets()->sync($syncData);
        
        $cantidad = count($request->cholets ?? []);
        return redirect()->route('admin.guias.show', $guia)
                         ->with('success', "✅ Se han asignado {$cantidad} cholets al guía");
    }

    /**
     * Guardar imagen desde Base64 (cámara)
     */
    private function guardarImagenBase64($base64)
    {
        try {
            if (!$base64 || $base64 == 'null' || empty($base64)) {
                return null;
            }
            
            $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            $base64 = str_replace(' ', '+', $base64);
            
            $imageBinary = base64_decode($base64);
            
            if ($imageBinary === false || empty($imageBinary)) {
                Log::error('Error al decodificar base64');
                return null;
            }
            
            $filename = 'guia_' . time() . '_' . uniqid() . '.jpg';
            $path = 'guias/' . $filename;
            $saved = Storage::disk('public')->put($path, $imageBinary);
            
            if ($saved) {
                return '/storage/' . $path;
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error en guardarImagenBase64: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Eliminar foto del storage
     */
    private function eliminarFoto($fotoPath)
    {
        try {
            if (!$fotoPath) {
                return;
            }
            
            $relativePath = str_replace('/storage/', '', $fotoPath);
            
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
                Log::info('Foto eliminada: ' . $relativePath);
            }
        } catch (\Exception $e) {
            Log::error('Error al eliminar foto: ' . $e->getMessage());
        }
    }
}