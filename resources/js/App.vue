<template>
  <div id="app" class="">
    <Header />
    
    <main class="flex justify-center lg:px-24 px-4">
      <router-view />
    </main>
    
    <Footer/>
  </div>
</template>

<script setup>
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

</script>

