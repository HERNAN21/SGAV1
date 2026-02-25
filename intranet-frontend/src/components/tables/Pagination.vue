<template>
  <div class="pagination">
    <button @click="$emit('prev')">Anterior</button>
    <button @click="$emit('next')">Siguiente</button>
  </div>
</template>