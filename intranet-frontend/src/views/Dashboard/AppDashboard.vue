<template>
  <RoleDashboardLayout
    title="Panel Unificado"
    :role="roleBadge"
    :page-title="pageTitle"
    :active-menu="activeMenu"
    :items="menuItems"
  >
    <template #actions>
      <el-select
        v-if="availableRoles.length > 1"
        v-model="selectedRole"
        size="small"
        class="role-select"
        @change="onRoleChange"
      >
        <el-option label="Todos" value="__ALL__" />
        <el-option
          v-for="role in availableRoles"
          :key="role"
          :label="role"
          :value="role"
        />
      </el-select>

      <el-switch
        v-model="profileToggle"
        inline-prompt
        active-text="Perfil"
        inactive-text="Módulos"
        @change="onToggleProfile"
      />
    </template>

    <RouterView />
  </RoleDashboardLayout>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { RouterView, useRoute, useRouter } from 'vue-router'
import RoleDashboardLayout from '../../components/layout/RoleDashboardLayout.vue'
import { useAuthStore } from '../../stores/authStore'
import dashboardModules from '../../config/dashboardModules'

const roleFilterKey = 'dashboard_role_filter'
const allRolesKey = '__ALL__'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const availableRoles = computed(() => {
  const roles = authStore.user?.roles || []
  if (roles.length > 0) return roles
  return authStore.user?.rol_principal ? [authStore.user.rol_principal] : []
})

const roleBadge = computed(() => {
  if (selectedRole.value === allRolesKey) return authStore.user?.rol_principal || 'Usuario'
  return selectedRole.value
})

const selectedRole = ref(localStorage.getItem(roleFilterKey) || allRolesKey)

watch(availableRoles, (roles) => {
  if (roles.length === 0) {
    selectedRole.value = allRolesKey
    return
  }

  if (selectedRole.value !== allRolesKey && !roles.includes(selectedRole.value)) {
    selectedRole.value = allRolesKey
  }
}, { immediate: true })

const hasModuleAccess = (module, roles) => {
  if (!module.roles || module.roles.length === 0) return true
  return module.roles.some((role) => roles.includes(role))
}

const filteredByRoleModules = computed(() => {
  const roles = availableRoles.value
  return dashboardModules.filter((module) => hasModuleAccess(module, roles))
})

const visibleModules = computed(() => {
  if (selectedRole.value === allRolesKey) return filteredByRoleModules.value

  return filteredByRoleModules.value.filter((module) => {
    if (!module.roles || module.roles.length === 0) return true
    return module.roles.includes(selectedRole.value)
  })
})

const menuItems = computed(() => visibleModules.value.map((module) => ({
  index: `/dashboard/${module.path}`.replace(/\/$/, ''),
  label: module.label,
  icon: module.icon,
})))

const dashboardPath = '/dashboard'
const activeMenu = computed(() => {
  const exact = menuItems.value.find((item) => item.index === route.path)
  if (exact) return exact.index
  if (route.path === dashboardPath) return dashboardPath
  return menuItems.value[0]?.index || dashboardPath
})

const pageTitle = computed(() => menuItems.value.find((item) => item.index === activeMenu.value)?.label || 'Dashboard')
const profileToggle = computed({
  get: () => route.path === '/dashboard/perfil',
  set: () => {},
})

const navigateToFirstModule = async () => {
  const first = menuItems.value[0]?.index || dashboardPath
  if (route.path !== first) {
    await router.replace(first)
  }
}

const ensureCurrentRouteVisible = async () => {
  const currentPath = route.path === dashboardPath ? '/dashboard' : route.path
  const exists = menuItems.value.some((item) => item.index === currentPath)
  if (!exists) {
    await navigateToFirstModule()
  }
}

watch(menuItems, async () => {
  await ensureCurrentRouteVisible()
}, { immediate: true })

const onRoleChange = async (value) => {
  localStorage.setItem(roleFilterKey, value)
  await ensureCurrentRouteVisible()
}

const onToggleProfile = async (value) => {
  if (value) {
    await router.push('/dashboard/perfil')
    return
  }

  await navigateToFirstModule()
}
</script>

<style scoped>
.role-select {
  width: 190px;
}
</style>
