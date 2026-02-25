import { defineStore } from 'pinia'

export const useDocenteStore = defineStore('docente', {
  state: () => ({ cursos: [] })
})