
<template>
    <div class="container mt-4">
        <div class="scrollable-container">
            <div class="row">
                <create_Store></create_Store>
            </div>
      </div>
    </div>
  </template>
  <script>
  import cards_product from '@/Components/Card/CardProductUser.vue';
  import create_Store from '../../views/PageStore/CreateStore.vue';
  import { useUserStore } from "@/stores/user.js";
  export default {
    components: {
      create_Store,
      cards_product,
    },
    data() {
      return {
        products: [],
        store_user: useUserStore(),
      };
    },
    
  }
  </script>

  
  <style>
  .zoom-container {
  position: relative;
  overflow: hidden;
}

.zoom-image {
  transition: transform 0.5s ease;
}

.zoom-container:hover .zoom-image {
  transform: scale(3); /* Adjust the scale as needed */
}
  </style>