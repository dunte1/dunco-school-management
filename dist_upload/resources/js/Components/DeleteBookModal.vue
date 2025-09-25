<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 relative">
      <button @click="$emit('close')" class="absolute top-4 right-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-10 h-10 flex items-center justify-center shadow focus:outline-none focus:ring-2 focus:ring-blue-400 z-10">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-lg font-bold text-navy-900 mb-4">Delete Book</h2>
      <p>Are you sure you want to delete <span class="font-semibold">{{ book.title }}</span>?</p>
      <div class="mt-6 flex justify-end gap-3">
        <button @click="$emit('close')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
        <button @click="$emit('confirm')" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({ book: Object });
</script> 