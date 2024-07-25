<template>
    <div class="container h-100">
      <!-- Search Bar -->
      <div class="d-flex justify-content-center m-3">
        <div class="search">
          <input class="search_input" type="text" v-model="searchQuery" placeholder="Search here..." @input="performSearch" />
          <a href="#" class="search_icon"><i class="fa fa-search"></i></a>
        </div>
      </div>
      <div class="text-center">
        <p><strong>Welcome to all everyone🙌🤑</strong> to finde all of each store</p>
        <p>It has more of specific product that you to <strong>view and Order</strong> </p>
      </div>
      <!-- Display Stores -->
 
        <div v-for="store in filteredStores" :key="store.id" class="card mb-4">
          <div class="card-body d-flex m-4">
            <div class="d-flex justify-content-center align-items-center">
              <router-link :to="{ name: 'CollectUserStore', params: { id: store.id } }">
                <img :src="imageStore(store.image)" @click="captureUserId(store.created_by)" alt="Store Image" style="width: 200px; height: 200px" />
              </router-link>
            </div>
            <div class="text1 m-4">
              <h2>Store name: {{ store.name }}</h2>
              <h5>Address: {{ store.address }}</h5>
              <p>{{ store.description }}</p>
            </div>
          </div>
        </div>
      </div>
  </template>
  
<script>
  import api from '../../views/api';
  import { useUserStore } from '@/stores/user.js';

  export default {
    data() {
      return {
        store: {
          name: '',
          address: '',
          description: '',
          image: null
        },
        userStore: useUserStore(),
        stores: [],
        loading: false,
        searchQuery: '',
        userHasStore: false
      };
    },
    computed: {
      userStores() {
        return this.stores.filter(store => store.created_by === this.userStore.user_id);
      },
      filteredStores() {
        return this.stores.filter(store => {
          const query = this.searchQuery.toLowerCase();
          return (
            store.name.toLowerCase().includes(query) ||
            store.address.toLowerCase().includes(query) ||
            store.description.toLowerCase().includes(query)
          );
        });
      }
    },
    methods: {
      async fetchStores() {
        try {
          const response = await api.getStores();
          this.stores = response.data.data;
          console.log(this.stores);
          this.userHasStore = this.userStores.length > 0;
        } catch (error) {
          console.error('Error fetching stores:', error);
        }
      },
      onFileChange(event) {
        this.store.image = event.target.files[0];
      },
      imageStore(filename) {
        return api.imageUrlStore(filename);
      },
      captureUserId(userId) {
        console.log('Store created by user ID:', userId);
      },
      closeModal() {
        const modal = document.getElementById('createFormModal');
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
      },
      performSearch() {
        // This will trigger the computed property to update
      }
    },
    async created() {
      await this.fetchStores();
    }
  };

</script>
  
  <style>
  .search {
    height: 50px;
    background-color: #fff;
    border-radius: 40px;
    padding: 10px;
    border: 1px solid #000;
  }
  .search_input {
    color: black;
    border: 0;
    outline: none;
    background: none;
    border: white;
    width: 500px;
    caret-color: black;
  }
  .search:hover > .search_icon {
    color: #fff;
  }
  .search_icon {
    margin-top: -4px;
    height: 35px;
    width: 40px;
    float: right;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    color: white;
    background-color: black;
  }
  a:link {
    text-decoration: none;
  }
  </style>
  