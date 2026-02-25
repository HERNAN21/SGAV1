<template>
  <el-card>
    <template #header>
      <div class="header-row">
        <strong>Listado de Alumnos</strong>
        <el-button v-if="canManage" type="primary" @click="openCreate">Nuevo Alumno</el-button>
      </div>
    </template>

    <el-row :gutter="12" class="toolbar-row">
      <el-col :xs="24" :md="8">
        <el-input v-model="filters.search" placeholder="Buscar por nombre/documento/código" clearable @keyup.enter="loadRows" />
      </el-col>
      <el-col :xs="24" :md="6">
        <el-select v-model="filters.id_programa" clearable placeholder="Programa" style="width: 100%">
          <el-option v-for="opt in programas" :key="opt.value" :label="opt.label" :value="opt.value" />
        </el-select>
      </el-col>
      <el-col :xs="24" :md="6">
        <el-select v-model="filters.id_estado_academico" clearable placeholder="Estado Académico" style="width: 100%">
          <el-option v-for="opt in estados" :key="opt.value" :label="opt.label" :value="opt.value" />
        </el-select>
      </el-col>
      <el-col :xs="24" :md="4" class="toolbar-actions">
        <el-button type="primary" @click="loadRows">Filtrar</el-button>
      </el-col>
    </el-row>

    <el-table :data="rows" border stripe v-loading="loading" style="width: 100%">
      <el-table-column prop="codigo_estudiante" label="Código" min-width="120" />
      <el-table-column prop="estudiante" label="Alumno" min-width="220" />
      <el-table-column prop="documento" label="Documento" min-width="120" />
      <el-table-column prop="email" label="Email" min-width="220" />
      <el-table-column prop="programa" label="Programa" min-width="220" />
      <el-table-column prop="estado_academico" label="Estado" min-width="130" />
      <el-table-column prop="fecha_ingreso" label="Ingreso" min-width="120" />
      <el-table-column v-if="canManage" label="Acciones" min-width="180" fixed="right">
        <template #default="{ row }">
          <el-space>
            <el-button size="small" @click="openEdit(row)">Editar</el-button>
            <el-button size="small" type="danger" :loading="deletingId === row.id_estudiante" @click="removeRow(row)">Eliminar</el-button>
          </el-space>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog v-model="dialogVisible" :title="isEdit ? 'Editar Alumno' : 'Nuevo Alumno'" width="900px" destroy-on-close>
      <el-form ref="formRef" :model="form" :rules="rules" label-position="top">
        <el-row :gutter="12">
          <el-col :xs="24" :md="12"><el-form-item label="Documento" prop="documento"><el-input v-model="form.documento" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Código Estudiante" prop="codigo_estudiante"><el-input v-model="form.codigo_estudiante" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Nombres" prop="nombres"><el-input v-model="form.nombres" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Apellidos" prop="apellidos"><el-input v-model="form.apellidos" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Email" prop="email"><el-input v-model="form.email" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item :label="isEdit ? 'Contraseña (opcional)' : 'Contraseña'" prop="password"><el-input v-model="form.password" type="password" show-password /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Facultad" prop="id_facultad"><el-select v-model="form.id_facultad" style="width:100%"><el-option v-for="opt in facultades" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Programa" prop="id_programa"><el-select v-model="form.id_programa" style="width:100%"><el-option v-for="opt in programas" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Estado Académico" prop="id_estado_academico"><el-select v-model="form.id_estado_academico" style="width:100%"><el-option v-for="opt in estados" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Fecha Ingreso" prop="fecha_ingreso"><el-date-picker v-model="form.fecha_ingreso" type="date" value-format="YYYY-MM-DD" style="width:100%" /></el-form-item></el-col>
        </el-row>
      </el-form>
      <template #footer>
        <el-button @click="dialogVisible = false">Cancelar</el-button>
        <el-button type="primary" :loading="saving" @click="submit">Guardar</el-button>
      </template>
    </el-dialog>
  </el-card>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import operacionesService from '../../services/operacionesService'
import { useAuthStore } from '../../stores/authStore'
import { hasAnyRole } from '../../utils/roles'

const loading = ref(false)
const rows = ref([])
const authStore = useAuthStore()
const programas = ref([])
const facultades = ref([])
const estados = ref([])
const dialogVisible = ref(false)
const isEdit = ref(false)
const editingId = ref(null)
const saving = ref(false)
const deletingId = ref(null)
const formRef = ref(null)

const emptyForm = () => ({
  documento: '',
  codigo_estudiante: '',
  nombres: '',
  apellidos: '',
  email: '',
  password: '',
  id_facultad: null,
  id_programa: null,
  id_estado_academico: 1,
  fecha_ingreso: '',
})

const form = ref(emptyForm())

const canManage = computed(() => hasAnyRole(
  ['Administrador', 'Secretaria Académica'],
  [...(authStore.user?.roles || []), authStore.user?.rol_principal].filter(Boolean)
))

const passwordValidator = (_rule, value, callback) => {
  if (isEdit.value && !value) {
    callback()
    return
  }

  if (!value || String(value).length < 6) {
    callback(new Error('Contraseña mínima 6 caracteres'))
    return
  }

  callback()
}

const rules = {
  documento: [{ required: true, message: 'Documento requerido', trigger: 'blur' }],
  codigo_estudiante: [{ required: true, message: 'Código requerido', trigger: 'blur' }],
  nombres: [{ required: true, message: 'Nombres requeridos', trigger: 'blur' }],
  apellidos: [{ required: true, message: 'Apellidos requeridos', trigger: 'blur' }],
  email: [{ required: true, message: 'Email requerido', trigger: 'blur' }, { type: 'email', message: 'Email inválido', trigger: 'blur' }],
  password: [{ validator: passwordValidator, trigger: 'blur' }],
  id_facultad: [{ required: true, message: 'Facultad requerida', trigger: 'change' }],
  id_programa: [{ required: true, message: 'Programa requerido', trigger: 'change' }],
  id_estado_academico: [{ required: true, message: 'Estado requerido', trigger: 'change' }],
  fecha_ingreso: [{ required: true, message: 'Fecha requerida', trigger: 'change' }],
}

const filters = ref({
  search: '',
  id_programa: null,
  id_estado_academico: null,
})

const loadCatalogs = async () => {
  const { data } = await operacionesService.catalogs()
  facultades.value = data.facultades || []
  programas.value = data.programas || []
  estados.value = data.estadosAcademicos || []
}

const loadRows = async () => {
  loading.value = true
  try {
    const { data } = await operacionesService.listarAlumnos(filters.value)
    rows.value = data
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo cargar alumnos')
  } finally {
    loading.value = false
  }
}

const openCreate = () => {
  isEdit.value = false
  editingId.value = null
  form.value = emptyForm()
  dialogVisible.value = true
}

const openEdit = (row) => {
  isEdit.value = true
  editingId.value = row.id_estudiante
  form.value = {
    documento: row.documento || '',
    codigo_estudiante: row.codigo_estudiante || '',
    nombres: row.nombres || '',
    apellidos: row.apellidos || '',
    email: row.email || '',
    password: '',
    id_facultad: row.id_facultad || null,
    id_programa: row.id_programa || null,
    id_estado_academico: row.id_estado_academico || null,
    fecha_ingreso: row.fecha_ingreso || '',
  }
  dialogVisible.value = true
}

const submit = async () => {
  await formRef.value?.validate()
  saving.value = true
  try {
    if (isEdit.value && editingId.value) {
      await operacionesService.actualizarAlumno(editingId.value, form.value)
      ElMessage.success('Alumno actualizado correctamente')
    } else {
      await operacionesService.crearAlumno(form.value)
      ElMessage.success('Alumno creado correctamente')
    }

    dialogVisible.value = false
    await loadRows()
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo guardar el alumno')
  } finally {
    saving.value = false
  }
}

const removeRow = async (row) => {
  try {
    await ElMessageBox.confirm(
      `Se eliminará al alumno ${row.estudiante}. ¿Deseas continuar?`,
      'Confirmar eliminación',
      { type: 'warning', confirmButtonText: 'Eliminar', cancelButtonText: 'Cancelar' }
    )
  } catch {
    return
  }

  deletingId.value = row.id_estudiante
  try {
    await operacionesService.eliminarAlumno(row.id_estudiante)
    ElMessage.success('Alumno eliminado correctamente')
    await loadRows()
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo eliminar el alumno')
  } finally {
    deletingId.value = null
  }
}

onMounted(async () => {
  await loadCatalogs()
  await loadRows()
})
</script>

<style scoped>
.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.toolbar-row {
  margin-bottom: 12px;
}

.toolbar-actions {
  display: flex;
  justify-content: flex-end;
}

@media (max-width: 768px) {
  .toolbar-actions {
    justify-content: flex-start;
    margin-top: 8px;
  }
}
</style>
