<?php

namespace App\Http\Controllers\Api\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id_usuario;

        $rows = DB::table('matriculas as m')
            ->join('cursos as c', 'c.id_curso', '=', 'm.id_curso')
            ->join('periodos_academicos as p', 'p.id_periodo', '=', 'm.id_periodo')
            ->leftJoin('cat_estado_matricula as em', 'em.id_estado_matricula', '=', 'm.id_estado_matricula')
            ->where('m.id_estudiante', $userId)
            ->orderByDesc('m.id_matricula')
            ->select([
                'm.id_matricula',
                'c.nombre_curso',
                'c.aula',
                'c.tipo_curso',
                'p.nombre as periodo',
                'em.nombre as estado_matricula',
                'm.fecha_matricula',
                'm.nota_final',
                'm.es_retiro',
                'm.motivo_retiro',
            ])
            ->get();

        return response()->json($rows);
    }
}