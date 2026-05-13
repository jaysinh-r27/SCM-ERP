<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report Card - {{ $result->student->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .receipt-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2px;
            border: 2px solid #000;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
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

        .details-table td:first-child,
        .details-table td:nth-child(3) {
            font-weight: bold;
            width: 150px;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }

        .marks-table th {
            background-color: #f5f5f5;
        }

        .marks-table td:first-child,
        .marks-table th:first-child {
            text-align: left;
        }

        .summary-box {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .summary-box td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
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
            display: table;
            width: 100%;
        }

        .signature div {
            display: table-cell;
            text-align: center;
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
            <h2>STUDENT REPORT CARD</h2>
        </div>

        <table class="details-table">
            <tr>
                <td>Student Name:</td>
                <td>{{ $result->student->name }}</td>
                <td>Email:</td>
                <td>{{ $result->student->email }}</td>
            </tr>
            <tr>
                <td>Exam Name:</td>
                <td>{{ $result->exam->name }}</td>
                <td>Session:</td>
                <td>{{ $result?->exam->session?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Class:</td>
                <td>{{ $result?->studentAdmission?->studentClasses?->name ?? 'N/A' }}</td>
            </tr>
        </table>

        <table class="marks-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Subject Name</th>
                    <th style="width: 25%;">Maximum Marks</th>
                    <th style="width: 25%;">Obtained Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($marks as $mark)
                    <tr>
                        <td>{{ $mark->subject->name }}</td>
                        <td>{{ number_format($mark->max_marks, 2) }}</td>
                        <td>{{ number_format($mark->obtained_marks, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-box">
            <tr>
                <td>Total Marks: {{ number_format($result->total_marks, 2) }}</td>
                <td>Obtained: {{ number_format($result->obtained_marks, 2) }}</td>
                <td>Percentage: {{ $result->percentage }}%</td>
                <td>Grade: {{ $result->grade }}</td>
                <td>Status: <span
                        style="color: {{ $result->status == 1 ? 'green' : 'red' }};">{{ $result->status == 1 ? 'Pass' : 'Fail' }}</span>
                </td>
            </tr>
        </table>

        <div class="signature">
            <div>
                <span>Class Teacher Signature</span>
            </div>
            <div>
                <span>Principal Signature</span>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer generated document. No signature is required for validity unless specifically asked
                for official purposes.</p>
        </div>
    </div>
</body>

</html>
