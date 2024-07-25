import axios from 'axios';

const API_URL = 'http://127.0.0.1:8000/api';
const URL_PORT = 'http://127.0.0.1:8000';

export default {
  myaccount(headers){
    return axios.get(`${API_URL}/myaccount`, {headers: headers});
  },

  listUsers(){
    return axios.get(`${API_URL}/user/list`);
  },
  
  listProduct() {
    return axios.get(`${API_URL}/products/list`);
  },
  createProduct(formData, config) {
    return axios.post(`${API_URL}/products/create`, formData, config);
  },

  
  imageUrlProduct(filename) {
    return `${API_URL}/products/image/${filename}`;
  },
  
  imageUrlCategory(filename) {
    return `${API_URL}/categories/image/${filename}`;
  },
  imageUrlStore(filename) {
    return `${API_URL}/stores/image/${filename}`;
  },
  
  imageComment(filename) {
    return `${URL_PORT}/images/product/${filename}`;
  },

  profile(filename) {
    return `${API_URL}/profiles/${filename}`;
  },

  qrimages(filename) {
    return `${API_URL}/userqrimage/${filename}`;
  },
  
  register(userData) {
    return axios.post(`${API_URL}/register`, userData);
  },

  login(userData) {
    return axios.post(`${API_URL}/login`, userData);
  },

  logout() {
    return axios.post(`${API_URL}/logout`);
  },

  detailProduct(productId, headers) {
    return axios.get(`${API_URL}/products/pro_details/${productId}`, {
      headers: headers
    });
  },

  comment_product(data, headers) {
    return axios.post(`${API_URL}/comment/create`, data, {
      headers: headers
    });
  },

  replycomment(formData, config) {
    return axios.post(`${API_URL}/reply/create`, formData, config);
  },
  userproduct(id) {
    return axios.get(`${API_URL}/userproduct`, {
      params: { id: id }
    });
  },



  getAllCate(){
    return axios.get(`${API_URL}/categories/list`);},
  createStore(formData, config){
    return axios.post(`${API_URL}/store/create`, formData, config)
  },
  getStores(){
    return axios.get(`${API_URL}/store/list`);
  },
  getStore(storeId) {
    return axios.get(`${API_URL}/store/show/${storeId}`);
  },
  updateStore(storeId, formData,config) {
    return axios.put(`${API_URL}/store/update/${storeId}`, formData,config);
  },
  deleteStore(storeId) {
    return axios.delete(`${API_URL}/store/remove/${storeId}`);
  },
  imageUrlStore(filename) {
    return `${API_URL}/stores/image/${filename}`;
  },
  deleteProduct(id, config) {
    return axios.delete(`${API_URL}/products/remove/${id}`, config);
  },
// __________________________pro_________________

  updateProduct(pro_id, data, token) {
    const headers = {
      'Authorization': `Bearer ${token}`
    };
    return axios.post(`${API_URL}/products/update/${pro_id}`, data, { headers: headers });
  },
   // New CRUD methods for OrderProduct
   listOrderProducts(headers) {
    return axios.get(`${API_URL}/order/list`, { headers: headers });
  },
   listSellerProducts(headers) {
    return axios.get(`${API_URL}/order/list/seller`, { headers: headers });
  },

  createOrderProduct(data, headers) {
    return axios.post(`${API_URL}/order/create/order`, data, { headers: headers });
  },

  updateOrderProduct(id, data, headers) {
    return axios.put(`${API_URL}/order/update/${id}`, data, { headers: headers });
  },

  deleteOrderProduct(id, headers) {
    console.log(`${API_URL}/order/remove/${id}`)
    return axios.delete(`${API_URL}/order/remove/${id}`, { headers: headers });
  },

  updateOrderStatus(orderId, status, token) {
    const headers = {
      'Authorization': `Bearer ${token}`
    };
    return axios.put(`${API_URL}/order/update/status/${orderId}`, { status: status }, { headers: headers });
  },

  // ____________________CATEGORY________________

  listCategories() {
    return axios.get(`${API_URL}/categories/list`);
},

createCategory(formData, config) {
    return axios.post(`${API_URL}/create/category`, formData, config);
},
updateCategory(id, formData, config) {
  return axios.post(`${API_URL}/update/category/${id}`, formData, config);
},

deleteCategory(categoryId) {
    return axios.delete(`${API_URL}/delete/category/${categoryId}`);
},


getAllCategories(){
  return axios.get(`${API_URL}/categories/list`);},
productCategory(cateId) {
  return axios.get(`${API_URL}/products/category/${cateId}`);
},

  // ______________________Ratings__________________
  rattingProduct(productId, rating, headers) {
    console.log(rating);
    return axios.post(`${API_URL}/products/ratting/${productId}`, { rating: rating }, { headers: headers });
  },

  ChargeMoney(amount) {
    return axios.post(`${API_URL}/stripe/payment`, { amount });
  },

  // __________________Admin_______________________

  adminDeleteProduct(id) {
    return axios.delete(`${API_URL}/product/remove/${id}`);
  },

  orderAndseller(){
    return axios.get(`${API_URL}/orders/list`);
  },
  
  getReplyComment(headers){
    return axios.get(`${API_URL}/reply/list`, {headers:headers});
  },

  // ________________Messages___________________________

  // http://127.0.0.1:8000/api/message/chat/room
  chatrooms(headers) {
    return axios.get(`${API_URL}/message/chat/rooms`, {
      headers: headers
    });
  },

  createChatRoom(userId, headers) {
    return axios.post(`${API_URL}/message/chat/room`, { user_id: userId }, { headers: headers });
  },


  getMessage(roomId, headers) {
    return axios.get(`${API_URL}/message/chat/messages/${roomId}`, {
      headers: headers
    });
  },

  sendMessage(roomId, message, headers) {
    return axios.post(`${API_URL}/message/chat/messages/${roomId}`, { message }, {
      headers: headers
    });
  },

  // __________________Messages________________________
  // http://127.0.0.1:8000/api/message/chat/room
  postChatRooms(user_id, headers) {
    return axios.post(`${API_URL}/message/chat/room`, user_id, { headers: headers });
  },

};

