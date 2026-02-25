import { createRouter, createWebHistory } from 'vue-router'
import comunRoutes from './routes/comun'
import dashboardRoutes from './routes/dashboard'
import { authGuard } from './guards'
import { useAuthStore } from '../stores/authStore'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    ...comunRoutes,
    ...dashboardRoutes,
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  const run = async () => {
    if (authStore.token && !authStore.user) {
      try {
        await authStore.fetchMe()
      } catch {
        authStore.logout()
      }
    }

    authGuard(to, from, next, authStore)
  }

  run()
})

export default router