import api from './api'

export default {
  cursos() {
    return api.get('/docente/cursos')
  },
}