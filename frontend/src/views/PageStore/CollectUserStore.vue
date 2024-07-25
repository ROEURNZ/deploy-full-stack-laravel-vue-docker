<template>
    <div class="container mt-5">
      <div v-if="storeData ">
        <div class="leftSide" style="width: 48%;">
          <div class="d-flex align-items-center" style="height: 60px;">
            <img v-if="storeData.image_url" :src="profileUrl(storeData.image_url)" alt="User Image" class="text-dark m-3 nav-link profile-img">
            <img v-else :src="ownerProfileName(storeData.store.user.name)" alt="User Image" class="text-dark m-3 nav-link profile-img">
            <h6 class="mb-0">Owner: {{ storeData.store.user.name }} - {{ storeData.store.name }}</h6>
          </div>
        </div>
      </div>
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
  import cards_product from '@/Components/Card/CardComponent.vue';
  import api from '../../views/api';
  import axios from 'axios';
  import { useUserStore } from "@/stores/user.js";
  
  export default {
    props: ['id'],
    components: {
      cards_product,
    },
    data() {
      return {
        products: [],
        storeData: null,
        store_user: useUserStore(),
      };
    },
    async created() {
      await this.fetchProducts();
      await this.fetchUserStore();
    },
    methods: {
      async fetchProducts() {
        try {
          const id = this.store_user.accountUser.id;  
          const response = await axios.get(`http://127.0.0.1:8000/api/userproduct?id=${id}`);
          if (response.data.status === 200) {
            this.products = response.data.data.product;
            console.log('Products:', this.products.length);
            this.store_user.get_num_products(this.products.length);
          } else {
            console.error('Error fetching products:', response.data.message);
          }
        } catch (error) {
          console.error('API error:', error);
        }
      },
      async fetchUserStore() {
        try {
          const storeId = this.$route.params.id;
          console.log(storeId);
          const response = await api.getStore(storeId);
          this.storeData = response.data.data;
          
          console.log('Store Data:', this.storeData);
        } catch (error) {
          console.error('Error fetching store details:', error);
        }
      },
      profileUrl(filename) {
        console.log('Profile URL:', this.profileUrl)
        return api.profile(filename);
      },
      ownerProfileName(username) {
        if (username) {
          const initials = `${username[0]}${username[username.length - 1]}`.toUpperCase();
          return `https://dummyimage.com/100x100/000/fff&text=${initials}`;
        }
        return '../../assets/images/Male User.png';
      }
    }
  };
  </script>
  
  <style>
  /* Add your component-specific styles here */
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
  