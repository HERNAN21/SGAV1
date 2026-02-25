import dashboardModules from '../../config/dashboardModules'

const moduleChildren = dashboardModules.map((module) => ({
  path: module.path,
  name: module.name,
  component: module.component,
  meta: {
    auth: true,
    roles: module.roles,
  },
}))

const dashboardRoutes = [
  {
    path: '/dashboard',
    component: () => import('../../views/Dashboard/AppDashboard.vue'),
    meta: { auth: true },
    children: moduleChildren,
  },
]

export default dashboardRoutes
