<template>
  <div class="w-full mx-auto px-4 py-8">
    <section class="w-full mb-8">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ category.name }}</h1>
      <p v-if="category.description" class="text-lg text-gray-600">
        {{ category.description }}
      </p>
    </section>

    <section v-for="(chunk, chunkIndex) in chunkedProjects" :key="chunkIndex"
      class="grid md:grid-cols-4 md:grid-rows-12 grid-cols-1 gap-8 md:gap-4 lg:gap-8 md:h-svh mb-9 auto-rows-[55svh]">
      <div v-for="(project, index) in chunk" :key="project.id" @click="openModal(project)" :class="[
        getGridPosition(index),
        'rounded-md bg-center bg-cover transition-transform duration-500 ease-in-out transform hover:scale-105 cursor-pointer'
      ]"
        :style="{ backgroundImage: `url(${project.thumbnail?.[0]?.url || 'https://placehold.co/600x400?text=' + (index + 1 + chunkIndex * 8)})` }">
      </div>
    </section>

    <div v-if="!projects || projects.length === 0" class="text-center py-12">
      <p class="text-brand-burgundy text-lg">Il n'y a pas encore de projet dans cette catégorie..</p>
    </div>

    <transition name="fade">
      <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-10">
        <div
          @click="closeModal"
          class="absolute inset-0 bg-black opacity-75"></div>
        <div :class="[
          'bg-white h-5/6 z-20 rounded-md',
          modalWidthClass,
          currentProject.youtube_url ? 'flex flex-col' : 'flex flex-col-reverse lg:flex-row'
        ]">
          <!-- Description Section (top when video present) -->
          <div v-if="currentProject.youtube_url" 
               class="w-full p-4 md:pt-8 md:px-8 md:h-5/12 overflow-y-auto scrollbar-thin">
            <div class="flex justify-end">
              <button @click="closeModal"
                class="text-black text-2xl transition-colors duration-200 hover:text-zinc-800">
                &#10005;
              </button>
            </div>
            <h2 class="text-brand-burgundy text-2xl md:text-4xl font-semibold mb-4 md:mb-6">{{ currentProject.title }}</h2>
            <div v-if="currentProject.tools && currentProject.tools.length" class="mb-4 flex flex-wrap gap-2">
              <span v-for="tool in currentProject.tools" :key="tool.id" :style="{
                border: `2px solid ${tool.color}`,
                backgroundColor: `${tool.color}22`,
                color: tool.color,
              }"
                class="text-sm font-medium px-3 py-1 rounded-full mr mb md:mr-2 md:mb-2">
                {{ tool.name }}
              </span>
            </div>
            <div class="text-black" v-html="currentProject.description"></div>
          </div>

          <!-- Video Section (full width bottom when present) -->
          <div v-if="currentProject.youtube_url" 
               class="w-full md:flex-1 flex items-center justify-center p-4 h-2/3 md:h-7/12">
              <lite-youtube 
                v-if="currentProject.youtube_url"
                :videoid="extractVideoId(currentProject.youtube_url)"
                :playlabel="`Play: ${currentProject.title}`"
                class="w-full h-full"
              ></lite-youtube>
          </div>

          <!-- Original Layout (when no video) -->
          <template v-else>
            <!-- Images Section -->
            <div class="w-full h-2/3 lg:h-full lg:w-2/3 flex flex-col overflow-hidden">
              <div v-if="currentProject.images && currentProject.images.length" 
                   class="flex-1 overflow-y-auto scrollbar-thin">
                <div v-for="(image, index) in currentProject.images" 
                     :key="index" 
                     class="w-full h-full flex justify-center mb-4 last:mb-0 relative overflow-hidden"
                     @wheel="(e) => handleWheel(e, image.url)"
                     @mousedown="(e) => handleMouseDown(e, image.url)"
                     @mousemove="(e) => handleMouseMove(e, image.url)">
                  
                  <!-- Zoom Controls - Only show for active image -->
                  <div v-if="isImageActive(image.url)" 
                       class="absolute top-4 right-4 z-10 flex flex-col gap-2 bg-black bg-opacity-50 rounded-lg p-2">
                    <button @click="() => {
                      initZoomState(image.url);
                      const state = zoomStates[image.url];
                      state.scale = Math.min(3, state.scale + 0.2);
                    }" 
                    class="text-white hover:text-gray-300 w-8 h-8 flex items-center justify-center text-lg font-bold">
                      +
                    </button>
                    <button @click="() => {
                      initZoomState(image.url);
                      const state = zoomStates[image.url];
                      state.scale = Math.max(1, state.scale - 0.2);
                      if (state.scale === 1) {
                        state.translateX = 0;
                        state.translateY = 0;
                      }
                    }" 
                    class="text-white hover:text-gray-300 w-8 h-8 flex items-center justify-center text-lg font-bold">
                      −
                    </button>
                    <button @click="() => resetZoom(image.url)" 
                    class="text-white hover:text-gray-300 w-8 h-8 flex items-center justify-center text-sm">
                      ⌂
                    </button>
                  </div>

                  <!-- Click indicator for non-active images -->
                  <div v-if="!isImageActive(image.url)" 
                       class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-200">
                    <div class="text-white opacity-0 hover:opacity-100 transition-opacity duration-200 bg-black bg-opacity-50 rounded-lg px-4 py-2">
                      Click to zoom
                    </div>
                  </div>

                  <img :src="image.url"
                       :alt="image.alt || currentProject.title + '_' + index" 
                       :class="[
                         'max-w-full h-full object-contain transition-transform duration-150 ease-out',
                         isImageActive(image.url) ? 'cursor-move' : 'cursor-pointer'
                       ]"
                       :style="{ 
                         transform: getImageTransform(image.url),
                         transformOrigin: 'center center'
                       }"
                       @click="(e) => handleImageClick(image.url, e)"
                       draggable="false"/>
                </div>
              </div>
            </div>
            
            <!-- Description Section -->
            <div class="w-full h-1/3 lg:h-full lg:w-1/3 relative flex flex-col overflow-hidden">
              <div class="sticky top-0 right-0 z-30 flex justify-end bg-white">
                <button @click="closeModal"
                  class="absolute p-5 text-black text-2xl transition-colors duration-200 hover:text-zinc-800">
                  &#10005;
                </button>
              </div>
              <div class="flex-1 pt-10 md:py-14 px-8 overflow-y-auto scrollbar-thin">
                <h2 class="text-brand-burgundy text-2xl md:text-4xl font-semibold mb-4 md:mb-6">{{ currentProject.title }}</h2>
                <div v-if="currentProject.tools && currentProject.tools.length" class="mb-2 md:mb-4 flex flex-wrap gap-2">
                  <span v-for="tool in currentProject.tools" :key="tool.id" :style="{
                    border: `2px solid ${tool.color}`,
                    backgroundColor: `${tool.color}22`,
                    color: tool.color,
                  }"
                    class="text-sm font-medium px-3 py-1 rounded-full mr-2 mb-2 transition-colors duration-200 hover:bg-opacity-30">
                    {{ tool.name }}
                  </span>
                </div>
                <div class="text-black mb-4" v-html="currentProject.description"></div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </transition>
  </div>
</template>


<script setup>
import { ref, computed } from 'vue'
import 'lite-youtube-embed/src/lite-yt-embed.css'
import 'lite-youtube-embed/src/lite-yt-embed.js'

const category = ref(window.appData.category || [])
const projects = ref(window.appData.projects || [])
const showModal = ref(false)
const currentProject = ref({})
const imageOrientations = ref({})
const imagesLoaded = ref(false)

// Zoom state management
const zoomStates = ref({})
const isDragging = ref(false)
const lastMousePos = ref({ x: 0, y: 0 })
const activeImageUrl = ref(null)

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

// Determine if modal should use portrait layout
const isPortraitLayout = computed(() => {
  if (!currentProject.value.images || currentProject.value.images.length === 0) {
    return false
  }
  
  // Check if any image is portrait (height > width)
  return currentProject.value.images.some(image => {
    const orientation = imageOrientations.value[image.url]
    return orientation && orientation.height > orientation.width
  })
})

// Get modal width class based on layout
const modalWidthClass = computed(() => {
  if (currentProject.value.youtube_url) {
    return 'w-10/12 max-w-[90rem]'
  }
  
  return isPortraitLayout.value ? 'w-10/12 lg:w-8/12  md:max-w-7xl' : 'w-11/12 '
})

// Load image dimensions
const loadImageDimensions = (imageUrl) => {
  return new Promise((resolve) => {
    const img = new Image()
    img.onload = () => {
      imageOrientations.value[imageUrl] = {
        width: img.naturalWidth,
        height: img.naturalHeight
      }
      resolve()
    }
    img.onerror = () => {
      imageOrientations.value[imageUrl] = {
        width: 1,
        height: 1
      }
      resolve()
    }
    img.src = imageUrl
  })
}

// Initialize zoom state for an image
const initZoomState = (imageUrl) => {
  if (!zoomStates.value[imageUrl]) {
    zoomStates.value[imageUrl] = {
      scale: 1,
      translateX: 0,
      translateY: 0
    }
  }
}

// Handle image click to activate zoom
const handleImageClick = (imageUrl, event) => {
  // Prevent modal close when clicking on image
  event.stopPropagation()
  
  // Set this image as active for zooming
  activeImageUrl.value = imageUrl
  initZoomState(imageUrl)
}

// Check if an image is active for zooming
const isImageActive = (imageUrl) => {
  return activeImageUrl.value === imageUrl
}

// Get transform style for an image
const getImageTransform = (imageUrl) => {
  const state = zoomStates.value[imageUrl]
  if (!state) return ''
  return `scale(${state.scale}) translate(${state.translateX}px, ${state.translateY}px)`
}

// Handle wheel zoom
const handleWheel = (event, imageUrl) => {
  // Only allow zoom if this image is active
  if (activeImageUrl.value !== imageUrl) {
    return // Allow normal scrolling
  }
  
  const container = event.currentTarget
  const rect = container.getBoundingClientRect()
  
  // Check if mouse is over the container (not scrolling past it)
  const mouseX = event.clientX
  const mouseY = event.clientY
  
  if (mouseX >= rect.left && mouseX <= rect.right && mouseY >= rect.top && mouseY <= rect.bottom) {
    initZoomState(imageUrl)
    const state = zoomStates.value[imageUrl]
    
    const delta = event.deltaY > 0 ? -0.1 : 0.1
    const newScale = Math.max(1, Math.min(3, state.scale + delta))
    
    // Only prevent default if we're actually zooming (scale > 1) or can zoom in
    if (state.scale > 1 || (state.scale === 1 && delta > 0)) {
      event.preventDefault()
      state.scale = newScale
      
      // Reset position when zooming out to 1
      if (newScale === 1) {
        state.translateX = 0
        state.translateY = 0
      }
    }
  } else {
    // Mouse is outside container, allow normal scrolling
    return true
  }
}

// Handle mouse down for dragging
const handleMouseDown = (event, imageUrl) => {
  // Only allow dragging if this image is active and zoomed
  if (activeImageUrl.value !== imageUrl) return
  
  const state = zoomStates.value[imageUrl]
  if (!state || state.scale <= 1) return
  
  isDragging.value = true
  lastMousePos.value = { x: event.clientX, y: event.clientY }
  event.preventDefault()
}

// Handle mouse move for dragging
const handleMouseMove = (event, imageUrl) => {
  if (!isDragging.value) return
  
  const state = zoomStates.value[imageUrl]
  if (!state) return
  
  const deltaX = event.clientX - lastMousePos.value.x
  const deltaY = event.clientY - lastMousePos.value.y
  
  state.translateX += deltaX / state.scale
  state.translateY += deltaY / state.scale
  
  lastMousePos.value = { x: event.clientX, y: event.clientY }
}

// Handle mouse up
const handleMouseUp = () => {
  isDragging.value = false
}

// Reset zoom for an image
const resetZoom = (imageUrl) => {
  initZoomState(imageUrl)
  const state = zoomStates.value[imageUrl]
  state.scale = 1
  state.translateX = 0
  state.translateY = 0
  // Clear active image when resetting
  if (activeImageUrl.value === imageUrl) {
    activeImageUrl.value = null
  }
}

const handleEsc = (e) => {
  if (e.key === 'Escape' && showModal.value) {
    closeModal();
  }
};

const openModal = async (project) => {
  currentProject.value = project;
  imagesLoaded.value = false;
  
  // Load image dimensions if project has images
  if (project.images && project.images.length > 0) {
    await Promise.all(project.images.map(image => loadImageDimensions(image.url)));
    // Initialize zoom states for all images
    project.images.forEach(image => initZoomState(image.url));
    imagesLoaded.value = true;
  }
  
  showModal.value = true;
  document.body.classList.add('overflow-hidden');
  window.addEventListener('keydown', handleEsc);
  window.addEventListener('mouseup', handleMouseUp);
};

const closeModal = () => {
  showModal.value = false;
  document.body.classList.remove('overflow-hidden');
  window.removeEventListener('keydown', handleEsc);
  window.removeEventListener('mouseup', handleMouseUp);
  isDragging.value = false;
  activeImageUrl.value = null;
};


const extractVideoId = (url) => {
  const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
  const match = url.match(regex)
  return match ? match[1] : null
}

</script>

<style scoped>
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity .5s;
}

:deep(lite-youtube) {
  width: 100% !important;
  height: 100% !important;
  max-width: none !important;
  aspect-ratio: unset !important;
}

:deep(lite-youtube::before) {
  padding-bottom: 0 !important;
}

:deep(lite-youtube > iframe) {
  width: 100% !important;
  height: 100% !important;
}
</style>
