const practicasRoutes = [
  { path: '/practicas', name: 'practicas.dashboard', component: () => import('../../views/Practicas/PracticasDashboard.vue'), meta: { role: 'Practicas' } },
  { path: '/practicas/empresas', name: 'practicas.empresas', component: () => import('../../views/Practicas/Empresas.vue'), meta: { role: 'Practicas' } },
  { path: '/practicas/convenios', name: 'practicas.convenios', component: () => import('../../views/Practicas/Convenios.vue'), meta: { role: 'Practicas' } },
  { path: '/practicas/egresados', name: 'practicas.egresados', component: () => import('../../views/Practicas/Egresados.vue'), meta: { role: 'Practicas' } },
  { path: '/practicas/bolsa-trabajo', name: 'practicas.bolsa', component: () => import('../../views/Practicas/BolsaTrabajo.vue'), meta: { role: 'Practicas' } },
]

export default practicasRoutes