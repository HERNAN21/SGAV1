export const authGuard = (to, from, next, authStore) => {
  if (to.meta?.auth && !authStore.token) {
    return next({ name: 'login' })
  }

  if (to.meta?.role && authStore.user?.rol !== to.meta.role) {
    return next({ name: 'login' })
  }

  return next()
}