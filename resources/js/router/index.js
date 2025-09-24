import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '@/pages/Homepage.vue'
import Category from '@/pages/Category.vue'

const routes = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage,
    props: true
  },
  {
    path: '/:slug',
    name: 'Category',
    component: Category,
    props: route => ({ slug: route.params.slug })
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

// Add navigation guards to prevent rapid navigation
let lastNavigationTime = 0
router.beforeEach((to, from, next) => {
  const now = Date.now()
  const timeSinceLastNavigation = now - lastNavigationTime
  
  // Prevent navigation if less than 100ms since last navigation
  if (timeSinceLastNavigation < 100 && to.path !== from.path) {
    console.log('Navigation blocked - too rapid')
    return false
  }
  
  lastNavigationTime = now
  next()
})

export default router
