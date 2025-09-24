<template>
  <div id="app">
    <nav class="flex flex-col md:flex-row items-center w-full h-full md:h-10 justify-between md:py-14 px-4 md:px-16 py-8">
      <div class="container mx-auto flex justify-between items-center">
		<a href="/" class="flex items-center">
			<img src="/img/svg/logo.svg" alt="Logo Fanny Séraudie" class="w-9 h-6 mr-2" />
			<h1 class="text-xl font-bold">Fanny Séraudie</h1>
		</a>
		<!-- For loop on all the categories -->
        <div
          v-if="category && category.length > 0" 
          class="space-x-4"> 
          <router-link 
            v-for="cat in category" 
            :key="cat.id" 
            :to="`/categories/${cat.slug}`" 
            class="text-gray-800 hover:text-gray-600 transition-colors"></router-link>
        </div>
      </div>
    </nav>
    
    <main class="flex justify-center sm:px-24 px-4">
      <router-view />
    </main>
    
    <footer class="text-black p-4 mt-8">
      <div class="container sm:px-24 px-4 mx-auto text-center text-sm">
        <p>&copy;{{ currentYear }} Fanny Séraudie Portfolio. Tous droits réservés.</p>
      </div>
	  <div class="container mx-auto text-center text-xs mt-2">
		 Site web réalisé par <a href="https://www.pauljaguin.com" target="_blank" class="text-blue-400 hover:underline">Paul Jaguin</a>
	  </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
const currentYear = new Date().getFullYear();

const route = useRoute()
const categories = ref({})
const loading = ref(true)
const error = ref(null)

const fetchCategoriesData = async () => {
  try {
    loading.value = true
    const slug = route.params.slug

    const response = await fetch(`/api/categories/${slug}`)
    if (!response.ok) {
      throw new Error('Network response was not ok')
    }
    const data = await response.json()
    categories.value = data
  } catch (err) {
    error.value = err.message
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCategoriesData()
})
</script>

