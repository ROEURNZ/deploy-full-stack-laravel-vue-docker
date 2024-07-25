<template>
  <div>
    <nav-bar></nav-bar>
    <div class="container bg-white">
      <router-view/>
    </div>
    <Footer/>
  </div>
</template>

<script>
import NavBar from '@/Components/navbars/NavBar.vue';
import Footer from '@/Components/footer/FooTer.vue';
import { useUserStore } from '@/stores/user.js';

export default {
  components: {
    NavBar,
    Footer,
  },
  data(){
    return {
      // Add your data here
      user_store: useUserStore(),
      user_id: null,
    }
  },
  computed: {
    userId() {
      this.user_id = this.user_store.accountUser;
    }
  }
}
</script>

<style>
</style>
