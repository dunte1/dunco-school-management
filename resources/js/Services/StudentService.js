import ApiService from './ApiService';

class StudentService {
  // Get student timetable for today
  async getTodayTimetable() {
    return ApiService.get('/timetable/today');
  }

  // Get student timetable for the week
  async getWeekTimetable() {
    return ApiService.get('/timetable/week');
  }

  // Get student attendance summary
  async getAttendanceSummary() {
    return ApiService.get('/attendance/summary');
  }

  // Get student exam results
  async getExamResults() {
    return ApiService.get('/exams/results');
  }

  // Get upcoming exams
  async getUpcomingExams() {
    return ApiService.get('/exams/upcoming');
  }

  // Get exam schedule
  async getExamSchedule() {
    return ApiService.get('/exams/schedule');
  }

  // Get finance summary
  async getFinanceSummary() {
    return ApiService.get('/finance/summary');
  }

  // Get fee balances
  async getFeeBalances() {
    return ApiService.get('/finance/balances');
  }

  // Get payment history
  async getPaymentHistory() {
    return ApiService.get('/finance/payments');
  }

  // Make a payment
  async makePayment(data) {
    return ApiService.post('/finance/pay', data);
  }

  // Get library books
  async getLibraryBooks() {
    return ApiService.get('/library/books');
  }

  // Get borrowed books
  async getBorrowedBooks() {
    return ApiService.get('/library/borrowed');
  }

  // Borrow a book
  async borrowBook(bookId) {
    return ApiService.post('/library/borrow', { book_id: bookId });
  }

  // Return a book
  async returnBook(transactionId) {
    return ApiService.post('/library/return', { transaction_id: transactionId });
  }

  // Get announcements
  async getAnnouncements() {
    return ApiService.get('/announcements');
  }

  // Get a specific announcement
  async getAnnouncement(id) {
    return ApiService.get(`/announcements/${id}`);
  }

  // Mark announcement as read
  async markAnnouncementRead(id) {
    return ApiService.post(`/announcements/${id}/mark-read`);
  }
}

// Export singleton instance
export default new StudentService();