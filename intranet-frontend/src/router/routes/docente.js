const docenteRoutes = [
  { path: '/docente', name: 'docente.dashboard', component: () => import('../../views/Docente/DocenteDashboard.vue'), meta: { role: 'Docente' } },
  { path: '/docente/cursos', name: 'docente.cursos', component: () => import('../../views/Docente/Cursos.vue'), meta: { role: 'Docente' } },
  { path: '/docente/calificaciones', name: 'docente.calificaciones', component: () => import('../../views/Docente/Calificaciones.vue'), meta: { role: 'Docente' } },
  { path: '/docente/asistencia', name: 'docente.asistencia', component: () => import('../../views/Docente/Asistencia.vue'), meta: { role: 'Docente' } },
]

export default docenteRoutes