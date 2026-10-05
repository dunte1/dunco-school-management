<template>
  <AppLayout>
    <div class="student-library">
      <div class="page-header">
        <h1>My Library</h1>
        <p>Browse, borrow, and manage your library books</p>
      </div>
      
      <div class="library-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon books">
              <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ borrowedBooks.length }}</div>
              <div class="stat-label">Books Borrowed</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon due">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ overdueBooks.length }}</div>
              <div class="stat-label">Overdue Books</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon fines">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">${{ totalFines.toFixed(2) }}</div>
              <div class="stat-label">Total Fines</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon history">
              <i class="fas fa-history"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ readingHistory.length }}</div>
              <div class="stat-label">Books Read</div>
            </div>
          </div>
        </div>
        
        <div class="search-section">
          <div class="search-bar">
            <i class="fas fa-search"></i>
            <input 
              type="text" 
              placeholder="Search books, authors, ISBN..." 
              v-model="searchQuery"
            >
            <button class="search-btn" @click="searchBooks">
              <i class="fas fa-search"></i>
              Search
            </button>
          </div>
          
          <div class="search-filters">
            <select v-model="categoryFilter">
              <option value="">All Categories</option>
              <option value="fiction">Fiction</option>
              <option value="non-fiction">Non-Fiction</option>
              <option value="science">Science</option>
              <option value="history">History</option>
              <option value="biography">Biography</option>
            </select>
            
            <select v-model="availabilityFilter">
              <option value="">All Availability</option>
              <option value="available">Available</option>
              <option value="borrowed">Borrowed</option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="library-tabs">
        <button 
          :class="{ active: activeTab === 'borrowed' }"
          @click="activeTab = 'borrowed'"
        >
          Borrowed Books
        </button>
        <button 
          :class="{ active: activeTab === 'catalog' }"
          @click="activeTab = 'catalog'"
        >
          Book Catalog
        </button>
        <button 
          :class="{ active: activeTab === 'history' }"
          @click="activeTab = 'history'"
        >
          Reading History
        </button>
        <button 
          :class="{ active: activeTab === 'reservations' }"
          @click="activeTab = 'reservations'"
        >
          Reservations
        </button>
      </div>
      
      <!-- Borrowed Books Tab -->
      <div v-if="activeTab === 'borrowed'" class="borrowed-tab">
        <div class="books-grid">
          <div 
            v-for="book in borrowedBooks" 
            :key="book.id"
            class="book-card borrowed"
          >
            <div class="book-cover">
              <img :src="book.cover" :alt="book.title">
            </div>
            
            <div class="book-info">
              <h3>{{ book.title }}</h3>
              <p class="author">{{ book.author }}</p>
              <p class="isbn">ISBN: {{ book.isbn }}</p>
              
              <div class="borrow-details">
                <div class="detail-item">
                  <span>Borrowed:</span>
                  <span>{{ formatDate(book.borrowedDate) }}</span>
                </div>
                <div class="detail-item">
                  <span>Due Date:</span>
                  <span :class="{ 'overdue': isOverdue(book.dueDate) }">
                    {{ formatDate(book.dueDate) }}
                  </span>
                </div>
                <div class="detail-item">
                  <span>Days Left:</span>
                  <span :class="{ 'overdue': getDaysUntilDue(book.dueDate) < 0 }">
                    {{ getDaysUntilDue(book.dueDate) }}
                  </span>
                </div>
              </div>
            </div>
            
            <div class="book-actions">
              <button class="btn-secondary" @click="renewBook(book)">
                <i class="fas fa-redo"></i>
                Renew
              </button>
              <button class="btn-primary" @click="returnBook(book)">
                <i class="fas fa-undo"></i>
                Return
              </button>
            </div>
          </div>
          
          <div v-if="borrowedBooks.length === 0" class="no-books">
            <i class="fas fa-book-open"></i>
            <p>You haven't borrowed any books</p>
          </div>
        </div>
      </div>
      
      <!-- Book Catalog Tab -->
      <div v-else-if="activeTab === 'catalog'" class="catalog-tab">
        <div class="books-grid">
          <div 
            v-for="book in filteredCatalog" 
            :key="book.id"
            class="book-card"
            :class="{ 'borrowed': book.status === 'borrowed' }"
          >
            <div class="book-cover">
              <img :src="book.cover" :alt="book.title">
              <div v-if="book.status === 'borrowed'" class="status-badge borrowed">
                Borrowed
              </div>
              <div v-else-if="book.status === 'reserved'" class="status-badge reserved">
                Reserved
              </div>
            </div>
            
            <div class="book-info">
              <h3>{{ book.title }}</h3>
              <p class="author">{{ book.author }}</p>
              <p class="category">{{ book.category }}</p>
              <p class="isbn">ISBN: {{ book.isbn }}</p>
              
              <div class="availability">
                <span v-if="book.status === 'available'" class="available">
                  <i class="fas fa-check-circle"></i>
                  Available
                </span>
                <span v-else-if="book.status === 'borrowed'" class="borrowed">
                  <i class="fas fa-times-circle"></i>
                  Borrowed
                </span>
                <span v-else class="reserved">
                  <i class="fas fa-clock"></i>
                  Reserved
                </span>
              </div>
            </div>
            
            <div class="book-actions">
              <button 
                v-if="book.status === 'available'" 
                class="btn-primary"
                @click="borrowBook(book)"
              >
                <i class="fas fa-book-reader"></i>
                Borrow
              </button>
              <button 
                v-else-if="book.status === 'borrowed'"
                class="btn-secondary"
                disabled
              >
                <i class="fas fa-book"></i>
                Borrowed
              </button>
              <button 
                v-else
                class="btn-secondary"
                @click="reserveBook(book)"
              >
                <i class="fas fa-bell"></i>
                Reserve
              </button>
            </div>
          </div>
        </div>
        
        <div class="pagination">
          <button 
            :disabled="currentPage === 1" 
            @click="currentPage--"
          >
            <i class="fas fa-chevron-left"></i>
          </button>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <button 
            :disabled="currentPage === totalPages" 
            @click="currentPage++"
          >
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
      
      <!-- Reading History Tab -->
      <div v-else-if="activeTab === 'history'" class="history-tab">
        <div class="history-list">
          <div 
            v-for="record in readingHistory" 
            :key="record.id"
            class="history-item"
          >
            <div class="book-cover-small">
              <img :src="record.book.cover" :alt="record.book.title">
            </div>
            
            <div class="history-info">
              <h4>{{ record.book.title }}</h4>
              <p class="author">{{ record.book.author }}</p>
              <div class="history-dates">
                <span>Borrowed: {{ formatDate(record.borrowedDate) }}</span>
                <span>Returned: {{ formatDate(record.returnedDate) }}</span>
              </div>
              <div class="reading-duration">
                Read for {{ getReadingDuration(record.borrowedDate, record.returnedDate) }} days
              </div>
            </div>
            
            <div class="history-actions">
              <button class="btn-icon" @click="viewDetails(record.book)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          
          <div v-if="readingHistory.length === 0" class="no-history">
            <i class="fas fa-history"></i>
            <p>No reading history available</p>
          </div>
        </div>
      </div>
      
      <!-- Reservations Tab -->
      <div v-else class="reservations-tab">
        <div class="reservations-list">
          <div 
            v-for="reservation in reservations" 
            :key="reservation.id"
            class="reservation-item"
          >
            <div class="book-cover-small">
              <img :src="reservation.book.cover" :alt="reservation.book.title">
            </div>
            
            <div class="reservation-info">
              <h4>{{ reservation.book.title }}</h4>
              <p class="author">{{ reservation.book.author }}</p>
              <div class="reservation-details">
                <span>Reserved: {{ formatDate(reservation.reservedDate) }}</span>
                <span v-if="reservation.expiryDate">
                  Expires: {{ formatDate(reservation.expiryDate) }}
                </span>
                <span v-else class="available-soon">
                  Available soon
                </span>
              </div>
            </div>
            
            <div class="reservation-actions">
              <button 
                v-if="reservation.status === 'active'" 
                class="btn-secondary"
                @click="cancelReservation(reservation)"
              >
                <i class="fas fa-times"></i>
                Cancel
              </button>
              <button 
                v-else-if="reservation.status === 'ready'" 
                class="btn-primary"
                @click="collectBook(reservation)"
              >
                <i class="fas fa-hand-holding"></i>
                Collect
              </button>
              <span v-else class="status-badge" :class="reservation.status">
                {{ reservation.status }}
              </span>
            </div>
          </div>
          
          <div v-if="reservations.length === 0" class="no-reservations">
            <i class="fas fa-bell"></i>
            <p>No reservations found</p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const activeTab = ref('borrowed');
const searchQuery = ref('');
const categoryFilter = ref('');
const availabilityFilter = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(12);

// Mock data - in a real app, this would come from API
const borrowedBooks = ref([
  {
    id: 1,
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    isbn: '978-0-06-112008-4',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+1',
    borrowedDate: '2025-09-01',
    dueDate: '2025-09-22'
  },
  {
    id: 2,
    title: '1984',
    author: 'George Orwell',
    isbn: '978-0-452-28423-4',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+2',
    borrowedDate: '2025-08-15',
    dueDate: '2025-09-05'
  },
  {
    id: 3,
    title: 'The Great Gatsby',
    author: 'F. Scott Fitzgerald',
    isbn: '978-0-7432-7356-5',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+3',
    borrowedDate: '2025-09-10',
    dueDate: '2025-10-01'
  }
]);

const bookCatalog = ref([
  {
    id: 1,
    title: 'To Kill a Mockingbird',
    author: 'Harper Lee',
    isbn: '978-0-06-112008-4',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+1',
    status: 'borrowed'
  },
  {
    id: 2,
    title: '1984',
    author: 'George Orwell',
    isbn: '978-0-452-28423-4',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+2',
    status: 'borrowed'
  },
  {
    id: 3,
    title: 'The Great Gatsby',
    author: 'F. Scott Fitzgerald',
    isbn: '978-0-7432-7356-5',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+3',
    status: 'borrowed'
  },
  {
    id: 4,
    title: 'Pride and Prejudice',
    author: 'Jane Austen',
    isbn: '978-0-14-143951-8',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+4',
    status: 'available'
  },
  {
    id: 5,
    title: 'The Catcher in the Rye',
    author: 'J.D. Salinger',
    isbn: '978-0-316-76948-0',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+5',
    status: 'available'
  },
  {
    id: 6,
    title: 'Lord of the Flies',
    author: 'William Golding',
    isbn: '978-0-571-05686-2',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+6',
    status: 'reserved'
  },
  {
    id: 7,
    title: 'The Hobbit',
    author: 'J.R.R. Tolkien',
    isbn: '978-0-547-92822-7',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+7',
    status: 'available'
  },
  {
    id: 8,
    title: 'Harry Potter and the Sorcerer\'s Stone',
    author: 'J.K. Rowling',
    isbn: '978-0-439-70818-8',
    category: 'Fiction',
    cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+8',
    status: 'available'
  }
]);

const readingHistory = ref([
  {
    id: 1,
    book: {
      id: 10,
      title: 'The Alchemist',
      author: 'Paulo Coelho',
      isbn: '978-0-06-231500-7',
      cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+10'
    },
    borrowedDate: '2025-08-01',
    returnedDate: '2025-08-20'
  },
  {
    id: 2,
    book: {
      id: 11,
      title: 'Brave New World',
      author: 'Aldous Huxley',
      isbn: '978-0-06-085052-4',
      cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+11'
    },
    borrowedDate: '2025-07-15',
    returnedDate: '2025-08-05'
  }
]);

const reservations = ref([
  {
    id: 1,
    book: {
      id: 6,
      title: 'Lord of the Flies',
      author: 'William Golding',
      isbn: '978-0-571-05686-2',
      cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+6'
    },
    reservedDate: '2025-09-10',
    expiryDate: '2025-09-24',
    status: 'active'
  },
  {
    id: 2,
    book: {
      id: 9,
      title: 'Dune',
      author: 'Frank Herbert',
      isbn: '978-0-441-17271-9',
      cover: 'https://via.placeholder.com/150x200/4F46E5/FFFFFF?text=Book+9'
    },
    reservedDate: '2025-09-05',
    expiryDate: null,
    status: 'ready'
  }
]);

// Computed properties
const overdueBooks = computed(() => {
  return borrowedBooks.value.filter(book => isOverdue(book.dueDate));
});

const totalFines = computed(() => {
  return overdueBooks.value.length * 2.50; // $2.50 per overdue book
});

const filteredCatalog = computed(() => {
  let filtered = bookCatalog.value;
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(book => 
      book.title.toLowerCase().includes(query) ||
      book.author.toLowerCase().includes(query) ||
      book.isbn.includes(query) ||
      book.category.toLowerCase().includes(query)
    );
  }
  
  // Apply category filter
  if (categoryFilter.value) {
    filtered = filtered.filter(book => 
      book.category.toLowerCase() === categoryFilter.value.toLowerCase()
    );
  }
  
  // Apply availability filter
  if (availabilityFilter.value) {
    filtered = filtered.filter(book => 
      book.status === availabilityFilter.value
    );
  }
  
  // Apply pagination
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(bookCatalog.value.length / itemsPerPage.value);
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const isOverdue = (dueDate) => {
  const today = new Date();
  const due = new Date(dueDate);
  return due < today;
};

const getDaysUntilDue = (dueDate) => {
  const today = new Date();
  const due = new Date(dueDate);
  const diffTime = due - today;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
};

const getReadingDuration = (borrowedDate, returnedDate) => {
  const borrowed = new Date(borrowedDate);
  const returned = new Date(returnedDate);
  const diffTime = returned - borrowed;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
};

const searchBooks = () => {
  // In a real app, this would make an API call
  console.log('Searching for:', searchQuery.value);
};

const borrowBook = (book) => {
  alert(`Borrowing book: ${book.title}`);
};

const renewBook = (book) => {
  alert(`Renewing book: ${book.title}`);
};

const returnBook = (book) => {
  alert(`Returning book: ${book.title}`);
};

const reserveBook = (book) => {
  alert(`Reserving book: ${book.title}`);
};

const cancelReservation = (reservation) => {
  alert(`Cancelling reservation for: ${reservation.book.title}`);
};

const collectBook = (reservation) => {
  alert(`Collecting book: ${reservation.book.title}`);
};

const viewDetails = (book) => {
  alert(`Viewing details for: ${book.title}`);
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.student-library {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  margin-bottom: 30px;
}

.page-header h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
  color: #1f2937;
}

.page-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.library-overview {
  display: grid;
  grid-template-columns: 1fr;
  gap: 20px;
  margin-bottom: 30px;
}

.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.stat-card {
  display: flex;
  align-items: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.5rem;
}

.stat-icon.books {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.due {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.fines {
  background: #fee2e2;
  color: #ef4444;
}

.stat-icon.history {
  background: #dcfce7;
  color: #22c55e;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.stat-label {
  font-size: 0.9rem;
  color: #6b7280;
}

.search-section {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.search-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.search-bar i {
  color: #9ca3af;
}

.search-bar input {
  flex: 1;
  padding: 12px 15px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
}

.search-bar input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.search-btn {
  padding: 12px 20px;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: background 0.2s ease;
}

.search-btn:hover {
  background: #2563eb;
}

.search-filters {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.search-filters select {
  padding: 10px 15px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  background: white;
  font-size: 0.9rem;
}

.library-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.library-tabs button {
  flex: 1;
  min-width: 120px;
  background: none;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.library-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Borrowed Books Tab */
.borrowed-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.books-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.book-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.2s ease;
  display: flex;
  flex-direction: column;
}

.book-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.book-card.borrowed {
  border-left: 4px solid #3b82f6;
}

.book-cover {
  position: relative;
  height: 200px;
  background: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
}

.book-cover img {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
}

.status-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status-badge.borrowed {
  background: #dbeafe;
  color: #1e40af;
}

.status-badge.reserved {
  background: #ffedd5;
  color: #9a3412;
}

.book-info {
  padding: 20px;
  flex: 1;
}

.book-info h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 8px;
  color: #1f2937;
}

.author {
  color: #6b7280;
  margin-bottom: 5px;
  font-size: 0.95rem;
}

.category {
  color: #3b82f6;
  font-size: 0.9rem;
  margin-bottom: 5px;
}

.isbn {
  color: #9ca3af;
  font-size: 0.85rem;
  margin-bottom: 15px;
}

.availability {
  margin-bottom: 15px;
}

.availability span {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.availability .available {
  background: #dcfce7;
  color: #166534;
}

.availability .borrowed {
  background: #fee2e2;
  color: #991b1b;
}

.availability .reserved {
  background: #ffedd5;
  color: #9a3412;
}

.borrow-details {
  background: #f9fafb;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 15px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 5px 0;
  font-size: 0.9rem;
}

.detail-item span:first-child {
  color: #6b7280;
}

.detail-item .overdue {
  color: #ef4444;
  font-weight: 600;
}

.book-actions {
  display: flex;
  gap: 10px;
  padding: 0 20px 20px;
}

.book-actions button {
  flex: 1;
  padding: 10px;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

.no-books,
.no-history,
.no-reservations {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-books i,
.no-history i,
.no-reservations i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Catalog Tab */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  margin-top: 30px;
}

.pagination button {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid #d1d5db;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination button:hover {
  background: #f3f4f6;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination span {
  font-weight: 500;
  color: #1f2937;
}

/* Reading History Tab */
.history-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.history-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.history-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  transition: all 0.2s ease;
}

.history-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.book-cover-small {
  width: 80px;
  height: 100px;
  background: #f3f4f6;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.book-cover-small img {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
}

.history-info {
  flex: 1;
}

.history-info h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.history-dates {
  display: flex;
  gap: 20px;
  margin: 10px 0;
  font-size: 0.9rem;
  color: #6b7280;
}

.reading-duration {
  font-size: 0.9rem;
  color: #3b82f6;
  font-weight: 500;
}

.history-actions {
  display: flex;
  align-items: center;
}

.btn-icon {
  background: none;
  border: none;
  color: #3b82f6;
  cursor: pointer;
  font-size: 1.2rem;
  padding: 8px;
  border-radius: 6px;
}

.btn-icon:hover {
  background: #dbeafe;
}

/* Reservations Tab */
.reservations-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.reservations-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.reservation-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  transition: all 0.2s ease;
}

.reservation-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.reservation-info {
  flex: 1;
}

.reservation-info h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.reservation-details {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin: 10px 0;
  font-size: 0.9rem;
  color: #6b7280;
}

.available-soon {
  color: #22c55e;
  font-weight: 500;
}

.reservation-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.reservation-actions .status-badge {
  position: static;
  margin: 0;
}

/* Dark mode support */
.dark .student-library {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .book-info h3,
.dark .history-info h4,
.dark .reservation-info h4 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .author,
.dark .category,
.dark .isbn,
.dark .detail-item span:first-child,
.dark .history-dates,
.dark .reservation-details {
  color: #d1d5db;
}

.dark .stat-card,
.dark .search-section,
.dark .borrowed-tab,
.dark .catalog-tab,
.dark .history-tab,
.dark .reservations-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .library-tabs {
  background: #374151;
}

.dark .library-tabs button.active {
  background: #1f2937;
}

.dark .search-bar input {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .search-bar input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

.dark .search-filters select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .book-card {
  border-color: #374151;
  background: #1f2937;
}

.dark .book-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .borrow-details {
  background: #111827;
}

.dark .availability .available {
  background: #064e3b;
  color: #6ee7b7;
}

.dark .availability .borrowed {
  background: #7f1d1d;
  color: #fca5a5;
}

.dark .availability .reserved {
  background: #78350f;
  color: #fcd34d;
}

.dark .history-item,
.dark .reservation-item {
  border-color: #374151;
  background: #1f2937;
}

.dark .history-item:hover,
.dark .reservation-item:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .btn-icon:hover {
  background: #1e3a8a;
}

.dark .pagination button {
  background: #1f2937;
  border-color: #374151;
}

.dark .pagination button:hover {
  background: #374151;
}

.dark .pagination span {
  color: #f9fafb;
}

.dark .no-books,
.dark .no-history,
.dark .no-reservations {
  color: #9ca3af;
}

.dark .no-books i,
.dark .no-history i,
.dark .no-reservations i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .student-library {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .stats-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .search-filters {
    flex-direction: column;
  }
  
  .book-card {
    flex-direction: row;
  }
  
  .book-cover {
    width: 100px;
    height: 120px;
  }
  
  .history-item,
  .reservation-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .book-actions {
    padding: 20px;
  }
}
</style>