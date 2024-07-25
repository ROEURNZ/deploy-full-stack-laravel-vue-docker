<template>
  <div>
    <!-- <product_slide/> -->
    <search_product @search="handleSearch"/>
    <div class="scrollable-container">
      <div class="row">
        <div class="col-md-3" v-for="(product, index) in filteredProducts" :key="index">
          <cards_product :searchQuery="searchQuery" :product="product" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import cards_product from '@/Components/Card/CardComponent.vue'
import product_slide from '@/Components/Card/ProductSlide.vue'
import search_product from '@/Components/Search/SearchProduct.vue'
import api from '@/views/api';
import { useUserStore } from '@/stores/user.js';

export default {
  components: {
    cards_product,
    product_slide,
    search_product,
  },
  setup() {
    const store_user = useUserStore();
    store_user.loadUser();
    return {
      store_user,
    }
  },
  data() {
    return {
      searchQuery: '',
      products: [],
    }
  },
  computed: {
    filteredProducts() {
      if (!this.searchQuery) {
        return this.products;
      }
      return this.products.filter(product =>
        product.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    removeProduct() {
      this.products = this.products.filter(product => product.id!== product.id)
    }
  },
  async created() {
    try {
      const response = await api.listProduct()
      if (response.data.status) {
        this.products = response.data.data
      } else {
        console.error('Error fetching products: ', response.data.message)
      }
    } catch (error) {
      console.error('API error: ', error)
    }
  },
  methods: {
    handleSearch(query) {
      this.searchQuery = query;
    }
  }
}
</script>

<style>
/* Add any custom styles here */

</style>
