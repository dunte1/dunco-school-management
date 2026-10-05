<template>
  <AppLayout>
    <div class="test-page">
      <h1>API Services Test</h1>
      <div class="test-results">
        <div class="test-section">
          <h2>Authentication Service</h2>
          <button @click="testAuth">Test Authentication</button>
          <div v-if="authResult" class="result">{{ authResult }}</div>
        </div>
        
        <div class="test-section">
          <h2>Dashboard Service</h2>
          <button @click="testDashboard">Test Dashboard</button>
          <div v-if="dashboardResult" class="result">{{ dashboardResult }}</div>
        </div>
        
        <div class="test-section">
          <h2>Student Service</h2>
          <button @click="testStudent">Test Student Data</button>
          <div v-if="studentResult" class="result">{{ studentResult }}</div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { AuthService, DashboardService, StudentService } from '@/Services';

const authResult = ref('');
const dashboardResult = ref('');
const studentResult = ref('');

const testAuth = async () => {
  try {
    // Test getting current user
    const user = await AuthService.getMe();
    authResult.value = `User: ${user.name || 'Unknown'}`;
  } catch (error) {
    authResult.value = `Error: ${error.message}`;
  }
};

const testDashboard = async () => {
  try {
    const data = await DashboardService.getDashboardData();
    dashboardResult.value = `Dashboard data loaded: ${Object.keys(data).length} keys`;
  } catch (error) {
    dashboardResult.value = `Error: ${error.message}`;
  }
};

const testStudent = async () => {
  try {
    const data = await StudentService.getTodayTimetable();
    studentResult.value = `Timetable loaded: ${data.schedule ? data.schedule.length : 0} classes`;
  } catch (error) {
    studentResult.value = `Error: ${error.message}`;
  }
};
</script>

<style scoped>
.test-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.test-page h1 {
  text-align: center;
  margin-bottom: 30px;
  color: #1f2937;
}

.test-results {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.test-section {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.test-section h2 {
  margin-bottom: 20px;
  color: #1f2937;
}

.test-section button {
  padding: 10px 20px;
  background: #667eea;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s ease;
}

.test-section button:hover {
  background: #5a67d8;
}

.result {
  margin-top: 15px;
  padding: 15px;
  background: #f9fafb;
  border-radius: 6px;
  color: #1f2937;
  font-family: monospace;
}
</style>