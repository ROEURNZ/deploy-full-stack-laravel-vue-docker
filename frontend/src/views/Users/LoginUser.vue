<template>
  <form @submit.prevent="login">
    <div class="container bg-light p-4 rounded shadow w-50">
      <div class="mb-3 text-center">
        <h3>Login account</h3>
        <span>Have a nice day!</span>
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label fw-bold">Email address</label>
        <input type="email" class="form-control" v-model="email" required />
        <span v-if="emailError" class="text-danger">{{ emailError }}</span>
      </div>
      <div class="mb-3"> 
        <label for="exampleInputPassword1" class="form-label fw-bold">Password</label>
        <div class="d-flex align-items-center">
          <input type="password" class="form-control flex-grow-1" v-model="password" required />
          <button type="submit" class="btn btn-dark ms-3" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Submit
          </button>
        </div>
        <span v-if="passwordError" class="text-danger">{{ passwordError }}</span>
      </div>
      <div>
        <a href="#" style="text-decoration: none">Forgot your password?</a>
        <a href="/register" style="text-decoration: none">| Register</a>
      </div>
    </div>
  </form>
</template>

<script>
import api from "../../views/api";
import { useUserStore } from '@/stores/user.js';

export default {
  name: "Login",
  data() {
    return {
      admin:1,
      email: '',
      password: '',
      store_user: useUserStore(),
      loading: false,
      emailError: null,
      passwordError: null
    }
  },
  watch: {
    email(newEmail) {
      this.validateEmail(newEmail);
    },
    password(newPassword) {
      this.validatePassword(newPassword);
    }
  },
  methods: {
    validateEmail(email) {
      if (!email) {
        this.emailError = 'Email is required.';
      } else if (!this.isValidEmail(email)) {
        this.emailError = 'Please enter a valid email address.';
      } else {
        this.emailError = null;
      }
    },
    validatePassword(password) {
      if (!password) {
        this.passwordError = 'Password is required.';
      } else if (password.length < 8) {
        this.passwordError = 'Password must be at least 8 characters long.';
      } else {
        this.passwordError = null;
      }
    },
    isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    },
    async login() {
      // Validate email and password
      this.validateEmail(this.email);
      this.validatePassword(this.password);

      // Check if there are any validation errors before proceeding
      if (this.emailError || this.passwordError) {
        return;
      }

      this.loading = true;
      try {
        const response = await api.login({ email: this.email, password: this.password });
        if (response.data.success) {
          this.store_user.accountUser = response.data.data;
          this.store_user.tokenUser = response.data.token;

          // Save to localStorage
          localStorage.setItem('user_token', response.data.token);
          localStorage.setItem('userAccount', JSON.stringify(response.data.data));

          console.log(response.data.data.id);
          if(response.data.data.id == this.admin){
            this.$router.push('/dashboard');
            return;
          }else{
            this.$router.push('/');
            return;
          }
        } else {
          this.emailError = response.data.message;
        }
      } catch (error) {
        console.error('Login error:', error);
      } finally {
        this.loading = false;
      }
    }
  },
  mounted() {
    // Check if token exists in localStorage on component mount
    const token = localStorage.getItem('user_token');
    const accountUser = localStorage.getItem('userAccount');
    if (token && accountUser) {
      this.store_user.tokenUser = token;
      this.store_user.accountUser = JSON.parse(accountUser);
      this.$router.push('/');
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
