package com.dunco.schoolmanagement.domain.models

import kotlinx.parcelize.Parcelize
import android.os.Parcelable

@Parcelize
data class Class(
    val id: Int,
    val name: String,
    val code: String,
    val description: String?,
    val isActive: Boolean,
    val sections: List<Section> = emptyList()
) : Parcelable

@Parcelize
data class Section(
    val id: Int,
    val name: String,
    val classId: Int,
    val className: String,
    val capacity: Int,
    val currentStudents: Int,
    val isActive: Boolean
) : Parcelable

@Parcelize
data class Subject(
    val id: Int,
    val name: String,
    val code: String,
    val description: String?,
    val classId: Int,
    val className: String,
    val teacherId: Int?,
    val teacherName: String?,
    val isActive: Boolean
) : Parcelable

@Parcelize
data class Grade(
    val id: Int,
    val studentId: Int,
    val studentName: String,
    val subjectId: Int,
    val subjectName: String,
    val examType: String,
    val examName: String,
    val marksObtained: Double,
    val totalMarks: Double,
    val percentage: Double,
    val grade: String,
    val remarks: String?,
    val examDate: String,
    val recordedBy: Int,
    val recordedByName: String,
    val createdAt: String
) : Parcelable

@Parcelize
data class Attendance(
    val id: Int,
    val studentId: Int,
    val studentName: String,
    val classId: Int,
    val className: String,
    val sectionId: Int,
    val sectionName: String,
    val date: String,
    val status: AttendanceStatus,
    val remarks: String?,
    val recordedBy: Int,
    val recordedByName: String,
    val createdAt: String
) : Parcelable

enum class AttendanceStatus {
    PRESENT,
    ABSENT,
    LATE,
    HALF_DAY,
    EXCUSED
}

@Parcelize
data class Assignment(
    val id: Int,
    val title: String,
    val description: String,
    val subjectId: Int,
    val subjectName: String,
    val classId: Int,
    val className: String,
    val dueDate: String,
    val totalMarks: Double,
    val assignedBy: Int,
    val assignedByName: String,
    val isActive: Boolean,
    val createdAt: String,
    val submissions: List<AssignmentSubmission> = emptyList()
) : Parcelable

@Parcelize
data class AssignmentSubmission(
    val id: Int,
    val assignmentId: Int,
    val studentId: Int,
    val studentName: String,
    val submittedAt: String,
    val fileUrl: String?,
    val marksObtained: Double?,
    val feedback: String?,
    val status: SubmissionStatus
) : Parcelable

enum class SubmissionStatus {
    SUBMITTED,
    GRADED,
    LATE,
    NOT_SUBMITTED
}

@Parcelize
data class Timetable(
    val id: Int,
    val classId: Int,
    val className: String,
    val sectionId: Int,
    val sectionName: String,
    val dayOfWeek: Int,
    val dayName: String,
    val periods: List<Period> = emptyList()
) : Parcelable

@Parcelize
data class Period(
    val id: Int,
    val startTime: String,
    val endTime: String,
    val subjectId: Int,
    val subjectName: String,
    val teacherId: Int,
    val teacherName: String,
    val room: String?
) : Parcelable 