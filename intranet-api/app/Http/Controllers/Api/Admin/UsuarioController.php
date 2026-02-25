<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('rolesCatalogo');

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nombres', 'like', "%{$search}%")
                    ->orWhere('apellidos', 'like', "%{$search}%")
                    ->orWhere('documento', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return response()->json($query->orderByDesc('id_usuario')->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'documento' => ['required', 'string', 'max:20', Rule::unique('usuarios', 'documento')],
            'tipo_documento' => ['required', Rule::in(['DNI', 'RUC', 'Pasaporte', 'Otro'])],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'id_sexo' => ['nullable', 'integer', 'exists:cat_sexo,id_sexo'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', Rule::unique('usuarios', 'email')],
            'direccion' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:6'],
            'estado' => ['sometimes', 'boolean'],
            'es_activo' => ['sometimes', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:cat_roles,id_rol'],
        ]);

        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $data['password'] = Hash::make($data['password']);

        $usuario = User::create($data);

        if (!empty($roles)) {
            $syncData = [];
            foreach ($roles as $idRol) {
                $syncData[$idRol] = ['asignado_por' => auth()->id()];
            }
            $usuario->rolesCatalogo()->sync($syncData);
        }

        return response()->json($usuario->load('rolesCatalogo'), 201);
    }

    public function update(Request $request, int $id)
    {
        $usuario = User::query()->findOrFail($id);

        $data = $request->validate([
            'documento' => ['required', 'string', 'max:20', Rule::unique('usuarios', 'documento')->ignore($usuario->id_usuario, 'id_usuario')],
            'tipo_documento' => ['required', Rule::in(['DNI', 'RUC', 'Pasaporte', 'Otro'])],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'id_sexo' => ['nullable', 'integer', 'exists:cat_sexo,id_sexo'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', Rule::unique('usuarios', 'email')->ignore($usuario->id_usuario, 'id_usuario')],
            'direccion' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6'],
            'estado' => ['sometimes', 'boolean'],
            'es_activo' => ['sometimes', 'boolean'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:cat_roles,id_rol'],
        ]);

        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        if (is_array($roles)) {
            $syncData = [];
            foreach ($roles as $idRol) {
                $syncData[$idRol] = ['asignado_por' => auth()->id()];
            }
            $usuario->rolesCatalogo()->sync($syncData);
        }

        return response()->json($usuario->load('rolesCatalogo'));
    }

    public function destroy(int $id)
    {
        $usuario = User::query()->findOrFail($id);
        $usuario->rolesCatalogo()->detach();
        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}