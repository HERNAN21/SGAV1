<template>
  <el-row :gutter="12">
    <el-col :xs="24" :sm="12" :md="8"><el-card><strong>Total Estudiantes</strong><div class="kpi">{{ data.total_estudiantes ?? 0 }}</div></el-card></el-col>
    <el-col :xs="24" :sm="12" :md="8"><el-card><strong>Total Cursos</strong><div class="kpi">{{ data.total_cursos ?? 0 }}</div></el-card></el-col>
    <el-col :xs="24" :sm="12" :md="8"><el-card><strong>Notas Registradas</strong><div class="kpi">{{ data.total_notas_registradas ?? 0 }}</div></el-card></el-col>
    <el-col :xs="24" :sm="12" :md="8"><el-card><strong>Promedio Notas</strong><div class="kpi">{{ data.promedio_general_notas ?? 0 }}</div></el-card></el-col>
    <el-col :xs="24" :sm="12" :md="8"><el-card><strong>Total Pagos</strong><div class="kpi">{{ data.total_pagos ?? 0 }}</div></el-card></el-col>
    <el-col :xs="24" :sm="12" :md="8"><el-card><strong>Monto Pagado</strong><div class="kpi">S/ {{ data.monto_total_pagado ?? 0 }}</div></el-card></el-col>
  </el-row>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ElMessage } from 'element-plus'
import operacionesService from '../../services/operacionesService'

const data = ref({})

onMounted(async () => {
  try {
    const response = await operacionesService.verReportes()
    data.value = response.data
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo cargar reportes')
  }
})
</script>

<style scoped>
.kpi {
  font-size: 1.4rem;
  font-weight: 700;
  margin-top: 8px;
}
</style>
