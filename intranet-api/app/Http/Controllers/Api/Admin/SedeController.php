<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index(Request $request)
    {
        $query = Sede::query();

        if ($request->filled('id_institucion')) {
            $query->where('id_institucion', (int)$request->input('id_institucion'));
        }

        if ($search = $request->string('search')->toString()) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        return response()->json($query->orderByDesc('id_sede')->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_institucion' => ['required', 'integer', 'exists:instituciones,id_institucion'],
            'nombre' => ['required', 'string', 'max:100'],
            'direccion' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'director_sede' => ['nullable', 'string', 'max:150'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $sede = Sede::create($data);

        return response()->json($sede, 201);
    }

    public function update(Request $request, int $id)
    {
        $sede = Sede::query()->findOrFail($id);

        $data = $request->validate([
            'id_institucion' => ['required', 'integer', 'exists:instituciones,id_institucion'],
            'nombre' => ['required', 'string', 'max:100'],
            'direccion' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'director_sede' => ['nullable', 'string', 'max:150'],
            'estado' => ['sometimes', 'boolean'],
        ]);

        $sede->update($data);

        return response()->json($sede);
    }

    public function destroy(int $id)
    {
        $sede = Sede::query()->findOrFail($id);
        $sede->delete();

        return response()->json(['message' => 'Sede eliminada']);
    }
}