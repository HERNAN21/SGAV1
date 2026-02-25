const adminRoutes = [
  { path: '/admin', name: 'admin.dashboard', component: () => import('../../views/Admin/AdminDashboard.vue'), meta: { role: 'Administrador' } },
  { path: '/admin/instituciones', name: 'admin.instituciones', component: () => import('../../views/Admin/Instituciones.vue'), meta: { role: 'Administrador' } },
  { path: '/admin/sedes', name: 'admin.sedes', component: () => import('../../views/Admin/Sedes.vue'), meta: { role: 'Administrador' } },
  { path: '/admin/usuarios', name: 'admin.usuarios', component: () => import('../../views/Admin/Usuarios.vue'), meta: { role: 'Administrador' } },
  { path: '/admin/notificaciones', name: 'admin.notificaciones', component: () => import('../../views/Admin/Notificaciones.vue'), meta: { role: 'Administrador' } },
]

export default adminRoutes