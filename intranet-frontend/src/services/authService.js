import api from './api'

export default {
  login(payload) {
    return api.post('/auth/login', payload)
  },
  me() {
    return api.get('/auth/me')
  },
  logout() {
    return api.post('/auth/logout')
  },
}