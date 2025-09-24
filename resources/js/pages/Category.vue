<template>
  <div class="category-page">
    <div class="mx-auto px-4 py-8">
      <header class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ category.name }}</h1>
        <p v-if="category.description" class="text-lg text-gray-600">{{ category.description }}</p>
      </header>

      <section v-if="projects && projects.length > 0" class="projects-grid">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="project in projects" 
            :key="project.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
          >
            <div v-if="project.image" class="h-48 bg-gray-200">
              <img 
                :src="project.image" 
                :alt="project.title"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ project.title }}</h3>
              <p v-if="project.description" class="text-gray-600 mb-4">{{ project.description }}</p>
              <div v-if="project.tools && project.tools.length > 0" class="flex flex-wrap gap-2">
                <span 
                  v-for="tool in project.tools" 
                  :key="tool.id"
				          :style ="{ backgroundColor: tool.color + '20', color: tool.color, border: '1px solid ' + tool.color }"
                  class="px-3 py-1 text-sm rounded-full"
                >
                  {{ tool.name }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div v-else class="text-center py-12">
        <p class="text-gray-500 text-lg">No projects found in this category yet.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const categories = ref([])
const projects = ref([])
const category = ref({})
const tools = ref([])
const loading = ref(false)

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

<style scoped>
.projects-grid {
  margin-top: 2rem;
}

.category-page {
  min-height: 100vh;
}
</style>
