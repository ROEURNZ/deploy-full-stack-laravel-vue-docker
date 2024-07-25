<template>
  <div class="container py-4 rounded">
    <div class="mb-4 shadow-sm card">
      <div class="text-center card-body">
        <div class="mb-4 d-flex justify-content-center">
          <img :src="imageProduct(product.image)" class="rounded img-fluid" style="width: 180px; height: 180px; object-fit: cover;" />
        </div>
        <h5 class="card-title">{{ product.name }}</h5>
        <p class="card-text text-muted">${{ product.price }}</p>
        <!-- <p class="card-text">{{ product.description }}</p> -->
        <div class="d-flex justify-content-center">
          <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" :data-bs-target="`#`+product.id" data-bs-whatever="@mdo" @click="update_product(product)">Edit</button>
          <button @click="confirmDelete" class="btn btn-outline-danger">Delete</button>
        </div>
      </div>
    </div>
    <!-- pop up form -->
    <div class="modal fade" :id="product.id" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabel">Edit Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form >
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="product-name" class="form-label fw-bold">Name</label>
                  <input type="text" class="form-control" id="product-name" placeholder="Product name" v-model="name">
                </div>
                <div class="col-md-6">
                  <label for="product-price" class="form-label fw-bold">Price</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" id="product-price" placeholder="Price" v-model="price">
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="product-quantity" class="form-label fw-bold">Quantity</label>
                  <input type="number" class="form-control" id="product-quantity" placeholder="Quantity" v-model="quantity">
                </div>
                <div class="col-md-6">
                  <label for="product-category" class="form-label fw-bold">Category</label>
                  <select class="form-control" id="product-category" v-model="category">
                    <option value="" hidden>Select a category</option>
                    <option v-for="categories in allCate" :key="categories.id" :value="categories.id">{{ categories.category_name }}</option>
                  </select>
                </div>
                <div class="col-12">
                  <label for="product-description" class="form-label fw-bold">Description</label>
                  <textarea class="form-control" id="product-description" placeholder="Enter details about your product!" v-model="description"></textarea>
                </div>
                <div class="col-12">
                  <label for="product-image" class="form-label fw-bold">Product Image</label>
                  <input type="file" class="form-control flex-grow-1" id="product-image" @change="handleFileUpload" ref="imageInput"/>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button @click="updateProduct" type="button" class="btn btn-primary">Save Edit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../views/api';
import { useUserStore } from '@/stores/user.js';

export default {
  name: 'CardComponent',
  props: ['product'],
  data() {
    return {
      userStore: useUserStore(),
      name: '',
      price: '',
      quantity: '',
      category: '',
      description: '',
      image:null,
      allCate: [],
    };
  },
  mounted() {
    this.getCateList();
  },
  methods: {
    imageProduct(filename) {
      return api.imageUrlProduct(filename);
    },
    confirmDelete() {
      if (confirm('Are you sure you want to delete this product?')) {
        this.deleteProduct(this.product.id);
      }
    },
    async deleteProduct(id) {
      try {
        const response = await api.deleteProduct(id, {
          headers: { Authorization: `Bearer ${this.userStore.tokenUser}` }
        });
        console.log(response);
        console.log(id);
        window.location.href ='/userprodcuts'
        if (response.status === 200) {
          this.$emit('productDeleted', id);
        } else {
          alert('Failed to delete the product');
        }
      } catch (error) {
        console.error('Error deleting product', error);
      }
    },
    handleFileUpload(event) {
      this.image = event.target.files[0];
      console.log('selected image', this.image);
    },
    async getCateList() {
      this.num_products = this.store_user;
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
    // get product
    update_product(product) {
      this.id = product.id;
      this.name = product.name;
      this.price = product.price;
      this.quantity = product.quantity;
      this.category = product.category;
      this.description = product.description;
      // console.log(this.name, this.price, this.quantity, this.description)
    },

    async updateProduct() {
      try {
        const id = this.id;
        const data = new FormData();
        
        // Append other fields to the FormData object
        data.append('name', this.name);
        data.append('price', this.price);
        data.append('quantity', this.quantity);
        data.append('category_id', this.category);
        data.append('description', this.description);
        
        // Append the image file if it exists
        if (this.image && this.image.name) {
          data.append('image', this.image);
        }
        
        console.log(...data); // Log the FormData content

        const response = await api.updateProduct(id, data, this.userStore.tokenUser);
        
        console.log(response);
        if (response.status === 200) {
          this.$emit('productUpdated', id); // Emit the productUpdated event
          window.location.reload() // Redirect after success
        } else {
          alert('Failed to update the product');
        }
      } catch (error) {
        console.error('Error updating product', error);
      }
    }
  }
};
</script>

<style>
</style>
