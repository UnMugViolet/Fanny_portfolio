<template>
  <nav class="font-sans relative w-full md:py-14 px-4 md:px-16 py-8">
    <div class="w-full flex justify-between items-center">
        <!-- Logo -->
      <router-link to="/" class="flex items-center w-full">
        <img src="/img/svg/logo.svg" alt="Logo Fanny Séraudie" class="w-9 h-6 mr-2" />
        <h1 class="text-xl font-bold font-heading">Fanny Séraudie</h1>
      </router-link>

      <!-- Mobile burger menu button -->
      <button 
        @click="toggleMobileMenu"
        class="md:hidden z-50 relative p-2"
        aria-label="Toggle menu"
      >
        <div class="w-6 h-6 flex flex-col justify-center space-y-1">
          <span 
            class="block h-0.5 bg-gray-800 transition-all duration-300 transform origin-center"
            :class="{ 'rotate-45 translate-y-1': isMobileMenuOpen, 'rotate-0 translate-y-0': !isMobileMenuOpen }"
          ></span>
          <span 
            class="block h-0.5 bg-gray-800 transition-all duration-300"
            :class="{ 'opacity-0': isMobileMenuOpen, 'opacity-100': !isMobileMenuOpen }"
          ></span>
          <span 
            class="block h-0.5 bg-gray-800 transition-all duration-300 transform origin-center"
            :class="{ '-rotate-45 -translate-y-1': isMobileMenuOpen, 'rotate-0 translate-y-0': !isMobileMenuOpen }"
          ></span>
        </div>
      </button>

      <!-- Desktop navigation -->
      <div
        v-if="categories && categories.length > 0" 
        class="hidden md:flex space-x-6"> 
        <router-link 
          v-for="category in categories" 
          :key="category.id" 
          :to="`/${category.slug}`" 
          class="text-gray-800 hover:text-gray-600 transition-colors font-medium">
          {{ category.name }}
        </router-link>
      </div>
    </div>

    <!-- Mobile navigation menu - full screen from right -->
    <div 
      class="fixed top-0 right-0 h-full w-full bg-white transform transition-transform duration-300 z-50 md:hidden overflow-hidden"
      :class="{ 'translate-x-0': isMobileMenuOpen, 'translate-x-full': !isMobileMenuOpen }"
    >
      <!-- Close button -->
      <div class="absolute top-8 right-8">
        <button 
          @click="closeMobileMenu"
          class="p-2"
          aria-label="Close menu"
        >
          <div class="w-6 h-6 flex flex-col justify-center">
            <span class="block h-0.5 bg-gray-800 transform rotate-45 translate-y-0"></span>
            <span class="block h-0.5 bg-gray-800 transform -rotate-45 -translate-y-0.5"></span>
          </div>
        </button>
      </div>

      <!-- Menu content -->
      <div class="flex flex-col justify-center items-center h-full px-8">
        <div class="space-y-8 text-center">
          <router-link 
            to="/" 
            @click="closeMobileMenu"
            class="block text-4xl font-bold font-heading text-gray-800 hover:text-gray-600 transition-colors"
          >
            Accueil
          </router-link>
          
          <div v-if="categories && categories.length > 0" class="space-y-6">
            <router-link 
              v-for="category in categories" 
              :key="category.id" 
              :to="`/${category.slug}`" 
              @click="closeMobileMenu"
              class="block text-3xl font-medium text-gray-700 hover:text-gray-900 transition-colors"
            >
              {{ category.name }}
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const categories = ref([])
const isMobileMenuOpen = ref(false)

// Toggle mobile menu
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

// Close mobile menu
const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

// Prevent body scroll when menu is open
watch(isMobileMenuOpen, (isOpen) => {
  if (isOpen) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = 'auto'
  }
})

onMounted(() => {
  // Get data passed from Laravel blade template
  if (window.appData && window.appData.categories) {
    categories.value = window.appData.categories
  }
})
</script>
