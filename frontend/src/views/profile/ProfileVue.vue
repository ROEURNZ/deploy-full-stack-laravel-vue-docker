<template>
  <section class="container">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-12">
        <div class="card h-100">
          <!-- Cover Photo and Profile Section -->
          <div class="cover rounded-top text-white d-flex flex-column align-items-center">
            <p class="cover-photo"></p>
            <div class="profile mt-5 d-flex flex-column align-items-center">
              <div class="position-relative">
                <img :src="profile_url(myAccount.profile)" class="img-fluid img-thumbnail mt-5" />
                <label for="inputGroupFile04" class="camera-icon position-absolute">
                  <i class="fas fa-camera fa-lg text-dark" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
                </label>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" ref="exampleModalRef" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Upload your profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form @submit.prevent="uploadProfilePicture" enctype="multipart/form-data">
                        <div class="modal-body">
                          <input type="file" class="form-control" aria-label="Upload" accept="image/*" @change="onFileChange" />
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-danger">Upload</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <h4 class="mt-3" style="color: black">{{ userStore.accountUser.name.toUpperCase() }}</h4>
            </div>
          </div>
          <!-- Navigation Tabs -->
          <div class="p-4 text-black">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex justify-content-center">
                <router-link to="/userprodcuts" class="mx-2 nav-item" active-class="text-dark active btn">Product</router-link>
                <router-link to="/booking" class="mx-2 nav-item" active-class="text-dark active btn">Inbox</router-link>
                <router-link to="/selling" class="mx-2 nav-item" active-class="text-dark active btn">Selling</router-link>
                <router-link to="/card" class="mx-2 nav-item" active-class="text-dark active btn">Favorites</router-link>
                <router-link to="/chats" class="mx-2 nav-item" active-class="text-dark active btn">Messagers</router-link>
                <router-link to="/userStore" class="mx-2 nav-item" active-class="text-dark active btn">Store</router-link>
                <div class="d-flex flex-grow-1">
              </div>

              </div>
              <div class="d-flex align-items-center gap-1">
                <a href="/product-post">
                  <button class="btn btn-dark pulse-animation">Post</button>
                </a>
                {{ num_products }}
              </div>
            </div>
          </div>
         
          <!-- Additional Sections -->
          <div class="p-4 text-black">
            <router-view/>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';
import { useUserStore } from '@/stores/user.js';
import api from '@/views/api.js';
import cards_product from '@/Components/Card/CardComponent.vue'

export default {
  name: 'Profile',
  data() {
    return {
      userStore: useUserStore(),
      profile: null,
      cover: null,
      myAccount:{},
      cards_product,
    };
  },
  mounted() {
    this.getMyAccount()
  },
  methods: {
    onFileChange(e) {
      this.profile = e.target.files[0];
      console.log(this.profile); // Log the file
    },
    async uploadProfilePicture() {
      if (!this.profile) {
        console.error('No file selected.');
        return;
      }

      const formData = new FormData();
      formData.append('profile', this.profile); // Ensure key matches backend

      try {
        const response = await axios.post('http://127.0.0.1:8000/api/user/update', formData, {
          headers: {
            Authorization: `Bearer ${this.userStore.tokenUser}`,
            'Content-Type': 'multipart/form-data'
          }
        });
        window.location.href = '/profile';

      } catch (error) {
        if (error.response && error.response.data) {
          console.error('Error:', error.response.data); // Log detailed error from server
        } else {
          console.error('Error:', error.message); // Log generic error
        }
      }
    },

    async getMyAccount(){
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/myaccount', {
          headers: {
            Authorization: `Bearer ${this.userStore.tokenUser}`
          }
        });
        this.myAccount = response.data.user
      } catch (error) {
        if (error.response && error.response.data) {
          console.error('Error:', error.response.data); // Log detailed error from server
        } else {
          console.error('Error:', error.message); // Log generic error
        }
      }
    },
    profile_url(filename){
      return api.profile(filename)
    }
  }
};
</script>

<style scoped>
html,
body,
#app {
  height: 100%;
  margin: 0;
  padding: 0;
}

.profile img {
  border-radius: 50%;
  width: 150px;
  height: 150px;
  z-index: 1;
}

.profile h4,
.profile p {
  text-align: center;
}

.card {
  width: 100%;
}

.cover {
  height: 295px;
  position: relative;
  background: linear-gradient(135deg, #79c7ff, #ffccff);
}

.cover-photo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.bg-light-gray {
  background-color: #676768a4;
}

.text-dark {
  color: #333;
}

.position-relative {
  position: relative;
}

.camera-icon {
  top: 150px;
  right: 25px;
  cursor: pointer;
}

.camera-icon:hover {
  color: black;
}

.nav-item {
  cursor: pointer;
  padding: 5px 10px;
  font-weight: bold;
  color: #333;
  transition: background-color 0.3s;
}

.nav-item:hover {
  background-color: #f0f0f0;
  border-radius: 5px;
}

.section {
  margin-top: 20px;
  padding: 15px;
  background-color: #f9f9f9;
  border-radius: 10px;
}

.section h5 {
  font-weight: bold;
  color: #4a4a4a;
}

.section p {
  margin: 0;
  color: #666;
}

.nav-item {
  text-decoration: none;
}

.btn {
  border-radius: 30px;
  font-size: 0.9em;
  padding: 10px 20px;
  cursor: pointer; /* Added cursor pointer */
}

@keyframes pulse {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

.pulse-animation {
  animation: pulse 1.5s infinite;
}
</style>
