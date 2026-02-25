<?php

namespace App\Http\Controllers\Api\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotasController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id_usuario;

        $rows = DB::table('notas as n')
            ->join('evaluaciones as e', 'e.id_evaluacion', '=', 'n.id_evaluacion')
            ->join('cursos as c', 'c.id_curso', '=', 'n.id_curso')
            ->leftJoin('periodos_academicos as p', 'p.id_periodo', '=', 'c.id_periodo')
            ->where('n.id_estudiante', $userId)
            ->orderByDesc('e.fecha')
            ->select([
                'n.id_nota',
                'c.nombre_curso',
                'p.nombre as periodo',
                'e.nombre as evaluacion',
                'e.tipo_evaluacion',
                'e.porcentaje',
                'e.fecha',
                'n.calificacion',
                'n.estado_calificacion',
                'n.tiene_reclamo',
                'n.motivo_reclamo',
            ])
            ->get();

        return response()->json($rows);
    }
}