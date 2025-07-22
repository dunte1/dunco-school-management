<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .details th, .details td { padding: 8px; border-bottom: 1px solid #ddd; }
        .details th { text-align: left; }
        .total { text-align: right; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Receipt</h1>
        </div>
        <div class="details">
            <table>
                <tr><th>Student:</th><td>{{ $student->name }}</td></tr>
                <tr><th>Date:</th><td>{{ $date }}</td></tr>
                <tr><th>Transaction ID:</th><td>{{ $transaction_id }}</td></tr>
                <tr><th>Fee:</th><td>{{ $fee->category }}</td></tr>
                <tr><th>Amount:</th><td class="total">{{ $fee->amount }}</td></tr>
            </table>
        </div>
    </div>
</body>
</html> 