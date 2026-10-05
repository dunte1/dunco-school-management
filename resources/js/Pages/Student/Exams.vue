<template>
  <div class="exams-container">
    <div class="header-section">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-white">My Exams</h1>
      <p class="text-gray-600 dark:text-gray-300">View upcoming exams and check your results</p>
    </div>

    <!-- Upcoming Exams Section -->
    <div class="mt-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Upcoming Exams</h2>
        <div class="relative">
          <select 
            v-model="selectedTerm" 
            class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg py-2 px-4 pr-8 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="all">All Terms</option>
            <option value="term1">Term 1</option>
            <option value="term2">Term 2</option>
            <option value="term3">Term 3</option>
          </select>
        </div>
      </div>

      <div v-if="upcomingExams.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div 
          v-for="exam in upcomingExams" 
          :key="exam.id"
          class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300"
        >
          <div class="p-5">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ exam.subject }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ exam.exam_type }}</p>
              </div>
              <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="getExamStatusClass(exam.status)">
                {{ exam.status }}
              </span>
            </div>
            
            <div class="mt-4 space-y-2">
              <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span>{{ formatDate(exam.date) }}</span>
              </div>
              <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                <i class="fas fa-clock mr-2"></i>
                <span>{{ exam.start_time }} - {{ exam.end_time }}</span>
              </div>
              <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <span>{{ exam.venue }}</span>
              </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
              <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Class:</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ exam.class }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
        <i class="fas fa-calendar-check text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Upcoming Exams</h3>
        <p class="text-gray-500 dark:text-gray-400">You don't have any exams scheduled at the moment.</p>
      </div>
    </div>

    <!-- Exam Results Section -->
    <div class="mt-8">
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Exam Results</h2>
      
      <div v-if="examResults.length > 0" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Exam</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Score</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="result in examResults" :key="result.id" class="hover:bg-gray-50 dark:hover:bg-gray-750">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">{{ result.exam_name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ result.class }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">{{ result.subject }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(result.date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">{{ result.score }}/{{ result.total }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getGradeClass(result.grade)">
                    {{ result.grade }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ result.status }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
        <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Exam Results</h3>
        <p class="text-gray-500 dark:text-gray-400">Your exam results will appear here once they are published.</p>
      </div>
    </div>

    <!-- Performance Overview -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-400 p-3">
            <i class="fas fa-chart-line text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm opacity-80">Average Score</p>
            <p class="text-2xl font-bold">{{ averageScore }}%</p>
          </div>
        </div>
      </div>
      
      <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center">
          <div class="rounded-full bg-green-400 p-3">
            <i class="fas fa-medal text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm opacity-80">Top Grade</p>
            <p class="text-2xl font-bold">{{ topGrade }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-400 p-3">
            <i class="fas fa-tasks text-xl"></i>
          </div>
          <div class="ml-4">
            <p class="text-sm opacity-80">Exams Taken</p>
            <p class="text-2xl font-bold">{{ examsTaken }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Mock data for upcoming exams
const upcomingExams = ref([
  {
    id: 1,
    subject: 'Mathematics',
    exam_type: 'Midterm Exam',
    date: '2023-11-15',
    start_time: '09:00',
    end_time: '11:00',
    venue: 'Room 101',
    class: 'Grade 10A',
    status: 'Scheduled'
  },
  {
    id: 2,
    subject: 'Physics',
    exam_type: 'Practical Exam',
    date: '2023-11-18',
    start_time: '14:00',
    end_time: '16:00',
    venue: 'Physics Lab',
    class: 'Grade 10A',
    status: 'Scheduled'
  },
  {
    id: 3,
    subject: 'English Literature',
    exam_type: 'Final Exam',
    date: '2023-11-22',
    start_time: '10:00',
    end_time: '12:00',
    venue: 'Main Hall',
    class: 'Grade 10A',
    status: 'Scheduled'
  }
]);

// Mock data for exam results
const examResults = ref([
  {
    id: 1,
    exam_name: 'Mathematics Midterm',
    subject: 'Mathematics',
    date: '2023-10-15',
    score: 85,
    total: 100,
    grade: 'A',
    status: 'Published',
    class: 'Grade 10A'
  },
  {
    id: 2,
    exam_name: 'Physics Quiz 1',
    subject: 'Physics',
    date: '2023-10-05',
    score: 78,
    total: 100,
    grade: 'B+',
    status: 'Published',
    class: 'Grade 10A'
  },
  {
    id: 3,
    exam_name: 'English Essay',
    subject: 'English',
    date: '2023-09-28',
    score: 92,
    total: 100,
    grade: 'A+',
    status: 'Published',
    class: 'Grade 10A'
  }
]);

const selectedTerm = ref('all');

// Helper functions
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getExamStatusClass = (status) => {
  switch (status.toLowerCase()) {
    case 'scheduled':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100';
    case 'ongoing':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100';
    case 'completed':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100';
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100';
  }
};

const getGradeClass = (grade) => {
  switch (grade) {
    case 'A+':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100';
    case 'A':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100';
    case 'A-':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100';
    case 'B+':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100';
    case 'B':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100';
    case 'B-':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100';
    case 'C+':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100';
    case 'C':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100';
    case 'C-':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100';
    default:
      return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100';
  }
};

// Performance metrics
const averageScore = computed(() => {
  if (examResults.value.length === 0) return 0;
  const total = examResults.value.reduce((sum, result) => sum + (result.score / result.total * 100), 0);
  return Math.round(total / examResults.value.length);
});

const topGrade = computed(() => {
  if (examResults.value.length === 0) return 'N/A';
  // Simple grade ranking (A+ > A > A- > B+ > B > B- > C+ > C > C- > D > F)
  const gradeRank = {
    'A+': 1, 'A': 2, 'A-': 3, 
    'B+': 4, 'B': 5, 'B-': 6,
    'C+': 7, 'C': 8, 'C-': 9,
    'D': 10, 'F': 11
  };
  
  return examResults.value
    .map(result => result.grade)
    .sort((a, b) => gradeRank[a] - gradeRank[b])[0];
});

const examsTaken = computed(() => {
  return examResults.value.length;
});
</script>

<style scoped>
.exams-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

@media (max-width: 768px) {
  .exams-container {
    padding: 15px;
  }
}
</style>