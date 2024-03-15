import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import Overview from '../views/Overview.vue'

const routes = [
  {
      path: '/home',
      name: 'Home',
      component: Home
  },
  {
      path: '/overview/:umkmId',
      name: 'Overview',
      component: Overview
  },
  {
      path: '/:pathMatch(.*)*',
      redirect: '/home'
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
