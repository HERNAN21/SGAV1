const bibliotecaRoutes = [
  { path: '/biblioteca', name: 'biblioteca.dashboard', component: () => import('../../views/Biblioteca/BibliotecaDashboard.vue'), meta: { role: 'Biblioteca' } },
  { path: '/biblioteca/recursos', name: 'biblioteca.recursos', component: () => import('../../views/Biblioteca/Recursos.vue'), meta: { role: 'Biblioteca' } },
  { path: '/biblioteca/prestamos', name: 'biblioteca.prestamos', component: () => import('../../views/Biblioteca/Prestamos.vue'), meta: { role: 'Biblioteca' } },
  { path: '/biblioteca/reservas', name: 'biblioteca.reservas', component: () => import('../../views/Biblioteca/Reservas.vue'), meta: { role: 'Biblioteca' } },
]

export default bibliotecaRoutes