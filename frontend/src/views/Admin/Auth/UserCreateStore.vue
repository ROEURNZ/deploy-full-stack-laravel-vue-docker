<template>
  <div>
    <table class="table table-dark table-striped">
      <thead>
        <tr class="text-center">
          <th scope="col">Id</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">StoreName</th>
          <th scope="col">Image</th>
          <th scope="col" style="width: 10%;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(user, index) in users" :key="index" class="text-center">
          <td>{{ index + 1 }}</td>
          <td>{{ user.user.name }}</td>
          <td>{{ user.user.email }}</td>
          <td>{{ user.name }}</td>
          <td>
            <img :src="user.image_url" alt="Store Image" class="img-fluid" style="height: 50px; width: 50px;" />
          </td>
          <td>
            <button class="btn btn-danger mb-2">Block</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import api from "@/views/api";

export default {
  data() {
    return {
      users: [],
    };
  },
  mounted() {
    this.getStores();
  },
  methods: {
    async getStores() {
      let response = await api.getStores();
      this.users = response.data.data.map(store => ({
        user: store.user,
        name: store.name,
        image_url: store.image_url,
      }));
    },
  },
};
</script>

<style>
</style>