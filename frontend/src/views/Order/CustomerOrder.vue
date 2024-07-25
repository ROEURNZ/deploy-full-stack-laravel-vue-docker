<template>
  <div class="container mt-4">
    <h3 class="mb-4 text-center">Payment Bank</h3>
    <div class="row">
      <!-- Left Side: Bank Information -->
      <div class="col-lg-8 mb-4 d-flex flex-column align-items-center" style="width:50%;">
        <div class="card text-center shadow-sm" style="width: 100%;">
          <div class="card-body">
            <img :src="qrimage_url(store_user.productDetails.data.pro_owner.qrimage)" alt="Bank Logo" class="img-fluid mb-3" style="width: 150px; height: 150px;">
          </div>
          <div class="card-footer d-flex justify-content-between">
            <button class="btn btn-secondary" style="width: 48%">
              <router-link @click="store_user.setUser_id(store_user.productDetails.data.pro_owner.id)" to="/chats" class="text-white">Contact</router-link>
            </button>
            <router-link to="" class="text-white" style="width: 48%;" @click="OrderProduct">
              <button class="btn btn-primary" style="width: 100%;">Order</button>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Right Side: Order Summary -->
      <div class="col-lg-4" style="width: 50%;">
        <div class="card shadow-sm">
          <div class="card-header bg-success text-white text-center">
            Order Summary
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
              <span>Product name:</span> <span>{{store_user.productDetails.data.name}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Price:</span> <span>${{store_user.productDetails.data.price}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Delivery:</span> <span>$2</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Quantity:</span> <span>{{ store_user.quantity  }}</span>
            </li>
          </ul>
          <div class="card-footer text-center">
            <strong>Total Items: <span>${{store_user.productDetails.data.price * store_user.quantity+2}}</span></strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { useUserStore } from "@/stores/user.js";
import api from "@/views/api.js";
export default {
  data() {
    return {
      store_user: useUserStore(),
      products: [
        { id: 1, name: 'Product 1', description: 'Description for product 1', image: 'https://via.placeholder.com/150' },
        { id: 2, name: 'Product 2', description: 'Description for product 2', image: 'https://via.placeholder.com/150' },
        { id: 3, name: 'Product 3', description: 'Description for product 3', image: 'https://via.placeholder.com/150' },
        // Add more products as needed
      ],
    }
  },
  computed: {
    totalItems() {
      return this.summary.reduce((total, item) => total + item.quantity, 0);
    }
  },

  methods: {
    async OrderProduct() {
      try {
        const product_id = this.store_user.productDetails.data.id;
        const quantity = this.store_user.quantity;

        const orderData = {
          product_id: product_id,
          quantity: quantity,
        };

        const headers = { Authorization: `Bearer ${this.store_user.tokenUser}` };
        const response = await api.createOrderProduct(orderData, headers);

        console.log(response.data); // Optional: handle response as needed

        // Redirect to '/order' route after successful order creation
        this.$router.push('/booking');
      } catch (error) {
        console.error('Error ordering product:', error);
        // Handle error scenario, e.g., show error message to user
      }
    },
    qrimage_url(filename){
      return api.qrimages(filename)
    },

    addToSummary(product) {
      const existingProduct = this.summary.find(item => item.id === product.id);
      if (existingProduct) {
        existingProduct.quantity += 1;
      } else {
        this.summary.push({ ...product, quantity: 1 });
      }
    }
  }
}
</script>
<style scoped>
.card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.card-img-top {
  height: 200px;
  object-fit: cover;
}
</style>
