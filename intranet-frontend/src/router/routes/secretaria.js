const secretariaRoutes = [
  { path: '/secretaria', name: 'secretaria.dashboard', component: () => import('../../views/Secretaria/SecretariaDashboard.vue'), meta: { role: 'Secretaria' } },
  { path: '/secretaria/actas', name: 'secretaria.actas', component: () => import('../../views/Secretaria/Actas.vue'), meta: { role: 'Secretaria' } },
  { path: '/secretaria/certificados', name: 'secretaria.certificados', component: () => import('../../views/Secretaria/Certificados.vue'), meta: { role: 'Secretaria' } },
  { path: '/secretaria/titulaciones', name: 'secretaria.titulaciones', component: () => import('../../views/Secretaria/Titulaciones.vue'), meta: { role: 'Secretaria' } },
  { path: '/secretaria/admisiones', name: 'secretaria.admisiones', component: () => import('../../views/Secretaria/Admisiones.vue'), meta: { role: 'Secretaria' } },
]

export default secretariaRoutes