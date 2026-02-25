<?php

use App\Http\Controllers\Api\Admin\FacultadController;
use App\Http\Controllers\Api\Admin\InstitucionController;
use App\Http\Controllers\Api\Admin\NotificacionController as AdminNotificacionController;
use App\Http\Controllers\Api\Admin\SedeController;
use App\Http\Controllers\Api\Admin\UsuarioController;
use App\Http\Controllers\Api\Admin\DynamicFormController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Biblioteca\PrestamoController;
use App\Http\Controllers\Api\Biblioteca\RecursoController;
use App\Http\Controllers\Api\Biblioteca\ReservaController;
use App\Http\Controllers\Api\Bienestar\ActividadController;
use App\Http\Controllers\Api\Bienestar\PsicologiaController;
use App\Http\Controllers\Api\Bienestar\ServicioController;
use App\Http\Controllers\Api\Comun\ArchivoController;
use App\Http\Controllers\Api\Comun\DashboardController;
use App\Http\Controllers\Api\Comun\PerfilController;
use App\Http\Controllers\Api\Docente\AsistenciaController;
use App\Http\Controllers\Api\Docente\CalificacionController;
use App\Http\Controllers\Api\Docente\CursoController;
use App\Http\Controllers\Api\Estudiante\HorarioController;
use App\Http\Controllers\Api\Estudiante\MatriculaController;
use App\Http\Controllers\Api\Estudiante\NotasController;
use App\Http\Controllers\Api\Finanzas\BecaController;
use App\Http\Controllers\Api\Finanzas\PagoController;
use App\Http\Controllers\Api\Finanzas\ReporteController;
use App\Http\Controllers\Api\Practicas\BolsaTrabajoController;
use App\Http\Controllers\Api\Practicas\ConvenioController;
use App\Http\Controllers\Api\Practicas\EgresadoController;
use App\Http\Controllers\Api\Practicas\EmpresaController;
use App\Http\Controllers\Api\Secretaria\ActaController;
use App\Http\Controllers\Api\Secretaria\AdmisionController;
use App\Http\Controllers\Api\Secretaria\CertificadoController;
use App\Http\Controllers\Api\Secretaria\DashboardController as SecretariaDashboardController;
use App\Http\Controllers\Api\Secretaria\TitulacionController;
use App\Http\Controllers\Api\Tutor\SeguimientoController;
use App\Http\Controllers\Api\Tutor\TutoriadoController;
use App\Http\Controllers\Api\OperacionController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    Route::prefix('comun')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::get('perfil', [PerfilController::class, 'show']);
        Route::post('archivos', [ArchivoController::class, 'store']);
    });

    Route::prefix('admin')->middleware('role:Administrador')->group(function () {
        Route::get('formularios/schemas', [DynamicFormController::class, 'schemas']);
        Route::get('formularios/catalogos', [DynamicFormController::class, 'catalogs']);

        Route::get('instituciones', [InstitucionController::class, 'index']);
        Route::post('instituciones', [InstitucionController::class, 'store']);
        Route::put('instituciones/{id}', [InstitucionController::class, 'update']);
        Route::delete('instituciones/{id}', [InstitucionController::class, 'destroy']);

        Route::get('sedes', [SedeController::class, 'index']);
        Route::post('sedes', [SedeController::class, 'store']);
        Route::put('sedes/{id}', [SedeController::class, 'update']);
        Route::delete('sedes/{id}', [SedeController::class, 'destroy']);

        Route::get('facultades', [FacultadController::class, 'index']);
        Route::post('facultades', [FacultadController::class, 'store']);
        Route::put('facultades/{id}', [FacultadController::class, 'update']);
        Route::delete('facultades/{id}', [FacultadController::class, 'destroy']);

        Route::get('usuarios', [UsuarioController::class, 'index']);
        Route::post('usuarios', [UsuarioController::class, 'store']);
        Route::put('usuarios/{id}', [UsuarioController::class, 'update']);
        Route::delete('usuarios/{id}', [UsuarioController::class, 'destroy']);

        Route::get('notificaciones', [AdminNotificacionController::class, 'index']);
        Route::post('notificaciones', [AdminNotificacionController::class, 'store']);
    });

    Route::prefix('docente')->middleware('role:Docente')->group(function () {
        Route::get('cursos', [CursoController::class, 'index']);
        Route::post('calificaciones', [CalificacionController::class, 'store']);
        Route::post('asistencia', [AsistenciaController::class, 'store']);
    });

    Route::prefix('estudiante')->middleware('role:Estudiante')->group(function () {
        Route::get('matriculas', [MatriculaController::class, 'index']);
        Route::get('notas', [NotasController::class, 'index']);
        Route::get('horario', [HorarioController::class, 'index']);
    });

    Route::prefix('tutor')->middleware('role:Tutor')->group(function () {
        Route::get('tutoriados', [TutoriadoController::class, 'index']);
        Route::get('seguimiento', [SeguimientoController::class, 'index']);
    });

    Route::prefix('secretaria')->middleware('role:Secretaria Académica')->group(function () {
        Route::get('dashboard', [SecretariaDashboardController::class, 'index']);
        Route::get('actas', [ActaController::class, 'index']);
        Route::get('certificados', [CertificadoController::class, 'index']);
        Route::get('titulaciones', [TitulacionController::class, 'index']);
        Route::get('admisiones', [AdmisionController::class, 'index']);
    });

    Route::prefix('finanzas')->middleware('role:Tesorero')->group(function () {
        Route::get('pagos', [PagoController::class, 'index']);
        Route::get('becas', [BecaController::class, 'index']);
        Route::get('reportes', [ReporteController::class, 'index']);
    });

    Route::prefix('biblioteca')->middleware('role:Bibliotecario')->group(function () {
        Route::get('recursos', [RecursoController::class, 'index']);
        Route::get('prestamos', [PrestamoController::class, 'index']);
        Route::get('reservas', [ReservaController::class, 'index']);
    });

    Route::prefix('bienestar')->middleware('role:Tutor,Personal Administrativo')->group(function () {
        Route::get('psicologia', [PsicologiaController::class, 'index']);
        Route::get('actividades', [ActividadController::class, 'index']);
        Route::get('servicios', [ServicioController::class, 'index']);
    });

    Route::prefix('practicas')->middleware('role:Coordinador,Jefe de Carrera')->group(function () {
        Route::get('empresas', [EmpresaController::class, 'index']);
        Route::get('convenios', [ConvenioController::class, 'index']);
        Route::get('egresados', [EgresadoController::class, 'index']);
        Route::get('bolsa-trabajo', [BolsaTrabajoController::class, 'index']);
    });

    Route::prefix('operaciones')->group(function () {
        Route::get('catalogos', [OperacionController::class, 'catalogs'])
            ->middleware('role:Administrador,Director,Coordinador,Jefe de Carrera,Docente,Secretaria Académica,Tesorero');

        Route::get('alumnos', [OperacionController::class, 'listarAlumnos'])
            ->middleware('role:Administrador,Director,Secretaria Académica,Coordinador,Jefe de Carrera');

        Route::get('cursos', [OperacionController::class, 'listarCursos'])
            ->middleware('role:Administrador,Director,Secretaria Académica,Coordinador,Jefe de Carrera,Docente');

        Route::post('alumnos', [OperacionController::class, 'createAlumno'])
            ->middleware('role:Administrador,Secretaria Académica');

        Route::put('alumnos/{idEstudiante}', [OperacionController::class, 'updateAlumno'])
            ->middleware('role:Administrador,Secretaria Académica');

        Route::delete('alumnos/{idEstudiante}', [OperacionController::class, 'deleteAlumno'])
            ->middleware('role:Administrador,Secretaria Académica');

        Route::post('cursos', [OperacionController::class, 'createCurso'])
            ->middleware('role:Administrador,Coordinador,Secretaria Académica');

        Route::put('cursos/{idCurso}', [OperacionController::class, 'updateCurso'])
            ->middleware('role:Administrador,Coordinador,Secretaria Académica');

        Route::delete('cursos/{idCurso}', [OperacionController::class, 'deleteCurso'])
            ->middleware('role:Administrador,Coordinador,Secretaria Académica');

        Route::post('notas', [OperacionController::class, 'registrarNota'])
            ->middleware('role:Administrador,Docente');

        Route::post('pagos', [OperacionController::class, 'registrarPago'])
            ->middleware('role:Administrador,Tesorero');

        Route::get('reportes', [OperacionController::class, 'reportes'])
            ->middleware('role:Administrador,Director,Coordinador,Jefe de Carrera,Secretaria Académica,Tesorero');

        Route::get('matriz-permisos', [OperacionController::class, 'matrizPermisos'])
            ->middleware('role:Administrador,Director,Coordinador,Jefe de Carrera,Secretaria Académica,Tesorero');

        Route::put('matriz-permisos/{idRol}', [OperacionController::class, 'actualizarPermisosRol'])
            ->middleware('role:Administrador');
    });
});