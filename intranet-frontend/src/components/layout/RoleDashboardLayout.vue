<template>
  <el-container class="role-layout">
    <el-aside class="role-aside" width="280px">
      <div class="role-brand">
        <div class="role-brand-logo">SG</div>
        <div>
          <h3>{{ title }}</h3>
          <small>{{ role }}</small>
        </div>
      </div>

      <el-menu
        class="role-menu"
        :default-active="activeMenu"
        @select="onSelect"
      >
        <el-menu-item
          v-for="item in items"
          :key="item.index"
          :index="item.index"
        >
          <el-icon>
            <component :is="resolveIcon(item.icon)" />
          </el-icon>
          <span>{{ item.label }}</span>
        </el-menu-item>
      </el-menu>
    </el-aside>

    <el-container>
      <el-header class="role-header">
        <div class="role-header-title">{{ pageTitle }}</div>
        <div class="role-header-actions">
          <slot name="actions" />
          <el-tag type="primary" effect="light">{{ role }}</el-tag>
          <el-button type="danger" plain @click="onLogout">
            <el-icon><SwitchButton /></el-icon>
            Cerrar sesión
          </el-button>
        </div>
      </el-header>

      <el-main class="role-main">
        <slot />
      </el-main>
    </el-container>
  </el-container>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/authStore'
import authService from '../../services/authService'
import {
  DataBoard,
  User,
  OfficeBuilding,
  Notification,
  Calendar,
  Reading,
  Tickets,
  Notebook,
  School,
  Document,
  Money,
  Collection,
  Bell,
  Suitcase,
  Files,
  SwitchButton,
} from '@element-plus/icons-vue'

const props = defineProps({
  title: { type: String, required: true },
  role: { type: String, required: true },
  pageTitle: { type: String, required: true },
  activeMenu: { type: String, required: true },
  items: {
    type: Array,
    default: () => [],
  },
})

const router = useRouter()
const authStore = useAuthStore()

const iconMap = {
  DataBoard,
  User,
  OfficeBuilding,
  Notification,
  Calendar,
  Reading,
  Tickets,
  Notebook,
  School,
  Document,
  Money,
  Collection,
  Bell,
  Suitcase,
  Files,
  SwitchButton,
}

const resolveIcon = (name) => iconMap[name] || DataBoard

const onSelect = (index) => {
  router.push(index)
}

const onLogout = async () => {
  try {
    if (authStore.token) {
      await authService.logout()
    }
  } catch {
  } finally {
    authStore.logout()
    router.replace({ name: 'login' })
  }
}
</script>

<style scoped>
.role-layout {
  min-height: 100vh;
  background: #f5f7fb;
}

.role-aside {
  border-right: 1px solid #e5e7eb;
  background: #fff;
}

.role-brand {
  display: flex;
  gap: 12px;
  align-items: center;
  padding: 16px;
  border-bottom: 1px solid #eef0f3;
}

.role-brand-logo {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: grid;
  place-items: center;
  background: #2563eb;
  color: #fff;
  font-weight: 700;
}

.role-brand h3 {
  margin: 0;
  font-size: 1rem;
}

.role-brand small {
  color: #6b7280;
}

.role-menu {
  border-right: none;
  padding-top: 8px;
}

.role-header {
  border-bottom: 1px solid #e5e7eb;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.role-header-title {
  font-weight: 600;
}

.role-header-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.role-main {
  padding: 20px;
}
</style>