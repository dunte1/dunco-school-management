<template>
  <div class="relative w-full max-w-md">
    <div class="flex items-center bg-white border border-blue-300 rounded-full shadow px-4 py-2 focus-within:ring-2 focus-within:ring-blue-400 transition-all">
      <i class="fas fa-search text-blue-400 mr-2"></i>
      <input
        v-model="query"
        @input="onInput"
        @keydown.down.prevent="moveSelection(1)"
        @keydown.up.prevent="moveSelection(-1)"
        @keydown.enter.prevent="selectResult()"
        @keydown.esc="closeDropdown"
        type="text"
        class="flex-1 bg-transparent outline-none text-blue-900 placeholder-blue-300 font-medium"
        placeholder="Search books by title, author, or ISBN..."
        autocomplete="off"
        aria-label="Search books"
        :aria-expanded="showDropdown.toString()"
        :aria-activedescendant="activeDescendantId"
      />
      <span v-if="loading" class="ml-2 animate-spin text-blue-400"><i class="fas fa-spinner"></i></span>
    </div>
    <div v-if="showDropdown && (localResults.length || openLibraryResults.length)" class="absolute z-50 mt-2 w-full bg-white/80 border border-blue-200 rounded-xl shadow-lg max-h-80 overflow-y-auto backdrop-blur-md">
      <div v-if="localResults.length" class="p-2">
        <div class="text-xs text-blue-500 font-bold mb-1">Local Books</div>
        <div v-for="(book, idx) in localResults" :key="'local-' + book.id" :id="'result-local-' + idx" :class="['flex items-center gap-2 px-2 py-2 rounded cursor-pointer transition-all', selectedIndex === idx && selectedGroup === 'local' ? 'bg-blue-100 shadow' : 'hover:bg-blue-50']" @click="goToLocalBook(book)" @mouseenter="setSelection('local', idx)" tabindex="0" role="option" :aria-selected="selectedIndex === idx && selectedGroup === 'local'">
          <img v-if="book.cover_image" :src="book.cover_image" alt="cover" class="w-8 h-10 object-cover rounded shadow mr-2" />
          <i v-else class="fas fa-book text-blue-400"></i>
          <span class="font-semibold text-blue-900">{{ book.title }}</span>
          <span class="text-xs text-blue-500 ml-2">by {{ book.author?.name || 'Unknown' }}</span>
        </div>
      </div>
      <div v-if="openLibraryResults.length" class="p-2 border-t border-blue-100">
        <div class="text-xs text-blue-500 font-bold mb-1">Open Library</div>
        <div v-for="(book, idx) in openLibraryResults" :key="'ol-' + book.key" :id="'result-ol-' + idx"
          :class="['flex items-center gap-2 px-2 py-2 rounded transition-all',
            isOLBookReadable(book) ? 'cursor-pointer ' + (selectedIndex === idx && selectedGroup === 'ol' ? 'bg-blue-100 shadow' : 'hover:bg-blue-50') : 'opacity-60 cursor-not-allowed']"
          @click="isOLBookReadable(book) ? goToOpenLibraryBook(book) : null"
          @mouseenter="setSelection('ol', idx)"
          tabindex="0" role="option" :aria-selected="selectedIndex === idx && selectedGroup === 'ol'">
          <img v-if="book.cover_i" :src="`https://covers.openlibrary.org/b/id/${book.cover_i}-S.jpg`" alt="cover" class="w-8 h-10 object-cover rounded shadow mr-2" />
          <i v-else class="fas fa-globe text-blue-400"></i>
          <span class="font-semibold text-blue-900">{{ book.title }}</span>
          <span class="text-xs text-blue-500 ml-2">by {{ book.author_name?.[0] || 'Unknown' }}</span>
          <span v-if="!isOLBookReadable(book)" class="ml-2 text-xs text-gray-400" title="Not available to read">(Not available to read)</span>
          <a v-if="!isOLBookReadable(book)" :href="`https://openlibrary.org${book.key}`" target="_blank" class="ml-2 text-xs text-blue-400 underline hover:text-blue-600">View on Open Library</a>
        </div>
      </div>
      <div v-if="!localResults.length && !openLibraryResults.length && !loading" class="p-4 text-center text-blue-400">No results found.</div>
    </div>
    <BookReaderModal :open="showReaderModal" :book="selectedOLBook" :onClose="closeReaderModal" />
  </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import BookReaderModal from './BookReaderModal.vue';

const query = ref('');
const loading = ref(false);
const showDropdown = ref(false);
const localResults = ref([]);
const openLibraryResults = ref([]);
let debounceTimeout = null;

// Keyboard navigation state
const selectedIndex = ref(-1);
const selectedGroup = ref('local'); // 'local' or 'ol'
const totalResults = computed(() => localResults.value.length + openLibraryResults.value.length);
const activeDescendantId = computed(() => {
  if (selectedIndex.value === -1) return null;
  if (selectedGroup.value === 'local') return `result-local-${selectedIndex.value}`;
  if (selectedGroup.value === 'ol') return `result-ol-${selectedIndex.value}`;
  return null;
});

const showReaderModal = ref(false);
const selectedOLBook = ref({});

function isOLBookReadable(book) {
  return !!(book.lending_edition_s || book.ia || book.public_scan_b);
}

function onInput() {
  clearTimeout(debounceTimeout);
  selectedIndex.value = -1;
  selectedGroup.value = 'local';
  if (!query.value) {
    showDropdown.value = false;
    localResults.value = [];
    openLibraryResults.value = [];
    return;
  }
  loading.value = true;
  showDropdown.value = true;
  debounceTimeout = setTimeout(async () => {
    try {
      // Local search
      const localRes = await fetch(`/library/books/search?q=${encodeURIComponent(query.value)}`);
      localResults.value = localRes.ok ? await localRes.json() : [];
    } catch (e) {
      localResults.value = [];
    }
    try {
      // Open Library search
      const olRes = await fetch(`https://openlibrary.org/search.json?q=${encodeURIComponent(query.value)}&limit=5`);
      const olData = olRes.ok ? await olRes.json() : { docs: [] };
      openLibraryResults.value = olData.docs.slice(0, 5).map(doc => ({ ...doc, olid: (doc.edition_key && doc.edition_key[0]) ? doc.edition_key[0] : null }));
    } catch (e) {
      openLibraryResults.value = [];
    }
    loading.value = false;
    await nextTick();
  }, 400);
}

function moveSelection(direction) {
  const localLen = localResults.value.length;
  const olLen = openLibraryResults.value.length;
  let idx = selectedIndex.value;
  let group = selectedGroup.value;
  if (group === 'local') {
    idx += direction;
    if (idx < 0) {
      idx = olLen > 0 ? 0 : localLen - 1;
      group = olLen > 0 ? 'ol' : 'local';
    } else if (idx >= localLen) {
      idx = 0;
      group = olLen > 0 ? 'ol' : 'local';
    }
  } else if (group === 'ol') {
    idx += direction;
    if (idx < 0) {
      idx = localLen > 0 ? localLen - 1 : 0;
      group = localLen > 0 ? 'local' : 'ol';
    } else if (idx >= olLen) {
      idx = 0;
      group = localLen > 0 ? 'local' : 'ol';
    }
  }
  selectedIndex.value = idx;
  selectedGroup.value = group;
}

function setSelection(group, idx) {
  selectedGroup.value = group;
  selectedIndex.value = idx;
}

function selectResult() {
  if (selectedGroup.value === 'local' && localResults.value[selectedIndex.value]) {
    goToLocalBook(localResults.value[selectedIndex.value]);
  } else if (selectedGroup.value === 'ol' && openLibraryResults.value[selectedIndex.value]) {
    if (isOLBookReadable(openLibraryResults.value[selectedIndex.value])) {
      goToOpenLibraryBook(openLibraryResults.value[selectedIndex.value]);
    }
  }
}

function closeDropdown() {
  showDropdown.value = false;
}

function goToLocalBook(book) {
  router.visit(`/library/books/${book.id}`);
  showDropdown.value = false;
}

function goToOpenLibraryBook(book) {
  selectedOLBook.value = book;
  showReaderModal.value = true;
  showDropdown.value = false;
}

function closeReaderModal() {
  showReaderModal.value = false;
  selectedOLBook.value = {};
}
</script> 