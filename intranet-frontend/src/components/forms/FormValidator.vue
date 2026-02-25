<template>
  <small v-if="message" class="error">{{ message }}</small>
</template>

<script setup>
defineProps({ message: { type: String, default: '' } })
</script>

<style scoped>
.error { color: #c00; }
</style>