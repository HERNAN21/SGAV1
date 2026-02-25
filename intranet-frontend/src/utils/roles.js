const normalizeText = (value) => {
  return String(value || '')
    .trim()
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9]+/g, ' ')
    .trim()
    .replace(/\s+/g, ' ')
}

export const roleKey = (role) => {
  const normalized = normalizeText(role)

  if (!normalized) return ''
  if (normalized === 'admin') return 'administrador'
  if (normalized.includes('secretaria') && normalized.includes('acad')) return 'secretaria academica'

  return normalized
}

export const rolesMatch = (roleA, roleB) => {
  const keyA = roleKey(roleA)
  const keyB = roleKey(roleB)
  return Boolean(keyA) && keyA === keyB
}

export const hasAnyRole = (requiredRoles = [], userRoles = []) => {
  if (!requiredRoles.length) return true

  const userKeys = new Set(userRoles.map((role) => roleKey(role)).filter(Boolean))
  return requiredRoles.some((role) => userKeys.has(roleKey(role)))
}

const roleLabels = {
  administrador: 'Administrador',
  director: 'Director',
  coordinador: 'Coordinador',
  'jefe de carrera': 'Jefe de Carrera',
  docente: 'Docente',
  tutor: 'Tutor',
  'secretaria academica': 'Secretaria Académica',
  tesorero: 'Tesorero',
  estudiante: 'Estudiante',
  bibliotecario: 'Bibliotecario',
  'personal administrativo': 'Personal Administrativo',
}

export const getRoleLabel = (role) => roleLabels[roleKey(role)] || String(role || 'Usuario')
