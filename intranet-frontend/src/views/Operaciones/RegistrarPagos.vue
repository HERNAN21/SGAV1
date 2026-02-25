<template>
  <el-card>
    <template #header><strong>Registrar Pagos</strong></template>
    <el-form ref="formRef" :model="form" :rules="rules" label-position="top">
      <el-row :gutter="12">
        <el-col :xs="24" :md="12"><el-form-item label="Estudiante" prop="id_estudiante"><el-select v-model="form.id_estudiante" filterable style="width:100%"><el-option v-for="opt in catalogs.estudiantes" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Concepto" prop="concepto"><el-input v-model="form.concepto" /></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Monto" prop="monto"><el-input-number v-model="form.monto" :min="0.1" :step="10" style="width:100%" /></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Fecha Pago" prop="fecha_pago"><el-date-picker v-model="form.fecha_pago" type="date" value-format="YYYY-MM-DD" style="width:100%" /></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Medio Pago" prop="medio_pago"><el-select v-model="form.medio_pago" style="width:100%"><el-option label="Efectivo" value="Efectivo" /><el-option label="Transferencia" value="Transferencia" /><el-option label="Tarjeta" value="Tarjeta" /><el-option label="Yape" value="Yape" /><el-option label="Plin" value="Plin" /><el-option label="Otro" value="Otro" /></el-select></el-form-item></el-col>
        <el-col :xs="24"><el-form-item label="Observaciones" prop="observaciones"><el-input v-model="form.observaciones" type="textarea" :rows="3" /></el-form-item></el-col>
      </el-row>
      <el-button type="primary" :loading="loading" @click="submit">Guardar</el-button>
    </el-form>
  </el-card>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { ElMessage } from 'element-plus'
import operacionesService from '../../services/operacionesService'

const loading = ref(false)
const formRef = ref(null)
const catalogs = ref({ estudiantes: [] })
const form = ref({ id_estudiante:null, concepto:'', monto:0, fecha_pago:'', medio_pago:'Efectivo', observaciones:'' })

const rules = {
  id_estudiante: [{ required: true, message: 'Estudiante requerido', trigger: 'change' }],
  concepto: [{ required: true, message: 'Concepto requerido', trigger: 'blur' }],
  monto: [{ required: true, message: 'Monto requerido', trigger: 'change' }],
  fecha_pago: [{ required: true, message: 'Fecha requerida', trigger: 'change' }],
  medio_pago: [{ required: true, message: 'Medio de pago requerido', trigger: 'change' }],
}

const loadCatalogs = async () => {
  const { data } = await operacionesService.catalogs()
  catalogs.value = data
}

const submit = async () => {
  await formRef.value.validate()
  loading.value = true
  try {
    await operacionesService.registrarPago(form.value)
    ElMessage.success('Pago registrado')
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo registrar el pago')
  } finally {
    loading.value = false
  }
}

onMounted(loadCatalogs)
</script>
