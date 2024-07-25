<template>
    <div class="container mt-4">
      <div class="row">
        <h3>Selling!</h3>
        <div class="mb-4 col-lg-8" style="width: 75%;">
          <!-- <div v-for="sell in currentSeller" :key="sell.id" class="mb-4 shadow-sm card"> -->
            <table class="table table-white table-striped">
                <thead>
                    <tr>
                    <th scope="col">Products</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Quantities</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody v-for="(sell) in currentSeller" :key="sell.id" >
                    <tr v-if="sell.status !== 2">
                        <td>{{sell.products[0].name}}</td>
                        <td>{{sell.buyer.name}}</td>
                        <td>{{ sell.quantity }}</td>
                        <td class="">
                            <button v-if="sell.status == 0" class="btn btn-success" @click="updateStatus(sell.id, 1)">Confirm</button>
                            <button v-else class="btn btn-primary" @click="updateStatus(sell.id, 2)">Finish</button>
                        </td>
                    </tr>
                </tbody>
              </table>
            <h3>Sold out!</h3>
            <!-- <div v-for="sell in currentSeller" :key="sell.id" class="mb-4 shadow-sm card"> -->
            <table class="table table-white table-striped">
                <thead>
                    <tr>
                    <th scope="col">Products</th>
                    <th scope="col">User Name</th>
                    <th scope="col">Quantities</th>
                    <th scope="col">Totals</th>
                    </tr>
                </thead>
                <tbody v-for="(sell) in currentSeller" :key="sell.id">
                  <tr v-if="sell.status == 2">
                    <td>{{ sell.products[0].name }}</td>
                    <td>{{ sell.buyer.name }}</td>
                    <td>{{ sell.quantity }}</td>
                    <td>${{ sell.products[0].price * sell.quantity }}</td>
                  </tr>
                </tbody>
            </table>
        </div>
  
        <div class="col-lg-4" style="width: 25%;">
            <div class="d-flex flex-column-reverse" style="height: calc(2 * (250px + 1rem)); overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(0, 0, 0, 0.3) rgba(0, 0, 0, 0.1);">
              <div class="scrollable-container">
                <div class="">
                  <div class="" v-for="(product, index) in products" :key="index">
                    <cards_product :searchQuery="searchQuery" :product="product" />
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { useUserStore } from '@/stores/user.js';
  import api from "@/views/api.js";
  import cards_product from '@/Components/Card/CardComponent.vue'
  
  export default {
    components:{
      cards_product
    },
    data() {
      return {
        store_user: useUserStore(),
        products: [
          { id: 1, name: 'Product 1', description: 'Description for product 1', image: 'https://via.placeholder.com/150' },
          { id: 2, name: 'Product 2', description: 'Description for product 2', image: 'https://via.placeholder.com/150' },
          { id: 3, name: 'Product 3', description: 'Description for product 3', image: 'https://via.placeholder.com/150' },
          // Add more products as needed
        ],
        currentSeller: null,
        pastOrder: []
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
    
    mounted() {
      setTimeout(() => {
        this.fetchCurrentOrder();
        setInterval(this.fetchCurrentOrder, 5000);
      }, 1000);
      // this.fetchPastOrder();
    },
    methods: {
      async fetchCurrentOrder(){
        try {
          const headers = { Authorization: `Bearer ${this.store_user.tokenUser}` };
          const response = await api.listSellerProducts(headers);
  
          this.currentSeller = response.data.orderProducts; // Assuming response has currentOrders key
          console.log(this.currentOrder);
        } catch (error) {
          console.error('Error fetching current orders:', error);
        }
      },

      async updateStatus(orderId, status) {
          const token = this.store_user.tokenUser; // Replace with the actual token

          try {
            const response = await api.updateOrderStatus(orderId, status, token);
            this.fetchCurrentOrder();
            console.log('Order status updated successfully', response);
          } catch (error) {
            console.error('Error updating order status', error);
          }
      },
  
      productImage(filename){
        return api.imageUrlProduct(filename)
      },
      ownerprofileName(username) {
        if (username) {
          const initials = `${username[0]}${username[username.length - 1]}`.toUpperCase();
          return `https://dummyimage.com/100x100/000/fff&text=${initials}`;
        }
        return '../../assets/images/Male User.png';
      },
      profile_url(filename) {
        return api.profile(filename);
      },
      
    },
    
  }
  </script>
  
  <style scoped>
  .card {
    width: 100%;
  }
  .profile-img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 5px;
  }

   /* Modern scrollbar styles */
   .d-flex {
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.3) rgba(0, 0, 0, 0.1);
  }

  .d-flex::-webkit-scrollbar {
    width: 6px;
  }

  .d-flex::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
  }

  .d-flex::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    border: 3px solid rgba(0, 0, 0, 0);
  }

  .d-flex::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.5);
  }
  </style>
  