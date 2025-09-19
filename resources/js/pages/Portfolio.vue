<template>
  <div class="w-full">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Portfolio</h1>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="project in projects" 
        :key="project.id"
        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
      >
        <img 
          :src="project.image" 
          :alt="project.title"
          class="w-full h-48 object-cover"
        >
        <div class="p-4">
          <h3 class="text-xl font-semibold mb-2">{{ project.title }}</h3>
          <div class="flex flex-wrap gap-2 mb-3">
            <span 
              v-for="tool in project.tools"
              :key="tool"
              class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm"
            >
              {{ tool }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

import { ref } from 'vue'

const projects = ref([
        {
          id: 1,
          title: 'Dessin',
          description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
          image: 'https://placehold.co/600x400?text=Hello+World',
          tools: ['Crayon', 'Pinceau', 'Aquarelle'],
          github: '#'
        },
        {
          id: 2,
          title: 'Dessin 2',
          description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
          image: 'https://placehold.co/600x400?text=Hello+World',
          tools: ['Crayon', 'Pinceau', 'Aquarelle'],
        },
        {
          id: 3,
          title: 'Dessin 3',
          description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
          image: 'https://placehold.co/600x400?text=Hello+World',
          tools: ['Crayon', 'Pinceau', 'Aquarelle'],
        },
        {
          id: 4,
          title: 'Dessin 4',
          description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
          image: 'https://placehold.co/600x400?text=Hello+World',
          tools: ['Crayon', 'Pinceau', 'Aquarelle'],
        },
        {
          id: 5,
          title: 'Dessin 5',
          description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
          image: 'https://placehold.co/600x400?text=Hello+World',
          tools: ['Crayon', 'Pinceau', 'Aquarelle'],
        },
        {
          id: 6,
          title: 'Dessin 6',
          description: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
          image: 'https://placehold.co/600x400?text=Hello+World',
          tools: ['Crayon', 'Pinceau', 'Aquarelle'],
        }
	])
</script>
