<template>
  <form @submit.prevent="$emit('submit')">
    <slot />
  </form>
</template>