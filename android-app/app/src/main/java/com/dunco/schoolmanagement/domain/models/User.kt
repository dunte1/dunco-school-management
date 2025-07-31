package com.dunco.schoolmanagement.domain.models

import kotlinx.parcelize.Parcelize
import android.os.Parcelable

@Parcelize
data class User(
    val id: Int,
    val name: String,
    val email: String,
    val phone: String?,
    val role: UserRole,
    val avatar: String?,
    val isActive: Boolean,
    val lastLoginAt: String?,
    val createdAt: String,
    val updatedAt: String,
    // Student specific fields
    val studentId: String? = null,
    val admissionNumber: String? = null,
    val dateOfBirth: String? = null,
    val gender: String? = null,
    val address: String? = null,
    val parentId: Int? = null,
    val classId: Int? = null,
    val sectionId: Int? = null,
    // Teacher specific fields
    val employeeId: String? = null,
    val department: String? = null,
    val qualification: String? = null,
    val joiningDate: String? = null,
    // Parent specific fields
    val children: List<Student>? = null
) : Parcelable

enum class UserRole {
    STUDENT,
    PARENT,
    TEACHER,
    ADMIN,
    LIBRARIAN,
    ACCOUNTANT,
    TRANSPORT_MANAGER,
    HOSTEL_MANAGER
}

@Parcelize
data class Student(
    val id: Int,
    val name: String,
    val admissionNumber: String,
    val classId: Int,
    val className: String,
    val sectionId: Int,
    val sectionName: String,
    val parentId: Int,
    val parentName: String,
    val isActive: Boolean
) : Parcelable

@Parcelize
data class AuthResponse(
    val user: User,
    val token: String,
    val refreshToken: String,
    val expiresAt: String
) : Parcelable

@Parcelize
data class LoginRequest(
    val email: String,
    val password: String,
    val deviceToken: String? = null
) : Parcelable

@Parcelize
data class RefreshTokenRequest(
    val refreshToken: String
) : Parcelable 