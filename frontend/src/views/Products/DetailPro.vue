
<template>
  <div class="container mt-5">
    <div v-if="productDetails">
      <div class="leftSide" style="width: 48%;">
        <router-link to="/chats">
          <div class="d-flex align-items-center" @click="store_user.setUser_id(productDetails.data.pro_owner.id)" style="height: 60px;">
            <img v-if="productDetails.data.pro_owner.profile" :src="profile_url(productDetails.data.pro_owner.profile)" alt="User Image" class="text-dark m-3 nav-link profile-img">
            <img v-else :src="ownerprofileName(productDetails.data.pro_owner.name)" alt="User Image" class="text-dark m-3 nav-link profile-img">
            <p class="mb-0">{{ productDetails.data.pro_owner.name }} - Owner: {{ productDetails.data.name }}</p>
          </div>
        </router-link>
      </div>
      <div class="gap-5 p-3 rounded shadow rightSide d-flex align-items-center bg-light">
        <div class="proImageSlide">
          <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" style="height: 600px;">
              <div class="carousel-item active zoom-container" @mousemove="handleMouseMove">
                <img :src="product_img_url(productDetails.data.image)" class="d-block w-100 zoom-image" style="height: 100%;" alt="Product Image">
              </div>
            </div>
          </div>
        </div>
        <form class="p-5" style="width: 60%;">
          <!-- Your existing form content -->
          <div class="title">
            <h1>{{ productDetails.data.name }}</h1>
            <h3>${{ productDetails.data.price }}</h3>
          </div>
          <div class="description">
            <h2>DESCRIPTION</h2>
            <p>{{ productDetails.data.description }}</p>
          </div>
          <div class="detail">
            <h2>DETAIL</h2>
            <ul>
              <li class="d-flex">
                <p>Color:</p>
                <p>Black</p>
              </li>
              <li class="d-flex">
                <p>Size:</p>
                <p>S</p>
              </li>
              <li class="d-flex">
                <p>Material:</p>
                <p>Cotton</p>
              </li>
            </ul>
          </div>
          <div class="quantity">
            <h2>QUANTITY</h2>
            <input type="number" v-model="form.quantity" class="p-4">
          </div>
          <div class="gap-2 mt-5 action d-flex justify-content-between">
            <router-link v-if="!store_user.accountUser" to="/login" class="mr-0 nav-link custom-font-size">
              <button class="btn btn-success" style="width: 270px; height: 52px;">Add to cart!</button>
            </router-link>
            <button v-else @click="addToCart(productDetails.data)" class="btn btn-success" style="width: 270px; height: 52px;">Add to cart!</button>
            <router-link v-if="!store_user.accountUser" to="/login" class="mr-0 nav-link custom-font-size text-danger">
              <button class="btn btn-primary" style="width: 270px; height: 52px;">Buy now!</button>
            </router-link>
            <router-link v-else to="" class="mr-0 nav-link custom-font-size text-danger">
              <button class="btn btn-primary" style="width: 270px; height: 52px;" @click="buyNow()">Buy now!</button>
            </router-link>
          </div>
        </form>
      </div>
    </div>
    <!-- {{ productDetails }} -->
    <p class="mt-5 p- border-bottom" style="width: 100%;">Ratings and Feedback for this product!</p>
    <div class="RateAndFeedback d-flex">
      <div class="feedback" style="width: 70%;">
        <div v-if="productDetails">
          <rate_show :product_id="id" :rangOfrating="productDetails.data.ratting"/>
        </div>
        <div v-if="productDetails" class="feedback">
          <div v-for="comment in productDetails.data.comments" :key="comment.id" class="">
            <comment :comment="comment" />
          </div>
        </div>
      </div>
      <div class="cardPro border-start" style="width: 30%">
        <div class="mt-3 product-container">
          <div v-for="(product, index) in products" :key="index">
            <cards_product :product="product" style="width: 90%" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import api from "@/views/api.js";
import { useUserStore } from "@/stores/user.js";
import rate_show from "@/Components/Card/RateProShow.vue";
import comment from "@/Components/Card/CommentPro.vue";
import cards_product from '@/Components/Card/CardComponent.vue';

export default {
  props: ['id'],
  components: {
    rate_show,
    comment,
    cards_product,
  },
  data() {
    return {
      productDetails: null,
      quantity: 1,
      products: [],
      store_user: useUserStore(),
      form:{
        quantity: 1,
      }
    };
  },
  async mounted() {
    try {
      const productId = this.id;
      const response = await api.detailProduct(productId);
      this.productDetails = response.data.data;
    } catch (error) {
      console.error('Error fetching product details:', error);
    }
  },
  computed: {
    filteredProducts() {
      if (!this.searchQuery) {
        return this.products;
      }
      return this.products.filter(product => product.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
    },

  },
  watch: {
    productDetails: {
      handler: 'getProductCategory',
      immediate: true,
    },
  },

  methods: {
    buyNow() {
      const orderStore = this.store_user;
      orderStore.setOrderData(this.form.quantity, this.productDetails);
      this.$router.push('/order');
    },

    async getProductCategory() {
      try {
        if (this.productDetails) {
          const productId = this.productDetails.data.category_id;
          const response = await api.productCategory(productId);
          console.log(response.data.data);
          this.products = response.data.data;
        }
      } catch (error) {
        console.error('Error fetching product details:', error);
      }
    },

    product_img_url(filename) {
      return api.imageUrlProduct(filename);
    },
    addToCart(product) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      cart.push(product);
      localStorage.setItem('cart', JSON.stringify(cart));
    },
    profile_url(filename) {
      return api.profile(filename);
    },
    ownerprofileName(username) {
      if (username) {
        const initials = `${username[0]}${username[username.length - 1]}`.toUpperCase();
        return `https://dummyimage.com/100x100/000/fff&text=${initials}`;
      }
      return '../../assets/images/Male User.png';
    },
    redirectToPayment() {
      const orderData = {
        product_id: this.productDetails.data.id,
        product_name: this.productDetails.data.name,
        quantity: this.quantity,
        price: this.productDetails.data.price,
        total: this.productDetails.data.price * this.quantity,
        delivery: 9.99,
      };
      localStorage.setItem('orderData', JSON.stringify(orderData));
      this.$router.push('/order');
    },

    async createChatRoom(id) {
        const user_id = id; // Ensure store_user is defined and accessible
        const auth = this.store_user.tokenUser; // Ensure tokenUser contains the correct token
        console.log(user_id, auth);
        try {
          const response = await api.createChatRoom(user_id, {
            headers: {
              'Authorization': `Bearer ${auth}`,
              'Content-Type': 'application/json',
            },
          });
          this.getChatRooms();
        } catch (error) {
          console.error(error);
        }
      },

    handleMouseMove(event) {
      const zoomContainer = event.currentTarget;
      const zoomImage = zoomContainer.querySelector('.zoom-image');
      const { left, top, width, height } = zoomContainer.getBoundingClientRect();
      const x = ((event.pageX - left) / width) * 100;
      const y = ((event.pageY - top) / height) * 100;
      zoomImage.style.transformOrigin = `${x}% ${y}%`;
    },
  },
};
</script>
<style>
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
