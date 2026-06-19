<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Library System Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .stats-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .stats-grid td {
            width: 25%;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .stats-value {
            font-size: 24px;
            font-weight: bold;
            color: #2980b9;
        }
        .stats-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #7f8c8d;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }
        table.data-table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #34495e;
            color: white;
        }
        .status-borrowed { color: #f39c12; font-weight: bold; }
        .status-returned { color: #27ae60; font-weight: bold; }
        .status-overdue { color: #c0392b; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Library System Report</h1>
    <p>Generated on: {{ date('F j, Y, g:i a') }}</p>

    <h2>System Overview</h2>
    <table class="stats-grid">
        <tr>
            <td>
                <div class="stats-value">{{ $stats['total_books'] }}</div>
                <div class="stats-label">Total Books</div>
            </td>
            <td>
                <div class="stats-value">{{ $stats['total_members'] }}</div>
                <div class="stats-label">Total Members</div>
            </td>
            <td>
                <div class="stats-value">{{ $stats['active_borrowings'] }}</div>
                <div class="stats-label">Active Borrowings</div>
            </td>
            <td>
                <div class="stats-value">{{ $stats['overdue_books'] }}</div>
                <div class="stats-label">Overdue Books</div>
            </td>
        </tr>
    </table>

    <h2>Recent Borrowing Activity</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Member</th>
                <th>Book</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_borrowings as $record)
            <tr>
                <td>{{ $record->member->name ?? 'Unknown' }}</td>
                <td>{{ $record->book->title ?? 'Unknown' }}</td>
                <td>{{ \Carbon\Carbon::parse($record->borrow_date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($record->due_date)->format('M d, Y') }}</td>
                <td class="status-{{ $record->status }}">{{ ucfirst($record->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
