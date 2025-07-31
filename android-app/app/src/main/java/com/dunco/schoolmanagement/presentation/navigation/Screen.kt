package com.dunco.schoolmanagement.presentation.navigation

sealed class Screen(val route: String) {
    // Splash
    object Splash : Screen("splash")
    
    // Authentication
    object Login : Screen("login")
    object ForgotPassword : Screen("forgot_password")
    object ResetPassword : Screen("reset_password")
    
    // Dashboards
    object StudentDashboard : Screen("student_dashboard")
    object ParentDashboard : Screen("parent_dashboard")
    object TeacherDashboard : Screen("teacher_dashboard")
    object AdminDashboard : Screen("admin_dashboard")
    
    // Academic
    object Grades : Screen("grades")
    object Attendance : Screen("attendance")
    object Assignments : Screen("assignments")
    object Timetable : Screen("timetable")
    object Subjects : Screen("subjects")
    object Classes : Screen("classes")
    
    // Finance
    object Fees : Screen("fees")
    object Payments : Screen("payments")
    object MpesaPayment : Screen("mpesa_payment")
    object FinancialReports : Screen("financial_reports")
    
    // Library
    object Library : Screen("library")
    object Books : Screen("books")
    object BorrowedBooks : Screen("borrowed_books")
    
    // Hostel
    object Hostel : Screen("hostel")
    object Rooms : Screen("rooms")
    object RoomAllocation : Screen("room_allocation")
    
    // Transport
    object Transport : Screen("transport")
    object Vehicles : Screen("vehicles")
    object Routes : Screen("routes")
    
    // Communication
    object Messages : Screen("messages")
    object Notifications : Screen("notifications")
    object Chat : Screen("chat")
    
    // Settings
    object Profile : Screen("profile")
    object Settings : Screen("settings")
    object ChangePassword : Screen("change_password")
    
    // Documents
    object Documents : Screen("documents")
    object UploadDocument : Screen("upload_document")
    
    // Examination
    object Examinations : Screen("examinations")
    object ExamResults : Screen("exam_results")
    
    // HR (for teachers and admins)
    object Employees : Screen("employees")
    object Payroll : Screen("payroll")
    
    // Analytics (for admins)
    object Analytics : Screen("analytics")
    object Reports : Screen("reports")
} 