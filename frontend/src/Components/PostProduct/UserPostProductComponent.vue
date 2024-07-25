<template>
  <form @submit.prevent="createProduct">
    <div class="container p-4 rounded shadow bg-light w-50">
      <div class="mb-3 text-center">
        <h3>Create your products</h3>
        <span>Start posting your product to make money here!</span>
      </div>
      <div class="mb-3 form-row d-flex" style="gap: 10px">
        <div class="form-group flex-grow-1">
          <label for="product-name" class="form-label fw-bold">Name</label>
          <input type="text" class="form-control" id="product-name" placeholder="Product name" v-model="name" required />
          <span style="color:red"> {{ errorName }}</span>
        </div>
        <div class="form-group flex-grow-1">
          <label for="product-price" class="form-label fw-bold">Price</label>
          <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" class="form-control" id="product-price" placeholder="Price" v-model="price" required />
          </div>
          <span style="color:red">{{ errorPrice }}</span>
        </div>
      </div>
      <div class="mb-3">
        <label for="product-quantity" class="form-label fw-bold">Quantity</label>
        <input type="number" class="form-control" id="product-quantity" placeholder="Quantity" v-model="quantity" required />
        <span style="color:red"> {{ errorQuantity }}</span>
      </div>
      <div class="mb-3">
        <label for="product-category" class="form-label fw-bold">Category</label>
        <select class="form-control" id="product-category" v-model="category" required>
          <option value="" hidden>Select a category</option>
          <option v-for="categories in allCate" :key="categories.id" :value="categories.id">{{ categories.category_name }}</option>
        </select>
        <span style="color:red"> {{ errorCategory }}</span>
      </div>
      <div class="mb-3">
        <label for="product-description" class="form-label fw-bold">Description</label>
        <textarea class="form-control" id="product-description" placeholder="Enter details about your product!" v-model="description" required></textarea>
        <span style="color:red"> {{ errorDescription }}</span>
      </div>
      <div class="mb-3">
        <!-- updata________________________ -->
        <label for="product-image" class="form-label fw-bold">Photo</label>
        <div class="d-flex align-items-center">
          <input type="file" class="form-control flex-grow-1" id="product-image" @change="handleFileUpload" ref="imageInput" required />
          <button v-if="num_products >= 1 || userInfo.user_qrimage !== null" type="submit" class="btn btn-dark ms-3 pulse-animation " :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Post
          </button>
          <button v-else type="button" class="btn btn-dark ms-3 pulse-animation " data-bs-toggle="modal" data-bs-target="#isProduct">
            <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Post
          </button>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="isProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Please put your QR code!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="uploadQRimage" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="file" class="form-control" aria-label="Upload" accept="image/*" @change="onFileChange" required />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </form>
</template>
<script>
import api from '../../views/api';
import axios from 'axios';
import { useUserStore } from '@/stores/user.js';

export default {
  data() {
    return {
      allCate: [],
      userqr_image: null,
      category: '', // Ensure this is correctly bound to the selected category
      name: '',
      price: null,
      quantity: null,
      description: '',
      image: null,
      loading: false,
      errorName: '',
      errorPrice: '',
      errorQuantity: '',
      errorDescription: '',
      errorCategory: '',
      store_user: useUserStore(),
      num_products: 0,
      userInfo: {},
    };
  },
  mounted() {
    this.getuser();
    this.getCateList();
  },
  methods: {
    async getCateList() {
      this.num_products = this.store_user.num_products;
      try {
        const response = await api.getAllCate();
        if (response.data.data) {
          this.allCate = response.data.data;
          console.log(this.allCate);
        } else {
          console.error('Error getting category list:', response.data.message);
        }
      } catch (error) {
        console.error('Error getting category list:', error);
      }
    },
    async getuser() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/myaccount', {
          headers: {
            Authorization: `Bearer ${this.store_user.tokenUser}`,
            'Content-Type': 'multipart/form-data'
          }
        });
        this.userInfo = response.data.user;
        console.log(this.userInfo);
      } catch (error) {
        console.error('Error getting user data: ', error);
      }
    },
    async createProduct() {
      try {
        this.loading = true;
        const formData = new FormData();
        formData.append('name', this.name);
        formData.append('price', this.price);
        formData.append('quantity', this.quantity);
        formData.append('description', this.description);
        formData.append('image', this.image);
        formData.append('category_id', this.category); // Ensure this matches the selected category

        const token = localStorage.getItem('authToken'); // Assuming you store JWT token in localStorage
        
        const response = await api.createProduct(formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            Authorization: `Bearer ${this.store_user.tokenUser}`
          }
        });
        if (response.data.status) {
          this.$router.push('/');
        } else {
          console.error('Error posting product: ', response.data.message);
        }
      } catch (error) {
        if (error.response && error.response.status === 422 || error.response && error.response.status === 403) {
          console.error('403 Forbidden:', error.response.data.message);
          this.$router.push('/plans'); // Redirect to payment page on 403 error
        } else {
          console.error('Error creating product:', error);
          // Handle general error
        }
      } finally {
        this.loading = false;
      }
    },
    handleFileUpload(event) {
      this.image = event.target.files[0];
    },
    resetForm() {
      this.name = '';
      this.price = null;
      this.quantity = null;
      this.description = '';
      this.image = null;
    },
    onFileChange(e) {
      this.userqr_image = e.target.files[0];
    },
    async uploadQRimage() {
      if (!this.userqr_image) {
        console.error('No file selected.');
        return;
      }

      const formData = new FormData();
      formData.append('user_qrimage', this.userqr_image);

      console.log(formData);
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/user/update', formData, {
          headers: {
            Authorization: `Bearer ${this.store_user.tokenUser}`,
            'Content-Type': 'multipart/form-data'
          }
        });
        window.location.href = '/product-post';
        if (response.data.status) {
          // Successfully uploaded QR image
          const modal = document.getElementById('isProduct');
          const modalInstance = bootstrap.Modal.getInstance(modal);
          modalInstance.hide();
          this.createProduct(); // Proceed with creating the product
        } else {
          console.error('Error uploading QR image: ', response.data.message);
        }
      } catch (error) {
        if (error.response && error.response.data) {
          console.error('Error:', error.response.data);
        } else {
          console.error('Error:', error.message);
        }
      }
    }
  },
  watch: {
    name(newName) {
      if (!newName) {
        this.errorName = 'Add valid product name';
      } else {
        this.errorName = null;
      }
    },
    price(newPrice) {
      if (!newPrice || newPrice <= 0) {
        this.errorPrice = 'Enter valid price';
      } else {
        this.errorPrice = null;
      }
    },
    quantity(newQuantity) {
      if (!newQuantity || newQuantity <= 0) {
        this.errorQuantity = 'Enter valid quantity';
      } else {
        this.errorQuantity = null;
      }
    },
    description(newDescription) {
      if (!newDescription) {
        this.errorDescription = 'Add valid product description';
      } else {
        this.errorDescription = null;
      }
    },
    category(newCategory) {
      if (!newCategory || newCategory <= 0) {
        this.errorCategory = 'Select valid category';
      } else {
        this.errorCategory = null;
      }
    }
  }
};
</script>
<style>

@media (max-width: 768px) {
  .container {
    width: 100% !important;
  }
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.pulse-animation {
  animation: pulse 1.5s infinite;
}
</style>
