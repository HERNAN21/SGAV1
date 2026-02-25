const comunRoutes = [
  { path: '/login', name: 'login', component: () => import('../../views/Auth/Login.vue') },
  { path: '/perfil', name: 'perfil', component: () => import('../../views/Perfil/MiPerfil.vue'), meta: { auth: true } },
]

export default comunRoutes