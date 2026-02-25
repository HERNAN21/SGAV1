<?php

namespace App\Http\Controllers\Api\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id_usuario;

        $rows = DB::table('matriculas as m')
            ->join('cursos as c', 'c.id_curso', '=', 'm.id_curso')
            ->join('periodos_academicos as p', 'p.id_periodo', '=', 'm.id_periodo')
            ->leftJoin('materias as mt', 'mt.id_materia', '=', 'c.id_materia')
            ->leftJoin('usuarios as u', 'u.id_usuario', '=', 'c.id_docente')
            ->where('m.id_estudiante', $userId)
            ->where('m.es_retiro', false)
            ->orderBy('p.nombre')
            ->orderBy('c.nombre_curso')
            ->select([
                'c.id_curso',
                'c.nombre_curso',
                'mt.nombre as materia',
                'p.nombre as periodo',
                'c.aula',
                'c.horario',
                'c.tipo_curso',
                DB::raw("CONCAT(COALESCE(u.nombres, ''), ' ', COALESCE(u.apellidos, '')) as docente"),
                'c.link_aula_virtual',
            ])
            ->get();

        return response()->json($rows);
    }
}