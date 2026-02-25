<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institucion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstitucionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institucion::query();

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nombre', 'like', "%{$search}%")
                    ->orWhere('ruc', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return response()->json($query->orderByDesc('id_institucion')->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'id_tipo_institucion' => ['required', 'integer', 'exists:cat_tipos_institucion,id_tipo_institucion'],
            'ruc' => ['nullable', 'string', 'size:11', Rule::unique('instituciones', 'ruc')],
            'direccion' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'sitio_web' => ['nullable', 'string', 'max:150'],
            'fecha_creacion' => ['nullable', 'date'],
            'rector_director' => ['nullable', 'string', 'max:150'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $institucion = Institucion::create($data);

        return response()->json($institucion, 201);
    }

    public function update(Request $request, int $id)
    {
        $institucion = Institucion::query()->findOrFail($id);

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'id_tipo_institucion' => ['required', 'integer', 'exists:cat_tipos_institucion,id_tipo_institucion'],
            'ruc' => ['nullable', 'string', 'size:11', Rule::unique('instituciones', 'ruc')->ignore($institucion->id_institucion, 'id_institucion')],
            'direccion' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'sitio_web' => ['nullable', 'string', 'max:150'],
            'fecha_creacion' => ['nullable', 'date'],
            'rector_director' => ['nullable', 'string', 'max:150'],
            'logo_url' => ['nullable', 'string', 'max:255'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $institucion->update($data);

        return response()->json($institucion);
    }

    public function destroy(int $id)
    {
        $institucion = Institucion::query()->findOrFail($id);
        $institucion->delete();

        return response()->json(['message' => 'Institución eliminada']);
    }
}