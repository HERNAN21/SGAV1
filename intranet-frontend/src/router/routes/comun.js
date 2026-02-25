const comunRoutes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'login', component: () => import('../../views/Auth/Login.vue') },
  { path: '/perfil', redirect: '/dashboard/perfil' },
  { path: '/admin/:subPath(.*)*', redirect: (to) => `/dashboard/admin/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/docente/:subPath(.*)*', redirect: (to) => `/dashboard/docente/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/estudiante/:subPath(.*)*', redirect: (to) => `/dashboard/estudiante/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/tutor/:subPath(.*)*', redirect: (to) => `/dashboard/tutor/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/secretaria/:subPath(.*)*', redirect: (to) => `/dashboard/secretaria/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/finanzas/:subPath(.*)*', redirect: (to) => `/dashboard/finanzas/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/biblioteca/:subPath(.*)*', redirect: (to) => `/dashboard/biblioteca/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/bienestar/:subPath(.*)*', redirect: (to) => `/dashboard/bienestar/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/practicas/:subPath(.*)*', redirect: (to) => `/dashboard/practicas/${to.params.subPath || ''}`.replace(/\/$/, '') },
  { path: '/:pathMatch(.*)*', redirect: '/login' },
]

export default comunRoutes