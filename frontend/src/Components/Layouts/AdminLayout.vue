<!-- src/App.vue -->
<template>
  <div>
  <HeaderMenu />
  <div class="h-full pt-10 md:px-50">
    <div class="h-full rounded-lg">
      <slot />
    </div>
  </div>
  </div>
</template>

<script setup lang="ts">
import HeaderMenu from '@/Components/HeaderMenu.vue'
</script>
