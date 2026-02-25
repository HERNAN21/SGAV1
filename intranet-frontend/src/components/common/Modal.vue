<template>
  <div class="modal" v-if="open">
    <div class="panel">
      <slot />
      <button @click="$emit('close')">Cerrar</button>
    </div>
  </div>
</template>

<script setup>
defineProps({ open: { type: Boolean, default: false } })
</script>

<style scoped>
.modal { position: fixed; inset: 0; background: rgba(0,0,0,.3); display: grid; place-items: center; }
.panel { background: #fff; padding: 16px; }
</style>