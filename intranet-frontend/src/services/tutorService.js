import api from './api'

export default {
  tutorados() {
    return api.get('/tutor/tutoriados')
  },
}