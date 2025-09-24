import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '@/pages/Homepage.vue'
import Portfolio from '@/pages/Portfolio.vue'
import Category from '@/pages/Category.vue'

const routes = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage,
    props: true
  },
  {
    path: '/portfolio',
    name: 'Portfolio',
    component: Portfolio
  },
  {
    path: '/:slug',
    name: 'Category',
    component: Category,
    props: true
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
