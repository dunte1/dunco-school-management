<template>
  <LibraryLayout>
    <div class="max-w-5xl mx-auto py-8 px-4">
      <h1 class="text-3xl font-bold text-navy-900 mb-8 tracking-tight">Library Dashboard</h1>
      <!-- Stat Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-navy-900 to-blue-700 rounded-2xl shadow-lg p-6 flex flex-col items-center border-2 border-blue-500">
          <span class="text-3xl mb-2 text-blue-500"><i class="fas fa-book"></i></span>
          <span class="uppercase text-xs text-blue-500 tracking-wider mb-1 font-bold">Total Books</span>
          <span class="text-3xl font-extrabold text-blue-600 drop-shadow-lg"><CountUp :endVal="totalBooks" /></span>
        </div>
        <div class="bg-gradient-to-br from-navy-900 to-blue-700 rounded-2xl shadow-lg p-6 flex flex-col items-center border-2 border-blue-500">
          <span class="text-3xl mb-2 text-blue-500"><i class="fas fa-users"></i></span>
          <span class="uppercase text-xs text-blue-500 tracking-wider mb-1 font-bold">Active Members</span>
          <span class="text-3xl font-extrabold text-blue-600 drop-shadow-lg"><CountUp :endVal="activeMembers" /></span>
        </div>
        <div class="bg-gradient-to-br from-navy-900 to-blue-700 rounded-2xl shadow-lg p-6 flex flex-col items-center border-2 border-blue-500">
          <span class="text-3xl mb-2 text-blue-500"><i class="fas fa-exchange-alt"></i></span>
          <span class="uppercase text-xs text-blue-500 tracking-wider mb-1 font-bold">Books Borrowed</span>
          <span class="text-3xl font-extrabold text-blue-600 drop-shadow-lg"><CountUp :endVal="booksBorrowed" /></span>
        </div>
        <div class="bg-gradient-to-br from-navy-900 to-blue-700 rounded-2xl shadow-lg p-6 flex flex-col items-center border-2 border-blue-500">
          <span class="text-3xl mb-2 text-blue-500"><i class="fas fa-exclamation-triangle"></i></span>
          <span class="uppercase text-xs text-blue-500 tracking-wider mb-1 font-bold">Overdue Books</span>
          <span class="text-3xl font-extrabold text-blue-600 drop-shadow-lg"><CountUp :endVal="overdueBooks" /></span>
        </div>
      </div>
      <!-- Quick Access -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <inertia-link href="/library/books" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-book text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Books</span>
        </inertia-link>
        <inertia-link href="/library/categories" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-tags text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Categories</span>
        </inertia-link>
        <inertia-link href="/library/authors" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-user-edit text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Authors</span>
        </inertia-link>
        <inertia-link href="/library/publishers" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-building text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Publishers</span>
        </inertia-link>
        <inertia-link href="/library/members" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-users text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Members</span>
        </inertia-link>
        <inertia-link href="/library/borrows" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-exchange-alt text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Borrows</span>
        </inertia-link>
        <inertia-link href="/library/reports/borrowed" class="flex flex-col items-center justify-center bg-gradient-to-br from-navy-900 to-blue-700 rounded-xl shadow-md py-6 hover:scale-105 transition-transform border-2 border-blue-500">
          <i class="fas fa-chart-bar text-2xl text-blue-500 mb-2"></i>
          <span class="text-blue-600 font-bold text-lg">Reports</span>
        </inertia-link>
      </div>
    </div>
  </LibraryLayout>
</template>

<script setup>
import LibraryLayout from '@/Layouts/LibraryLayout.vue';
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';
import { ref, watch } from 'vue';

// Simple count-up animation component
const CountUp = {
  props: ['endVal'],
  setup(props) {
    const val = ref(0);
    watch(() => props.endVal, (newVal) => {
      let start = 0;
      const end = Number(newVal) || 0;
      if (start === end) return;
      const duration = 800;
      const step = Math.ceil(duration / (end || 1));
      let current = start;
      const timer = setInterval(() => {
        current += 1;
        val.value = current;
        if (current >= end) {
          val.value = end;
          clearInterval(timer);
        }
      }, step);
    }, { immediate: true });
    return () => val.value;
  }
};

const props = defineProps({
  totalBooks: Number,
  activeMembers: Number,
  booksBorrowed: Number,
  overdueBooks: Number,
  borrowTrends: Array,
  recentActivity: Array,
});

function formatDate(dateStr) {
  const d = new Date(dateStr);
  return d.toLocaleDateString() + ' ' + d.toLocaleTimeString();
}

onMounted(() => {
  const ctx = document.getElementById('borrowTrendsChart');
  if (ctx && props.borrowTrends && props.borrowTrends.length) {
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: props.borrowTrends.map(t => t.date),
        datasets: [{
          label: 'Borrows',
          data: props.borrowTrends.map(t => t.count),
          borderColor: '#42a5f5',
          backgroundColor: 'rgba(66,165,245,0.2)',
          tension: 0.3,
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
        },
        scales: {
          x: { title: { display: true, text: 'Date' } },
          y: { title: { display: true, text: 'Borrows' }, beginAtZero: true },
        },
      },
    });
  }
});
</script>

<style scoped>
/* The custom CSS classes are removed as per the new_code,
   but the template still uses them. This will cause errors.
   To strictly follow the new_code, I will remove the custom classes
   and the script that uses them, as the new_code does not provide
   the CSS for the new structure. */
</style> 