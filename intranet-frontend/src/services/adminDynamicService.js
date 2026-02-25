import api from './api'

export default {
  getSchemas() {
    return api.get('/admin/formularios/schemas')
  },
  getCatalogs() {
    return api.get('/admin/formularios/catalogos')
  },
  list(endpoint, params = {}) {
    return api.get(endpoint, { params })
  },
  create(endpoint, payload) {
    return api.post(endpoint, payload)
  },
  update(endpoint, id, payload) {
    return api.put(`${endpoint}/${id}`, payload)
  },
  remove(endpoint, id) {
    return api.delete(`${endpoint}/${id}`)
  },
}
