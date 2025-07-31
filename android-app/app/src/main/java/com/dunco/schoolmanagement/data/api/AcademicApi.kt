package com.dunco.schoolmanagement.data.api

import com.dunco.schoolmanagement.domain.models.*
import retrofit2.http.*

interface AcademicApi {
    
    // Classes and Sections
    @GET("academic/classes")
    suspend fun getClasses(): ApiResponse<List<Class>>
    
    @GET("academic/classes/{id}")
    suspend fun getClass(@Path("id") id: Int): ApiResponse<Class>
    
    @GET("academic/sections")
    suspend fun getSections(): ApiResponse<List<Section>>
    
    // Subjects
    @GET("academic/subjects")
    suspend fun getSubjects(): ApiResponse<List<Subject>>
    
    @GET("academic/subjects/{id}")
    suspend fun getSubject(@Path("id") id: Int): ApiResponse<Subject>
    
    // Grades
    @GET("academic/grades")
    suspend fun getGrades(
        @Query("student_id") studentId: Int? = null,
        @Query("subject_id") subjectId: Int? = null,
        @Query("exam_type") examType: String? = null,
        @Query("date_from") dateFrom: String? = null,
        @Query("date_to") dateTo: String? = null
    ): ApiResponse<List<Grade>>
    
    @GET("academic/grades/{id}")
    suspend fun getGrade(@Path("id") id: Int): ApiResponse<Grade>
    
    @POST("academic/grades")
    suspend fun createGrade(@Body grade: CreateGradeRequest): ApiResponse<Grade>
    
    @PUT("academic/grades/{id}")
    suspend fun updateGrade(@Path("id") id: Int, @Body grade: UpdateGradeRequest): ApiResponse<Grade>
    
    @DELETE("academic/grades/{id}")
    suspend fun deleteGrade(@Path("id") id: Int): ApiResponse<Unit>
    
    // Attendance
    @GET("academic/attendance")
    suspend fun getAttendance(
        @Query("student_id") studentId: Int? = null,
        @Query("class_id") classId: Int? = null,
        @Query("date") date: String? = null,
        @Query("date_from") dateFrom: String? = null,
        @Query("date_to") dateTo: String? = null
    ): ApiResponse<List<Attendance>>
    
    @GET("academic/attendance/{id}")
    suspend fun getAttendanceRecord(@Path("id") id: Int): ApiResponse<Attendance>
    
    @POST("academic/attendance")
    suspend fun createAttendance(@Body attendance: CreateAttendanceRequest): ApiResponse<Attendance>
    
    @PUT("academic/attendance/{id}")
    suspend fun updateAttendance(@Path("id") id: Int, @Body attendance: UpdateAttendanceRequest): ApiResponse<Attendance>
    
    @DELETE("academic/attendance/{id}")
    suspend fun deleteAttendance(@Path("id") id: Int): ApiResponse<Unit>
    
    // Assignments
    @GET("academic/assignments")
    suspend fun getAssignments(
        @Query("student_id") studentId: Int? = null,
        @Query("subject_id") subjectId: Int? = null,
        @Query("class_id") classId: Int? = null,
        @Query("status") status: String? = null
    ): ApiResponse<List<Assignment>>
    
    @GET("academic/assignments/{id}")
    suspend fun getAssignment(@Path("id") id: Int): ApiResponse<Assignment>
    
    @POST("academic/assignments")
    suspend fun createAssignment(@Body assignment: CreateAssignmentRequest): ApiResponse<Assignment>
    
    @PUT("academic/assignments/{id}")
    suspend fun updateAssignment(@Path("id") id: Int, @Body assignment: UpdateAssignmentRequest): ApiResponse<Assignment>
    
    @DELETE("academic/assignments/{id}")
    suspend fun deleteAssignment(@Path("id") id: Int): ApiResponse<Unit>
    
    // Assignment Submissions
    @GET("academic/assignments/{id}/submissions")
    suspend fun getAssignmentSubmissions(@Path("id") assignmentId: Int): ApiResponse<List<AssignmentSubmission>>
    
    @POST("academic/assignments/{id}/submit")
    suspend fun submitAssignment(
        @Path("id") assignmentId: Int,
        @Body submission: SubmitAssignmentRequest
    ): ApiResponse<AssignmentSubmission>
    
    @PUT("academic/submissions/{id}/grade")
    suspend fun gradeSubmission(
        @Path("id") submissionId: Int,
        @Body grade: GradeSubmissionRequest
    ): ApiResponse<AssignmentSubmission>
    
    // Timetable
    @GET("academic/timetable")
    suspend fun getTimetable(
        @Query("class_id") classId: Int? = null,
        @Query("section_id") sectionId: Int? = null
    ): ApiResponse<List<Timetable>>
    
    @GET("academic/timetable/{id}")
    suspend fun getTimetableById(@Path("id") id: Int): ApiResponse<Timetable>
}

data class CreateGradeRequest(
    val studentId: Int,
    val subjectId: Int,
    val examType: String,
    val examName: String,
    val marksObtained: Double,
    val totalMarks: Double,
    val remarks: String? = null,
    val examDate: String
)

data class UpdateGradeRequest(
    val marksObtained: Double,
    val totalMarks: Double,
    val remarks: String? = null
)

data class CreateAttendanceRequest(
    val studentId: Int,
    val date: String,
    val status: AttendanceStatus,
    val remarks: String? = null
)

data class UpdateAttendanceRequest(
    val status: AttendanceStatus,
    val remarks: String? = null
)

data class CreateAssignmentRequest(
    val title: String,
    val description: String,
    val subjectId: Int,
    val classId: Int,
    val dueDate: String,
    val totalMarks: Double
)

data class UpdateAssignmentRequest(
    val title: String,
    val description: String,
    val dueDate: String,
    val totalMarks: Double,
    val isActive: Boolean
)

data class SubmitAssignmentRequest(
    val fileUrl: String? = null,
    val remarks: String? = null
)

data class GradeSubmissionRequest(
    val marksObtained: Double,
    val feedback: String? = null
) 