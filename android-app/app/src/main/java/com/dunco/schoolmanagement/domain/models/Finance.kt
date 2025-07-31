package com.dunco.schoolmanagement.domain.models

import kotlinx.parcelize.Parcelize
import android.os.Parcelable

@Parcelize
data class Fee(
    val id: Int,
    val name: String,
    val description: String?,
    val amount: Double,
    val frequency: FeeFrequency,
    val classId: Int?,
    val className: String?,
    val isActive: Boolean,
    val dueDate: String?,
    val createdAt: String
) : Parcelable

enum class FeeFrequency {
    MONTHLY,
    QUARTERLY,
    SEMESTER,
    ANNUAL,
    ONE_TIME
}

@Parcelize
data class FeeStructure(
    val id: Int,
    val name: String,
    val description: String?,
    val academicYear: String,
    val classId: Int,
    val className: String,
    val fees: List<FeeStructureItem> = emptyList(),
    val isActive: Boolean,
    val createdAt: String
) : Parcelable

@Parcelize
data class FeeStructureItem(
    val id: Int,
    val feeId: Int,
    val feeName: String,
    val amount: Double,
    val frequency: FeeFrequency,
    val dueDate: String?
) : Parcelable

@Parcelize
data class StudentFee(
    val id: Int,
    val studentId: Int,
    val studentName: String,
    val feeId: Int,
    val feeName: String,
    val amount: Double,
    val paidAmount: Double,
    val balance: Double,
    val dueDate: String,
    val status: FeeStatus,
    val academicYear: String,
    val createdAt: String
) : Parcelable

enum class FeeStatus {
    PAID,
    PARTIAL,
    UNPAID,
    OVERDUE
}

@Parcelize
data class Payment(
    val id: Int,
    val studentId: Int,
    val studentName: String,
    val feeId: Int,
    val feeName: String,
    val amount: Double,
    val paymentMethod: PaymentMethod,
    val transactionId: String?,
    val referenceNumber: String?,
    val status: PaymentStatus,
    val paidBy: Int,
    val paidByName: String,
    val remarks: String?,
    val paidAt: String,
    val createdAt: String
) : Parcelable

enum class PaymentMethod {
    CASH,
    MPESA,
    BANK_TRANSFER,
    CHEQUE,
    CARD
}

enum class PaymentStatus {
    PENDING,
    COMPLETED,
    FAILED,
    CANCELLED,
    REFUNDED
}

@Parcelize
data class MpesaPayment(
    val id: Int,
    val phoneNumber: String,
    val amount: Double,
    val accountReference: String,
    val transactionDesc: String,
    val checkoutRequestId: String?,
    val merchantRequestId: String?,
    val resultCode: String?,
    val resultDesc: String?,
    val status: MpesaStatus,
    val createdAt: String
) : Parcelize

enum class MpesaStatus {
    PENDING,
    SUCCESS,
    FAILED,
    CANCELLED
}

@Parcelize
data class MpesaStkPushRequest(
    val phoneNumber: String,
    val amount: Double,
    val accountReference: String,
    val transactionDesc: String
) : Parcelable

@Parcelize
data class MpesaStkPushResponse(
    val success: Boolean,
    val checkoutRequestId: String?,
    val merchantRequestId: String?,
    val message: String
) : Parcelable

@Parcelize
data class FinancialReport(
    val totalFees: Double,
    val totalPaid: Double,
    val totalBalance: Double,
    val totalStudents: Int,
    val paidStudents: Int,
    val unpaidStudents: Int,
    val monthlyData: List<MonthlyData> = emptyList()
) : Parcelable

@Parcelize
data class MonthlyData(
    val month: String,
    val totalFees: Double,
    val totalPaid: Double,
    val totalBalance: Double
) : Parcelable 