<template>
  <el-card>
    <template #header><strong>Registrar Notas</strong></template>
    <el-form ref="formRef" :model="form" :rules="rules" label-position="top">
      <el-row :gutter="12">
        <el-col :xs="24" :md="12"><el-form-item label="Evaluación" prop="id_evaluacion"><el-select v-model="form.id_evaluacion" style="width:100%"><el-option v-for="opt in catalogs.evaluaciones" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Curso" prop="id_curso"><el-select v-model="form.id_curso" style="width:100%"><el-option v-for="opt in catalogs.cursos" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Estudiante" prop="id_estudiante"><el-select v-model="form.id_estudiante" filterable style="width:100%"><el-option v-for="opt in catalogs.estudiantes" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
        <el-col :xs="24" :md="12"><el-form-item label="Calificación" prop="calificacion"><el-input-number v-model="form.calificacion" :min="0" :max="20" :step="0.1" style="width:100%" /></el-form-item></el-col>
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
const catalogs = ref({ evaluaciones: [], cursos: [], estudiantes: [] })
const form = ref({ id_evaluacion:null, id_curso:null, id_estudiante:null, calificacion: 11, observaciones:'' })

const rules = {
  id_evaluacion: [{ required: true, message: 'Evaluación requerida', trigger: 'change' }],
  id_curso: [{ required: true, message: 'Curso requerido', trigger: 'change' }],
  id_estudiante: [{ required: true, message: 'Estudiante requerido', trigger: 'change' }],
  calificacion: [{ required: true, message: 'Calificación requerida', trigger: 'change' }],
}

const loadCatalogs = async () => {
  const { data } = await operacionesService.catalogs()
  catalogs.value = data
}

const submit = async () => {
  await formRef.value.validate()
  loading.value = true
  try {
    await operacionesService.registrarNota(form.value)
    ElMessage.success('Nota registrada')
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo registrar la nota')
  } finally {
    loading.value = false
  }
}

onMounted(loadCatalogs)
</script>
