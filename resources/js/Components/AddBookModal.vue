<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-all">
    <div class="bg-white text-gray-900 rounded-2xl shadow-2xl w-full max-w-lg sm:max-w-xl md:max-w-2xl p-4 sm:p-6 md:p-8 relative border border-gray-200 animate-fadeIn max-h-[90vh] overflow-y-auto">
      <button @click="$emit('close')" class="absolute top-4 right-4 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full w-10 h-10 flex items-center justify-center shadow focus:outline-none focus:ring-2 focus:ring-blue-400 z-10" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-2xl font-bold text-blue-700 mb-6 flex items-center gap-2 uppercase tracking-wide">
        <i class="fas fa-book-medical"></i>
        Add New Book
      </h2>
      <form @submit.prevent="submit" autocomplete="off">
        <div v-if="form.errors && Object.keys(form.errors).length" class="bg-red-50 text-red-700 border border-red-300 rounded-lg px-4 py-3 mb-4 font-semibold">
          <ul class="list-disc pl-5">
            <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
          </ul>
        </div>
        <div v-if="form.success" class="bg-green-50 text-green-700 border border-green-300 rounded-lg px-4 py-3 mb-4 font-semibold">
          {{ form.success }}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
          <div>
            <label class="block text-gray-700 font-bold mb-1">Title</label>
            <input v-model="form.title" type="text" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" required placeholder="Book Title" />
          </div>
          <div>
            <label class="block text-gray-700 font-bold mb-1">ISBN</label>
            <input v-model="form.isbn" type="text" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" required placeholder="ISBN Number" />
          </div>
          <div class="relative flex items-center">
            <label class="block text-gray-700 font-bold mb-1">Barcode
              <span class="ml-1 text-blue-400 cursor-pointer group relative">
                <i class="fas fa-info-circle"></i>
                <span class="absolute left-1/2 -translate-x-1/2 mt-2 w-40 bg-white text-xs text-gray-700 rounded-lg shadow-lg px-3 py-2 opacity-0 group-hover:opacity-100 transition pointer-events-none z-20 border border-gray-200">
                  Optional: Unique barcode for physical tracking
                </span>
              </span>
            </label>
            <input v-model="form.barcode" type="text" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" placeholder="Barcode (optional)" />
          </div>
          <div class="relative flex items-center">
            <label class="block text-gray-700 font-bold mb-1">Edition
              <span class="ml-1 text-blue-400 cursor-pointer group relative">
                <i class="fas fa-info-circle"></i>
                <span class="absolute left-1/2 -translate-x-1/2 mt-2 w-40 bg-white text-xs text-gray-700 rounded-lg shadow-lg px-3 py-2 opacity-0 group-hover:opacity-100 transition pointer-events-none z-20 border border-gray-200">
                  Optional: e.g. 2nd, Revised, etc.
                </span>
              </span>
            </label>
            <input v-model="form.edition" type="text" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" placeholder="Edition (optional)" />
          </div>
          <div>
            <label class="block text-gray-700 font-bold mb-1">Year</label>
            <input v-model="form.year" type="number" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" placeholder="Year (optional)" />
          </div>
          <div>
            <label class="block text-gray-700 font-bold mb-1">Category</label>
            <select v-model="form.category_id" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-900 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 font-semibold transition-all duration-150">
              <option value="">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-gray-700 font-bold mb-1">Author</label>
            <select v-model="form.author_id" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-900 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 font-semibold transition-all duration-150">
              <option value="">Select Author</option>
              <option v-for="author in authors" :key="author.id" :value="author.id">{{ author.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-gray-700 font-bold mb-1">Publisher</label>
            <select v-model="form.publisher_id" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-900 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 font-semibold transition-all duration-150">
              <option value="">Select Publisher</option>
              <option v-for="pub in publishers" :key="pub.id" :value="pub.id">{{ pub.name }}</option>
            </select>
          </div>
          <div class="md:col-span-2">
            <label class="block text-gray-700 font-bold mb-1">Cover Image URL</label>
            <input v-model="form.cover_image" type="text" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" placeholder="https://..." />
          </div>
          <div class="md:col-span-2">
            <label class="block text-gray-700 font-bold mb-1">Description</label>
            <textarea v-model="form.description" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 text-gray-900 placeholder-gray-400 font-semibold transition-all duration-150" rows="2" placeholder="Short description..."></textarea>
          </div>
          <div>
            <label class="block text-gray-700 font-bold mb-1">Status</label>
            <select v-model="form.status" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 text-gray-900 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 font-semibold transition-all duration-150">
              <option value="available">Available</option>
              <option value="borrowed">Borrowed</option>
              <option value="reserved">Reserved</option>
              <option value="lost">Lost</option>
            </select>
          </div>
          <div class="flex flex-col">
            <label class="block text-gray-700 font-bold mb-1">eBook File (PDF, ePub, MP3)</label>
            <label class="relative cursor-pointer w-full">
              <input type="file" class="sr-only" @change="handleFileUpload" />
              <div class="flex items-center justify-between w-full bg-white border border-gray-300 rounded-lg px-4 py-2 focus-within:ring-2 focus-within:ring-blue-400 focus-within:border-blue-500 text-gray-900 font-semibold transition-all duration-150 file:mr-4">
                <span class="truncate text-gray-600" v-if="form.ebook_file">{{ form.ebook_file.name }}</span>
                <span class="truncate text-gray-400" v-else>Choose file...</span>
                <span class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-lg font-semibold ml-4 transition">Browse</span>
              </div>
            </label>
          </div>
        </div>
        <div class="flex justify-end gap-3 mt-8">
          <button type="button" class="px-6 py-2 rounded-lg bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition shadow" @click="$emit('close')">
            Cancel
          </button>
          <button type="submit" class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg transition flex items-center gap-2" :disabled="form.processing">
            <i class="fas fa-plus"></i>
            <span v-if="!form.processing">Add Book</span>
            <span v-else>Adding...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
const props = defineProps({
  categories: Array,
  authors: Array,
  publishers: Array,
});
const form = useForm({
  title: '',
  isbn: '',
  barcode: '',
  edition: '',
  year: '',
  category_id: '',
  author_id: '',
  publisher_id: '',
  cover_image: '',
  description: '',
  status: 'available',
  ebook_file: null,
  success: '',
});
function handleFileUpload(e) {
  form.ebook_file = e.target.files[0];
}
function submit() {
  form.post(route('library.books.store'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset();
      form.clearErrors();
      form.success = 'Book added successfully!';
      setTimeout(() => { form.success = ''; emit('close'); }, 1200);
    },
  });
}
const emit = defineEmits(['close']);
// NOTE: For best accessibility, test this modal on multiple screens and lighting conditions.
// TODO: For sidebar, increase contrast between active/inactive items for better orientation (edit in sidebar component).
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(40px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
  animation: fadeIn 0.3s cubic-bezier(0.4,0,0.2,1);
}
input, select, textarea {
  font-size: 1rem;
}
</style> 