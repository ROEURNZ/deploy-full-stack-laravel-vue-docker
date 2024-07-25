<template>
  <div class="">
    <search_product @search="updateSearchQuery" />
    <div class="scrollable-container">
        <div class="row">
          <div class="col-md-3 mt-3" v-for="(product, index) in filteredCategories" :key="index">
            <cards_product  :product="product" />
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import { useUserStore } from '../../stores/user'
import search_product from '@/Components/Search/SearchProduct.vue'
import api from '@/views/api'
import cards_product from '@/Components/Card/CardComponent.vue'
export default {
    props: ['id'],
    components: {
        search_product,
        cards_product
    },
    data(){
        return{
            cateId: useUserStore(),
            products: null,
            searchQuery: ''
        }
    },
    mounted(){
        this.getProductCategory();
    },
    watch:{
        id: {
            immediate: true,
            handler(newId) {
                this.getProductCategory(newId);
            }
        }
    },
    methods: {
        async getProductCategory(){
            const cate_id = this.id;
            try {
                const res = await api.productCategory(cate_id);
                if (res.status){
                    this.products = res.data.data;
                    if (this.products == null){
                        alert("No products found for product category here!"); 
                    }
                }
            } catch (error) {
                console.error(error);
            }
        },
        updateSearchQuery(query) {
            this.searchQuery = query;
            console.log("Updated search query:", this.searchQuery);
        }
    },
    computed: {
        filteredCategories() {
            if (!this.products) return [];
            return this.products.filter(product => product.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },
    }
}
</script>

<style>

</style>