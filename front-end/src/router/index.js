import { createRouter, createWebHistory } from 'vue-router'
import Dasboard from '../views/Dashboard.vue'
import ListUMKM from '../views/ListUMKM.vue'
import OverviewUMKM from '../views/OverviewUMKM.vue'
import ListAdmin from '../views/ListAdmin.vue'
import Login from '../views/Login.vue'
import OverviewProduct from '../views/OverviewProduct.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dasboard
    },
    {
      path: '/list-umkm',
      name: 'ListUMKM',
      component: ListUMKM
    },
    {
      path: '/list-admin',
      name: 'ListAdmin',
      component: ListAdmin
    },
    {
      path: '/umkm-overview/:umkmId', // Parameter dinamis dengan nama umkmId
      name: 'OverviewUMKM',
      component: OverviewUMKM
    },
    {
      path: '/product-overview/:productId', // Parameter dinamis dengan nama umkmId
      name: 'OverviewProduct',
      component: OverviewProduct
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard'
    },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
