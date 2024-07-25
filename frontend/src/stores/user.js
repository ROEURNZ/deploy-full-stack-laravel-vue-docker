// stores/user.js

import { defineStore } from 'pinia';

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    accountUser: null,
    tokenUser: null,
    quantity: null,
    productDetails: null,
    num_products: 0,
    cateId: null,
    post_count: 0, 
    has_paid: false, 
    notification: null,
    user_id:null,
  }),
  actions: {
    setUser(data) {
      this.accountUser = data.accountUser;
      this.tokenUser = data.tokenUser;
      this.post_count = data.post_count || 0;
      this.has_paid = data.has_paid || false;
      this.user_id = data.user_id;
      localStorage.setItem('user_token', data.userToken);
      localStorage.setItem('userAccount', JSON.stringify(data.accountUser));
      localStorage.setItem('post_count', this.post_count);
      localStorage.setItem('has_paid', this.has_paid);

    },
    loadUser() {
      const token = localStorage.getItem('user_token');
      const accountUser = localStorage.getItem('userAccount');
      const postCount = localStorage.getItem('post_count');
      const hasPaid = localStorage.getItem('has_paid');
       const userId = localStorage.getItem('user_id');
      if (token && accountUser) {
        this.tokenUser = token;
        this.accountUser = JSON.parse(accountUser);
        this.post_count = parseInt(postCount, 10) || 0;
        this.has_paid = hasPaid === 1;
      }
    },
    logout() {
      this.accountUser = null;
      this.tokenUser = null;
      this.post_count = 0; 
      this.has_paid = 0; 
      localStorage.removeItem('user_token');
      localStorage.removeItem('userAccount');
    },
    setOrderData(quantity, productDetails) {
      this.quantity = quantity;
      this.productDetails = productDetails;
    },
    get_num_products(num_products) {
      this.num_products = num_products;
    },
    getCateProId(id){
      this.cateId = id;
      localStorage.removeItem('post_count');
      localStorage.removeItem('has_paid');
    },
    updatePostCount(count) {
      this.post_count = count;
      localStorage.setItem('post_count', count);
    },
    updateHasPaid(status) {
      this.has_paid = status;
      localStorage.setItem('has_paid', status);
    },
    setNotification(notification) {
      this.notification = notification;
    },
    setUser_id(id){
      this.user_id = id;
    }
  },
});

