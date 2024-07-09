<!DOCTYPE html>
<html>
<head>
    <title>Students Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Students Report</h2>
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Filiere</th>
                <th>Niveau</th>
                <th>Section</th>
                <th>Engagement</th>
                <th>Status</th>
                <th>Amount Paid</th>
                <th>Join Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->studentId }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->filiere->name }}</td>
                    <td>{{ $student->niveau->name }}</td>
                    <td>{{ $student->section->name }}</td>
                    <td>{{ $student->billMethod->name }}</td>
                    <td>{{ $student->status->name }}</td>
                    <td>{{ $student->amount_paid }}</td>
                    <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
