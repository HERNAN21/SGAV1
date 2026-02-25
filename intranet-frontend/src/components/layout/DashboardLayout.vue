<template>
  <div>
    <Navbar />
    <div class="content">
      <Sidebar>
        <slot name="sidebar" />
      </Sidebar>
      <main class="main">
        <slot />
      </main>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import Footer from './Footer.vue'
</script>

<style scoped>
.content { display: flex; }
.main { flex: 1; padding: 16px; }
</style>