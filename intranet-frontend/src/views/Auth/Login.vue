<template>
  <section>
    <h2>Login</h2>
    <form @submit.prevent="onLogin">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Contraseña" />
      <button type="submit">Ingresar</button>
    </form>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../../stores/authStore'

const email = ref('')
const password = ref('')
const authStore = useAuthStore()

const onLogin = async () => {
  await authStore.login({ email: email.value, password: password.value })
}
</script>