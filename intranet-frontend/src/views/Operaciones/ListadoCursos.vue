<template>
  <el-card>
    <template #header>
      <div class="header-row">
        <strong>Listado de Cursos</strong>
        <el-button v-if="canManage" type="primary" @click="openCreate">Nuevo Curso</el-button>
      </div>
    </template>

    <el-row :gutter="12" class="toolbar-row">
      <el-col :xs="24" :md="8">
        <el-input v-model="filters.search" placeholder="Buscar por curso/materia/docente" clearable @keyup.enter="loadRows" />
      </el-col>
      <el-col :xs="24" :md="5">
        <el-select v-model="filters.id_periodo" clearable placeholder="Periodo" style="width: 100%">
          <el-option v-for="opt in periodos" :key="opt.value" :label="opt.label" :value="opt.value" />
        </el-select>
      </el-col>
      <el-col :xs="24" :md="5">
        <el-select v-model="filters.id_docente" clearable filterable placeholder="Docente" style="width: 100%">
          <el-option v-for="opt in docentes" :key="opt.value" :label="opt.label" :value="opt.value" />
        </el-select>
      </el-col>
      <el-col :xs="24" :md="4">
        <el-select v-model="filters.id_materia" clearable filterable placeholder="Materia" style="width: 100%">
          <el-option v-for="opt in materias" :key="opt.value" :label="opt.label" :value="opt.value" />
        </el-select>
      </el-col>
      <el-col :xs="24" :md="2" class="toolbar-actions">
        <el-button type="primary" @click="loadRows">Filtrar</el-button>
      </el-col>
    </el-row>

    <el-table :data="rows" border stripe v-loading="loading" style="width: 100%">
      <el-table-column prop="nombre_curso" label="Curso" min-width="220" />
      <el-table-column prop="materia" label="Materia" min-width="180" />
      <el-table-column prop="docente" label="Docente" min-width="180" />
      <el-table-column prop="periodo" label="Periodo" min-width="120" />
      <el-table-column prop="aula" label="Aula" min-width="90" />
      <el-table-column prop="horario" label="Horario" min-width="160" />
      <el-table-column prop="tipo_curso" label="Tipo" min-width="120" />
      <el-table-column prop="cupo_maximo" label="Cupo" min-width="90" />
      <el-table-column v-if="canManage" label="Acciones" min-width="180" fixed="right">
        <template #default="{ row }">
          <el-space>
            <el-button size="small" @click="openEdit(row)">Editar</el-button>
            <el-button size="small" type="danger" :loading="deletingId === row.id_curso" @click="removeRow(row)">Eliminar</el-button>
          </el-space>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog v-model="dialogVisible" :title="isEdit ? 'Editar Curso' : 'Nuevo Curso'" width="860px" destroy-on-close>
      <el-form ref="formRef" :model="form" :rules="rules" label-position="top">
        <el-row :gutter="12">
          <el-col :xs="24" :md="12"><el-form-item label="Materia" prop="id_materia"><el-select v-model="form.id_materia" filterable style="width:100%"><el-option v-for="opt in materias" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Docente" prop="id_docente"><el-select v-model="form.id_docente" filterable style="width:100%"><el-option v-for="opt in docentes" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Periodo" prop="id_periodo"><el-select v-model="form.id_periodo" style="width:100%"><el-option v-for="opt in periodos" :key="opt.value" :label="opt.label" :value="opt.value" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Nombre Curso" prop="nombre_curso"><el-input v-model="form.nombre_curso" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Aula" prop="aula"><el-input v-model="form.aula" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Horario" prop="horario"><el-input v-model="form.horario" /></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Tipo Curso" prop="tipo_curso"><el-select v-model="form.tipo_curso" style="width:100%"><el-option label="Presencial" value="Presencial" /><el-option label="Virtual" value="Virtual" /><el-option label="Híbrido" value="Híbrido" /></el-select></el-form-item></el-col>
          <el-col :xs="24" :md="12"><el-form-item label="Cupo Máximo" prop="cupo_maximo"><el-input-number v-model="form.cupo_maximo" :min="1" :max="999" style="width:100%" /></el-form-item></el-col>
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
const periodos = ref([])
const docentes = ref([])
const materias = ref([])
const authStore = useAuthStore()
const dialogVisible = ref(false)
const isEdit = ref(false)
const editingId = ref(null)
const saving = ref(false)
const deletingId = ref(null)
const formRef = ref(null)

const emptyForm = () => ({
  id_materia: null,
  id_docente: null,
  id_periodo: null,
  nombre_curso: '',
  aula: '',
  horario: '',
  tipo_curso: 'Presencial',
  cupo_maximo: 40,
})

const form = ref(emptyForm())

const canManage = computed(() => hasAnyRole(
  ['Administrador', 'Coordinador', 'Secretaria Académica'],
  [...(authStore.user?.roles || []), authStore.user?.rol_principal].filter(Boolean)
))

const rules = {
  id_materia: [{ required: true, message: 'Materia requerida', trigger: 'change' }],
  id_docente: [{ required: true, message: 'Docente requerido', trigger: 'change' }],
  id_periodo: [{ required: true, message: 'Periodo requerido', trigger: 'change' }],
  nombre_curso: [{ required: true, message: 'Nombre requerido', trigger: 'blur' }],
  tipo_curso: [{ required: true, message: 'Tipo requerido', trigger: 'change' }],
  cupo_maximo: [{ required: true, message: 'Cupo requerido', trigger: 'change' }],
}

const filters = ref({
  search: '',
  id_periodo: null,
  id_docente: null,
  id_materia: null,
})

const loadCatalogs = async () => {
  const { data } = await operacionesService.catalogs()
  periodos.value = data.periodos || []
  docentes.value = data.docentes || []
  materias.value = data.materias || []
}

const loadRows = async () => {
  loading.value = true
  try {
    const { data } = await operacionesService.listarCursos(filters.value)
    rows.value = data
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo cargar cursos')
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
  editingId.value = row.id_curso
  form.value = {
    id_materia: row.id_materia ?? null,
    id_docente: row.id_docente ?? null,
    id_periodo: row.id_periodo ?? null,
    nombre_curso: row.nombre_curso ?? '',
    aula: row.aula ?? '',
    horario: row.horario ?? '',
    tipo_curso: row.tipo_curso ?? 'Presencial',
    cupo_maximo: row.cupo_maximo ?? 40,
  }
  dialogVisible.value = true
}

const submit = async () => {
  await formRef.value?.validate()
  saving.value = true
  try {
    if (isEdit.value && editingId.value) {
      await operacionesService.actualizarCurso(editingId.value, form.value)
      ElMessage.success('Curso actualizado correctamente')
    } else {
      await operacionesService.crearCurso(form.value)
      ElMessage.success('Curso creado correctamente')
    }

    dialogVisible.value = false
    await loadRows()
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo guardar el curso')
  } finally {
    saving.value = false
  }
}

const removeRow = async (row) => {
  try {
    await ElMessageBox.confirm(
      `Se eliminará el curso ${row.nombre_curso}. ¿Deseas continuar?`,
      'Confirmar eliminación',
      { type: 'warning', confirmButtonText: 'Eliminar', cancelButtonText: 'Cancelar' }
    )
  } catch {
    return
  }

  deletingId.value = row.id_curso
  try {
    await operacionesService.eliminarCurso(row.id_curso)
    ElMessage.success('Curso eliminado correctamente')
    await loadRows()
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'No se pudo eliminar el curso')
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
