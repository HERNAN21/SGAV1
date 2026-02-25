<template>
  <el-card>
    <template #header><strong>Matriz de Permisos por Rol</strong></template>

    <el-table :data="rows" border stripe v-loading="loading" style="width: 100%">
      <el-table-column prop="rol" label="Rol" min-width="190" />
      <el-table-column label="Crea Alumnos" min-width="130" align="center">
        <template #default="{ row }">
          <el-checkbox v-if="isAdmin" v-model="row.crea_alumnos" />
          <el-tag v-else :type="row.crea_alumnos ? 'success' : 'danger'" effect="plain">{{ row.crea_alumnos ? '✔' : '✖' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Crea Cursos" min-width="120" align="center">
        <template #default="{ row }">
          <el-checkbox v-if="isAdmin" v-model="row.crea_cursos" />
          <el-tag v-else :type="row.crea_cursos ? 'success' : 'danger'" effect="plain">{{ row.crea_cursos ? '✔' : '✖' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Registra Notas" min-width="130" align="center">
        <template #default="{ row }">
          <el-checkbox v-if="isAdmin" v-model="row.registra_notas" />
          <el-tag v-else :type="row.registra_notas ? 'success' : 'danger'" effect="plain">{{ row.registra_notas ? '✔' : '✖' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Registra Pagos" min-width="130" align="center">
        <template #default="{ row }">
          <el-checkbox v-if="isAdmin" v-model="row.registra_pagos" />
          <el-tag v-else :type="row.registra_pagos ? 'success' : 'danger'" effect="plain">{{ row.registra_pagos ? '✔' : '✖' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Ver Reportes" min-width="120" align="center">
        <template #default="{ row }">
          <el-checkbox v-if="isAdmin" v-model="row.ver_reportes" />
          <el-tag v-else :type="row.ver_reportes ? 'success' : 'danger'" effect="plain">{{ row.ver_reportes ? '✔' : '✖' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column v-if="isAdmin" label="Guardar" min-width="110" align="center">
        <template #default="{ row }">
          <el-button type="primary" size="small" :loading="savingRoleId === row.id_rol" @click="saveRow(row)">Guardar</el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-card>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ElMessage } from 'element-plus'
import operacionesService from '../../services/operacionesService'
import { useAuthStore } from '../../stores/authStore'

const loading = ref(false)
const rows = ref([])
const savingRoleId = ref(null)
const authStore = useAuthStore()

const isAdmin = (authStore.user?.roles || []).includes('Administrador') || authStore.user?.rol_principal === 'Administrador'

const saveRow = async (row) => {
  savingRoleId.value = row.id_rol
  try {
    await operacionesService.actualizarPermisosRol(row.id_rol, {
      crea_alumnos: !!row.crea_alumnos,
      crea_cursos: !!row.crea_cursos,
      registra_notas: !!row.registra_notas,
      registra_pagos: !!row.registra_pagos,
      ver_reportes: !!row.ver_reportes,
    })
    ElMessage.success(`Permisos actualizados para ${row.rol}`)
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo actualizar permisos')
  } finally {
    savingRoleId.value = null
  }
}

onMounted(async () => {
  loading.value = true
  try {
    const { data } = await operacionesService.matrizPermisos()
    rows.value = data
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo cargar la matriz de permisos')
  } finally {
    loading.value = false
  }
})
</script>
