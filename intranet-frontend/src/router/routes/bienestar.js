const bienestarRoutes = [
  { path: '/bienestar', name: 'bienestar.dashboard', component: () => import('../../views/Bienestar/BienestarDashboard.vue'), meta: { role: 'Bienestar' } },
  { path: '/bienestar/psicologia', name: 'bienestar.psicologia', component: () => import('../../views/Bienestar/Psicologia.vue'), meta: { role: 'Bienestar' } },
  { path: '/bienestar/actividades', name: 'bienestar.actividades', component: () => import('../../views/Bienestar/Actividades.vue'), meta: { role: 'Bienestar' } },
  { path: '/bienestar/servicios', name: 'bienestar.servicios', component: () => import('../../views/Bienestar/Servicios.vue'), meta: { role: 'Bienestar' } },
]

export default bienestarRoutes