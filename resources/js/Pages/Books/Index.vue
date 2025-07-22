<template>
  <LibraryLayout>
    <div class="max-w-6xl mx-auto py-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-bold text-navy-900">Books</h1>
        <div class="flex flex-col sm:flex-row gap-2">
          <input v-model="search" @input="applyFilters" type="text" placeholder="Search by title, author, ISBN..." class="border border-blue-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400" />
          <select v-model="selectedCategory" @change="applyFilters" class="border border-blue-300 rounded-lg px-3 py-2">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <select v-model="selectedAuthor" @change="applyFilters" class="border border-blue-300 rounded-lg px-3 py-2">
            <option value="">All Authors</option>
            <option v-for="auth in authors" :key="auth.id" :value="auth.id">{{ auth.name }}</option>
          </select>
          <select v-model="selectedPublisher" @change="applyFilters" class="border border-blue-300 rounded-lg px-3 py-2">
            <option value="">All Publishers</option>
            <option v-for="pub in publishers" :key="pub.id" :value="pub.id">{{ pub.name }}</option>
          </select>
          <select v-model="selectedStatus" @change="applyFilters" class="border border-blue-300 rounded-lg px-3 py-2">
            <option value="">All Statuses</option>
            <option value="available">Available</option>
            <option value="borrowed">Borrowed</option>
            <option value="reserved">Reserved</option>
            <option value="lost">Lost</option>
          </select>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow" @click="showAddBookModal = true">Add Book</button>
      </div>
      <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
        <thead>
          <tr class="bg-blue-50 text-blue-900">
            <th class="px-4 py-2 text-left">Title</th>
            <th class="px-4 py-2 text-left">Author</th>
            <th class="px-4 py-2 text-left">Category</th>
            <th class="px-4 py-2 text-left">Publisher</th>
            <th class="px-4 py-2 text-left">ISBN</th>
            <th class="px-4 py-2 text-left">Status</th>
            <th class="px-4 py-2 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="book in books.data" :key="book.id" class="border-b last:border-b-0 hover:bg-blue-50">
            <td class="px-4 py-2">{{ book.title }}</td>
            <td class="px-4 py-2">{{ book.author?.name || '-' }}</td>
            <td class="px-4 py-2">{{ book.category?.name || '-' }}</td>
            <td class="px-4 py-2">{{ book.publisher?.name || '-' }}</td>
            <td class="px-4 py-2">{{ book.isbn }}</td>
            <td class="px-4 py-2">
              <span :class="book.status === 'available' ? 'text-green-600 font-bold' : 'text-red-500 font-bold'">
                {{ book.status.charAt(0).toUpperCase() + book.status.slice(1) }}
              </span>
            </td>
            <td class="px-4 py-2 text-right">
              <button class="text-blue-600 hover:underline font-semibold mr-3" @click="viewBook(book)">View</button>
              <button class="text-yellow-600 hover:underline font-semibold mr-3" @click="editBook(book)">Edit</button>
              <button class="text-red-500 hover:underline font-semibold" @click="confirmDelete(book)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Pagination -->
      <div class="flex justify-end mt-6 gap-2">
        <button v-for="pageNum in books.last_page" :key="pageNum" @click="goToPage(pageNum)" :class="['px-3 py-1 rounded', books.current_page === pageNum ? 'bg-blue-600 text-white' : 'bg-gray-200 text-blue-900 hover:bg-blue-100']">
          {{ pageNum }}
        </button>
      </div>
      <!-- Modals -->
      <AddBookModal
        v-if="showAddBookModal"
        :categories="categories"
        :authors="authors"
        :publishers="publishers"
        @close="showAddBookModal = false"
      />
      <ViewBookModal v-if="showViewModal" :book="selectedBook" @close="showViewModal = false" />
      <EditBookModal v-if="showEditModal" :book="selectedBook" :categories="categories" :authors="authors" :publishers="publishers" @close="showEditModal = false" />
      <DeleteBookModal v-if="showDeleteModal" :book="selectedBook" @close="showDeleteModal = false" @confirm="deleteBook" />
    </div>
  </LibraryLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import LibraryLayout from '@/Layouts/LibraryLayout.vue';
import AddBookModal from '@/Components/AddBookModal.vue';
// You will need to create these modal components:
import ViewBookModal from '@/Components/ViewBookModal.vue';
import EditBookModal from '@/Components/EditBookModal.vue';
import DeleteBookModal from '@/Components/DeleteBookModal.vue';
const props = defineProps({
  books: Object,
  categories: Array,
  authors: Array,
  publishers: Array,
  filters: Object,
});
const showAddBookModal = ref(false);
const showViewModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedBook = ref(null);
const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const selectedAuthor = ref(props.filters.author || '');
const selectedPublisher = ref(props.filters.publisher || '');
const selectedStatus = ref(props.filters.status || '');
function applyFilters() {
  router.get('/library/books', {
    search: search.value,
    category: selectedCategory.value,
    author: selectedAuthor.value,
    publisher: selectedPublisher.value,
    status: selectedStatus.value,
  }, { preserveState: true, replace: true });
}
function goToPage(pageNum) {
  router.get('/library/books', {
    search: search.value,
    category: selectedCategory.value,
    author: selectedAuthor.value,
    publisher: selectedPublisher.value,
    status: selectedStatus.value,
    page: pageNum,
  }, { preserveState: true, replace: true });
}
function viewBook(book) {
  selectedBook.value = book;
  showViewModal.value = true;
}
function editBook(book) {
  selectedBook.value = book;
  showEditModal.value = true;
}
function confirmDelete(book) {
  selectedBook.value = book;
  showDeleteModal.value = true;
}
function deleteBook() {
  router.delete(`/library/books/${selectedBook.value.id}`);
  showDeleteModal.value = false;
}
</script>

<style scoped>
.sidebar {
  min-height: 100vh;
}
</style> 