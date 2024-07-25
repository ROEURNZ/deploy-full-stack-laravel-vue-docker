<template>
  <section class="container">
    <div class="card mb-3 shadow-sm bg-light">
      <router-link :to="{ name: 'produc_detail', params: { id: product.id } }" style="text-decoration: none;">
        <img :src="imageProduct(product.image)" class="card-img" alt="clothe" style="height: 270px;" />
      </router-link>
      <div class="card-body">
        <h5 class="card-title">{{ product.name }}</h5>
        <h5 class="card-price text-success">${{ product.price }}</h5>
        <div class="d-flex align-items-center justify-content-between mt-3">
          <div v-if="product.ratting" class="star-rating" style="font-size: 1.5em; color: #f39c12">
            <div class="star d-flex">
              <p v-for="star in product.ratting.topRating" :key="star"><i class="fas fa-star" style="font-size: 17px;"></i></p>
              <p v-for="star in 5 - product.ratting.topRating" :key="star"><i class="bi bi-star" style="font-size: 17px;"></i></p>
            </div>
          </div>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" :data-bs-target="`#${product.id}`">Buy</button>
        </div>
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div class="modal fade" :id="product.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="col-lg-4" style="width: 100%;">
          <div class="">
            <div class="card-header bg-success text-white text-center p-3">
              Order Summary
            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                <span>Product name:</span> <span>{{ product.name }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Price:</span> <span>{{ product.price }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Delivery:</span> <span>$2</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Quantity:</span>
                <span>
                  <div class="quantity-input">
                    <button @click="decrement" class="outline-primary">-</button>
                    <input class="outline-primary" type="number" v-model="qty" min="1">
                    <button @click="increment" class="outline-primary">+</button>
                  </div>
                </span>
              </li>
            </ul>
            <div class="card-footer text-center">
              <strong>Total Items: <span>${{ totalPrice }}</span></strong>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" @click="OrderProduct(product)">Order</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../views/api';
import { useUserStore } from "@/stores/user.js";

export default {
  name: 'CardComponent',
  props: ['searchQuery', 'product'],
  data() {
    return {
      store_user: useUserStore(),
      qty: 1,
    };
  },
  computed: {
    totalPrice() {
      return this.product.price * this.qty;
    },
  },
  methods: {
    imageProduct(filename) {
      return api.imageUrlProduct(filename);
    },
    increment() {
      this.qty++;
    },
    decrement() {
      if (this.qty > 1) {
        this.qty--;
      }
    },
    async OrderProduct(product) {
      try {
        const product_id = product.id;

        const orderData = {
          product_id: product_id,
          quantity: this.qty,
        };

        const headers = { Authorization: `Bearer ${this.store_user.tokenUser}` };
        const response = await api.createOrderProduct(orderData, headers);

        console.log(response.data); // Optional: handle response as needed

        // Redirect to '/order' route after successful order creation
        window.location.href = ('/booking');
        // this.$router.push('/booking');
      } catch (error) {
        console.error('Error ordering product:', error);
        // Handle error scenario, e.g., show error message to user
      }
    },
  }
};
</script>

<style scoped>
.card {
  border-radius: 15px;
  overflow: hidden;
  transition: transform 0.2s;
}

.card:hover {
  transform: scale(1.05);
}

.card-img-top {
  height: 200px;
  object-fit: cover;
}

.card-body {
  padding: 20px;
}

.card-title {
  font-size: 1.2em;
  margin-bottom: 10px;
}

.card-text {
  font-size: 0.9em;
}

.card-price {
  font-size: 1.1em;
  font-weight: bold;
}

.star-rating span {
  margin-right: 5px;
}

.bi-star {
  margin-right: 5px;
}

.btn {
  border-radius: 30px;
  font-size: 0.9em;
  padding: 10px 20px;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  background-color: #0056b3;
  border-color: #004085;
}

.quantity-input {
  display: flex;
  align-items: center;
}

.quantity-input input {
  text-align: center;
  width: 50px;
}

.quantity-input button {
  width: 30px;
  height: 30px;
}
</style>
