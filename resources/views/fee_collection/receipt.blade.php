<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .receipt-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0;
            color: #555;
        }

        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .details-table td {
            padding: 8px 0;
        }

        .details-table td:first-child {
            font-weight: bold;
            width: 150px;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .payment-table th,
        .payment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .payment-table th {
            background-color: #f5f5f5;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 50px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature span {
            border-top: 1px solid #000;
            padding-top: 5px;
            display: inline-block;
            width: 200px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="header">
            <h1>SCM ERP</h1>
            <p>Complete School / College Management</p>
            <h2>FEE RECEIPT</h2>
        </div>

        <table class="details-table">
            <tr>
                <td>Receipt No:</td>
                <td>{{ $payment->receipt_number }}</td>
                <td>Date:</td>
                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <td>Student Name:</td>
                <td>{{ $payment->studentFee->student->name }}</td>
                <td>Email:</td>
                <td>{{ $payment->studentFee->student->email }}</td>
            </tr>
            <tr>
                <td>Payment Method:</td>
                <td>{{ $payment->payment_method }}</td>
            </tr>
        </table>

        <table class="payment-table">
            <thead>
                <tr>
                    <th>Fee Category</th>
                    <th>Total Fee Amount</th>
                    <th>Paid Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->studentFee->category->name }}</td>
                    <td>{{ number_format($payment->studentFee->amount, 2) }}</td>
                    <td>{{ number_format($payment->amount_paid, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: right;">Total Paid:</th>
                    <th> {{ number_format($payment->amount_paid, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="signature">
            <span>Authorized Signature</span>
        </div>

        <div class="footer">
            This is a computer-generated receipt and does not require a physical signature.
        </div>
    </div>
</body>

</html>
