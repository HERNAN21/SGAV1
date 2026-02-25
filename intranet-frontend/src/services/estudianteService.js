import api from './api'

export default {
  notas() {
    return api.get('/estudiante/notas')
  },
}