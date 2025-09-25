<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
      <button @click="$emit('close')" class="absolute top-4 right-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-10 h-10 flex items-center justify-center shadow focus:outline-none focus:ring-2 focus:ring-blue-400 z-10">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-xl font-bold text-navy-900 mb-4">Book Details</h2>
      <div class="space-y-2">
        <div><span class="font-semibold text-blue-900">Title:</span> {{ book.title }}</div>
        <div><span class="font-semibold text-blue-900">Author:</span> {{ book.author?.name || '-' }}</div>
        <div><span class="font-semibold text-blue-900">Category:</span> {{ book.category?.name || '-' }}</div>
        <div><span class="font-semibold text-blue-900">Publisher:</span> {{ book.publisher?.name || '-' }}</div>
        <div><span class="font-semibold text-blue-900">ISBN:</span> {{ book.isbn }}</div>
        <div><span class="font-semibold text-blue-900">Status:</span> <span :class="book.status === 'available' ? 'text-green-600 font-bold' : 'text-red-500 font-bold'">{{ book.status }}</span></div>
        <div v-if="book.description"><span class="font-semibold text-blue-900">Description:</span> {{ book.description }}</div>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({ book: Object });
</script> 