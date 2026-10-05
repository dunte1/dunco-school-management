<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - {{ $payment->receipt_number ?? 'N/A' }}</title>
    <style>
        @page {
            size: A5;
            margin: 15mm;
        }
        
        body {
            font-family: {{ $standards['fonts']['primary'] }};
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.03;
            z-index: -1;
            pointer-events: none;
        }
        
        .watermark img {
            width: 300px;
            height: 300px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid {{ $standards['colors']['primary'] }};
            padding-bottom: 15px;
        }
        
        .school-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 8px;
            display: block;
        }
        
        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }
        
        .school-motto {
            font-size: 11px;
            color: {{ $standards['colors']['secondary'] }};
            margin: 0 0 8px 0;
            font-style: italic;
        }
        
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            margin: 0;
            text-transform: uppercase;
        }
        
        .receipt-number {
            font-size: 12px;
            color: {{ $standards['colors']['secondary'] }};
            margin: 3px 0 0 0;
        }
        
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid {{ $standards['colors']['primary'] }};
        }
        
        .payer-info {
            flex: 1;
        }
        
        .payment-info {
            flex: 1;
            text-align: right;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 12px;
        }
        
        .info-label {
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            width: 80px;
            flex-shrink: 0;
        }
        
        .info-value {
            color: #333;
        }
        
        .payment-details {
            margin-bottom: 20px;
        }
        
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }
        
        .payment-table th {
            background: {{ $standards['colors']['primary'] }};
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }
        
        .payment-table td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 12px;
        }
        
        .payment-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }
        
        .total-box {
            background: {{ $standards['colors']['primary'] }};
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-align: center;
        }
        
        .total-label {
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .total-amount {
            font-size: 18px;
            font-weight: bold;
        }
        
        .payment-method {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid {{ $standards['colors']['accent'] }};
        }
        
        .method-label {
            font-size: 12px;
            color: {{ $standards['colors']['secondary'] }};
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .method-value {
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
            margin: 0 15px;
        }
        
        .signature-line {
            width: 150px;
            height: 1px;
            background: #333;
            margin: 40px auto 8px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 12px;
            color: {{ $standards['colors']['primary'] }};
        }
        
        .signature-title {
            font-size: 10px;
            color: {{ $standards['colors']['secondary'] }};
            margin-top: 3px;
        }
        
        .qr-section {
            position: absolute;
            bottom: 15px;
            right: 15px;
            text-align: center;
        }
        
        .qr-code {
            width: 60px;
            height: 60px;
        }
        
        .qr-label {
            font-size: 8px;
            color: {{ $standards['colors']['secondary'] }};
            margin-top: 3px;
        }
        
        .footer {
            position: absolute;
            bottom: 15px;
            left: 15px;
            font-size: 9px;
            color: {{ $standards['colors']['secondary'] }};
        }
        
        .thank-you {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: {{ $standards['colors']['primary'] }};
            font-style: italic;
        }
        
        .stamp {
            position: absolute;
            top: 50%;
            right: 30px;
            transform: translateY(-50%);
            opacity: 0.08;
            font-size: 40px;
            color: {{ $standards['colors']['primary'] }};
            font-weight: bold;
            transform: translateY(-50%) rotate(-15deg);
        }
    </style>
</head>
<body>
    @if($watermark)
    <div class="watermark">
        <img src="data:image/png;base64,{{ $watermark }}" alt="School Logo">
    </div>
    @endif
    
    <div class="stamp">PAID</div>
    
    <div class="header">
        @if($school->logo)
        <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
        @endif
        <h1 class="school-name">{{ $school->name }}</h1>
        @if($school->motto)
        <p class="school-motto">{{ $school->motto }}</p>
        @endif
        <h2 class="receipt-title">Fee Receipt</h2>
        <p class="receipt-number">Receipt No: {{ $payment->receipt_number ?? 'N/A' }}</p>
    </div>
    
    <div class="receipt-info">
        <div class="payer-info">
            <div class="info-row">
                <span class="info-label">Student:</span>
                <span class="info-value">{{ $student->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adm No:</span>
                <span class="info-value">{{ $student->admission_number }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Class:</span>
                <span class="info-value">{{ $student->class->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payer:</span>
                <span class="info-value">{{ $payment->payer_name ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="payment-info">
            <div class="info-row">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Time:</span>
                <span class="info-value">{{ $payment->created_at->format('H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ref:</span>
                <span class="info-value">{{ $payment->reference ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value" style="color: #28a745; font-weight: bold;">PAID</span>
            </div>
        </div>
    </div>
    
    <div class="payment-details">
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Fee Item</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->fee_items ?? [] as $item)
                <tr>
                    <td>{{ $item['name'] ?? 'N/A' }}</td>
                    <td>{{ $item['description'] ?? 'N/A' }}</td>
                    <td>KES {{ number_format($item['amount'] ?? 0, 2) }}</td>
                </tr>
                @endforeach
                @if(empty($payment->fee_items))
                <tr>
                    <td>{{ $payment->fee_category ?? 'Fee Payment' }}</td>
                    <td>{{ $payment->description ?? 'General fee payment' }}</td>
                    <td>KES {{ number_format($payment->amount ?? 0, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        
        <div class="total-section">
            <div class="total-box">
                <div class="total-label">Total Amount Paid</div>
                <div class="total-amount">KES {{ number_format($payment->amount ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
    
    <div class="payment-method">
        <div class="method-label">Payment Method</div>
        <div class="method-value">{{ $payment->method ?? 'Cash' }}</div>
    </div>
    
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">Cashier</div>
            <div class="signature-title">Cashier</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">Principal</div>
            <div class="signature-title">Principal</div>
        </div>
    </div>
    
    <div class="qr-section">
        {!! $qr_code !!}
        <div class="qr-label">Scan to Verify</div>
    </div>
    
    <div class="footer">
        Generated on: {{ now()->format('d/m/Y H:i') }}<br>
        This is a computer generated receipt
    </div>
    
    <div class="thank-you">
        Thank you for your payment!
    </div>
</body>
</html>
