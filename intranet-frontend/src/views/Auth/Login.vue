<template>
  <div class="login-page">
    <el-card class="login-card" shadow="hover">
      <template #header>
        <div class="login-title">Intranet Académica</div>
      </template>

      <el-form @submit.prevent="onLogin" label-position="top" class="login-form">
        <el-form-item label="Correo">
          <el-input v-model="email" type="email" placeholder="admin@uninp.edu.pe" autocomplete="username" />
        </el-form-item>

        <el-form-item label="Contraseña">
          <el-input v-model="password" type="password" placeholder="******" show-password autocomplete="current-password" />
        </el-form-item>

        <el-button type="primary" :loading="submitting" native-type="submit" class="login-btn">
          Ingresar
        </el-button>
      </el-form>
    </el-card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { ElMessage } from 'element-plus'
import { useAuthStore } from '../../stores/authStore'

const email = ref('')
const password = ref('')
const submitting = ref(false)

const router = useRouter()
const authStore = useAuthStore()

const onLogin = async () => {
  try {
    submitting.value = true
    await authStore.login({ email: email.value, password: password.value })

    await router.push('/dashboard')
    ElMessage.success('Sesión iniciada correctamente')
  } catch (error) {
    const message = error?.response?.data?.message || 'No se pudo iniciar sesión'
    ElMessage.error(message)
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 16px;
}

.login-card {
  width: 100%;
  max-width: 420px;
}

.login-title {
  font-size: 1.1rem;
  font-weight: 700;
}

.login-btn {
  width: 100%;
}
</style>