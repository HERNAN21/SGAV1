const tutorRoutes = [
  { path: '/tutor', name: 'tutor.dashboard', component: () => import('../../views/Tutor/TutorDashboard.vue'), meta: { role: 'Tutor' } },
  { path: '/tutor/tutorados', name: 'tutor.tutorados', component: () => import('../../views/Tutor/Tutorados.vue'), meta: { role: 'Tutor' } },
  { path: '/tutor/seguimiento', name: 'tutor.seguimiento', component: () => import('../../views/Tutor/Seguimiento.vue'), meta: { role: 'Tutor' } },
]

export default tutorRoutes