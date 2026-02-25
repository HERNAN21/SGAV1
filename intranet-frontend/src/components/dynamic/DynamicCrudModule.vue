<template>
  <el-card shadow="never" class="crud-card">
    <template #header>
      <div class="crud-header">
        <div>
          <h3>{{ schema?.title || entity }}</h3>
          <small>Formulario dinámico con validaciones</small>
        </div>
        <el-button type="primary" @click="openCreate">Nuevo registro</el-button>
      </div>
    </template>

    <el-row :gutter="12" class="toolbar-row">
      <el-col :xs="24" :md="12">
        <el-input v-model="search" placeholder="Buscar..." clearable @keyup.enter="loadRows" />
      </el-col>
      <el-col :xs="24" :md="12" class="toolbar-actions">
        <el-button @click="loadRows">Buscar</el-button>
      </el-col>
    </el-row>

    <el-table :data="rows" border stripe v-loading="loading" style="width: 100%">
      <el-table-column
        v-for="column in columns"
        :key="column.prop"
        :prop="column.prop"
        :label="column.label"
        :formatter="formatCell"
        min-width="140"
      />
      <el-table-column label="Acciones" width="180" fixed="right">
        <template #default="{ row }">
          <el-button link type="primary" @click="openEdit(row)">Editar</el-button>
          <el-button link type="danger" @click="removeRow(row)">Eliminar</el-button>
        </template>
      </el-table-column>
    </el-table>

    <div class="pager">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        :page-size="perPage"
        :current-page="page"
        @current-change="onPageChange"
      />
    </div>

    <el-dialog v-model="dialogVisible" :title="isEditing ? 'Editar' : 'Nuevo'" width="760px">
      <el-form ref="formRef" :model="form" :rules="formRules" label-position="top">
        <el-row :gutter="12">
          <el-col
            v-for="field in schema?.fields || []"
            :key="field.name"
            :xs="24"
            :sm="field.type === 'textarea' ? 24 : 12"
          >
            <el-form-item :label="field.label" :prop="field.name">
              <el-select
                v-if="field.type === 'select'"
                v-model="form[field.name]"
                filterable
                clearable
                style="width: 100%"
              >
                <el-option
                  v-for="option in getOptions(field.optionsKey)"
                  :key="option.value"
                  :label="option.label"
                  :value="option.value"
                />
              </el-select>

              <el-select
                v-else-if="field.type === 'multiselect'"
                v-model="form[field.name]"
                multiple
                filterable
                clearable
                style="width: 100%"
              >
                <el-option
                  v-for="option in getOptions(field.optionsKey)"
                  :key="option.value"
                  :label="option.label"
                  :value="option.value"
                />
              </el-select>

              <el-date-picker
                v-else-if="field.type === 'date'"
                v-model="form[field.name]"
                type="date"
                value-format="YYYY-MM-DD"
                placeholder="Selecciona fecha"
                style="width: 100%"
              />

              <el-switch
                v-else-if="field.type === 'switch'"
                v-model="form[field.name]"
              />

              <el-input
                v-else-if="field.type === 'textarea'"
                v-model="form[field.name]"
                type="textarea"
                :rows="3"
              />

              <el-input
                v-else
                v-model="form[field.name]"
                :type="field.type === 'password' ? 'password' : 'text'"
                :show-password="field.type === 'password'"
                :maxlength="field.maxlength || null"
              />
            </el-form-item>
          </el-col>
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
import { computed, onMounted, reactive, ref } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import adminDynamicService from '../../services/adminDynamicService'

const props = defineProps({
  entity: { type: String, required: true },
})

const loading = ref(false)
const saving = ref(false)
const dialogVisible = ref(false)
const isEditing = ref(false)
const page = ref(1)
const perPage = ref(15)
const total = ref(0)
const rows = ref([])
const search = ref('')
const schemaMap = ref({})
const catalogs = ref({})
const formRef = ref(null)

const form = reactive({})

const schema = computed(() => schemaMap.value[props.entity] || null)
const endpoint = computed(() => schema.value?.endpoint || '/admin/instituciones')
const primaryKey = computed(() => schema.value?.primaryKey || 'id')
const fieldsByName = computed(() => {
  const map = {}
  for (const field of schema.value?.fields || []) {
    map[field.name] = field
  }
  return map
})

const columns = computed(() => {
  if (!schema.value?.fields) return []

  if (Array.isArray(schema.value.listColumns) && schema.value.listColumns.length > 0) {
    return schema.value.listColumns.map((column) => {
      if (typeof column === 'string') {
        const field = fieldsByName.value[column]
        return {
          prop: column,
          label: field?.label || column,
        }
      }

      const field = fieldsByName.value[column.prop]
      return {
        prop: column.prop,
        label: column.label || field?.label || column.prop,
      }
    })
  }

  return schema.value.fields
    .filter((field) => !['password', 'textarea', 'switch', 'multiselect'].includes(field.type))
    .slice(0, 6)
    .map((field) => ({ prop: field.name, label: field.label }))
})

const formRules = computed(() => {
  const rules = {}

  for (const field of schema.value?.fields || []) {
    const fieldRules = []

    if (field.required) {
      fieldRules.push({ required: true, message: `${field.label} es obligatorio`, trigger: 'blur' })
    }

    if (field.type === 'email') {
      fieldRules.push({ type: 'email', message: 'Email inválido', trigger: 'blur' })
    }

    if (field.maxlength) {
      fieldRules.push({ max: field.maxlength, message: `Máximo ${field.maxlength} caracteres`, trigger: 'blur' })
    }

    if (field.minlength) {
      fieldRules.push({ min: field.minlength, message: `Mínimo ${field.minlength} caracteres`, trigger: 'blur' })
    }

    if (fieldRules.length > 0) {
      rules[field.name] = fieldRules
    }
  }

  return rules
})

const getOptions = (key) => catalogs.value[key] || []

const formatCell = (row, column) => {
  const prop = column?.property
  const value = row?.[prop]
  const field = fieldsByName.value[prop]

  if (field?.type === 'select' && field.optionsKey) {
    const option = getOptions(field.optionsKey).find((item) => String(item.value) === String(value))
    return option?.label || value || '-'
  }

  if (field?.type === 'multiselect') {
    if (Array.isArray(value)) {
      const optionMap = new Map(getOptions(field.optionsKey).map((item) => [String(item.value), item.label]))
      const labels = value.map((item) => optionMap.get(String(item)) || item).filter(Boolean)
      return labels.length ? labels.join(', ') : '-'
    }

    if (props.entity === 'usuarios' && Array.isArray(row?.roles_catalogo)) {
      const labels = row.roles_catalogo.map((role) => role?.nombre).filter(Boolean)
      return labels.length ? labels.join(', ') : '-'
    }
  }

  if (field?.type === 'switch') {
    return value ? 'Sí' : 'No'
  }

  if (value === null || value === undefined || value === '') {
    return '-'
  }

  return value
}

const resetForm = () => {
  Object.keys(form).forEach((key) => delete form[key])

  for (const field of schema.value?.fields || []) {
    if (field.type === 'switch') form[field.name] = true
    else if (field.type === 'multiselect') form[field.name] = []
    else form[field.name] = ''
  }
}

const normalizePayload = () => {
  const payload = {}
  for (const field of schema.value?.fields || []) {
    const value = form[field.name]
    if (field.type === 'password' && isEditing.value && !value) continue
    payload[field.name] = value === '' ? null : value
  }

  return payload
}

const loadRows = async () => {
  if (!schema.value) return
  loading.value = true
  try {
    const { data } = await adminDynamicService.list(endpoint.value, { page: page.value, search: search.value })
    rows.value = data.data || []
    total.value = data.total || 0
    perPage.value = data.per_page || 15
  } catch (error) {
    ElMessage.error(error?.response?.data?.message || 'Error al listar registros')
  } finally {
    loading.value = false
  }
}

const loadConfig = async () => {
  const [schemasResponse, catalogsResponse] = await Promise.all([
    adminDynamicService.getSchemas(),
    adminDynamicService.getCatalogs(),
  ])

  schemaMap.value = schemasResponse.data
  catalogs.value = catalogsResponse.data
}

const openCreate = () => {
  isEditing.value = false
  resetForm()
  dialogVisible.value = true
}

const openEdit = (row) => {
  isEditing.value = true
  resetForm()

  for (const field of schema.value?.fields || []) {
    if (field.name === 'password') continue

    if (field.name === 'roles') {
      form[field.name] = (row.roles_catalogo || []).map((role) => role.id_rol)
      continue
    }

    form[field.name] = row[field.name] ?? (field.type === 'multiselect' ? [] : '')
  }

  form[primaryKey.value] = row[primaryKey.value]
  dialogVisible.value = true
}

const submit = async () => {
  if (!formRef.value) return

  await formRef.value.validate()

  saving.value = true
  try {
    const payload = normalizePayload()

    if (isEditing.value) {
      await adminDynamicService.update(endpoint.value, form[primaryKey.value], payload)
      ElMessage.success('Registro actualizado')
    } else {
      await adminDynamicService.create(endpoint.value, payload)
      ElMessage.success('Registro creado')
    }

    dialogVisible.value = false
    await loadRows()
  } catch (error) {
    if (error?.response?.status === 422) {
      const firstError = Object.values(error.response.data.errors || {})[0]
      if (firstError?.[0]) {
        ElMessage.error(firstError[0])
        return
      }
    }

    ElMessage.error(error?.response?.data?.message || 'No se pudo guardar')
  } finally {
    saving.value = false
  }
}

const removeRow = async (row) => {
  try {
    await ElMessageBox.confirm('¿Eliminar este registro?', 'Confirmación', { type: 'warning' })
    await adminDynamicService.remove(endpoint.value, row[primaryKey.value])
    ElMessage.success('Registro eliminado')
    await loadRows()
  } catch {
  }
}

const onPageChange = async (newPage) => {
  page.value = newPage
  await loadRows()
}

onMounted(async () => {
  await loadConfig()
  resetForm()
  await loadRows()
})
</script>

<style scoped>
.crud-card {
  width: 100%;
}

.crud-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.crud-header h3 {
  margin: 0;
}

.crud-header small {
  color: #6b7280;
}

.toolbar-row {
  margin-bottom: 12px;
}

.toolbar-actions {
  display: flex;
  justify-content: flex-end;
}

.pager {
  margin-top: 14px;
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
