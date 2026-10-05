<template>
  <AppLayout>
    <div class="student-timetable">
      <div class="page-header">
        <h1>My Timetable</h1>
        <p>View your class schedule</p>
      </div>
      
      <div class="timetable-controls">
        <div class="view-toggle">
          <button 
            :class="{ active: viewMode === 'day' }"
            @click="viewMode = 'day'"
          >
            Daily
          </button>
          <button 
            :class="{ active: viewMode === 'week' }"
            @click="viewMode = 'week'"
          >
            Weekly
          </button>
        </div>
        
        <div class="date-navigation">
          <button @click="previousPeriod">
            <i class="fas fa-chevron-left"></i>
          </button>
          <span class="current-period">{{ currentPeriodLabel }}</span>
          <button @click="nextPeriod">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
      
      <!-- Daily View -->
      <div v-if="viewMode === 'day'" class="daily-view">
        <div class="day-header">
          <h2>{{ formatDate(currentDate, 'full') }}</h2>
        </div>
        
        <div class="classes-list">
          <div 
            v-for="classItem in dailyClasses" 
            :key="classItem.id"
            class="class-item"
            :class="{ 'current': isCurrentClass(classItem), 'upcoming': isUpcomingClass(classItem) }"
          >
            <div class="class-time">
              <div class="start-time">{{ classItem.startTime }}</div>
              <div class="end-time">{{ classItem.endTime }}</div>
            </div>
            <div class="class-details">
              <div class="subject">{{ classItem.subject }}</div>
              <div class="location">
                <i class="fas fa-map-marker-alt"></i>
                {{ classItem.room }}
              </div>
              <div class="instructor">
                <i class="fas fa-chalkboard-teacher"></i>
                {{ classItem.teacher }}
              </div>
              <div class="class-type" :class="classItem.type">
                {{ classItem.type }}
              </div>
            </div>
            <div class="class-actions">
              <button 
                v-if="isUpcomingClass(classItem) && !classItem.notificationSet"
                @click="setNotification(classItem)"
                class="notification-btn"
                title="Set reminder"
              >
                <i class="fas fa-bell"></i>
              </button>
              <span v-if="classItem.notificationSet" class="notification-set" title="Reminder set">
                <i class="fas fa-bell"></i>
              </span>
              <button 
                v-if="isCurrentClass(classItem)"
                @click="joinClass(classItem)"
                class="join-btn"
              >
                Join Class
              </button>
            </div>
            <div class="class-status">
              <span v-if="isCurrentClass(classItem)" class="status live">
                <i class="fas fa-circle"></i> Live
              </span>
              <span v-else-if="isUpcomingClass(classItem)" class="status upcoming">
                Upcoming
              </span>
              <span v-else class="status completed">
                Completed
              </span>
            </div>
          </div>
          
          <div v-if="dailyClasses.length === 0" class="no-classes">
            <i class="fas fa-calendar-times"></i>
            <p>No classes scheduled for today</p>
          </div>
        </div>
      </div>
      
      <!-- Weekly View -->
      <div v-else class="weekly-view">
        <div class="week-grid">
          <div 
            v-for="day in weekDays" 
            :key="day.date"
            class="day-column"
            :class="{ 'today': isToday(day.date) }"
          >
            <div class="day-header">
              <div class="day-name">{{ day.name }}</div>
              <div class="day-date">{{ formatDate(day.date, 'short') }}</div>
            </div>
            
            <div class="day-classes">
              <div 
                v-for="classItem in getClassesForDay(day.date)" 
                :key="classItem.id"
                class="class-item mini"
                :class="{ 'current': isCurrentClass(classItem), 'upcoming': isUpcomingClass(classItem) }"
              >
                <div class="class-time">
                  {{ classItem.startTime }} - {{ classItem.endTime }}
                </div>
                <div class="class-subject">
                  {{ classItem.subject }}
                </div>
                <div class="class-room">
                  <i class="fas fa-door-open"></i>
                  {{ classItem.room }}
                </div>
                <div class="mini-status">
                  <span v-if="isCurrentClass(classItem)" class="status live">
                    <i class="fas fa-circle"></i>
                  </span>
                  <span v-else-if="isUpcomingClass(classItem)" class="status upcoming">
                    <i class="fas fa-clock"></i>
                  </span>
                  <span v-else class="status completed">
                    <i class="fas fa-check"></i>
                  </span>
                </div>
              </div>
              
              <div v-if="getClassesForDay(day.date).length === 0" class="no-classes-mini">
                No classes
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
// Import our services
import { StudentService } from '../../Services';

// Reactive data
const viewMode = ref('day');
const currentDate = ref(new Date());
const weekStartDate = ref(new Date());
const timetableData = ref([]);

// Fetch timetable data
const fetchTimetable = async () => {
  try {
    if (viewMode.value === 'day') {
      const data = await StudentService.getTodayTimetable();
      timetableData.value = data.schedule || [];
    } else {
      const data = await StudentService.getWeekTimetable();
      timetableData.value = data.schedule || [];
    }
  } catch (error) {
    console.error('Error fetching timetable:', error);
  }
};

// Computed properties
const dailyClasses = computed(() => {
  const today = currentDate.value.toISOString().split('T')[0];
  return timetableData.value.filter(classItem => classItem.date === today);
});

const weekDays = computed(() => {
  const days = [];
  const start = new Date(weekStartDate.value);
  for (let i = 0; i < 7; i++) {
    const date = new Date(start);
    date.setDate(start.getDate() + i);
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    days.push({
      name: dayNames[date.getDay()],
      date: date.toISOString().split('T')[0],
      shortDate: `${date.getDate()} ${months[date.getMonth()]}`
    });
  }
  return days;
});

const currentPeriodLabel = computed(() => {
  if (viewMode.value === 'day') {
    return formatDate(currentDate.value, 'full');
  } else {
    const endOfWeek = new Date(weekStartDate.value);
    endOfWeek.setDate(weekStartDate.value.getDate() + 6);
    return `${formatDate(weekStartDate.value, 'short')} - ${formatDate(endOfWeek, 'short')}`;
  }
});

// Navigation functions
const previousPeriod = () => {
  if (viewMode.value === 'day') {
    currentDate.value.setDate(currentDate.value.getDate() - 1);
  } else {
    weekStartDate.value.setDate(weekStartDate.value.getDate() - 7);
  }
  fetchTimetable();
};

const nextPeriod = () => {
  if (viewMode.value === 'day') {
    currentDate.value.setDate(currentDate.value.getDate() + 1);
  } else {
    weekStartDate.value.setDate(weekStartDate.value.getDate() + 7);
  }
  fetchTimetable();
};

// Helper functions
const formatDate = (date, format) => {
  if (format === 'full') {
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const d = new Date(date);
    return `${dayNames[d.getDay()]}, ${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
  } else {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const d = new Date(date);
    return `${d.getDate()} ${months[d.getMonth()]}`;
  }
};

const isToday = (dateString) => {
  const today = new Date().toISOString().split('T')[0];
  return dateString === today;
};

const isCurrentClass = (classItem) => {
  // Implementation would compare current time with class times
  return false;
};

const isUpcomingClass = (classItem) => {
  // Implementation would check if class is in the future
  return false;
};

const getClassesForDay = (dateString) => {
  return timetableData.value.filter(classItem => classItem.date === dateString);
};

const setNotification = (classItem) => {
  // Implementation for setting class reminder
  classItem.notificationSet = true;
};

const joinClass = (classItem) => {
  // Implementation for joining class
  console.log('Joining class:', classItem);
};

// Fetch data when component mounts
onMounted(() => {
  // Set week start date to Monday of current week
  const today = new Date();
  const day = today.getDay();
  const diff = today.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday
  weekStartDate.value = new Date(today.setDate(diff));
  
  fetchTimetable();
});
</script>

<style scoped>
/* ... existing styles ... */
.student-timetable {
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
  margin-bottom: 5px;
  color: #1f2937;
}

.page-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.timetable-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 20px;
}

.view-toggle {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
}

.view-toggle button {
  padding: 8px 16px;
  border: none;
  background: transparent;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  color: #6b7280;
  transition: all 0.2s ease;
}

.view-toggle button.active {
  background: white;
  color: #667eea;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.date-navigation {
  display: flex;
  align-items: center;
  gap: 15px;
}

.date-navigation button {
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

.date-navigation button:hover {
  background: #f9fafb;
  border-color: #667eea;
}

.current-period {
  font-weight: 600;
  color: #1f2937;
  min-width: 200px;
  text-align: center;
}

.daily-view {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  padding: 25px;
}

.day-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 20px;
  text-align: center;
}

.classes-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.class-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 20px;
  border-radius: 12px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
  position: relative;
}

.class-item:hover {
  border-color: #667eea;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.class-item.current {
  border-color: #22c55e;
  background: rgba(34, 197, 94, 0.05);
}

.class-item.upcoming {
  border-color: #f59e0b;
  background: rgba(245, 158, 11, 0.05);
}

.class-time {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 80px;
}

.start-time {
  font-weight: 700;
  color: #1f2937;
  font-size: 1.1rem;
}

.end-time {
  font-size: 0.9rem;
  color: #6b7280;
}

.class-details {
  flex: 1;
}

.subject {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 10px;
}

.location, .instructor {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.95rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.location i, .instructor i {
  width: 16px;
  text-align: center;
}

.class-type {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
  margin-top: 10px;
}

.class-type.Lecture {
  background: #dbeafe;
  color: #1d4ed8;
}

.class-type.Lab {
  background: #dcfce7;
  color: #166534;
}

.class-type.Tutorial {
  background: #fef3c7;
  color: #92400e;
}

.class-actions {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  min-width: 100px;
}

.notification-btn, .join-btn {
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease;
  border: none;
}

.notification-btn {
  background: #f3f4f6;
  color: #6b7280;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.notification-btn:hover {
  background: #667eea;
  color: white;
}

.join-btn {
  background: #667eea;
  color: white;
}

.join-btn:hover {
  background: #5a67d8;
}

.notification-set {
  color: #f59e0b;
  font-size: 1.2rem;
}

.class-status {
  position: absolute;
  top: 15px;
  right: 15px;
}

.class-status .status {
  display: flex;
  align-items: center;
  gap: 5px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 500;
}

.class-status .live {
  background: #dcfce7;
  color: #166534;
}

.class-status .live i {
  color: #22c55e;
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0% { opacity: 1; }
  50% { opacity: 0.5; }
  100% { opacity: 1; }
}

.class-status .upcoming {
  background: #fef3c7;
  color: #92400e;
}

.class-status .completed {
  background: #f3f4f6;
  color: #6b7280;
}

.no-classes {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.no-classes i {
  font-size: 3rem;
  margin-bottom: 20px;
  color: #d1d5db;
}

.weekly-view {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  padding: 25px;
  overflow-x: auto;
}

.week-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 15px;
  min-width: 800px;
}

.day-column {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.day-column.today {
  background: rgba(102, 126, 234, 0.05);
  border-radius: 8px;
  padding: 15px;
}

.day-header {
  text-align: center;
  padding-bottom: 15px;
  border-bottom: 1px solid #f3f4f6;
}

.day-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.day-date {
  font-size: 0.9rem;
  color: #6b7280;
}

.day-classes {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.class-item.mini {
  padding: 15px;
  gap: 10px;
}

.class-time {
  font-size: 0.85rem;
  color: #6b7280;
  min-width: auto;
}

.class-subject {
  font-weight: 600;
  color: #1f2937;
  font-size: 0.95rem;
  margin-bottom: 5px;
}

.class-room {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 0.8rem;
  color: #6b7280;
}

.class-room i {
  font-size: 0.7rem;
}

.mini-status {
  margin-left: auto;
}

.mini-status .status {
  font-size: 0.7rem;
}

.mini-status .live i {
  color: #22c55e;
}

.mini-status .upcoming i {
  color: #f59e0b;
}

.mini-status .completed i {
  color: #9ca3af;
}

.no-classes-mini {
  text-align: center;
  padding: 20px;
  color: #9ca3af;
  font-size: 0.9rem;
  font-style: italic;
}

@media (max-width: 768px) {
  .student-timetable {
    padding: 15px;
  }
  
  .timetable-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .date-navigation {
    justify-content: center;
  }
  
  .week-grid {
    grid-template-columns: repeat(1, 1fr);
    min-width: auto;
  }
  
  .class-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .class-actions {
    flex-direction: row;
    width: 100%;
    justify-content: flex-end;
  }
  
  .class-status {
    position: static;
    margin-top: 10px;
  }
}
</style>