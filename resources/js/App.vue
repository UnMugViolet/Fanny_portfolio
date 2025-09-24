<template>
  <div id="app" class="">
    <Header />
    
    <main class="flex justify-center sm:px-24 px-4">
      <router-view />
    </main>
    
    <footer class="text-black p-4 mt-8">
      <div class="container sm:px-24 px-4 mx-auto text-center">
        <p>&copy;{{ currentYear }} Fanny Séraudie Portfolio. Tous droits réservés.</p>
      </div>
	  <div class="container mx-auto text-center text-sm mt-2">
		 Site web réalisé par <a href="https://www.pauljaguin.com" target="_blank" class="text-blue-400 hover:underline">Paul Jaguin</a>
	  </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Header from '@/components/Header.vue'

const currentYear = new Date().getFullYear()
</script>

