package com.dunco.schoolmanagement.data.api

import com.dunco.schoolmanagement.domain.models.*
import retrofit2.http.*

interface FinanceApi {
    
    // Fees
    @GET("finance/fees")
    suspend fun getFees(): ApiResponse<List<Fee>>
    
    @GET("finance/fees/{id}")
    suspend fun getFee(@Path("id") id: Int): ApiResponse<Fee>
    
    @GET("finance/fee-structures")
    suspend fun getFeeStructures(): ApiResponse<List<FeeStructure>>
    
    @GET("finance/fee-structures/{id}")
    suspend fun getFeeStructure(@Path("id") id: Int): ApiResponse<FeeStructure>
    
    // Student Fees
    @GET("finance/student-fees")
    suspend fun getStudentFees(
        @Query("student_id") studentId: Int? = null,
        @Query("status") status: String? = null,
        @Query("academic_year") academicYear: String? = null
    ): ApiResponse<List<StudentFee>>
    
    @GET("finance/student-fees/{id}")
    suspend fun getStudentFee(@Path("id") id: Int): ApiResponse<StudentFee>
    
    // Payments
    @GET("finance/payments")
    suspend fun getPayments(
        @Query("student_id") studentId: Int? = null,
        @Query("payment_method") paymentMethod: String? = null,
        @Query("status") status: String? = null,
        @Query("date_from") dateFrom: String? = null,
        @Query("date_to") dateTo: String? = null
    ): ApiResponse<List<Payment>>
    
    @GET("finance/payments/{id}")
    suspend fun getPayment(@Path("id") id: Int): ApiResponse<Payment>
    
    @POST("finance/payments")
    suspend fun createPayment(@Body payment: CreatePaymentRequest): ApiResponse<Payment>
    
    @PUT("finance/payments/{id}")
    suspend fun updatePayment(@Path("id") id: Int, @Body payment: UpdatePaymentRequest): ApiResponse<Payment>
    
    @DELETE("finance/payments/{id}")
    suspend fun deletePayment(@Path("id") id: Int): ApiResponse<Unit>
    
    // M-Pesa Integration
    @POST("finance/mpesa/stk-push")
    suspend fun initiateMpesaPayment(@Body request: MpesaStkPushRequest): ApiResponse<MpesaStkPushResponse>
    
    @GET("finance/mpesa/status/{checkoutRequestId}")
    suspend fun checkMpesaStatus(@Path("checkoutRequestId") checkoutRequestId: String): ApiResponse<MpesaPayment>
    
    @POST("finance/mpesa/callback")
    suspend fun mpesaCallback(@Body callback: MpesaCallbackRequest): ApiResponse<Unit>
    
    // Financial Reports
    @GET("finance/reports/summary")
    suspend fun getFinancialSummary(
        @Query("academic_year") academicYear: String? = null,
        @Query("class_id") classId: Int? = null
    ): ApiResponse<FinancialReport>
    
    @GET("finance/reports/monthly")
    suspend fun getMonthlyReport(
        @Query("year") year: Int,
        @Query("month") month: Int? = null
    ): ApiResponse<List<MonthlyData>>
    
    @GET("finance/reports/student/{studentId}")
    suspend fun getStudentFinancialReport(@Path("studentId") studentId: Int): ApiResponse<StudentFinancialReport>
}

data class CreatePaymentRequest(
    val studentId: Int,
    val feeId: Int,
    val amount: Double,
    val paymentMethod: PaymentMethod,
    val transactionId: String? = null,
    val referenceNumber: String? = null,
    val remarks: String? = null
)

data class UpdatePaymentRequest(
    val status: PaymentStatus,
    val remarks: String? = null
)

data class MpesaCallbackRequest(
    val checkoutRequestId: String,
    val resultCode: String,
    val resultDesc: String,
    val amount: Double,
    val mpesaReceiptNumber: String?,
    val transactionDate: String
)

data class StudentFinancialReport(
    val studentId: Int,
    val studentName: String,
    val totalFees: Double,
    val totalPaid: Double,
    val totalBalance: Double,
    val feeDetails: List<StudentFee>,
    val paymentHistory: List<Payment>
) 