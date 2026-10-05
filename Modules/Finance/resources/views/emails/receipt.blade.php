<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .receipt-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .content {
            padding: 30px;
        }
        .receipt-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
        .info-value {
            color: #212529;
        }
        .amount-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }
        .amount-section h2 {
            margin: 0 0 10px 0;
            font-size: 32px;
            font-weight: 700;
        }
        .amount-section p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .student-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 25px 0;
        }
        .student-info h3 {
            margin: 0 0 15px 0;
            color: #1976d2;
            font-size: 18px;
        }
        .payment-details {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 20px;
            margin: 25px 0;
        }
        .payment-details h3 {
            margin: 0 0 15px 0;
            color: #f57c00;
            font-size: 18px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 150px;
            height: auto;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .amount-section h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>🎓 Dunco School Management System</h1>
            <p>Payment Receipt</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Receipt Information -->
            <div class="receipt-info">
                <div class="info-row">
                    <span class="info-label">Receipt Number:</span>
                    <span class="info-value"><strong>{{ $receipt->receipt_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Date:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($receipt->payment->payment_date)->format('d M Y, h:i A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ ucfirst($receipt->payment->method) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Transaction Reference:</span>
                    <span class="info-value">{{ $receipt->payment->reference }}</span>
                </div>
                @if($receipt->payment->mpesa_transaction_id)
                <div class="info-row">
                    <span class="info-label">M-Pesa Transaction ID:</span>
                    <span class="info-value">{{ $receipt->payment->mpesa_transaction_id }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-completed">Completed</span>
                    </span>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="amount-section">
                <h2>KES {{ number_format($receipt->amount, 2) }}</h2>
                <p>Amount Paid</p>
            </div>

            <!-- Student Information -->
            @if($student)
            <div class="student-info">
                <h3>👨‍🎓 Student Information</h3>
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value">{{ $student->first_name }} {{ $student->last_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Admission Number:</span>
                    <span class="info-value">{{ $student->admission_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">{{ $student->class->name ?? 'N/A' }}</span>
                </div>
            </div>
            @endif

            <!-- Payment Details -->
            <div class="payment-details">
                <h3>💰 Payment Details</h3>
                <div class="info-row">
                    <span class="info-label">Invoice Number:</span>
                    <span class="info-value">{{ $invoice->invoice_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Term:</span>
                    <span class="info-value">{{ $invoice->term }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Due Date:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</span>
                </div>
                @if($receipt->payment->phone_number)
                <div class="info-row">
                    <span class="info-label">Phone Number:</span>
                    <span class="info-value">{{ $receipt->payment->phone_number }}</span>
                </div>
                @endif
            </div>

            <!-- QR Code for verification -->
            <div class="qr-code">
                <p style="margin-bottom: 10px; color: #6c757d; font-size: 14px;">
                    <strong>Receipt Verification QR Code</strong>
                </p>
                <!-- QR Code would be generated here -->
                <div style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; margin: 0 auto; border-radius: 8px;">
                    <span style="color: #6c757d; font-size: 12px; text-align: center;">
                        QR Code<br>
                        {{ $receipt->receipt_number }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your payment!</strong></p>
            <p>This is an automated receipt. Please keep this for your records.</p>
            <p>For any queries, contact us at: finance@duncowebsolutions.co.ke</p>
            <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
                Generated on {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
            </p>
        </div>
    </div>
</body>
</html>
