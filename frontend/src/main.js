import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css';
import { createApp } from 'vue'
// import App from './App.vue'
// import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.js' 
import '@fortawesome/fontawesome-free/css/all.css';
import Loukdo from '../../frontend/src/Index.vue'
import router from './router'

import { createPinia } from 'pinia'
const pinia = createPinia()

createApp(Loukdo).use(bootstrap).use(router).use(pinia).mount('#app')
