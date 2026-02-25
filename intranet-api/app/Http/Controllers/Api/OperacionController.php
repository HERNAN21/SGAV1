<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OperacionController extends Controller
{
    private const OPCION_PERMISO_MAP = [
        'crea_alumnos' => 'crear-usuarios',
        'crea_cursos' => 'crear-cursos',
        'registra_notas' => 'calificar',
        'registra_pagos' => 'registrar-pagos',
        'ver_reportes' => 'ver-reportes',
    ];

    public function catalogs()
    {
        return response()->json([
            'estudiantes' => DB::table('estudiantes as e')
                ->join('usuarios as u', 'u.id_usuario', '=', 'e.id_estudiante')
                ->select('e.id_estudiante as value', DB::raw("CONCAT(u.nombres, ' ', u.apellidos) as label"))
                ->orderBy('label')
                ->get(),
            'docentes' => DB::table('docentes as d')
                ->join('usuarios as u', 'u.id_usuario', '=', 'd.id_docente')
                ->select('d.id_docente as value', DB::raw("CONCAT(u.nombres, ' ', u.apellidos) as label"))
                ->orderBy('label')
                ->get(),
            'facultades' => DB::table('facultades_departamentos')->select('id_facultad as value', 'nombre as label')->orderBy('nombre')->get(),
            'materias' => DB::table('materias')->select('id_materia as value', 'nombre as label')->orderBy('nombre')->get(),
            'programas' => DB::table('programas')->select('id_programa as value', 'nombre as label')->orderBy('nombre')->get(),
            'estadosAcademicos' => DB::table('cat_estado_academico')->select('id_estado_academico as value', 'nombre as label')->orderBy('nombre')->get(),
            'periodos' => DB::table('periodos_academicos')->select('id_periodo as value', 'nombre as label')->orderByDesc('id_periodo')->get(),
            'cursos' => DB::table('cursos')->select('id_curso as value', 'nombre_curso as label')->orderByDesc('id_curso')->get(),
            'evaluaciones' => DB::table('evaluaciones')->select('id_evaluacion as value', 'nombre as label')->orderByDesc('id_evaluacion')->get(),
        ]);
    }

    public function createAlumno(Request $request)
    {
        $data = $request->validate([
            'documento' => ['required', 'string', 'max:20', 'unique:usuarios,documento'],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:6'],
            'id_facultad' => ['required', 'integer', 'exists:facultades_departamentos,id_facultad'],
            'id_programa' => ['required', 'integer', 'exists:programas,id_programa'],
            'codigo_estudiante' => ['required', 'string', 'max:20', 'unique:estudiantes,codigo_estudiante'],
            'fecha_ingreso' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($data) {
            $userId = DB::table('usuarios')->insertGetId([
                'documento' => $data['documento'],
                'tipo_documento' => 'DNI',
                'nombres' => $data['nombres'],
                'apellidos' => $data['apellidos'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'estado' => 1,
                'es_activo' => 1,
                'fecha_registro' => now(),
                'updated_at' => now(),
            ]);

            DB::table('estudiantes')->insert([
                'id_estudiante' => $userId,
                'id_facultad' => $data['id_facultad'],
                'id_programa' => $data['id_programa'],
                'codigo_estudiante' => $data['codigo_estudiante'],
                'fecha_ingreso' => $data['fecha_ingreso'],
                'id_estado_academico' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('usuario_roles')->insert([
                'id_usuario' => $userId,
                'id_rol' => 11,
                'asignado_por' => auth()->id(),
                'fecha_asignacion' => now(),
            ]);
        });

        return response()->json(['message' => 'Alumno creado correctamente'], 201);
    }

    public function listarAlumnos(Request $request)
    {
        $query = DB::table('estudiantes as e')
            ->join('usuarios as u', 'u.id_usuario', '=', 'e.id_estudiante')
            ->leftJoin('programas as p', 'p.id_programa', '=', 'e.id_programa')
            ->leftJoin('facultades_departamentos as f', 'f.id_facultad', '=', 'e.id_facultad')
            ->leftJoin('cat_estado_academico as ea', 'ea.id_estado_academico', '=', 'e.id_estado_academico')
            ->select([
                'e.id_estudiante',
                'e.codigo_estudiante',
                'u.nombres',
                'u.apellidos',
                DB::raw("CONCAT(u.nombres, ' ', u.apellidos) as estudiante"),
                'u.email',
                'u.documento',
                'e.id_facultad',
                'f.nombre as facultad',
                'e.id_programa',
                'p.nombre as programa',
                'e.id_estado_academico',
                'ea.nombre as estado_academico',
                'e.fecha_ingreso',
            ]);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('u.nombres', 'like', "%{$search}%")
                    ->orWhere('u.apellidos', 'like', "%{$search}%")
                    ->orWhere('u.documento', 'like', "%{$search}%")
                    ->orWhere('e.codigo_estudiante', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_programa')) {
            $query->where('e.id_programa', (int)$request->input('id_programa'));
        }

        if ($request->filled('id_estado_academico')) {
            $query->where('e.id_estado_academico', (int)$request->input('id_estado_academico'));
        }

        $rows = $query->orderByDesc('e.id_estudiante')->get();

        return response()->json($rows);
    }

    public function updateAlumno(Request $request, int $idEstudiante)
    {
        $exists = DB::table('estudiantes')->where('id_estudiante', $idEstudiante)->exists();
        if (!$exists) {
            return response()->json(['message' => 'Alumno no existe'], 404);
        }

        $data = $request->validate([
            'documento' => ['required', 'string', 'max:20', Rule::unique('usuarios', 'documento')->ignore($idEstudiante, 'id_usuario')],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', Rule::unique('usuarios', 'email')->ignore($idEstudiante, 'id_usuario')],
            'password' => ['nullable', 'string', 'min:6'],
            'id_facultad' => ['required', 'integer', 'exists:facultades_departamentos,id_facultad'],
            'id_programa' => ['required', 'integer', 'exists:programas,id_programa'],
            'id_estado_academico' => ['required', 'integer', 'exists:cat_estado_academico,id_estado_academico'],
            'codigo_estudiante' => ['required', 'string', 'max:20', Rule::unique('estudiantes', 'codigo_estudiante')->ignore($idEstudiante, 'id_estudiante')],
            'fecha_ingreso' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($idEstudiante, $data) {
            DB::table('usuarios')
                ->where('id_usuario', $idEstudiante)
                ->update([
                    'documento' => $data['documento'],
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'email' => $data['email'],
                    'password' => filled($data['password'] ?? null)
                        ? password_hash($data['password'], PASSWORD_BCRYPT)
                        : DB::raw('password'),
                    'updated_at' => now(),
                ]);

            DB::table('estudiantes')
                ->where('id_estudiante', $idEstudiante)
                ->update([
                    'id_facultad' => $data['id_facultad'],
                    'id_programa' => $data['id_programa'],
                    'id_estado_academico' => $data['id_estado_academico'],
                    'codigo_estudiante' => $data['codigo_estudiante'],
                    'fecha_ingreso' => $data['fecha_ingreso'],
                    'updated_at' => now(),
                ]);
        });

        return response()->json(['message' => 'Alumno actualizado correctamente']);
    }

    public function deleteAlumno(int $idEstudiante)
    {
        $exists = DB::table('estudiantes')->where('id_estudiante', $idEstudiante)->exists();
        if (!$exists) {
            return response()->json(['message' => 'Alumno no existe'], 404);
        }

        DB::transaction(function () use ($idEstudiante) {
            DB::table('usuario_roles')->where('id_usuario', $idEstudiante)->delete();
            DB::table('estudiantes')->where('id_estudiante', $idEstudiante)->delete();
            DB::table('usuarios')->where('id_usuario', $idEstudiante)->delete();
        });

        return response()->json(['message' => 'Alumno eliminado correctamente']);
    }

    public function createCurso(Request $request)
    {
        $data = $request->validate([
            'id_materia' => ['required', 'integer', 'exists:materias,id_materia'],
            'id_docente' => ['required', 'integer', 'exists:docentes,id_docente'],
            'id_periodo' => ['required', 'integer', 'exists:periodos_academicos,id_periodo'],
            'nombre_curso' => ['required', 'string', 'max:150'],
            'cupo_maximo' => ['nullable', 'integer', 'min:1'],
            'aula' => ['nullable', 'string', 'max:50'],
            'horario' => ['nullable', 'string'],
            'tipo_curso' => ['required', 'in:Presencial,Virtual,Híbrido'],
        ]);

        DB::table('cursos')->insert([
            'id_materia' => $data['id_materia'],
            'id_docente' => $data['id_docente'],
            'id_periodo' => $data['id_periodo'],
            'nombre_curso' => $data['nombre_curso'],
            'cupo_maximo' => $data['cupo_maximo'] ?? 40,
            'aula' => $data['aula'] ?? null,
            'horario' => $data['horario'] ?? null,
            'tipo_curso' => $data['tipo_curso'],
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Curso creado correctamente'], 201);
    }

    public function listarCursos(Request $request)
    {
        $query = DB::table('cursos as c')
            ->leftJoin('materias as m', 'm.id_materia', '=', 'c.id_materia')
            ->leftJoin('periodos_academicos as p', 'p.id_periodo', '=', 'c.id_periodo')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'c.id_docente')
            ->select([
                'c.id_curso',
                'c.id_materia',
                'c.id_docente',
                'c.id_periodo',
                'c.nombre_curso',
                'm.nombre as materia',
                DB::raw("CONCAT(COALESCE(u.nombres, ''), ' ', COALESCE(u.apellidos, '')) as docente"),
                'p.nombre as periodo',
                'c.aula',
                'c.horario',
                'c.tipo_curso',
                'c.cupo_maximo',
            ]);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('c.nombre_curso', 'like', "%{$search}%")
                    ->orWhere('m.nombre', 'like', "%{$search}%")
                    ->orWhere('u.nombres', 'like', "%{$search}%")
                    ->orWhere('u.apellidos', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_periodo')) {
            $query->where('c.id_periodo', (int)$request->input('id_periodo'));
        }

        if ($request->filled('id_docente')) {
            $query->where('c.id_docente', (int)$request->input('id_docente'));
        }

        if ($request->filled('id_materia')) {
            $query->where('c.id_materia', (int)$request->input('id_materia'));
        }

        $rows = $query->orderByDesc('c.id_curso')->get();

        return response()->json($rows);
    }

    public function updateCurso(Request $request, int $idCurso)
    {
        $exists = DB::table('cursos')->where('id_curso', $idCurso)->exists();
        if (!$exists) {
            return response()->json(['message' => 'Curso no existe'], 404);
        }

        $data = $request->validate([
            'id_materia' => ['required', 'integer', 'exists:materias,id_materia'],
            'id_docente' => ['required', 'integer', 'exists:docentes,id_docente'],
            'id_periodo' => ['required', 'integer', 'exists:periodos_academicos,id_periodo'],
            'nombre_curso' => ['required', 'string', 'max:150'],
            'cupo_maximo' => ['nullable', 'integer', 'min:1'],
            'aula' => ['nullable', 'string', 'max:50'],
            'horario' => ['nullable', 'string'],
            'tipo_curso' => ['required', 'in:Presencial,Virtual,Híbrido'],
        ]);

        DB::table('cursos')
            ->where('id_curso', $idCurso)
            ->update([
                'id_materia' => $data['id_materia'],
                'id_docente' => $data['id_docente'],
                'id_periodo' => $data['id_periodo'],
                'nombre_curso' => $data['nombre_curso'],
                'cupo_maximo' => $data['cupo_maximo'] ?? 40,
                'aula' => $data['aula'] ?? null,
                'horario' => $data['horario'] ?? null,
                'tipo_curso' => $data['tipo_curso'],
                'updated_at' => now(),
            ]);

        return response()->json(['message' => 'Curso actualizado correctamente']);
    }

    public function deleteCurso(int $idCurso)
    {
        $exists = DB::table('cursos')->where('id_curso', $idCurso)->exists();
        if (!$exists) {
            return response()->json(['message' => 'Curso no existe'], 404);
        }

        DB::transaction(function () use ($idCurso) {
            DB::table('notas')->where('id_curso', $idCurso)->delete();
            DB::table('cursos')->where('id_curso', $idCurso)->delete();
        });

        return response()->json(['message' => 'Curso eliminado correctamente']);
    }

    public function registrarNota(Request $request)
    {
        $data = $request->validate([
            'id_evaluacion' => ['required', 'integer', 'exists:evaluaciones,id_evaluacion'],
            'id_estudiante' => ['required', 'integer', 'exists:estudiantes,id_estudiante'],
            'id_curso' => ['required', 'integer', 'exists:cursos,id_curso'],
            'calificacion' => ['required', 'numeric', 'min:0', 'max:20'],
            'observaciones' => ['nullable', 'string'],
        ]);

        DB::table('notas')->updateOrInsert(
            [
                'id_evaluacion' => $data['id_evaluacion'],
                'id_estudiante' => $data['id_estudiante'],
            ],
            [
                'id_curso' => $data['id_curso'],
                'calificacion' => $data['calificacion'],
                'estado_calificacion' => 'Calificado',
                'fecha_calificacion' => now(),
                'id_docente_calificador' => auth()->id(),
                'observaciones' => $data['observaciones'] ?? null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['message' => 'Nota registrada correctamente']);
    }

    public function registrarPago(Request $request)
    {
        $data = $request->validate([
            'id_estudiante' => ['required', 'integer', 'exists:estudiantes,id_estudiante'],
            'concepto' => ['required', 'string', 'max:120'],
            'monto' => ['required', 'numeric', 'min:0.1'],
            'fecha_pago' => ['required', 'date'],
            'medio_pago' => ['required', 'in:Efectivo,Transferencia,Tarjeta,Yape,Plin,Otro'],
            'observaciones' => ['nullable', 'string'],
        ]);

        DB::table('pagos')->insert([
            'id_estudiante' => $data['id_estudiante'],
            'concepto' => $data['concepto'],
            'monto' => $data['monto'],
            'fecha_pago' => $data['fecha_pago'],
            'medio_pago' => $data['medio_pago'],
            'registrado_por' => auth()->id(),
            'observaciones' => $data['observaciones'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Pago registrado correctamente'], 201);
    }

    public function reportes()
    {
        $data = [
            'total_estudiantes' => DB::table('estudiantes')->count(),
            'total_cursos' => DB::table('cursos')->count(),
            'total_notas_registradas' => DB::table('notas')->count(),
            'promedio_general_notas' => round((float)DB::table('notas')->avg('calificacion'), 2),
            'total_pagos' => DB::table('pagos')->count(),
            'monto_total_pagado' => round((float)DB::table('pagos')->sum('monto'), 2),
        ];

        return response()->json($data);
    }

    public function matrizPermisos()
    {
        $roles = DB::table('cat_roles')
            ->whereIn('nombre', [
                'Administrador',
                'Director',
                'Coordinador',
                'Jefe de Carrera',
                'Docente',
                'Tutor',
                'Secretaria Académica',
                'Tesorero',
                'Estudiante',
            ])
            ->orderBy('id_rol')
            ->get(['id_rol', 'nombre']);

        $permisosByRol = DB::table('role_permisos as rp')
            ->join('cat_permisos as cp', 'cp.id_permiso', '=', 'rp.id_permiso')
            ->select('rp.id_rol', 'cp.nombre')
            ->get()
            ->groupBy('id_rol')
            ->map(fn ($rows) => collect($rows)->pluck('nombre')->values()->all());

        $result = $roles->map(function ($rol) use ($permisosByRol) {
            $permisos = $permisosByRol->get($rol->id_rol, []);

            return [
                'id_rol' => $rol->id_rol,
                'rol' => $rol->nombre,
                'crea_alumnos' => in_array('crear-usuarios', $permisos, true),
                'crea_cursos' => in_array('crear-cursos', $permisos, true),
                'registra_notas' => in_array('calificar', $permisos, true),
                'registra_pagos' => in_array('registrar-pagos', $permisos, true),
                'ver_reportes' => in_array('ver-reportes', $permisos, true),
            ];
        })->values();

        return response()->json($result);
    }

    public function actualizarPermisosRol(Request $request, int $idRol)
    {
        $request->validate([
            'crea_alumnos' => ['required', 'boolean'],
            'crea_cursos' => ['required', 'boolean'],
            'registra_notas' => ['required', 'boolean'],
            'registra_pagos' => ['required', 'boolean'],
            'ver_reportes' => ['required', 'boolean'],
        ]);

        $existsRole = DB::table('cat_roles')->where('id_rol', $idRol)->exists();
        if (!$existsRole) {
            return response()->json(['message' => 'Rol no existe'], 404);
        }

        DB::transaction(function () use ($request, $idRol) {
            foreach (self::OPCION_PERMISO_MAP as $field => $permisoNombre) {
                $permisoId = DB::table('cat_permisos')->where('nombre', $permisoNombre)->value('id_permiso');
                if (!$permisoId) {
                    continue;
                }

                $enabled = (bool)$request->boolean($field);
                $exists = DB::table('role_permisos')
                    ->where('id_rol', $idRol)
                    ->where('id_permiso', $permisoId)
                    ->exists();

                if ($enabled && !$exists) {
                    DB::table('role_permisos')->insert([
                        'id_rol' => $idRol,
                        'id_permiso' => $permisoId,
                    ]);
                }

                if (!$enabled && $exists) {
                    DB::table('role_permisos')
                        ->where('id_rol', $idRol)
                        ->where('id_permiso', $permisoId)
                        ->delete();
                }
            }
        });

        return response()->json(['message' => 'Permisos actualizados correctamente']);
    }
}
