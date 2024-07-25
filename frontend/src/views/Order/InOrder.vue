<template>
  <div class="container mt-4">
    <div class="row d-flex justify-content-between">
      <div class="col-lg-8 mb-4" style="width: 75%;">
        <template v-if="groupedOrders">
          <h3>Current orders</h3>
          <div v-for="(group, ownerName) in groupedOrders" :key="ownerName">
            <div class="rounded mb-4" style="width: 100%;">
              <strong class="">Delivery: $2</strong>
              <div v-for="(order) in group" :key="order.id" class="">
                <div v-if="order.status !== 2" class="bg-light rounded shadow mb-2 shadow-sm">
                  <div class="card-header d-flex justify-content-between">
                    <div class="product d-flex gap-3">
                      <img :src="productImage(order.products[0].image)" alt="" style="width: 100px; height: 100px;">
                      <div class="product_title">
                        <div>
                          <router-link class="d-flex align-items-center text-dark" to="/chats" @click="store_user.setUser_id(order.products[0].owner.id)">
                            <img v-if="order.products[0].owner.image" :src="profile_url(order.products[0].owner.image)" alt="User Image" class="text-dark profile-img">
                            <img v-else :src="ownerprofileName(order.products[0].owner.name)" alt="User Image" class="text-dark profile-img">
                            <p class="mb-0 p-1">{{ order.products[0].owner.name }}</p>
                          </router-link>
                        </div>
                        <p class="mb-0 p-1">{{ order.products[0].name }}</p>
                        <p class="mb-0 p-1">Quantity: <strong>{{ order.quantity }}</strong></p>
                      </div>
                    </div>
                    <div class="d-flex align-items-center" style="height: 100px;">
                      <button v-if="order.status == 1" class="btn btn-dark" @click="status">Delivery</button>
                      <button v-if="order.status == 0" class="btn btn-dark" @click="cancelOrderProduct(order.id)">Cancel</button>
                    </div>
                  </div>
                  <div class="card-header d-flex justify-content-between">
                    <div class="d-flex flex-column align-items-start"><span>Order by</span> <strong>{{ store_user.accountUser.name }}</strong></div>
                    <div class="d-flex flex-column align-items-start"><span>Order date:</span> <strong>{{ order.created_at }}</strong></div>
                    <div class="d-flex flex-column align-items-start"><span>Payment status</span><strong>${{ order.products[0].price * order.quantity }}</strong></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
        
        <h3>Past orders</h3>
        <div v-for="order in currentOrder" :key="order.id" class="bg-light rounded shadow mb-4 shadow-sm">
          <div v-if="order.status == 2">
            <div class="card-header d-flex justify-content-between">
              <div class="product d-flex gap-3">
                <img :src="productImage(order.products[0].image)" alt="" style="width: 100px; height: 100px;">
                <div class="product_title">
                  <div class="d-flex align-items-center">
                    <img v-if="order.products[0].owner.image" :src="profile_url(order.products[0].owner.image)" alt="User Image" class="text-dark profile-img">
                    <img v-else :src="ownerprofileName(order.products[0].owner.name)" alt="User Image" class="text-dark profile-img">
                    <p class="mb-0 p-1">{{ order.products[0].owner.name }}</p>
                  </div>
                  <p class="mb-0 p-1">{{ order.products[0].name }}</p>
                  <p class="mb-0 p-1">Quantity: <strong>{{ order.quantity }}</strong></p>
                </div>
              </div>
              <div class="d-flex align-items-center" style="height: 100px;">
                <a href="#" class="btn btn-secondary" @click.prevent="toggleDetails(order)">See order</a>
              </div>
            </div>
            <div v-if="order.showDetails" class="card-header d-flex justify-content-between">
              <div class="d-flex flex-column align-items-start"><span>Order by</span> <strong>{{ store_user.accountUser.name }}</strong></div>
              <div class="d-flex flex-column align-items-start"><span>Order date:</span> <strong>{{ order.created_at }}</strong></div>
              <div class="d-flex flex-column align-items-start"><span>Payment status</span><strong>${{ order.products[0].price * order.quantity }} Done</strong></div>
              <div class="d-flex flex-column align-items-start"><span>Delivery</span><strong>$2</strong></div>
            </div>
          </div>
        </div>
        <div v-for="order in pastOrder" :key="order.id" class="card mb-4 shadow-sm">
          <div v-if="order.status == 2">
            <div class="card-header d-flex justify-content-between">
              <div class="product d-flex gap-3">
                <img :src="productImage(order.products[0].image)" alt="" style="width: 100px; height: 100px;">
                <div class="product_title">
                  <div class="d-flex align-items-center">
                    <img v-if="order.products[0].owner.image" :src="profile_url(order.products[0].owner.image)" alt="User Image" class="text-dark profile-img">
                    <img v-else :src="ownerprofileName(order.products[0].owner.name)" alt="User Image" class="text-dark profile-img">
                    <p class="mb-0 p-1">{{ order.products[0].owner.name }}</p>
                  </div>
                  <p class="mb-0 p-1">{{ order.products[0].name }}</p>
                  <p class="mb-0 p-1">Quantity: <strong>{{ order.quantity }}</strong></p>
                </div>
              </div>
              <div class="d-flex align-items-center" style="height: 100px;">
                <a href="#" @click.prevent="toggleDetails(order)">See order</a>
              </div>
            </div>
            <div v-if="order.showDetails" class="card-header d-flex justify-content-between">
              <div class="d-flex flex-column align-items-start"><span>Order by</span> <strong>{{ store_user.accountUser.name }}</strong></div>
              <div class="d-flex flex-column align-items-start"><span>Order date:</span> <strong>{{ order.created_at }}</strong></div>
              <div class="d-flex flex-column align-items-start"><span>Payment status</span><strong>${{ order.products[0].price * order.quantity }} Done</strong></div>
              <div class="d-flex flex-column align-items-start"><span>Delivery</span><strong>$2</strong></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4" style="width: 25%;">
        <div class="d-flex flex-column-reverse" style="height: calc(2 * (250px + 1rem)); overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(0, 0, 0, 0.3) rgba(0, 0, 0, 0.1);">
          <div class="scrollable-container">
            <div>
              <div v-for="(product, index) in products" :key="index">
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
import cards_product from '@/Components/Card/CardComponent.vue';

export default {
  components: {
    cards_product
  },
  data() {
    return {
      store_user: useUserStore(),
      products: [],
      currentOrder: [],
      pastOrder: []
    };
  },
  async created() {
    try {
      const response = await api.listProduct();
      if (response.data.status) {
        this.products = response.data.data;
      } else {
        console.error('Error fetching products: ', response.data.message);
      }
    } catch (error) {
      console.error('API error: ', error);
    }
  },
  mounted() {
    this.fetchCurrentOrderWithDelay();
    this.interval = setInterval(this.fetchCurrentOrderWithDelay, 1000); // Fetch orders every 10 seconds
  },
  beforeUnmount() {
    clearInterval(this.interval);
  },
  computed: {
    groupedOrders() {
      return this.currentOrder.reduce((acc, order) => {
        const ownerName = order.products[0].owner.name;
        if (!acc[ownerName]) {
          acc[ownerName] = [];
        }
        acc[ownerName].push(order);
        return acc;
      }, {});
    }
  },
  methods: {
    fetchCurrentOrderWithDelay() {
      setTimeout(() => {
        this.fetchCurrentOrder();
      }, 1000);
    },
    async fetchCurrentOrder() {
      try {
        const headers = { Authorization: `Bearer ${this.store_user.tokenUser}` };
        const response = await api.listOrderProducts(headers);
        this.currentOrder = response.data.orderProducts || [];
        console.log(this.currentOrder);
      } catch (error) {
        console.error('Error fetching current orders:', error);
      }
    },
    async cancelOrderProduct(orderId) {
      try {
        const headers = { Authorization: `Bearer ${this.store_user.tokenUser}` };
        const confirmed = confirm('Are you sure you want to cancel this order?');
        if (confirmed) {
          await api.deleteOrderProduct(orderId, headers);
          this.fetchCurrentOrderWithDelay();
        }
      } catch (error) {
        console.error('Error deleting order product:', error);
      }
    },
    status() {
      alert('Your order has been successfully in delivery, please wait for the order. Thanks!');
    },
    productImage(filename) {
      return api.imageUrlProduct(filename);
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
    toggleDetails(order) {
      order.showDetails = !order.showDetails;
    }
  }
};
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
