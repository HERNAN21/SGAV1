import { defineStore } from 'pinia'
import authService from '../services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({ token: localStorage.getItem('token'), user: null }),
  actions: {
    async login(payload) {
      const { data } = await authService.login(payload)
      this.token = data.token
      this.user = data.user
      localStorage.setItem('token', data.token)
    },
    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
    }
  }
})