<template>
  <div class="container h-100">
    <!-- Create Button -->
    <div class="d-flex justify-content-start mb-3">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFormModal">
        Create Own Store
      </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createFormModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="createModalLabel">Create Store</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="createStore">
              <div class="mb-3">
                <label for="StoreName" class="form-label">Store Name</label>
                <input type="text" class="form-control" id="StoreName" v-model="store.name" placeholder="Enter store name" required />
              </div>
              <div class="mb-3">
                <label for="Address" class="form-label">Address</label>
                <input type="text" class="form-control" id="Address" v-model="store.address" placeholder="Enter address" required />
              </div>
              <div class="mb-3">
                <label for="Description" class="form-label">Description</label>
                <input type="text" class="form-control" id="Description" v-model="store.description" placeholder="Enter description" required />
              </div>
              <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" @change="onFileChange" required />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Display Stores -->
    <div>
      <div v-for="store in stores" :key="store.id" class="card mb-4">
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
            <div class="mt-2">
              <router-link :to="{ name: 'editStore', params: { id: store.id } }" class="btn btn-primary me-3">Edit</router-link>
              <button class="btn btn-danger" @click="deleteStore(store.id)">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../views/api';
import { useUserStore } from '@/stores/user.js';
import axios from 'axios';


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
  
  methods: {
    async createStore() {
      this.loading = true;
      try {
        const formData = new FormData();
        formData.append('name', this.store.name);
        formData.append('address', this.store.address);
        formData.append('description', this.store.description);
        formData.append('image', this.store.image);

        const response = await api.createStore(formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            Authorization: `Bearer ${this.userStore.tokenUser}`
          }
        });

        // Add the new store to the stores array
        this.stores.push(response.data.data);
        
        this.resetForm();
        console.log('Store created successfully:', response.data);
        this.closeModal();  // Ensure this is called after the state updates
      } catch (error) {
        console.error('Error creating store:', error);
        if (error.response && error.response.status === 401) {
          console.log('Unauthorized access. Redirecting to login.');
          this.$router.push('/');
        }
      } finally {
        this.loading = false;
      }
    },
    resetForm() {
      this.store.name = '';
      this.store.address = '';
      this.store.description = '';
      this.store.image = null;
    },
    async deleteStore(storeId) {
      try {
        await api.deleteStore(storeId);
        this.stores = this.stores.filter(store => store.id !== storeId);
      } catch (error) {
        console.error('Delete error:', error);
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
      this.userStore.user_id = userId; 
    },
    closeModal() {
      const modalElement = this.$el.querySelector('#createFormModal');
      if(modalElement){
        modalElement.style.display = 'none';
        this.userStore.user_id = null;  
        
      }
    },
    async fetchStores() {
      try {
        const id = this.userStore.accountUser.id;  
        const response = await axios.get(`http://127.0.0.1:8000/api/userStore?id=${id}`);
        if (response.data.status === 200) {
          this.stores = response.data.data.owner; 
          console.log('store:', this.stores.length);
        } else {
          console.error('Error fetching store:', response.data.message);
        }
      } catch (error) {
        console.error('API error:', error);
      }
    }
  },
  mounted() {
    this.userStore.loadUser();
    this.fetchStores();
  }
};
</script>
