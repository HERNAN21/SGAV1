import { createRouter, createWebHistory } from 'vue-router'
import adminRoutes from './routes/admin'
import bienestarRoutes from './routes/bienestar'
import bibliotecaRoutes from './routes/biblioteca'
import docenteRoutes from './routes/docente'
import estudianteRoutes from './routes/estudiante'
import finanzasRoutes from './routes/finanzas'
import practicasRoutes from './routes/practicas'
import secretariaRoutes from './routes/secretaria'
import tutorRoutes from './routes/tutor'
import comunRoutes from './routes/comun'
import { authGuard } from './guards'
import { useAuthStore } from '../stores/authStore'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    ...comunRoutes,
    ...adminRoutes,
    ...docenteRoutes,
    ...estudianteRoutes,
    ...tutorRoutes,
    ...secretariaRoutes,
    ...finanzasRoutes,
    ...bibliotecaRoutes,
    ...bienestarRoutes,
    ...practicasRoutes,
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  authGuard(to, from, next, authStore)
})

export default router