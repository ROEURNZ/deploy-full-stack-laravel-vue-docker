<template>
    <div>
      <!-- Your existing HTML template -->
      <div class="score d-flex" style="height: 200px;">
        <div style="width: 30%;">
          <div class="rate d-flex">
          <h1>{{ rangOfrating.topRating }}.0</h1>
          </div>
          <div class="star d-flex">
            <p v-for="star in rangOfrating.topRating" :key="star"><i class="fas fa-star"></i></p>
            <p v-for="star in 5-rangOfrating.topRating" :key="star"><i class="bi bi-star"></i></p>
          </div>
          <div class="alert alert-primary text-center" style="width: 80%;">
            Top Rating: <i class="fas fa-star"></i> {{ rangOfrating.topRating }}
          </div>
        </div>
        <div class="score" style="width: 40%;">
          <div v-if="rangOfrating">
            <div class="progress mb-3" v-for="(value, key, index) in rangOfrating.levels" :key="index">
              <div
                class="progress-bar"
                role="progressbar"
                :style="{ width: value + '%', backgroundColor: 'gold' }"
                :aria-valuenow="value"
                aria-valuemin="0"
                aria-valuemax="100"
              >
              {{ parseInt(value) }}%
              </div>
              <p>{{ index+1 }}<i class="fas fa-star"></i></p>
            </div>
          </div>

        </div>
      </div>
      <div class="rateObtion d-flex justify-content-end border-top border-bottom">
        <div class="feadback d-flex">
          <button type="button" class="btn btn-outline-none border-start" data-bs-toggle="modal" data-bs-target="#feadback" data-bs-whatever="@fat">Feedback</button>
  
          <div class="modal fade" id="feadback" tabindex="-1" aria-labelledby="feadbackLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="feadbackLabel">Feedback for this product</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form @submit.prevent="submitFeedback">
                    <div class="mb-3">
                      <label for="recipient-name" class="col-form-label">Photo:</label>
                      <div class="input-group">
                        <input type="file" class="form-control" id="inputGroupFile04" @change="handleFileUpload">
                      </div>
                    </div>
                    <div class="mb-3">
                      <label for="message-text" class="col-form-label">Message:</label>
                      <textarea class="form-control" id="message-text" v-model="message"></textarea>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="star d-flex align-items-center">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-star"></i> Rate
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" @click="rattingProducts(5)">5 Star</a></li>
                <li><a class="dropdown-item" @click="rattingProducts(4)">4 Star</a></li>
                <li><a class="dropdown-item" @click="rattingProducts(3)">3 Star</a></li>
                <li><a class="dropdown-item" @click="rattingProducts(2)">2 Star</a></li>
                <li><a class="dropdown-item" @click="rattingProducts(1)">1 Star</a></li>
            </ul>
        </div>
      </div>
  
      <!-- Display comments -->
      <div v-if="comments.length > 0">
        <h3>Comments:</h3>
        <ul>
          <li v-for="comment in comments" :key="comment.id">
            {{ comment.message }}
          </li>
        </ul>
      </div>
      <div v-else>
        <p>No comments yet.</p>
      </div>
    </div>
  </template>
  
  <script>
  import api from '../../views/api';
  import { useUserStore } from '@/stores/user.js';
  
  export default {
    props: ['product_id', 'rangOfrating'],
    data() {
      return {
        message: '',
        selectedFile: null,
        user_store: useUserStore(),
        comments: [] // Initialize comments array
      };
    },
    methods: {
      handleFileUpload(event) {
        this.selectedFile = event.target.files[0];
      },

      async rattingProducts(rating) {
        const userToken = this.user_store.tokenUser;
        const product_id = this.product_id;

        try{
          const headers = {
            Authorization: `Bearer ${userToken}`,
            'Content-Type': 'application/json'
          };
          console.log(product_id, userToken, rating);
          const response = await api.rattingProduct(product_id, rating, headers);
          alert(`You have successfully ratting a product with ${rating} stars.`)
          window.location.reload();
          console.log('Rating product successfully:', response.data);
        }catch(error) {
          console.error('Error rating product:', error);
        }
      },

      async submitFeedback() {
        if (this.selectedFile && this.message) {
          const userToken = this.user_store.tokenUser;
  
          const formData = new FormData();
          formData.append('product_id', this.product_id);
          formData.append('comment', this.message);
          formData.append('image', this.selectedFile);
  
          try {
            const headers = {
              Authorization: `Bearer ${userToken}`,
              'Content-Type': 'multipart/form-data'
            };
  
            const response = await api.comment_product(formData, headers);
  
            console.log('Feedback submitted successfully:', response.data);
  
            // Clear form fields
            this.message = '';
            this.selectedFile = null;
  
            // Refresh comments
            window.location.href = `/product/${this.product_id}`;// Assuming you have a method to fetch comments
          } catch (error) {
            console.error('Error submitting feedback:', error);
          }
        } else {
          // alert('Please fill in all fields.');
        }
      },
     
    },
    mounted() {
      // Initial fetch of comments when component mounts
      this.submitFeedback();
    }
  };
  </script>
  
  <style>
  /* Your CSS styles */
.star .btn-outline-secondary {
    border-color: #ced4da;
    background-color: #fff;
    color: #495057;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.2s ease-in-out;
}

.star .btn-outline-secondary:hover,
.star .btn-outline-secondary:focus {
    background-color: #f8f9fa;
    color: #212529;
    border-color: #adb5bd;
}

.star .dropdown-menu {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.star .dropdown-item:hover {
    background-color: #e9ecef;
    color: #212529;
}

.star i.fas.fa-star {
    color: #ffc107;
    margin-right: 0.5rem;
}
/* Add this to your CSS file */
.gold-icon {
  color: gold;
}

</style>
  
