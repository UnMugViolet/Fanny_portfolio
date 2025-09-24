<template>
  <div class="category-page">
    <div class="mx-auto px-4 py-8">
      <header class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ category.name }}</h1>
        <p v-if="category.description" class="text-lg text-gray-600">{{ category.description }}</p>
      </header>

      <section v-if="projects && projects.length > 0" class="projects-grid">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Projects</h2>
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
                  class="px-3 py-1 text-sm rounded-full"
				  :style ="{ backgroundColor: tool.color + '20', color: tool.color, border: '1px solid ' + tool.color }"
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
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const category = ref({})
const projects = ref([])
const loading = ref(true)
const error = ref(null)

const fetchCategoryData = async () => {
	try {
	loading.value = true
	const slug = route.params.slug
	
	// Fetch category data from Laravel API
	const response = await fetch(`/api/categories/${slug}`)
	
	if (!response.ok) {
		throw new Error('Category not found')
	}
	
	const data = await response.json()
	category.value = data.category
	projects.value = data.projects || []
	
	} catch (err) {
	error.value = err.message
	console.error('Error fetching category:', err)
	} finally {
	loading.value = false
	}
}

onMounted(() => {
	fetchCategoryData()
})

</script>

<style scoped>
.projects-grid {
  margin-top: 2rem;
}

.category-page {
  min-height: 100vh;
  background-color: #f9fafb;
}
</style>
