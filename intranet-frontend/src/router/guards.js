export const authGuard = (to, from, next, authStore) => {
  if (to.meta?.auth && !authStore.token) {
    return next({ name: 'login' })
  }

  const requiredRoles = to.meta?.roles || (to.meta?.role ? [to.meta.role] : [])

  if (requiredRoles.length > 0) {
    const userRoles = authStore.user?.roles || []
    const primaryRole = authStore.user?.rol_principal
    const hasRole = requiredRoles.some((role) => userRoles.includes(role) || primaryRole === role)

    if (!hasRole) {
      return next({ name: 'login' })
    }
  }

  return next()
}