import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '@/pages/Homepage.vue'
import Category from '@/pages/Category.vue'
import PrivacyPolicy from '@/pages/PrivacyPolicy.vue'
import LegalNotice from '@/pages/LegalNotice.vue'
import TermsOfService from '@/pages/TermsOfService.vue'

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
  },
  {
    path: '/politique-de-confidentialite',
    name: 'PrivacyPolicy',
    component: PrivacyPolicy,
    props: true
  },
  {
    path: '/mentions-legales',
    name: 'LegalNotice',
    component: LegalNotice,
    props: true
  },
  {
    path: '/cgu',
    name: 'TermsOfService',
    component: TermsOfService,
    props: true
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

export default router
