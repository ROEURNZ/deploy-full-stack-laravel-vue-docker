
<template>
  <div class="container">
    <form @submit.prevent="submitForm">
      <div class="row justify-content-center">
        <div class="bg-light rounded shadow w-50" style="width: 50%">
          <div class="">
            <div class="title text-center">
              <h2><b>Create an account</b></h2>
              <p>Enter your email to sign up for this app</p>
            </div>
            <div class="form-row d-flex" style="gap: 10px">
              <div class="col">
                <div class="form-group">
                  <label for="firstName">First name</label>
                  <input type="text" class="form-control" id="firstName" v-model="user.firstname"/>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="lastName">Last name</label>
                  <input type="text" class="form-control" id="lastName" v-model="user.lastname"/>
                </div>
              </div>
            </div>

            <div class="form-group fw-bold mt-3">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" v-model="user.email"/>
            </div>

            <div class="form-group fw-bold mt-3">
              <label for="password" class="form-label mt-2">Password</label>
            </div>
            <div class="d-flex mb-3">
              <input type="password" class="form-control fw-bold flex-grow-1" v-model="user.password"/>
              <button type="submit" class="btn btn-dark ms-3" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Submit
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import api from "../../views/api";
import { useUserStore } from "@/stores/user.js";

export default {
  name: "RegisterAccVue",
  data() {
    return {
      user: {
        firstname: "",
        lastname: "",
        email: "",
        password: "",
      },
      store_user: useUserStore(),
      loading: false,
    };
  },
  computed: {
    fullName() {
      return this.user.firstname + " " + this.user.lastname;
    }
  },
  methods: {
    async register(userData) {
      this.loading = true;
      try {
        let response = await api.register(userData);
        if (response.data.success) {
          this.store_user.accountUser = response.data.data;
          this.store_user.tokenUser = response.data.token;

          // Save to localStorage
          localStorage.setItem('user_token', response.data.token);
          localStorage.setItem('userAccount', JSON.stringify(response.data.data));

          this.$router.push("/");
        }
      } catch (e) {
        this.loading = false;
        console.log("Error register:", e);
      }
    },
    submitForm() {
      const userWithFullName = {
        ...this.user,
        name: this.fullName
      };
      this.register(userWithFullName);
    },
  },
  mounted() {
    // Check if token exists in localStorage on component mount
    const token = localStorage.getItem('user_token');
    const accountUser = localStorage.getItem('userAccount');
    if (token && accountUser) {
      this.store_user.tokenUser = token;
      this.store_user.accountUser = JSON.parse(accountUser);
      this.$router.push("/");
    }
  }
};
</script>

<style>
/* Add any necessary styles here */
.btn {
  border-radius: 30px;
  font-size: 0.9em;
  padding: 10px 20px;
  cursor: pointer; /* Added cursor pointer */
}
</style>
