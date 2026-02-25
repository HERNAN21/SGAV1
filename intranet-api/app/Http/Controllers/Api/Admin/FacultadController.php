<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacultadDepartamento;
use Illuminate\Http\Request;

class FacultadController extends Controller
{
    public function index(Request $request)
    {
        $query = FacultadDepartamento::query();

        if ($request->filled('id_sede')) {
            $query->where('id_sede', (int)$request->input('id_sede'));
        }

        if ($search = $request->string('search')->toString()) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        return response()->json($query->orderByDesc('id_facultad')->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_sede' => ['required', 'integer', 'exists:sedes,id_sede'],
            'id_tipo_facultad' => ['required', 'integer', 'exists:cat_tipos_facultad,id_tipo_facultad'],
            'nombre' => ['required', 'string', 'max:150'],
            'director' => ['nullable', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $facultad = FacultadDepartamento::create($data);

        return response()->json($facultad, 201);
    }

    public function update(Request $request, int $id)
    {
        $facultad = FacultadDepartamento::query()->findOrFail($id);

        $data = $request->validate([
            'id_sede' => ['required', 'integer', 'exists:sedes,id_sede'],
            'id_tipo_facultad' => ['required', 'integer', 'exists:cat_tipos_facultad,id_tipo_facultad'],
            'nombre' => ['required', 'string', 'max:150'],
            'director' => ['nullable', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $facultad->update($data);

        return response()->json($facultad);
    }

    public function destroy(int $id)
    {
        $facultad = FacultadDepartamento::query()->findOrFail($id);
        $facultad->delete();

        return response()->json(['message' => 'Facultad/Departamento eliminado']);
    }
}