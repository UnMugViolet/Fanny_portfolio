<template>
  <div id="app">
    <nav class="flex flex-col md:flex-row items-center w-full h-full md:h-10 justify-between md:py-14 px-4 md:px-16 py-8">
      <div class="container mx-auto flex justify-between items-center">
		<a href="/" class="flex items-center">
			<img src="/img/svg/logo.svg" alt="Logo Fanny Séraudie" class="w-9 h-6 mr-2" />
			<h1 class="text-xl font-bold">Fanny Séraudie</h1>
		</a>
		<!-- For loop on all the categories -->
        <div class="space-x-4"> 
          <router-link to="/portfolio" class="hover:text-gray-700 transition-colors">Portfolio</router-link>
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
const currentYear = new Date().getFullYear();


</script>

