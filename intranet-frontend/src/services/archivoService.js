import api from './api'

export default {
  upload(formData) {
    return api.post('/comun/archivos', formData)
  },
}