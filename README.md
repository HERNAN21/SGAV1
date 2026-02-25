# SGAV1 · Intranet Académica

Arquitectura solicitada con dashboards independientes por rol:

- Administrador
- Docente
- Tutor
- Estudiante

Documentación principal:

- [Arquitectura backend/frontend por módulos](docs/arquitectura-modulos.md)

Incluye:

- Diseño de módulos Laravel por dominio y rol
- Diseño Vue 3 por dashboard independiente
- Rutas API por prefijo de rol
- Mapeo con tu esquema MySQL real
- Ajustes obligatorios de base de datos para autenticación y tutorías

## Ejecución actual

### Backend (Laravel)

```powershell
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe d:\PROYECTOS\Colegio\SGAV1\intranet-api\artisan serve --host=127.0.0.1 --port=8000
```

### Frontend (Vue)

```powershell
npm --prefix d:\PROYECTOS\Colegio\SGAV1\intranet-frontend run dev
```

### Base de datos

Configuración activa en [intranet-api/.env](intranet-api/.env):

- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3307`
- `DB_DATABASE=intranet_academica`
- `DB_USERNAME=root`