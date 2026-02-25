import { defineStore } from 'pinia'

export const useEstudianteStore = defineStore('estudiante', {
  state: () => ({ matriculas: [], notas: [] })
})