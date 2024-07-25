<script setup lang="ts">
import WebHeaderMenu from '@/Components/WebHeaderMenu.vue'
</script>
<template>
  <div>
    <WebHeaderMenu />
    <div class="h-full md:px-50">
      <div class="h-full">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

</script>
