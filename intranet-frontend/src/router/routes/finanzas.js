const finanzasRoutes = [
  { path: '/finanzas', name: 'finanzas.dashboard', component: () => import('../../views/Finanzas/FinanzasDashboard.vue'), meta: { role: 'Finanzas' } },
  { path: '/finanzas/pagos', name: 'finanzas.pagos', component: () => import('../../views/Finanzas/Pagos.vue'), meta: { role: 'Finanzas' } },
  { path: '/finanzas/becas', name: 'finanzas.becas', component: () => import('../../views/Finanzas/Becas.vue'), meta: { role: 'Finanzas' } },
  { path: '/finanzas/reportes', name: 'finanzas.reportes', component: () => import('../../views/Finanzas/Reportes.vue'), meta: { role: 'Finanzas' } },
]

export default finanzasRoutes