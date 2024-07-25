<template>
  <div>
    <cate_nav/>
    <div class="scrollable-container">
      <div class="row">
        <div class="col-md-3" v-for="(product, index) in products" :key="index">
          <cards_product :product="product" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cards_product from '@/Components/Card/CardProductUser.vue';
import cate_nav from '@/Components/Form/CateNav.vue';
import axios from 'axios';
import { useUserStore } from "@/stores/user.js";

export default {
  components: {
    cate_nav,
    cards_product,
  },
  data() {
    return {
      products: [],
      store_user: useUserStore(),
    };
  },
  async created() {
    try {
        const id = this.store_user.accountUser.id;  // Assuming this gives you the user ID
        const response = await axios.get(`http://127.0.0.1:8000/api/userproduct?id=${id}`);
        if (response.data.status === 200) {
          this.products = response.data.data.product;  // Adjusted to access 'product' array
          console.log('Products:', this.products.length);
          // localStorage.setItem('numproduct', this.products.length);
          this.store_user.get_num_products(this.products.length)
        } else {
          console.error('Error fetching products:', response.data.message);
        }
    } catch (error) {
        console.error('API error:', error);
    }
  }
}
</script>




<style>

</style>