package com.dunco.schoolmanagement.presentation.navigation

import androidx.compose.runtime.Composable
import androidx.navigation.NavHostController
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import com.dunco.schoolmanagement.presentation.screens.auth.LoginScreen
import com.dunco.schoolmanagement.presentation.screens.auth.ForgotPasswordScreen
import com.dunco.schoolmanagement.presentation.screens.auth.ResetPasswordScreen
import com.dunco.schoolmanagement.presentation.screens.dashboard.StudentDashboardScreen
import com.dunco.schoolmanagement.presentation.screens.dashboard.ParentDashboardScreen
import com.dunco.schoolmanagement.presentation.screens.dashboard.TeacherDashboardScreen
import com.dunco.schoolmanagement.presentation.screens.dashboard.AdminDashboardScreen
import com.dunco.schoolmanagement.presentation.screens.splash.SplashScreen

@Composable
fun AppNavigation(navController: NavHostController) {
    NavHost(
        navController = navController,
        startDestination = Screen.Splash.route
    ) {
        // Splash Screen
        composable(Screen.Splash.route) {
            SplashScreen(navController = navController)
        }
        
        // Authentication Screens
        composable(Screen.Login.route) {
            LoginScreen(navController = navController)
        }
        
        composable(Screen.ForgotPassword.route) {
            ForgotPasswordScreen(navController = navController)
        }
        
        composable(Screen.ResetPassword.route) {
            ResetPasswordScreen(navController = navController)
        }
        
        // Dashboard Screens
        composable(Screen.StudentDashboard.route) {
            StudentDashboardScreen(navController = navController)
        }
        
        composable(Screen.ParentDashboard.route) {
            ParentDashboardScreen(navController = navController)
        }
        
        composable(Screen.TeacherDashboard.route) {
            TeacherDashboardScreen(navController = navController)
        }
        
        composable(Screen.AdminDashboard.route) {
            AdminDashboardScreen(navController = navController)
        }
        
        // Academic Screens
        composable(Screen.Grades.route) {
            // GradesScreen(navController = navController)
        }
        
        composable(Screen.Attendance.route) {
            // AttendanceScreen(navController = navController)
        }
        
        composable(Screen.Assignments.route) {
            // AssignmentsScreen(navController = navController)
        }
        
        composable(Screen.Timetable.route) {
            // TimetableScreen(navController = navController)
        }
        
        // Finance Screens
        composable(Screen.Fees.route) {
            // FeesScreen(navController = navController)
        }
        
        composable(Screen.Payments.route) {
            // PaymentsScreen(navController = navController)
        }
        
        composable(Screen.MpesaPayment.route) {
            // MpesaPaymentScreen(navController = navController)
        }
        
        // Library Screens
        composable(Screen.Library.route) {
            // LibraryScreen(navController = navController)
        }
        
        // Hostel Screens
        composable(Screen.Hostel.route) {
            // HostelScreen(navController = navController)
        }
        
        // Transport Screens
        composable(Screen.Transport.route) {
            // TransportScreen(navController = navController)
        }
        
        // Communication Screens
        composable(Screen.Messages.route) {
            // MessagesScreen(navController = navController)
        }
        
        composable(Screen.Notifications.route) {
            // NotificationsScreen(navController = navController)
        }
        
        // Settings Screens
        composable(Screen.Profile.route) {
            // ProfileScreen(navController = navController)
        }
        
        composable(Screen.Settings.route) {
            // SettingsScreen(navController = navController)
        }
    }
} 