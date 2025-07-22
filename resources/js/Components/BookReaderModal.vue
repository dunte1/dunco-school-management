<template>
  <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[80vh] flex flex-col relative">
      <button @click="onClose" class="absolute top-4 right-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-10 h-10 flex items-center justify-center shadow focus:outline-none focus:ring-2 focus:ring-blue-400 z-10">
        <i class="fas fa-times"></i>
      </button>
      <div class="flex-1 flex flex-col">
        <div class="p-4 border-b border-blue-100 flex items-center gap-3">
          <img v-if="book.cover_i" :src="`https://covers.openlibrary.org/b/id/${book.cover_i}-M.jpg`" alt="cover" class="w-12 h-16 object-cover rounded shadow" />
          <div>
            <div class="font-bold text-lg text-blue-900">{{ book.title }}</div>
            <div class="text-blue-500 text-sm">by {{ book.author_name?.[0] || 'Unknown' }}</div>
          </div>
        </div>
        <div class="flex-1">
          <iframe
            v-if="book.olid"
            :src="`https://openlibrary.org/books/${book.olid}?embed=true#reader`"
            class="w-full h-full rounded-b-2xl border-0"
            allowfullscreen
          ></iframe>
          <div v-else class="flex items-center justify-center h-full text-blue-400 font-semibold">
            No online reader available for this book.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  open: Boolean,
  book: Object,
  onClose: Function,
});
</script> 