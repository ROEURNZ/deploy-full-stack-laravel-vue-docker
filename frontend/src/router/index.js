import { createWebHistory, createRouter } from 'vue-router'

import Home from '../views/HomePage/Homeview.vue'
import product from '../views/Products/ProductShow.vue'
import produc_detail from '../views/Products/DetailPro.vue'
import LoginUser from '../views/Users/LoginUser.vue'
import CustomerOrder from '../views/Order/CustomerOrder.vue'
import ChangePassword from '../views/FogetPassword/ChangePassword.vue'
import register from '../views/Users/ReginsterAcc.vue'
import Viewprofile from '../views/profile/ProfileVue.vue'
import UserPostProduct from '../views/Products/ProductPostUser.vue'  
import ProductShow from '../views/Products/ProductShow.vue';
import userprodcuts from '../views/Users/ProductVue.vue'
import addcard from '../views/CardAdd/addCard.vue';
// import PageStore from '../views/PageStore/StorePage.vue';
// import userchats from '../views/Users/Chat/ChatView.vue';
import booking from '../views/Order/InOrder.vue'
import sellProduct from '../views/Order/InSell.vue'
import payment from '../views/Order/PaymentOrder.vue'
import CollectUserStore from '../views/PageStore/CollectUserStore.vue'
import Createstore from '../views/PageStore/CreateStore.vue'
import editStore from '../views/PageStore/EditStore.vue'
import userStore from '../views/Users/UserStore.vue'
import DetailStore from '../views/PageStore/DetailProdcut.vue'
import productCategory from '../views/Products/ProductCategories.vue'
import userchats from '../views/Users/Chat/ChatView.vue'
import Charge from '../Components/Payment/PaymentComponent.vue'
import plans from '../views/Charge/ChargeMoney.vue'
import ChargeMoney from '../views/Order/PaymentOrder.vue';
// __________________ADMIN________________
import admin from '../Admin.vue'
import Loukdo from '../LoukDo.vue'

const routes = [
    {
        path: '/home',
        name: 'Home', 
        component: Loukdo,
        children: [
            
            { 
                path: '/login',
                name: 'login', 
                component: LoginUser
            },
            {
                path: '/register',
                name: 'register', 
                component: register
            },
            { 
                path: '/',
                name: 'Home', 
                component: Home,
            },
            { 
                path: '/product',
                name: 'home_pro', 
                component: product
            },
            { 
                path: '/product/:id',
                name: 'produc_detail', 
                component: produc_detail,
                props: true
            },
          
            { 
                path: '/order',
                name: 'order', 
                component: CustomerOrder
            },
            { 
                path: '/foget',
                name: 'foget', 
                component: ChangePassword
            },
            { 
                path: '/product-post',
                name:'product-post', 
                component: UserPostProduct
            },
            {
                path: '/profile',
                name: 'profile', 
                component: Viewprofile,
                children: [
                    {
                        path: '/userprodcuts',
                        name: 'userprodcuts', 
                        component: userprodcuts
                    },
                    {
                        path: '/booking',
                        name: 'booking', 
                        component: booking
                    },
                    {
                        path: '/selling',
                        name: 'sellProduct', 
                        component: sellProduct
                    },
                    {
                        path: '/card',
                        name: 'card', 
                        component: addcard,
                        props: true
                    },
                ]
            },
            
            {
                path: '/chats',
                name: '/chats', 
                component: userchats
            },
            {
                path: '/payment',
                name: 'payment',  
                component: payment
            },
            {
                path: '/product/category/:id',

                name: 'product/category',
                component: productCategory,
                props: true
            },
            {
                path: '/CollectUserStore/:id',
                name: 'CollectUserStore', 
                component: CollectUserStore,
                props:true

            },
            {
                path: '/userStore',
                name: 'userStore', 
                component: userStore
            },
            {
                path: '/DetailStore',
                name: 'DetailStore', 
                component: DetailStore
            },
            {
                path: '/editStore/:id',
                name: 'editStore', 
                component: editStore,
                props: true
            },
           
        ]
    },

    // ________________________Admin_________________________

    {
        path: '/admin',
        name: 'admin_dashboard',
        component: () => admin,
        children: [
            {
                path: '/dashboard',
                name: 'admin_users',
                component: () => import('../views/Admin/DashboardView.vue'),
            },
            {
                path: '/products',
                name: 'admin_products',
                component: () => import('../views/Admin/Product/ProductList.vue'),
            },
            {
                path: '/creategory',
                name: 'admin_category',
                component: () => import('../views/Admin/Product/Categories.vue')
            },
            {
                path: '/users',
                name: 'users',
                component: () => import('../views/Admin/Auth/User.vue')
            },
            {

                path: '/usercreatestore',
                name: 'usercreatestore',
                component: () => import('../views/Admin/Auth/UserCreateStore.vue')
              },
            {
                path: '/admin/orders',
                name: 'admin_orders',
                component: () => import('../views/Admin/Auth/OrderAndSeller.vue')
            },
            {
                path: '/userCharge',
                name: 'userCharge',
                component: () => import('../views/Admin/ChargeBoard/UserCharge.vue')
            },
            // {
            //     path: '/admin/pages',
            //     name: 'admin_pages',
            //     component: () => import('../views/Admin/Pages.vue')
            // },
        ]
    },
    {
        path: '/plans',
        name: '/plans', 
        component: plans
    },
    {
        path: '/charge',
        name: 'charge', 
        component: ChargeMoney,
        // props: route => ({ 
        //     planTitle: route.query.planTitle,
        //     planPrice: route.query.planPrice
        //   })
    },
    {
        path: '/chargeMoney',
        name: 'chargeMoney', 
        component: Charge,
        props: route => ({ 
            planTitle: route.query.planTitle,
            planPrice: route.query.planPrice
          })
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
