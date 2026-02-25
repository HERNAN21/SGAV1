/* =========================================================
   INTRANET ACADÉMICA PROFESIONAL - COMPLETA CON MENÚS
   Motor: MySQL 8+
   Actualizado: 2026-02-25
   ========================================================= */

DROP DATABASE IF EXISTS intranet_academica;
CREATE DATABASE intranet_academica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE intranet_academica;

/* =========================================================
   ================== 1. TABLAS MAESTRAS (CATÁLOGOS) =======
   ========================================================= */

CREATE TABLE cat_tipos_institucion (
    id_tipo_institucion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cat_tipos_institucion (nombre, descripcion) VALUES
('Secundaria', 'Institución de educación secundaria'),
('Instituto', 'Instituto técnico superior'),
('Universidad', 'Universidad con pregrado y postgrado'),
('Centro Profesional', 'Centro de formación profesional');


CREATE TABLE cat_tipos_facultad (
    id_tipo_facultad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_tipos_facultad (nombre) VALUES
('Facultad'), ('Departamento'), ('Área Académica');


CREATE TABLE cat_roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    nivel_acceso INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cat_roles (nombre, descripcion, nivel_acceso) VALUES
('Administrador', 'Acceso total al sistema', 2),
('Director', 'Directivo de la institución', 2),
('Coordinador', 'Coordinador académico', 1),
('Jefe de Carrera', 'Jefe de programa académico', 1),
('Docente', 'Profesor/Docente', 0),
('Tutor', 'Tutor académico/Psicólogo', 0),
('Secretaria Académica', 'Personal de secretaría', 1),
('Tesorero', 'Responsable de finanzas', 1),
('Bibliotecario', 'Encargado de biblioteca', 0),
('Personal Administrativo', 'Personal administrativo general', 0),
('Estudiante', 'Estudiante de la institución', 0),
('Apoderado', 'Apoderado/Tutor de estudiante', 0);


CREATE TABLE cat_permisos (
    id_permiso INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    modulo VARCHAR(50),
    accion VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cat_permisos (nombre, descripcion, modulo, accion) VALUES
('crear-usuarios', 'Crear nuevos usuarios', 'usuarios', 'create'),
('editar-usuarios', 'Editar información de usuarios', 'usuarios', 'update'),
('ver-usuarios', 'Ver listado de usuarios', 'usuarios', 'view'),
('eliminar-usuarios', 'Eliminar usuarios', 'usuarios', 'delete'),
('crear-cursos', 'Crear cursos/asignaturas', 'academico', 'create'),
('calificar', 'Registrar calificaciones', 'academico', 'update'),
('ver-calificaciones', 'Ver calificaciones', 'academico', 'view'),
('registrar-asistencia', 'Registrar asistencias', 'academico', 'create'),
('ver-pagos', 'Ver información de pagos', 'finanzas', 'view'),
('registrar-pagos', 'Registrar pagos', 'finanzas', 'create'),
('emitir-boletas', 'Emitir boletas', 'finanzas', 'create'),
('gestionar-becas', 'Gestionar becas', 'finanzas', 'update'),
('generar-reportes', 'Generar reportes', 'reportes', 'create'),
('ver-reportes', 'Ver reportes', 'reportes', 'view'),
('exportar-reportes', 'Exportar reportes a PDF/Excel', 'reportes', 'create');


CREATE TABLE role_permisos (
    id_rol INT NOT NULL,
    id_permiso INT NOT NULL,
    PRIMARY KEY (id_rol, id_permiso),
    FOREIGN KEY (id_rol) REFERENCES cat_roles(id_rol),
    FOREIGN KEY (id_permiso) REFERENCES cat_permisos(id_permiso)
);


CREATE TABLE cat_estado_academico (
    id_estado_academico INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_estado_academico (nombre) VALUES
('Activo'), ('Egresado'), ('Suspendido'), ('Retirado'), ('Licencia');


CREATE TABLE cat_tipo_contrato (
    id_tipo_contrato INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_tipo_contrato (nombre) VALUES
('Tiempo Completo'), ('Medio Tiempo'), ('Por Horas'), ('Contratado');


CREATE TABLE cat_niveles_programa (
    id_nivel INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_niveles_programa (nombre) VALUES
('Secundaria'), ('Técnico'), ('Tecnólogo'), ('Pregrado'), ('Postgrado'), ('Especialización');


CREATE TABLE cat_estado_periodo (
    id_estado_periodo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_estado_periodo (nombre) VALUES
('Planificado'), ('Activo'), ('Cerrado'), ('Suspendido');


CREATE TABLE cat_estado_matricula (
    id_estado_matricula INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_estado_matricula (nombre) VALUES
('Activa'), ('Cancelada'), ('Aprobada'), ('Reprobada'), ('Retirada');


CREATE TABLE cat_estado_asistencia (
    id_estado_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_estado_asistencia (nombre) VALUES
('Presente'), ('Ausente'), ('Justificado'), ('Tardanza');


CREATE TABLE cat_sexo (
    id_sexo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO cat_sexo (nombre) VALUES
('Masculino'), ('Femenino'), ('Otro');


CREATE TABLE cat_tipo_notificacion (
    id_tipo_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_tipo_notificacion (nombre) VALUES
('General'), ('Académica'), ('Financiera'),
('Disciplinaria'), ('Sistema'), ('Emergencia'), ('Mantenimiento');


CREATE TABLE cat_prioridad_notificacion (
    id_prioridad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    nivel INT NOT NULL
);

INSERT INTO cat_prioridad_notificacion (nombre, nivel) VALUES
('Baja',1), ('Media',2), ('Alta',3), ('Crítica',4);


CREATE TABLE cat_estado_notificacion_usuario (
    id_estado_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO cat_estado_notificacion_usuario (nombre) VALUES
('No Leída'), ('Leída'), ('Archivada'), ('Eliminada');


CREATE TABLE cat_tipo_archivo (
    id_tipo_archivo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO cat_tipo_archivo (nombre) VALUES
('PDF'),
('Documento Word'),
('Hoja de Cálculo'),
('Imagen'),
('Video'),
('Audio'),
('Presentación'),
('Comprimido'),
('Otro');


/* =========================================================
   ================== 2. USUARIOS Y AUTENTICACIÓN ===========
   ========================================================= */

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    
    documento VARCHAR(20) UNIQUE NOT NULL,
    tipo_documento ENUM('DNI', 'RUC', 'Pasaporte', 'Otro') DEFAULT 'DNI',
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    id_sexo INT,
    
    telefono VARCHAR(20),
    email VARCHAR(150) UNIQUE NOT NULL,
    direccion TEXT,
    
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    
    estado BOOLEAN DEFAULT TRUE,
    es_activo BOOLEAN DEFAULT TRUE,
    
    last_login TIMESTAMP NULL,
    ultimo_ip VARCHAR(50),
    intentos_fallidos INT DEFAULT 0,
    bloqueado_hasta TIMESTAMP NULL,
    
    avatar_url VARCHAR(255),
    idioma_preferido VARCHAR(10) DEFAULT 'es',
    
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_sexo) REFERENCES cat_sexo(id_sexo),
    INDEX idx_documento (documento),
    INDEX idx_email (email),
    INDEX idx_estado (estado)
);


CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    FOREIGN KEY (email) REFERENCES usuarios(email)
);


CREATE TABLE usuario_roles (
    id_usuario INT,
    id_rol INT,
    asignado_por INT,
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_rol),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_rol) REFERENCES cat_roles(id_rol),
    FOREIGN KEY (asignado_por) REFERENCES usuarios(id_usuario)
);


CREATE TABLE usuario_permisos (
    id_usuario INT,
    id_permiso INT,
    PRIMARY KEY (id_usuario, id_permiso),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_permiso) REFERENCES cat_permisos(id_permiso)
);


CREATE TABLE auditoria (
    id_auditoria INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    tabla_afectada VARCHAR(100),
    id_registro INT,
    tipo_accion ENUM('CREATE', 'UPDATE', 'DELETE', 'READ') DEFAULT 'READ',
    valores_anteriores JSON,
    valores_nuevos JSON,
    descripcion TEXT,
    ip_origen VARCHAR(50),
    user_agent VARCHAR(255),
    fecha_accion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    INDEX idx_usuario (id_usuario),
    INDEX idx_tabla (tabla_afectada),
    INDEX idx_fecha (fecha_accion)
);


/* =========================================================
   ================== 3. ESTRUCTURA ACADÉMICA ===============
   ========================================================= */

CREATE TABLE instituciones (
    id_institucion INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    id_tipo_institucion INT NOT NULL,
    ruc VARCHAR(11) UNIQUE,
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(100),
    sitio_web VARCHAR(150),
    fecha_creacion DATE,
    rector_director VARCHAR(150),
    logo_url VARCHAR(255),
    estado BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_tipo_institucion)
        REFERENCES cat_tipos_institucion(id_tipo_institucion)
);


CREATE TABLE sedes (
    id_sede INT AUTO_INCREMENT PRIMARY KEY,
    id_institucion INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    director_sede VARCHAR(150),
    estado BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_institucion)
        REFERENCES instituciones(id_institucion)
);


CREATE TABLE facultades_departamentos (
    id_facultad INT AUTO_INCREMENT PRIMARY KEY,
    id_sede INT NOT NULL,
    id_tipo_facultad INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    director VARCHAR(150),
    telefono VARCHAR(20),
    email VARCHAR(100),
    estado BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sede) REFERENCES sedes(id_sede),
    FOREIGN KEY (id_tipo_facultad) REFERENCES cat_tipos_facultad(id_tipo_facultad)
);


CREATE TABLE periodos_academicos (
    id_periodo INT AUTO_INCREMENT PRIMARY KEY,
    id_institucion INT,
    
    nombre VARCHAR(50) NOT NULL,
    tipo_periodo ENUM('Semestre', 'Trimestre', 'Cuatrimestre', 'Año') DEFAULT 'Semestre',
    numero INT,
    anio INT,
    
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    
    fecha_cierre_matricula DATE,
    fecha_cierre_notas DATE,
    
    id_estado_periodo INT DEFAULT 1,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_institucion) REFERENCES instituciones(id_institucion),
    FOREIGN KEY (id_estado_periodo) REFERENCES cat_estado_periodo(id_estado_periodo),
    UNIQUE (id_institucion, anio, numero),
    INDEX idx_fechas (fecha_inicio, fecha_fin),
    INDEX idx_estado (id_estado_periodo)
);


CREATE TABLE programas (
    id_programa INT AUTO_INCREMENT PRIMARY KEY,
    id_facultad INT NOT NULL,
    id_nivel INT NOT NULL,
    
    nombre VARCHAR(150) NOT NULL,
    codigo_programa VARCHAR(20) UNIQUE,
    
    duracion_meses INT,
    duracion_semestres INT,
    creditos_totales INT,
    jefe_programa INT,
    
    estado BOOLEAN DEFAULT TRUE,
    acreditado BOOLEAN DEFAULT FALSE,
    fecha_acreditacion DATE,
    
    resolucion_sunedu VARCHAR(50),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_facultad) REFERENCES facultades_departamentos(id_facultad),
    FOREIGN KEY (id_nivel) REFERENCES cat_niveles_programa(id_nivel),
    INDEX idx_estado (estado)
);


CREATE TABLE materias (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    id_programa INT NOT NULL,
    
    nombre VARCHAR(150) NOT NULL,
    codigo VARCHAR(20) UNIQUE,
    
    creditos INT DEFAULT 0,
    horas_teoricas INT DEFAULT 0,
    horas_practicas INT DEFAULT 0,
    horas_laboratorio INT DEFAULT 0,
    
    descripcion TEXT,
    competencias TEXT,
    requisitos VARCHAR(255),
    
    estado BOOLEAN DEFAULT TRUE,
    es_electiva BOOLEAN DEFAULT FALSE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa),
    INDEX idx_codigo (codigo)
);


CREATE TABLE docentes (
    id_docente INT PRIMARY KEY,
    id_facultad INT,
    
    especialidad VARCHAR(150) NOT NULL,
    grado_academico ENUM('Bachiller', 'Licenciado', 'Maestro', 'Doctor', 'Otro') DEFAULT 'Licenciado',
    numero_colegiatura VARCHAR(50),
    
    fecha_contratacion DATE NOT NULL,
    id_tipo_contrato INT DEFAULT 1,
    
    horas_teoricas_sem INT DEFAULT 0,
    horas_practicas_sem INT DEFAULT 0,
    
    calificacion_promedio DECIMAL(4,2),
    cantidad_evaluaciones INT DEFAULT 0,
    
    estado_laboral ENUM('Activo', 'Licencia', 'Suspensión', 'Jubilado', 'Retiro') DEFAULT 'Activo',
    
    curriculum_url VARCHAR(255),
    despacho_numero VARCHAR(20),
    telefono_despacho VARCHAR(20),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_docente) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_facultad) REFERENCES facultades_departamentos(id_facultad),
    FOREIGN KEY (id_tipo_contrato) REFERENCES cat_tipo_contrato(id_tipo_contrato),
    INDEX idx_estado_laboral (estado_laboral)
);


ALTER TABLE programas 
ADD CONSTRAINT fk_programas_jefe_programa 
FOREIGN KEY (jefe_programa) REFERENCES docentes(id_docente);


CREATE TABLE estudiantes (
    id_estudiante INT PRIMARY KEY,
    id_facultad INT,
    id_programa INT,
    
    codigo_estudiante VARCHAR(20) UNIQUE NOT NULL,
    fecha_ingreso DATE NOT NULL,
    id_estado_academico INT DEFAULT 1,
    
    lugar_nacimiento VARCHAR(150),
    estado_civil ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo', 'Otro') DEFAULT 'Soltero',
    ocupacion VARCHAR(100),
    
    apoderado_nombre VARCHAR(150),
    apoderado_parentesco VARCHAR(50),
    apoderado_telefono VARCHAR(20),
    apoderado_email VARCHAR(100),
    
    tiene_beca BOOLEAN DEFAULT FALSE,
    tipo_beca VARCHAR(50),
    
    foto_url VARCHAR(255),
    curriculum_url VARCHAR(255),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_estudiante) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_facultad) REFERENCES facultades_departamentos(id_facultad),
    FOREIGN KEY (id_programa) REFERENCES programas(id_programa),
    FOREIGN KEY (id_estado_academico) REFERENCES cat_estado_academico(id_estado_academico),
    INDEX idx_codigo (codigo_estudiante),
    INDEX idx_estado (id_estado_academico)
);


CREATE TABLE tutores (
    id_tutor INT PRIMARY KEY,
    id_facultad INT,
    
    especialidad VARCHAR(150),
    tipo_tutor ENUM('Académico', 'Psicólogo', 'Orientador') DEFAULT 'Académico',
    
    certificaciones TEXT,
    experiencia_anios INT DEFAULT 0,
    
    cantidad_tutorados_asignados INT DEFAULT 0,
    cantidad_tutorados_maximo INT DEFAULT 30,
    
    estado ENUM('Activo', 'Licencia', 'Suspendido') DEFAULT 'Activo',
    
    horario_atencion VARCHAR(255),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_tutor) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_facultad) REFERENCES facultades_departamentos(id_facultad),
    INDEX idx_tipo (tipo_tutor)
);


CREATE TABLE personal_administrativo (
    id_personal INT PRIMARY KEY,
    id_sede INT,
    
    puesto VARCHAR(150) NOT NULL,
    area_desempeno VARCHAR(100),
    fecha_ingreso DATE NOT NULL,
    
    id_tipo_contrato INT DEFAULT 1,
    estado_laboral ENUM('Activo', 'Licencia', 'Suspensión', 'Jubilado', 'Retiro') DEFAULT 'Activo',
    
    sueldo_base DECIMAL(10,2) DEFAULT 0,
    asignaciones DECIMAL(10,2) DEFAULT 0,
    descuentos DECIMAL(10,2) DEFAULT 0,
    
    fecha_cts DATE,
    cts_acumulado DECIMAL(10,2) DEFAULT 0,
    fecha_vacaciones DATE,
    dias_vacaciones INT DEFAULT 30,
    
    jefe_directo INT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_personal) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_sede) REFERENCES sedes(id_sede),
    FOREIGN KEY (id_tipo_contrato) REFERENCES cat_tipo_contrato(id_tipo_contrato),
    FOREIGN KEY (jefe_directo) REFERENCES usuarios(id_usuario),
    INDEX idx_estado_laboral (estado_laboral)
);


CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    id_materia INT NOT NULL,
    id_docente INT NOT NULL,
    id_periodo INT NOT NULL,
    
    nombre_curso VARCHAR(150),
    codigo_curso VARCHAR(20),
    
    cupo_maximo INT DEFAULT 50,
    aula VARCHAR(50),
    horario TEXT,
    
    tipo_curso ENUM('Presencial', 'Virtual', 'Híbrido') DEFAULT 'Presencial',
    link_aula_virtual VARCHAR(255),
    
    porcentaje_asistencia_requerida INT DEFAULT 80,
    nota_minima_aprobatoria DECIMAL(4,2) DEFAULT 11.00,
    
    silabo_url VARCHAR(255),
    estado BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_materia) REFERENCES materias(id_materia),
    FOREIGN KEY (id_docente) REFERENCES docentes(id_docente),
    FOREIGN KEY (id_periodo) REFERENCES periodos_academicos(id_periodo),
    INDEX idx_periodo (id_periodo),
    INDEX idx_docente (id_docente)
);


CREATE TABLE matriculas (
    id_matricula INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_curso INT NOT NULL,
    id_periodo INT NOT NULL,
    
    fecha_matricula DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_estado_matricula INT DEFAULT 1,
    
    numero_intentos INT DEFAULT 1,
    es_retiro BOOLEAN DEFAULT FALSE,
    fecha_retiro DATETIME,
    motivo_retiro VARCHAR(255),
    
    nota_final DECIMAL(4,2),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE (id_estudiante, id_curso, id_periodo),
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso),
    FOREIGN KEY (id_periodo) REFERENCES periodos_academicos(id_periodo),
    FOREIGN KEY (id_estado_matricula) REFERENCES cat_estado_matricula(id_estado_matricula),
    INDEX idx_estudiante (id_estudiante),
    INDEX idx_estado (id_estado_matricula)
);


CREATE TABLE evaluaciones (
    id_evaluacion INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT NOT NULL,
    
    nombre VARCHAR(100) NOT NULL,
    tipo_evaluacion ENUM('Examen', 'Práctica', 'Trabajo', 'Participación', 'Proyecto', 'Otro') DEFAULT 'Examen',
    
    porcentaje DECIMAL(5,2) NOT NULL,
    puntaje_maximo DECIMAL(4,2) DEFAULT 20.00,
    
    fecha DATE NOT NULL,
    fecha_publicacion_resultados DATE,
    
    descripcion TEXT,
    archivo_url VARCHAR(255),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso),
    INDEX idx_fecha (fecha)
);


CREATE TABLE notas (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    id_evaluacion INT NOT NULL,
    id_estudiante INT NOT NULL,
    id_curso INT NOT NULL,
    
    calificacion DECIMAL(4,2),
    estado_calificacion ENUM('Pendiente', 'Calificado', 'Revisado', 'Reclamado') DEFAULT 'Pendiente',
    
    fecha_calificacion DATETIME,
    id_docente_calificador INT,
    observaciones TEXT,
    
    tiene_reclamo BOOLEAN DEFAULT FALSE,
    motivo_reclamo VARCHAR(255),
    fecha_reclamo DATETIME,
    resolucion_reclamo VARCHAR(255),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE (id_evaluacion, id_estudiante),
    FOREIGN KEY (id_evaluacion) REFERENCES evaluaciones(id_evaluacion),
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso),
    FOREIGN KEY (id_docente_calificador) REFERENCES docentes(id_docente),
    INDEX idx_estado (estado_calificacion)
);


CREATE TABLE asistencias (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_curso INT NOT NULL,
    id_estudiante INT NOT NULL,
    id_periodo INT NOT NULL,
    
    fecha DATE NOT NULL,
    id_estado_asistencia INT NOT NULL,
    
    requiere_justificacion BOOLEAN DEFAULT FALSE,
    archivo_justificacion VARCHAR(255),
    estado_justificacion ENUM('Pendiente', 'Aprobada', 'Rechazada') DEFAULT 'Pendiente',
    
    registrado_por INT,
    observaciones TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    UNIQUE (id_curso, id_estudiante, fecha),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso),
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id_estudiante),
    FOREIGN KEY (id_periodo) REFERENCES periodos_academicos(id_periodo),
    FOREIGN KEY (id_estado_asistencia) REFERENCES cat_estado_asistencia(id_estado_asistencia),
    FOREIGN KEY (registrado_por) REFERENCES usuarios(id_usuario),
    INDEX idx_fecha (fecha),
    INDEX idx_estado (id_estado_asistencia)
);


/* =========================================================
   ================= NOTIFICACIONES ======================
   ========================================================= */

CREATE TABLE notificaciones (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    
    id_tipo_notificacion INT NOT NULL,
    id_prioridad INT NOT NULL,
    id_usuario_creador INT NOT NULL,
    
    titulo VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    contenido_html TEXT,
    
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_programada DATETIME NULL,
    fecha_expiracion DATETIME NULL,
    
    es_masiva BOOLEAN DEFAULT FALSE,
    requiere_confirmacion BOOLEAN DEFAULT FALSE,
    
    enviar_email BOOLEAN DEFAULT TRUE,
    enviar_sms BOOLEAN DEFAULT FALSE,
    enviar_push BOOLEAN DEFAULT TRUE,
    
    estado BOOLEAN DEFAULT TRUE,
    enviada BOOLEAN DEFAULT FALSE,
    fecha_envio DATETIME,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_tipo_notificacion) REFERENCES cat_tipo_notificacion(id_tipo_notificacion),
    FOREIGN KEY (id_prioridad) REFERENCES cat_prioridad_notificacion(id_prioridad),
    FOREIGN KEY (id_usuario_creador) REFERENCES usuarios(id_usuario),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha_creacion),
    INDEX idx_enviada (enviada)
);


CREATE TABLE notificacion_usuarios (
    id_notificacion_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_notificacion INT NOT NULL,
    id_usuario INT NOT NULL,
    id_estado_notificacion INT NOT NULL,
    
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_lectura DATETIME NULL,
    confirmado BOOLEAN DEFAULT FALSE,
    fecha_confirmacion DATETIME NULL,
    
    clic_url BOOLEAN DEFAULT FALSE,
    fecha_clic DATETIME,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    UNIQUE (id_notificacion, id_usuario),
    FOREIGN KEY (id_notificacion) REFERENCES notificaciones(id_notificacion),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_estado_notificacion) REFERENCES cat_estado_notificacion_usuario(id_estado_notificacion),
    INDEX idx_usuario_estado (id_usuario, id_estado_notificacion),
    INDEX idx_fecha_lectura (fecha_lectura)
);


CREATE TABLE notificacion_roles (
    id_notificacion_rol INT AUTO_INCREMENT PRIMARY KEY,
    id_notificacion INT NOT NULL,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_notificacion) REFERENCES notificaciones(id_notificacion),
    FOREIGN KEY (id_rol) REFERENCES cat_roles(id_rol)
);


CREATE TABLE notificacion_cursos (
    id_notificacion_curso INT AUTO_INCREMENT PRIMARY KEY,
    id_notificacion INT NOT NULL,
    id_curso INT NOT NULL,
    FOREIGN KEY (id_notificacion) REFERENCES notificaciones(id_notificacion),
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso)
);


CREATE TABLE notificacion_adjuntos (
    id_adjunto INT AUTO_INCREMENT PRIMARY KEY,
    id_notificacion INT NOT NULL,
    nombre_archivo VARCHAR(200),
    ruta_archivo VARCHAR(300),
    tipo_mime VARCHAR(100),
    peso_kb INT,
    FOREIGN KEY (id_notificacion) REFERENCES notificaciones(id_notificacion)
);


/* =========================================================
   ================= GESTIÓN DOCUMENTAL ====================
   ========================================================= */

CREATE TABLE archivos (
    id_archivo INT AUTO_INCREMENT PRIMARY KEY,
    id_tipo_archivo INT NOT NULL,
    id_usuario_creador INT NOT NULL,

    nombre_original VARCHAR(255) NOT NULL,
    nombre_sistema VARCHAR(255) NOT NULL,
    ruta_archivo VARCHAR(500) NOT NULL,
    tipo_mime VARCHAR(150),
    extension VARCHAR(20),
    peso_kb INT,

    version INT DEFAULT 1,
    es_publico BOOLEAN DEFAULT FALSE,
    descripcion TEXT,

    fecha_subida DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (id_tipo_archivo) REFERENCES cat_tipo_archivo(id_tipo_archivo),
    FOREIGN KEY (id_usuario_creador) REFERENCES usuarios(id_usuario),
    INDEX idx_usuario (id_usuario_creador),
    INDEX idx_tipo (id_tipo_archivo)
);


CREATE TABLE archivo_entidades (
    id_archivo_entidad INT AUTO_INCREMENT PRIMARY KEY,
    id_archivo INT NOT NULL,
    tipo_entidad VARCHAR(50) NOT NULL,
    id_entidad INT NOT NULL,
    fecha_asociacion DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_archivo) REFERENCES archivos(id_archivo),
    INDEX idx_entidad (tipo_entidad, id_entidad)
);


CREATE TABLE archivo_roles (
    id_archivo_rol INT AUTO_INCREMENT PRIMARY KEY,
    id_archivo INT NOT NULL,
    id_rol INT NOT NULL,
    puede_ver BOOLEAN DEFAULT TRUE,
    puede_descargar BOOLEAN DEFAULT TRUE,
    puede_editar BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_archivo) REFERENCES archivos(id_archivo),
    FOREIGN KEY (id_rol) REFERENCES cat_roles(id_rol)
);


CREATE TABLE archivo_descargas (
    id_descarga INT AUTO_INCREMENT PRIMARY KEY,
    id_archivo INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_descarga DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_origen VARCHAR(50),
    FOREIGN KEY (id_archivo) REFERENCES archivos(id_archivo),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    INDEX idx_usuario (id_usuario),
    INDEX idx_fecha (fecha_descarga)
);


/* =========================================================
   ================= MENÚS DINÁMICOS ======================
   ========================================================= */

CREATE TABLE cat_menus (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    icon VARCHAR(100),
    ruta VARCHAR(255) UNIQUE NOT NULL,
    orden INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE menu_roles (
    id_menu INT NOT NULL,
    id_rol INT NOT NULL,
    PRIMARY KEY (id_menu, id_rol),
    FOREIGN KEY (id_menu) REFERENCES cat_menus(id_menu),
    FOREIGN KEY (id_rol) REFERENCES cat_roles(id_rol)
);


CREATE TABLE menu_permisos (
    id_menu INT NOT NULL,
    id_permiso INT NOT NULL,
    PRIMARY KEY (id_menu, id_permiso),
    FOREIGN KEY (id_menu) REFERENCES cat_menus(id_menu),
    FOREIGN KEY (id_permiso) REFERENCES cat_permisos(id_permiso)
);


/* =========================================================
   INSERTAR MENÚS
   ========================================================= */

INSERT INTO cat_menus (id_menu, nombre, icon, ruta, orden, activo) VALUES
(1, 'Dashboard', 'DataBoard', '/', 1, TRUE),
(2, 'Instituciones', 'OfficeBuilding', '/admin/instituciones', 10, TRUE),
(3, 'Sedes', 'School', '/admin/sedes', 11, TRUE),
(4, 'Usuarios', 'User', '/admin/usuarios', 12, TRUE),
(5, 'Notificaciones', 'Notification', '/admin/notificaciones', 13, TRUE),
(6, 'Mis Cursos', 'Reading', '/docente/cursos', 20, TRUE),
(7, 'Calificaciones', 'Notebook', '/docente/calificaciones', 21, TRUE),
(8, 'Asistencia', 'Calendar', '/docente/asistencia', 22, TRUE),
(9, 'Mis Notas', 'Notebook', '/estudiante/mis-notas', 30, TRUE),
(10, 'Matrículas', 'Tickets', '/estudiante/matriculas', 31, TRUE),
(11, 'Horario', 'Calendar', '/estudiante/horario', 32, TRUE),
(12, 'Tutorados', 'User', '/tutor/tutorados', 40, TRUE),
(13, 'Seguimiento', 'Document', '/tutor/seguimiento', 41, TRUE),
(14, 'Psicología', 'User', '/bienestar/psicologia', 42, TRUE),
(15, 'Actividades', 'Bell', '/bienestar/actividades', 43, TRUE),
(16, 'Servicios', 'Files', '/bienestar/servicios', 44, TRUE),
(17, 'Actas', 'Document', '/secretaria/actas', 50, TRUE),
(18, 'Certificados', 'Files', '/secretaria/certificados', 51, TRUE),
(19, 'Titulaciones', 'Notebook', '/secretaria/titulaciones', 52, TRUE),
(20, 'Admisiones', 'User', '/secretaria/admisiones', 53, TRUE),
(21, 'Pagos', 'Money', '/finanzas/pagos', 60, TRUE),
(22, 'Becas', 'Tickets', '/finanzas/becas', 61, TRUE),
(23, 'Reportes', 'Document', '/finanzas/reportes', 62, TRUE),
(24, 'Recursos', 'Reading', '/biblioteca/recursos', 70, TRUE),
(25, 'Préstamos', 'Collection', '/biblioteca/prestamos', 71, TRUE),
(26, 'Reservas', 'Calendar', '/biblioteca/reservas', 72, TRUE),
(27, 'Empresas', 'OfficeBuilding', '/practicas/empresas', 80, TRUE),
(28, 'Convenios', 'Document', '/practicas/convenios', 81, TRUE),
(29, 'Egresados', 'User', '/practicas/egresados', 82, TRUE),
(30, 'Bolsa Trabajo', 'Suitcase', '/practicas/bolsa-trabajo', 83, TRUE),
(31, 'Mi Perfil', 'User', '/perfil', 100, TRUE);


/* =========================================================
   ASIGNAR MENÚS A ROLES
   ========================================================= */

-- Dashboard (Todos)
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12);

-- ADMINISTRADOR - Todos los menús
INSERT INTO menu_roles (id_menu, id_rol) 
SELECT id_menu, 1 FROM cat_menus WHERE id_menu > 1;

-- DIRECTOR
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(2, 2), (3, 2), (4, 2), (5, 2), (23, 2);

-- COORDINADOR
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(27, 3), (28, 3), (29, 3), (30, 3);

-- JEFE DE CARRERA
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(27, 4), (28, 4), (29, 4), (30, 4);

-- DOCENTE
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(6, 5), (7, 5), (8, 5);

-- TUTOR
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(12, 6), (13, 6), (14, 6), (15, 6), (16, 6);

-- SECRETARIA ACADÉMICA
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(17, 7), (18, 7), (19, 7), (20, 7);

-- TESORERO
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(21, 8), (22, 8), (23, 8);

-- BIBLIOTECARIO
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(24, 9), (25, 9), (26, 9);

-- PERSONAL ADMINISTRATIVO
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(4, 10), (14, 10), (15, 10), (16, 10);

-- ESTUDIANTE
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(9, 11), (10, 11), (11, 11);

-- APODERADO
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(9, 12), (10, 12), (11, 12);

-- Mi Perfil - Solo agregar para roles que NO sean Administrador
INSERT INTO menu_roles (id_menu, id_rol) VALUES
(31, 2), (31, 3), (31, 4), (31, 5), (31, 6), (31, 7), (31, 8), (31, 9), (31, 10), (31, 11), (31, 12);


/* =========================================================
   DATOS DE PRUEBA
   ========================================================= */

INSERT INTO instituciones (nombre, id_tipo_institucion, ruc, direccion, telefono, email, sitio_web, fecha_creacion, rector_director, estado) 
VALUES ('Universidad Nacional de Prueba', 1, '12345678901', 'Av. Universitaria 1000', '5512345678', 'contacto@uninp.edu.pe', 'www.uninp.edu.pe', '2020-01-15', 'Dr. Juan Pérez', TRUE);

INSERT INTO sedes (id_institucion, nombre, direccion, telefono, director_sede, estado) 
VALUES (1, 'Sede Lima', 'Av. Universitaria 1000, Lima', '5512345678', 'Ing. Maria García', TRUE);

INSERT INTO facultades_departamentos (id_sede, id_tipo_facultad, nombre, director, telefono, email, estado) 
VALUES (1, 1, 'Facultad de Ingeniería', 'Dr. Carlos López', '5587654321', 'ingenieria@uninp.edu.pe', TRUE);

INSERT INTO periodos_academicos (id_institucion, nombre, tipo_periodo, numero, anio, fecha_inicio, fecha_fin, id_estado_periodo) 
VALUES (1, '2024-I', 'Semestre', 1, 2024, '2024-03-04', '2024-07-12', 2);

INSERT INTO programas (id_facultad, id_nivel, nombre, codigo_programa, duracion_meses, duracion_semestres, creditos_totales, estado) 
VALUES (1, 4, 'Ingeniería de Sistemas', 'ING-SIS-2024', 48, 8, 240, TRUE);

INSERT INTO materias (id_programa, nombre, codigo, creditos, horas_teoricas, horas_practicas, estado) 
VALUES (1, 'Programación I', 'PRG-001', 4, 3, 2, TRUE);

INSERT INTO usuarios (documento, tipo_documento, nombres, apellidos, fecha_nacimiento, id_sexo, telefono, email, direccion, password, email_verified_at, estado, es_activo) 
VALUES ('12345678', 'DNI', 'Admin', 'Sistema', '1990-01-01', 1, '5512345678', 'admin@uninp.edu.pe', 'Lima', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHQWEe', NOW(), TRUE, TRUE);

INSERT INTO usuario_roles (id_usuario, id_rol, asignado_por) 
VALUES (1, 1, 1);

INSERT INTO usuarios (documento, tipo_documento, nombres, apellidos, fecha_nacimiento, id_sexo, telefono, email, direccion, password, email_verified_at, estado, es_activo) 
VALUES ('87654321', 'DNI', 'Juan', 'Profesor', '1985-05-15', 1, '5587654321', 'juan.profesor@uninp.edu.pe', 'Lima', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHQWEe', NOW(), TRUE, TRUE);

INSERT INTO docentes (id_docente, id_facultad, especialidad, grado_academico, fecha_contratacion, id_tipo_contrato, estado_laboral) 
VALUES (2, 1, 'Ingeniería de Sistemas', 'Maestro', '2020-01-15', 1, 'Activo');

INSERT INTO usuario_roles (id_usuario, id_rol, asignado_por) 
VALUES (2, 5, 1);

UPDATE programas SET jefe_programa = 2 WHERE id_programa = 1;

INSERT INTO usuarios (documento, tipo_documento, nombres, apellidos, fecha_nacimiento, id_sexo, telefono, email, direccion, password, email_verified_at, estado, es_activo) 
VALUES ('11223344', 'DNI', 'Carlos', 'Estudiante', '2000-08-20', 1, '5599887766', 'carlos.estudiante@uninp.edu.pe', 'Lima', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36CHQWEe', NOW(), TRUE, TRUE);

INSERT INTO estudiantes (id_estudiante, id_facultad, id_programa, codigo_estudiante, fecha_ingreso, id_estado_academico, apoderado_nombre, apoderado_telefono) 
VALUES (3, 1, 1, 'EST-2024-0001', '2024-01-15', 1, 'Pedro Estudiante', '5599887765');

INSERT INTO usuario_roles (id_usuario, id_rol, asignado_por) 
VALUES (3, 11, 1);

INSERT INTO cursos (id_materia, id_docente, id_periodo, nombre_curso, cupo_maximo, aula, tipo_curso, estado) 
VALUES (1, 2, 1, 'Programación I - Sección A', 40, 'Aula 101', 'Presencial', TRUE);

INSERT INTO matriculas (id_estudiante, id_curso, id_periodo, id_estado_matricula) 
VALUES (3, 1, 1, 1);


/* =========================================================
   CONFIGURACIÓN DE PERMISOS POR ROL
   ========================================================= */

SET SQL_SAFE_UPDATES = 0;
DELETE FROM role_permisos WHERE id_rol >= 1;
SET SQL_SAFE_UPDATES = 1;

INSERT INTO role_permisos (id_rol, id_permiso) 
SELECT 1, id_permiso FROM cat_permisos;

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(2, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios')),
(2, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'crear-usuarios')),
(2, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'editar-usuarios')),
(2, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-reportes')),
(2, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'generar-reportes')),
(2, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'exportar-reportes'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(3, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'crear-cursos')),
(3, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios')),
(3, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones')),
(3, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-reportes'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(4, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios')),
(4, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones')),
(4, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-reportes')),
(4, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'generar-reportes'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(5, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'calificar')),
(5, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones')),
(5, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'registrar-asistencia'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(6, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios')),
(6, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(7, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'crear-usuarios')),
(7, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'editar-usuarios')),
(7, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios')),
(7, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'crear-cursos')),
(7, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones')),
(7, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-reportes'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(8, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-pagos')),
(8, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'registrar-pagos')),
(8, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'emitir-boletas')),
(8, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'gestionar-becas')),
(8, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-reportes'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(9, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios')),
(9, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-reportes'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(10, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-usuarios'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(11, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones'));

INSERT INTO role_permisos (id_rol, id_permiso) VALUES
(12, (SELECT id_permiso FROM cat_permisos WHERE nombre = 'ver-calificaciones'));


/* =========================================================
   VISTAS
   ========================================================= */

CREATE OR REPLACE VIEW v_menus_por_rol AS
SELECT 
    cr.id_rol,
    cr.nombre AS rol,
    GROUP_CONCAT(DISTINCT cm.nombre SEPARATOR ' | ') AS menus,
    COUNT(DISTINCT cm.id_menu) AS total_menus
FROM cat_roles cr
LEFT JOIN menu_roles mr ON cr.id_rol = mr.id_rol
LEFT JOIN cat_menus cm ON mr.id_menu = cm.id_menu
WHERE cm.id_menu IS NOT NULL
GROUP BY cr.id_rol, cr.nombre
ORDER BY cr.id_rol;


CREATE OR REPLACE VIEW v_permisos_por_rol AS
SELECT 
    cr.id_rol,
    cr.nombre AS rol,
    GROUP_CONCAT(cp.nombre SEPARATOR ', ') AS permisos,
    COUNT(cp.id_permiso) AS total_permisos
FROM role_permisos rp
JOIN cat_roles cr ON rp.id_rol = cr.id_rol
JOIN cat_permisos cp ON rp.id_permiso = cp.id_permiso
GROUP BY cr.id_rol, cr.nombre
ORDER BY cr.id_rol;


/* =========================================================
   FIN
   ========================================================= */