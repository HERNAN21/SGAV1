const estudianteRoutes = [
  { path: '/estudiante', name: 'estudiante.dashboard', component: () => import('../../views/Estudiante/EstudianteDashboard.vue'), meta: { role: 'Estudiante' } },
  { path: '/estudiante/mis-notas', name: 'estudiante.notas', component: () => import('../../views/Estudiante/MisNotas.vue'), meta: { role: 'Estudiante' } },
  { path: '/estudiante/matriculas', name: 'estudiante.matriculas', component: () => import('../../views/Estudiante/Matriculas.vue'), meta: { role: 'Estudiante' } },
  { path: '/estudiante/horario', name: 'estudiante.horario', component: () => import('../../views/Estudiante/Horario.vue'), meta: { role: 'Estudiante' } },
]

export default estudianteRoutes