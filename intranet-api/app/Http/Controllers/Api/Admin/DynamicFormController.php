<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DynamicFormController extends Controller
{
    public function schemas()
    {
        return response()->json([
            'instituciones' => [
                'title' => 'Instituciones',
                'endpoint' => '/admin/instituciones',
                'primaryKey' => 'id_institucion',
                'fields' => [
                    ['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text', 'required' => true, 'maxlength' => 150],
                    ['name' => 'id_tipo_institucion', 'label' => 'Tipo Institución', 'type' => 'select', 'required' => true, 'optionsKey' => 'tiposInstitucion'],
                    ['name' => 'ruc', 'label' => 'RUC', 'type' => 'text', 'maxlength' => 11],
                    ['name' => 'direccion', 'label' => 'Dirección', 'type' => 'textarea'],
                    ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'maxlength' => 20],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'maxlength' => 100],
                    ['name' => 'sitio_web', 'label' => 'Sitio Web', 'type' => 'text', 'maxlength' => 150],
                    ['name' => 'fecha_creacion', 'label' => 'Fecha Creación', 'type' => 'date'],
                    ['name' => 'rector_director', 'label' => 'Rector/Director', 'type' => 'text', 'maxlength' => 150],
                    ['name' => 'estado', 'label' => 'Activo', 'type' => 'switch'],
                ],
            ],
            'sedes' => [
                'title' => 'Sedes',
                'endpoint' => '/admin/sedes',
                'primaryKey' => 'id_sede',
                'fields' => [
                    ['name' => 'id_institucion', 'label' => 'Institución', 'type' => 'select', 'required' => true, 'optionsKey' => 'instituciones'],
                    ['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text', 'required' => true, 'maxlength' => 100],
                    ['name' => 'direccion', 'label' => 'Dirección', 'type' => 'textarea'],
                    ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'maxlength' => 20],
                    ['name' => 'director_sede', 'label' => 'Director Sede', 'type' => 'text', 'maxlength' => 150],
                    ['name' => 'estado', 'label' => 'Activo', 'type' => 'switch'],
                ],
            ],
            'facultades' => [
                'title' => 'Facultades / Departamentos',
                'endpoint' => '/admin/facultades',
                'primaryKey' => 'id_facultad',
                'fields' => [
                    ['name' => 'id_sede', 'label' => 'Sede', 'type' => 'select', 'required' => true, 'optionsKey' => 'sedes'],
                    ['name' => 'id_tipo_facultad', 'label' => 'Tipo', 'type' => 'select', 'required' => true, 'optionsKey' => 'tiposFacultad'],
                    ['name' => 'nombre', 'label' => 'Nombre', 'type' => 'text', 'required' => true, 'maxlength' => 150],
                    ['name' => 'director', 'label' => 'Director', 'type' => 'text', 'maxlength' => 150],
                    ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'maxlength' => 20],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'maxlength' => 100],
                    ['name' => 'estado', 'label' => 'Activo', 'type' => 'switch'],
                ],
            ],
            'usuarios' => [
                'title' => 'Usuarios',
                'endpoint' => '/admin/usuarios',
                'primaryKey' => 'id_usuario',
                'listColumns' => [
                    'documento',
                    'tipo_documento',
                    'nombres',
                    'apellidos',
                    'fecha_nacimiento',
                    'id_sexo',
                    'email',
                    'roles',
                    'estado',
                    'es_activo',
                ],
                'fields' => [
                    ['name' => 'documento', 'label' => 'Documento', 'type' => 'text', 'required' => true, 'maxlength' => 20],
                    ['name' => 'tipo_documento', 'label' => 'Tipo Documento', 'type' => 'select', 'required' => true, 'optionsKey' => 'tiposDocumento'],
                    ['name' => 'nombres', 'label' => 'Nombres', 'type' => 'text', 'required' => true, 'maxlength' => 100],
                    ['name' => 'apellidos', 'label' => 'Apellidos', 'type' => 'text', 'required' => true, 'maxlength' => 100],
                    ['name' => 'fecha_nacimiento', 'label' => 'Fecha Nacimiento', 'type' => 'date'],
                    ['name' => 'id_sexo', 'label' => 'Sexo', 'type' => 'select', 'optionsKey' => 'sexos'],
                    ['name' => 'telefono', 'label' => 'Teléfono', 'type' => 'text', 'maxlength' => 20],
                    ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true, 'maxlength' => 150],
                    ['name' => 'direccion', 'label' => 'Dirección', 'type' => 'textarea'],
                    ['name' => 'password', 'label' => 'Contraseña', 'type' => 'password', 'required' => true, 'minlength' => 6],
                    ['name' => 'roles', 'label' => 'Roles', 'type' => 'multiselect', 'required' => true, 'optionsKey' => 'roles'],
                    ['name' => 'estado', 'label' => 'Estado', 'type' => 'switch'],
                    ['name' => 'es_activo', 'label' => 'Activo Sistema', 'type' => 'switch'],
                ],
            ],
        ]);
    }

    public function catalogs()
    {
        return response()->json([
            'tiposInstitucion' => DB::table('cat_tipos_institucion')->select('id_tipo_institucion as value', 'nombre as label')->orderBy('nombre')->get(),
            'instituciones' => DB::table('instituciones')->select('id_institucion as value', 'nombre as label')->orderBy('nombre')->get(),
            'sedes' => DB::table('sedes')->select('id_sede as value', 'nombre as label')->orderBy('nombre')->get(),
            'tiposFacultad' => DB::table('cat_tipos_facultad')->select('id_tipo_facultad as value', 'nombre as label')->orderBy('nombre')->get(),
            'roles' => DB::table('cat_roles')->select('id_rol as value', 'nombre as label')->orderBy('nombre')->get(),
            'sexos' => DB::table('cat_sexo')->select('id_sexo as value', 'nombre as label')->orderBy('nombre')->get(),
            'tiposDocumento' => [
                ['value' => 'DNI', 'label' => 'DNI'],
                ['value' => 'RUC', 'label' => 'RUC'],
                ['value' => 'Pasaporte', 'label' => 'Pasaporte'],
                ['value' => 'Otro', 'label' => 'Otro'],
            ],
        ]);
    }
}
