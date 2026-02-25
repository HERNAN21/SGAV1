# Arquitectura por módulos · Intranet Académica (Laravel + Vue)

## 1. Objetivo
Implementar una intranet con **4 dashboards independientes por tipo de usuario**:
- Administración
- Docente
- Tutor
- Estudiante

Stack:
- Backend: Laravel 11 API + Sanctum
- Frontend: Vue 3 SPA + Pinia + Vue Router
- DB: MySQL 8 (`intranet_academica`)
- Storage: local/S3

---

## 2. Ajustes mínimos al esquema SQL (obligatorios)

Tu base está muy bien estructurada, pero para autenticación y módulo tutor hacen falta 2 ajustes:

### 2.1 Contraseña para login
La tabla `usuarios` no tiene campo password.

```sql
ALTER TABLE usuarios
ADD COLUMN password VARCHAR(255) NOT NULL AFTER email;
```

### 2.2 Relación Tutor ↔ Estudiante
No existe tabla de vinculación para el dashboard de tutor.

```sql
CREATE TABLE tutor_estudiantes (
    id_tutor INT NOT NULL,
    id_estudiante INT NOT NULL,
    parentesco VARCHAR(50),
    es_responsable_financiero BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id_tutor, id_estudiante),
    FOREIGN KEY (id_tutor) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante)
);
```

---

## 3. Arquitectura Backend (Laravel 11)

## 3.1 Estructura recomendada

```text
intranet-api/
  app/
    Http/
      Controllers/
        Api/
          Auth/
          Admin/
          Docente/
          Tutor/
          Estudiante/
    Models/
      Usuario.php
      Rol.php
      UsuarioRol.php
      Institucion.php
      Sede.php
      Facultad.php
      Estudiante.php
      Docente.php
      Curso.php
      Matricula.php
      Evaluacion.php
      Nota.php
      Asistencia.php
      Notificacion.php
      Archivo.php
    Services/
      Auth/
      Admin/
      Docente/
      Tutor/
      Estudiante/
      Notificaciones/
      Archivos/
    Policies/
    Repositories/
  routes/api.php
```

## 3.2 Modelo de autenticación con tabla personalizada

Usa `Usuario` como modelo autenticable:
- tabla: `usuarios`
- PK: `id_usuario`
- login por `email` + `password`
- token con Sanctum

Campos clave:
- `protected $table = 'usuarios';`
- `protected $primaryKey = 'id_usuario';`
- `public $timestamps = false;` (si no agregas `created_at`/`updated_at`)
- `use HasApiTokens`

## 3.3 Roles y permisos

Para mantener compatibilidad con tu esquema:
- `cat_roles` y `usuario_roles` como fuente funcional de rol académico.
- Middleware de rol propio (`role:Administrador`, `role:Docente`, etc.) validando `usuario_roles`.

Si quieres usar Spatie también:
- úsalo para permisos finos (`create-notification`, `grade-students`, etc.)
- y sincroniza nombres de `cat_roles` con roles de Spatie por seeder.

## 3.4 Rutas API por dashboard independiente

```php
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
  Route::post('/auth/logout', [AuthController::class, 'logout']);
  Route::get('/auth/me', [AuthController::class, 'me']);

  Route::prefix('admin')->middleware('role:Administrador')->group(function () {
    // instituciones, sedes, facultades, usuarios, periodos, programas, materias, cursos
  });

  Route::prefix('docente')->middleware('role:Docente')->group(function () {
    // mis cursos, evaluaciones, notas, asistencias, notificaciones del curso
  });

  Route::prefix('tutor')->middleware('role:Tutor')->group(function () {
    // mis estudiantes, notas de mis estudiantes, asistencias, notificaciones
  });

  Route::prefix('estudiante')->middleware('role:Estudiante')->group(function () {
    // mi perfil, mis cursos, mis notas, mi asistencia, mis notificaciones
  });
});
```

## 3.5 Módulos Backend por rol

### A) Administración
Responsabilidades:
- Gestión institucional: `instituciones`, `sedes`, `facultades_departamentos`
- Gestión académica: `programas`, `materias`, `periodos_academicos`, `cursos`
- Gestión de personas: `usuarios`, asignación `usuario_roles`
- Gestión de matrículas: `matriculas`
- Gestión documental global: `archivos`, `archivo_roles`, `archivo_entidades`
- Notificaciones masivas/segmentadas: `notificaciones`, `notificacion_roles`, `notificacion_cursos`

### B) Docente
Responsabilidades:
- Ver sus cursos (`cursos` por `id_docente`)
- Crear evaluaciones (`evaluaciones`)
- Registrar notas (`notas`)
- Registrar asistencia (`asistencias`)
- Publicar recursos de curso (`archivos` + `archivo_entidades` tipo `CURSO`)

### C) Tutor
Responsabilidades:
- Ver estudiantes asociados (`tutor_estudiantes`)
- Consultar notas y asistencia del estudiante
- Consultar notificaciones dirigidas al tutor o por curso del estudiante

### D) Estudiante
Responsabilidades:
- Ver cursos matriculados (`matriculas`)
- Ver evaluaciones y notas (`evaluaciones`, `notas`)
- Ver asistencia (`asistencias`)
- Ver/descargar archivos permitidos (`archivo_roles`)
- Ver notificaciones personales (`notificacion_usuarios`)

## 3.6 Notificaciones (servicio transversal)

Servicio único `NotificationService` con destinos:
- Usuario específico (`notificacion_usuarios`)
- Por rol (`notificacion_roles`)
- Por curso (`notificacion_cursos`)

Estados por usuario:
- No Leída
- Leída
- Archivada
- Eliminada

## 3.7 Gestión documental (servicio transversal)

Flujo:
1. Subir archivo a disco local o S3
2. Guardar metadata en `archivos`
3. Asociar a entidad en `archivo_entidades`
4. Definir permisos por rol en `archivo_roles`
5. Auditar descargas en `archivo_descargas`

---

## 4. Arquitectura Frontend (Vue 3)

## 4.1 Estructura recomendada

```text
intranet-frontend/
  src/
    modules/
      admin/
        views/
        components/
        services/
        store/
      docente/
        views/
        components/
        services/
        store/
      tutor/
        views/
        components/
        services/
        store/
      estudiante/
        views/
        components/
        services/
        store/
    core/
      api/
        http.js
      auth/
        auth.store.js
        auth.guard.js
      layout/
        AdminLayout.vue
        DocenteLayout.vue
        TutorLayout.vue
        EstudianteLayout.vue
    router/
      index.js
```

## 4.2 Routing por rol (dashboard independiente)

- `/admin/*` → solo Administrador
- `/docente/*` → solo Docente
- `/tutor/*` → solo Tutor
- `/estudiante/*` → solo Estudiante

`beforeEach` global:
1. valida token
2. obtiene usuario (`/auth/me`)
3. valida rol para la ruta
4. redirige al dashboard correspondiente

## 4.3 Módulos frontend por dashboard

### Admin Dashboard
- Resumen institucional
- Gestión usuarios/roles
- Gestión académica (periodos, programas, materias, cursos)
- Matrículas
- Centro de notificaciones
- Biblioteca documental

### Docente Dashboard
- Mis cursos
- Evaluaciones
- Registro de notas
- Registro de asistencia
- Publicación de recursos

### Tutor Dashboard
- Mis estudiantes
- Progreso académico
- Asistencia
- Notificaciones

### Estudiante Dashboard
- Mi horario y cursos
- Mis notas
- Mi asistencia
- Mis notificaciones
- Mis archivos

## 4.4 Estado global (Pinia)

Stores mínimos:
- `useAuthStore`
- `useNotificationStore`
- `useFileStore`
- `useAdminStore`
- `useDocenteStore`
- `useTutorStore`
- `useEstudianteStore`

---

## 5. API mínima inicial (MVP)

## 5.1 Auth
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`

## 5.2 Admin
- `GET/POST/PUT/DELETE /api/admin/instituciones`
- `GET/POST/PUT/DELETE /api/admin/usuarios`
- `GET/POST/PUT/DELETE /api/admin/cursos`
- `POST /api/admin/notificaciones`

## 5.3 Docente
- `GET /api/docente/cursos`
- `POST /api/docente/evaluaciones`
- `POST /api/docente/notas/lote`
- `POST /api/docente/asistencias/lote`

## 5.4 Tutor
- `GET /api/tutor/estudiantes`
- `GET /api/tutor/estudiantes/{id}/notas`
- `GET /api/tutor/estudiantes/{id}/asistencia`

## 5.5 Estudiante
- `GET /api/estudiante/cursos`
- `GET /api/estudiante/notas`
- `GET /api/estudiante/asistencia`
- `GET /api/estudiante/notificaciones`

---

## 6. Orden de implementación recomendado

1. Autenticación (`usuarios`, password, Sanctum)
2. Middleware rol + rutas por prefijo
3. Dashboard Estudiante (lectura)
4. Dashboard Docente (notas/asistencia)
5. Dashboard Tutor (seguimiento)
6. Dashboard Admin (gestión completa)
7. Notificaciones y archivos

---

## 7. Seguridad y buenas prácticas

- Validación de request por FormRequest
- Policies para acceso a cursos/notas
- Rate limit en login
- Auditoría: log de cambios sensibles
- CORS restringido por ambiente
- Subida de archivos con validación MIME + tamaño

---

## 8. Resultado esperado

- 1 backend Laravel unificado
- 1 frontend Vue con 4 módulos independientes por rol
- Misma base de datos relacional como fuente única
- Notificaciones y gestión documental como servicios transversales
