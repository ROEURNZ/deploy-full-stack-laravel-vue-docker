<template>
    <div class="container">
      <button type="button" class="mb-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProduct" data-bs-whatever="@mdo">Add product</button>
      <table class="table table-white table-striped">
          <thead>
              <tr>
              <th scope="col">Id</th>
              <th scope="col">Name</th>
              <th scope="col">Quantities</th>
              <th scope="col">Prices</th>
              <th scope="col">Owners</th>
              <th scope="col" style="width: 10%;">Actions</th>
              </tr>
          </thead>
          <tbody>
              <tr v-for="(product, list) in products" :key="product.id">
              <th scope="row">{{ list + 1 }}</th>
              <td >{{ product.name }}</td>
              <td>{{ product.quantity }}</td>
              <td>${{ product.price }}</td>
              <td>{{ product.creator }}</td>
              <td class="gap-2 d-flex">
                  <!-- <button type="button" class="mb-2 btn btn-primary" data-bs-toggle="modal" :data-bs-target="'#' + product.id" data-bs-whatever="@mdo">Edit</button> -->
                  <button class="mb-2 btn btn-danger" @click="deleteProduct(product.id)">Delete</button>
              </td>
              </tr>
          </tbody>
      </table>
  
      <!-- Create Product Modal -->
      <div class="modal fade" id="createProduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">New Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form @submit.prevent="createProduct">
                          <div class="gap-2 d-flex justify-content-between">
                              <div style="width: 50%;">
                                  <label for="namePro" class="col-form-label">Name:</label>
                                  <input type="text" class="form-control" id="namePro" v-model="name">
                              </div>
                              <div style="width: 50%;">
                                  <label for="price" class="col-form-label">Price:</label>
                                  <input type="number" class="form-control" id="price" v-model="price">
                              </div>
                          </div>
                          <div class="gap-2 d-flex justify-content-between">
                              <div style="width: 50%;">
                                  <label for="quantity" class="col-form-label">Quantity:</label>
                                  <input type="number" class="form-control" id="quantity" v-model="quantity">
                              </div>
                              <div style="width: 50%;">
                                  <label for="category" class="col-form-label">Categories:</label>
                                  <select class="form-select form-select-sm" v-model="category">
                                      <option selected class="hide">Categories</option>
                                      <option v-for="cate in categories" :key="cate.id" :value="cate.id">{{ cate.category_name }}</option>
                                  </select>
                              </div>
                          </div>
                          <div>
                              <label for="description" class="col-form-label">Description:</label>
                              <textarea class="form-control" id="description" v-model="description"></textarea>
                          </div>
                          <div>
                              <label for="pro-image" class="col-form-label">Photo:</label>
                              <input type="file" class="form-control" id="pro-image" @change="handleImageUpload">
                          </div>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary" @click="createProduct">Create</button>
                  </div>
              </div>
          </div>
      </div>
  
      <!-- Edit Product Modals -->
      <div v-for="product in products" :key="product.id">
          <div class="modal fade" :id="product.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <form>
                              <div class="gap-2 d-flex justify-content-between">
                                  <div style="width: 50%;">
                                      <label for="namePro" class="col-form-label">Name:</label>
                                      <input type="text" class="form-control" id="namePro" v-model="product.name">
                                  </div>
                                  <div style="width: 50%;">
                                      <label for="price" class="col-form-label">Price:</label>
                                      <input type="number" class="form-control" id="price" v-model="product.price">
                                  </div>
                              </div>
                              <div class="gap-2 d-flex justify-content-between">
                                  <div style="width: 50%;">
                                      <label for="quantity" class="col-form-label">Quantity:</label>
                                      <input type="number" class="form-control" id="quantity" v-model="product.quantity">
                                  </div>
                                  <div style="width: 50%;">
                                      <label for="category" class="col-form-label">Category:</label>
                                      <input type="text" class="form-control" id="category" v-model="product.category">
                                  </div>
                              </div>
                              <div>
                                  <label for="description" class="col-form-label">Description:</label>
                                  <textarea class="form-control" id="description" v-model="product.description"></textarea>
                              </div>
                              <div>
                                  <label for="pro-image" class="col-form-label">Photo:</label>
                                  <input type="file" class="form-control" id="pro-image" @change="handleImageUploadEdit(product)">
                              </div>
                          </form>
                      </div>
                      {{ useUserStore }}
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" @click="updateProduct(product)">Save changes</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</template>
<script>
import api from "@/views/api";
import { useUserStore } from "@/stores/user.js";

export default {
  data() {
    return {
      name: '',
      price: '',
      quantity: '',
      category: '',
      description: '',
      image: null,
      categories: [],
      products: [],
      user_store: useUserStore()
    };
  },
  mounted() {
    this.fetchProducts();
    this.fetchProductCategories();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await api.listProduct();
        if (response.data.status) {
          this.products = response.data.data;
        } else {
          console.error("Error fetching products: ", response.data.message);
        }
      } catch (error) {
        console.error("API error: ", error);
      }
    },
    async createProduct() {
      const token = localStorage.getItem('user_token');
      const formData = new FormData();
      formData.append('name', this.name);
      formData.append('price', this.price);
      formData.append('quantity', this.quantity);
      formData.append('category_id', this.category);
      formData.append('description', this.description);
      if (this.image) {
        formData.append('image', this.image);
      }
      console.log(this.category);
      try {
        const response = await api.createProduct(formData, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'multipart/form-data'
          }
        });
        if (response.data.status) {
          this.fetchProducts();
          window.location.reload();
        } else {
          console.error("Error creating product: ", response.data.message);
        }
      } catch (error) {
        console.error("API error: ", error);
      }
    },
    handleImageUpload(event) {
      this.image = event.target.files[0];
    },
    async updateProduct(product) {
      const formData = new FormData();
      formData.append('name', product.name);
      formData.append('price', product.price);
      formData.append('quantity', product.quantity);
      formData.append('category', product.category);
      formData.append('description', product.description);
      if (product.image) {
        formData.append('image', product.image);
      }

      try {
        const response = await api.updateProduct(product.id, formData, {
          headers: {
            'Authorization': `Bearer ${this.user_store.userToken}`,
            'Content-Type': 'multipart/form-data'
          }
        });
        if (response.data.status) {
          this.fetchProducts();
          window.location.reload();
        } else {
          console.error("Error updating product: ", response.data.message);
        }
      } catch (error) {
        console.error("API error: ", error);
      }
    },
    handleImageUploadEdit(product) {
      product.image = event.target.files[0];
    },
    async deleteProduct(id) {
      try {
        const response = await api.adminDeleteProduct(id);
        if (response.data.status) {
          this.fetchProducts();
        } else {
          console.error("Error deleting product: ", response.data.message);
        }
      } catch (error) {
        console.error("API error: ", error);
      }
    },
    async fetchProductCategories() {
      try {
        const response = await api.listCategories();
        if (response.status === 200) {
          this.categories = response.data.data;
        } else {
          console.error("Error fetching categories: ", response);
        }
      } catch (error) {
        console.error("API error: ", error);
      }
    }
  }
};

</script>  