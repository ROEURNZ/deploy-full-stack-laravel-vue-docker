<template>
  <div class="container mt-5 row justify-content-center">
    <div class="p-3 rounded shadow bg-light w-50">
      <div class="card-body">
        <h4 class="mb-2 text-center card-title">Update Store</h4>
        <form @submit.prevent="updateStore">
          <div class="form-row d-flex" style="gap: 10px">
            <div class="mb-3 col">
              <label for="StoreName" class="form-label">Store Name</label>
              <input type="text" class="form-control" id="StoreName" v-model="store.name" placeholder="Enter store name"
                required />
            </div>
          </div>
          <div class="mb-3">
            <label for="Address" class="form-label">Address</label>
            <input type="text" class="form-control" id="Address" v-model="store.address" placeholder="Enter address"
              required />
          </div>
          <div class="mb-3">
            <label for="Description" class="form-label">Description</label>
            <input type="text" class="form-control" id="Description" v-model="store.description"
              placeholder="Enter description" required />
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" @change="onFileChange" />
          </div>
          <div class="mb-3 d-flex justify-content-between">
            <router-link to="/userStore">
              <button type="button" class="btn btn-primary" style="width: 120px;">
                Back
              </button>
            </router-link>
            <button type="submit" class="btn btn-dark" style="width: 120px;">
              Save
            </button>
          </div>
        </form>
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
    };
  },
  methods: {
    async fetchStore() {
      const storeId = this.$route.params.id;
      try {
        const response = await api.getStore(storeId);
        this.store = response.data.data;
      } catch (error) {
        console.error('API error:', error);
      }
    },
    async updateStore() {
      try {
        const formData = {
          name: this.store.name,
          address: this.store.address,
          description: this.store.description
        }
        console.log(formData);
        console.log(this.userStore.tokenUser);

        const storeId = this.$route.params.id;
        const response = await api.updateStore(storeId, formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            Authorization: `Bearer ${this.userStore.tokenUser}`
          }
        });
        console.log('Store updated successfully:', response.data);
        this.$router.push('/userStore');
      } catch (error) {
        console.error('Error updating store:', error.response?.data || error.message);
      }
    },
    onFileChange(event) {
      this.store.image = event.target.files[0];
    },
  },
  async created() {
    await this.fetchStore();
  }
};
</script>

<style scoped>
/* Add your styles here */
</style>
