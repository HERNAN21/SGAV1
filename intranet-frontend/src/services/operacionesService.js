import api from './api'

export default {
  catalogs() {
    return api.get('/operaciones/catalogos')
  },
  listarAlumnos(params = {}) {
    return api.get('/operaciones/alumnos', { params })
  },
  listarCursos(params = {}) {
    return api.get('/operaciones/cursos', { params })
  },
  crearAlumno(payload) {
    return api.post('/operaciones/alumnos', payload)
  },
  actualizarAlumno(idEstudiante, payload) {
    return api.put(`/operaciones/alumnos/${idEstudiante}`, payload)
  },
  eliminarAlumno(idEstudiante) {
    return api.delete(`/operaciones/alumnos/${idEstudiante}`)
  },
  crearCurso(payload) {
    return api.post('/operaciones/cursos', payload)
  },
  actualizarCurso(idCurso, payload) {
    return api.put(`/operaciones/cursos/${idCurso}`, payload)
  },
  eliminarCurso(idCurso) {
    return api.delete(`/operaciones/cursos/${idCurso}`)
  },
  registrarNota(payload) {
    return api.post('/operaciones/notas', payload)
  },
  registrarPago(payload) {
    return api.post('/operaciones/pagos', payload)
  },
  verReportes() {
    return api.get('/operaciones/reportes')
  },
  matrizPermisos() {
    return api.get('/operaciones/matriz-permisos')
  },
  actualizarPermisosRol(idRol, payload) {
    return api.put(`/operaciones/matriz-permisos/${idRol}`, payload)
  },
}
