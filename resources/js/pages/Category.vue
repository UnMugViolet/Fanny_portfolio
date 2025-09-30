<template>
  <div class="w-full mx-auto px-4 py-8">
    <section class="w-full mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ category.name }}</h1>
      <p v-if="category.description" class="text-lg text-gray-600">
        {{ category.description }}
      </p>
    </section>

    <section
      v-for="(chunk, chunkIndex) in chunkedProjects"
      :key="chunkIndex"
      class="grid md:grid-cols-4 md:grid-rows-12 grid-cols-1 gap-4 md:gap-8 md:h-svh mb-9 auto-rows-[40svh]"
    >
      <div 
        v-for="(project, index) in chunk" 
        :key="project.id"
        :class="[
          getGridPosition(index),
          'rounded-md bg-center bg-cover transition-transform duration-500 ease-in-out transform hover:scale-105 cursor-pointer'
        ]"
        :style="{ 
          backgroundImage: `url(${project.thumbnail || 'https://placehold.co/600x400?text=' + (index + 1 + chunkIndex * 8)})`
        }"
      ></div>
    </section>

    <div v-if="!projects || projects.length === 0" class="text-center py-12">
      <p class="text-brand-burgundy text-lg">Il n'y a pas encore de projet dans cette catégorie..</p>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const categories = ref([])
const projects = ref([])
const category = ref({})
const loading = ref(false)

const gridPositions = [
  "md:col-start-1 md:col-end-2 md:row-start-1 md:row-end-6 ",
  "md:col-start-2 md:col-end-4 md:row-start-1 md:row-end-5",
  "md:col-start-4 md:col-end-5 md:row-start-1 md:row-end-9",
  "md:col-start-1 md:col-end-2 md:row-start-6 md:row-end-10",
  "md:col-start-2 md:col-end-3 md:row-start-5 md:row-end-10",
  "md:col-start-3 md:col-end-4 md:row-start-5 md:row-end-9",
  "md:col-start-1 md:col-end-3 md:row-start-10 md:row-end-13",
  "md:col-start-3 md:col-end-5 md:row-start-9 md:row-end-13",
]

const loadCategoryData = async (slug) => {
  // Prevent multiple simultaneous requests
  if (loading.value) {
    return
  }
  
  try {
    loading.value = true
    
    // Fetch category data from the server
    const response = await fetch(`/api/categories/${slug}`)
    if (!response.ok) {
      throw new Error('Failed to fetch category data')
    }
    
    const data = await response.json()
    category.value = data.category
    projects.value = data.projects
    
  } catch (error) {
    console.error('Error loading category data:', error)
  } finally {
    loading.value = false
  }
}

// Split projects into chunks of 8
const chunkedProjects = computed(() => {
  const size = 8
  return projects.value.reduce((acc, project, i) => {
    const chunkIndex = Math.floor(i / size)
    if (!acc[chunkIndex]) acc[chunkIndex] = []
    acc[chunkIndex].push(project)
    return acc
  }, [])
})

// Map index to grid position (repeats every 8)
const getGridPosition = (index) => {
  return gridPositions[index % gridPositions.length]
}

const loadInitialData = () => {
  // Load categories from window.appData (for navigation)
  if (window.appData && window.appData.categories) {
    categories.value = window.appData.categories
  }
  
  // If we have initial data from server-side rendering, use it
  if (window.appData && window.appData.category && window.appData.projects) {
    category.value = window.appData.category
    projects.value = window.appData.projects
  } else {
    // Otherwise, fetch data based on current route
    loadCategoryData(route.params.slug)
  }
}

// Watch for route changes and reload data
watch(() => route.params.slug, (newSlug, oldSlug) => {
  if (newSlug && newSlug !== oldSlug) {
    loadCategoryData(newSlug)
  }
}, { immediate: false })

onMounted(() => {
  loadInitialData()
})
</script>

