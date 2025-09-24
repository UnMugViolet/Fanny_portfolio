<template>
  <div class="w-full">
    <div class="text-center py-3 md:py-10 px-2 md:px-4">
      <h1 class="text-2xl md:text-4xl font-bold text-brand-burgundy mb-6">
        Bienvenue sur mon portfolio
      </h1>
      <p class="text-sm md:text-lg text-brand-black mb-8 px-2 md:px-11">
        Passionnée de dessin depuis le plus jeune âge et fraîchement diplômée d'une école d'animation 2D, j'ai créé un site qui présente mon travail, que ce soit en animation ou en illustration. <br />
        Cet espace est dédié à partager ma passion avec vous. Bienvenue dans mon univers.
      </p>
    </div>
    <section class="flex md:flex-row flex-col justify-center items-center md:gap-10">
      <div class="w-full md:w-1/2 text-left space-y-4 px-2 md:px-0">
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
              class="w-full text-4xl md:text-7xl uppercase font-bold font-heading mb-2 transition-colors duration-300"
              :style="{ color: getCategoryColor(category.id) }">
              {{ category.name }}
            </h2>
          </router-link>
          <hr class="border-t-3 border-brand-black w-full" />
        </div>
      </div>
      <div class="md:w-1/2 w-full p-4 md:px-25 pt-10">
        <img 
          src="/img/cover-image-homepage.webp" 
          alt="Fanny Séraudie Portfolio Couverture"
          aria-label="Image de couverture du portfolio de Fanny Séraudie" 
          class="w-full h-auto object-cover rounded-lg shadow-lg"
        />

      </div>
    </section>
  </div> 
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const categories = ref([])
const colors = [ 
  '#b7d8fe',
  '#d1c260',
  '#f94e19',
  '#fcd1cd',
  '#c28d1c',
  '#4a90e2',
  '#7ed6df',
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
    return categoryColors.value.get(categoryId) || '#010101'
  }
  return '#010101' // Default color Black
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
