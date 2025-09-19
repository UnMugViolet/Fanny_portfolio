<template>
  <div class="text-center">
    <h1 class="text-4xl font-bold text-gray-800 mb-6">Bienvenue sur mon portfolio</h1>
    <p class="text-lg text-gray-600 mb-8">
      Découvrez mon travail, mes compétences et ma passion pour la création d'expériences numériques incroyables.
    </p>
    
    <div class="grid md:grid-cols-3 gap-6 mt-12">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-3">Illustration</h3>
        <p class="text-gray-600">Création d'illustrations et de visuels captivants pour divers supports.</p>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-3">Jeunesse</h3>
        <p class="text-gray-600">Création de contenus et d'expériences pour le public jeune.</p>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-3">Animation</h3>
        <p class="text-gray-600">Création d'animations pour divers supports numériques.</p>
      </div>
    </div>
    
    <div class="mt-12">
      <router-link 
        to="/portfolio" 
        class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-block"
      >
        Voir mon portfolio
      </router-link>
    </div>
  </div>
</template>

<script setup>

</script>
