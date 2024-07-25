<template>
  <div>
    <!-- Sidebar -->
    <div class="d-flex">
      <div class="my-4 d-flex justify-content-between">
        <div class="p-3 mx-2 users card" @click="showSection('users')">
          <h5 class="card-title">Users</h5>
          <p class="card-text"><strong>{{ users.length }}</strong> /users active</p>
        </div>
        <div class="p-3 mx-2 product card" @click="showSection('products')">
          <h5 class="card-title">Products</h5>
          <p class="card-text"><strong>{{ products.length }}</strong> /products in stock.</p>
        </div>
        <div class="p-3 mx-2 categories card" @click="showSection('categories')">
          <h5 class="card-title">Categories</h5>
          <p class="card-text"><strong>{{ categories.length }}</strong> /different categories.</p>
        </div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="mx-2">
      <table v-if="currentSection === 'users'" class="table table-white table-striped">
        <thead>
          <tr class="text-center">
            <th scope="col">Id</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col" style="width: 10%;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <user-list v-for="(user, list) in users" :key="user.id" :user="user" :list="list" class="text-center"/>
        </tbody>
      </table>
    </div>
    <div v-if="currentSection === 'products'">
      <ProductLis/>
    </div>
    <div v-if="currentSection === 'categories'">
      <Categories/>
    </div>
  </div>
</template>

<script>
import api from "@/views/api";
import UserList from "@/Components/AdminCom/users/ListUser.vue";
import ProductLis from "@/views/Admin/Product/ProductList.vue"
import Categories from "@/views/Admin/Product/Categories.vue"

export default {
  name: 'AdminLayout',
  components: {
    UserList,
    ProductLis,
    Categories
  },
  data() {
    return {
      users: [],
      products: [],
      categories: [],
      currentSection: "users",
    }
  },
  mounted() {
    this.listUsers();
    this.fetchProducts();
    this.fetchCategories();
  },
  methods: {
    async listUsers() {
      try {
        const response = await api.listUsers();
        this.users = response.data;
      } catch (error) {
        console.error("Error fetching users: ", error);
      }
    },
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
    async fetchCategories() {
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
    },
    showSection(section) {
      this.currentSection = section;
    },
    blockUser(id) {
      // Implement the logic to block the user with the given id
      // You can use the api.put method to make a PUT request to the '/users/{id}/block' endpoint
      // Example: api.put(`/users/${id}/block`)
    }
  }
}
</script>

<style scoped>
/* Add your custom styles here */
</style>
