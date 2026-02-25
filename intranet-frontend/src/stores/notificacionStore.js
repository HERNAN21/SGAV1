import { defineStore } from 'pinia'

export const useNotificacionStore = defineStore('notificaciones', {
  state: () => ({ items: [] })
})