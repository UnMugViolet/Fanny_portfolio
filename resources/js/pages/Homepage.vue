<template>
  <div class="w-full">
    <div class="text-center py-3 md:py-16 md:px-4">
      <h1 class="text-2xl md:text-4xl font-bold text-gray-800 mb-6">Bienvenue sur mon portfolio</h1>
      <p class="text-sm md:text-lg text-gray-600 mb-8 px-2 md:px-11">
        Passionnée de dessin depuis le plus jeune âge et fraîchement diplômée d'une école d'animation 2D, j'ai créé un site qui présente mon travail, que ce soit en animation ou en illustration. <br />
        Cet espace est dédié à partager ma passion avec vous. Bienvenue dans mon univers.
      </p>
    </div>
    
    <div class="h-60vh w-full text-left space-y-4">
      <div 
        v-for="category in categories" :key="category.id" 
        class="flex flex-col w-full py md:py-3"> 
        <router-link 
          :to="{ name: 'Category', params: { slug: category.slug } }"
          class="block w-full"
          @click="handleCategoryClick"
          @mouseenter="handleMouseEnter(category.id)"
          @mouseleave="handleMouseLeave(category.id)"
        >
          <h2 
            class="w-full text-5xl md:text-9xl uppercase font-bold font-heading mb-2 transition-colors duration-300"
            :style="{ color: getCategoryColor(category.id) }">
            {{ category.name }}
          </h2>
        </router-link>
        <hr class="border-t-3 border-black w-full" />
      </div>
    </div>
  </div> 
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const categories = ref([])
const colors = [
  '#FF5733', // Red-Orange
  '#FF33A8', // Hot Pink
  '#75FF33', // Lime Green
  '#FF8F33', // Orange
  '#3380FF', // Blue
  '#FF3366', // Red-Pink
  '#33FFAA', // Mint Green
  '#8A33FF', // Purple
  '#FFD700', // Gold
  '#FF6B35', // Coral
]

// Track hover states for each category
const hoveredCategories = ref(new Set())
const categoryColors = ref(new Map())

// Get data passed from Laravel blade template
const loadCategories = () => {
  if (window.appData && window.appData.categories) {
    categories.value = window.appData.categories
    console.log('Categories loaded:', categories.value)
  }
}

// Get a random color for a category
const getRandomColor = () => {
  return colors[Math.floor(Math.random() * colors.length)]
}

// Get the color for a specific category (black by default, random on hover)
const getCategoryColor = (categoryId) => {
  if (hoveredCategories.value.has(categoryId)) {
    return categoryColors.value.get(categoryId) || '#000'
  }
  return '#000' // Default black color
}

// Handle mouse enter - generate new random color each time
const handleMouseEnter = (categoryId) => {
  const randomColor = getRandomColor()
  categoryColors.value.set(categoryId, randomColor)
  hoveredCategories.value.add(categoryId)
}

// Handle mouse leave - remove from hovered set
const handleMouseLeave = (categoryId) => {
  hoveredCategories.value.delete(categoryId)
}

// Handle category click with debouncing to prevent multiple rapid clicks
let clickTimeout = null
const handleCategoryClick = (event) => {
  if (clickTimeout) {
    clearTimeout(clickTimeout)
  }
  
  clickTimeout = setTimeout(() => {
    // Let router-link handle the navigation naturally
  }, 100)
}

onMounted(() => {
  loadCategories()
})
</script>
