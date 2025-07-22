<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .container { width: 80%; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; }
        .header h1 { margin: 0; }
        .school-details { text-align: center; margin-bottom: 20px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { border: 1px solid #ddd; padding: 8px; }
        .details-table th { background-color: #f2f2f2; text-align: left; }
        .footer { text-align: center; margin-top: 30px; font-size: 0.8em; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- <img src="{{ public_path('images/logo.png') }}" alt="School Logo"> --}}
            <h1>Dunco School</h1>
        </div>
        <div class="school-details">
            <p>123 Education Lane, Knowledge City, 10100</p>
            <p>contact@duncoschool.com | +254 700 000 000</p>
        </div>

        <h3 style="text-align: center;">Payment Receipt</h3>

        <table class="details-table">
            <tr>
                <th>Receipt Number</th>
                <td>{{ $payment->reference }}</td>
                <th>Date & Time</th>
                <td>{{ $payment->payment_date->format('M d, Y h:i A') }}</td>
            </tr>
            <tr>
                <th>Student Name</th>
                <td>{{ $payment->student->name }}</td>
                <th>Admission No.</th>
                <td>{{ $payment->student->admission_number }}</td>
            </tr>
            <tr>
                <th>Class</th>
                <td colspan="3">{{ $payment->student->currentClass?->name }}</td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th>Fee Category</th>
                    <th>Payment Method</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->fee->category }}</td>
                    <td>{{ ucfirst($payment->method) }}</td>
                    <td>KES {{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="details-table" style="width: 50%; float: right;">
             <tr>
                <th>Total Fee</th>
                <td>KES {{ number_format($payment->fee->amount, 2) }}</td>
            </tr>
            <tr>
                <th>Total Paid</th>
                <td>KES {{ number_format($payment->fee->payments()->sum('amount'), 2) }}</td>
            </tr>
            <tr>
                <th>Balance Remaining</th>
                <td>KES {{ number_format($payment->fee->amount - $payment->fee->payments()->sum('amount'), 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>This is a system-generated receipt. No signature is required.</p>
        </div>
    </div>
</body>
</html> 