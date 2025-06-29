<?php
session_start();
include 'db_connect.php';

// Only allow admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM payment ORDER BY Payment_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Records - HostelSync</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: linear-gradient(to right, #e0f2f1, #a7ffeb);
        }

        header {
            background-color: #166534;
            color: white;
            padding: 1.25rem 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 1.75rem;
            font-weight: 800;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 1.5rem;
        }

        .card {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #d1fae5;
        }

        .card h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #166534;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        thead {
            background-color: #166534;
            color: white;
        }

        th, td {
            padding: 0.75rem 1rem;
            text-align: center;
            border: 1px solid #cbd5e1;
        }

        tbody tr:hover {
            background-color: #f0fdf4;
        }

        .edit-btn {
            background-color: #22c55e;
            color: white;
        }

        .delete-btn {
            background-color: #ef4444;
            color: white;
        }

        .edit-btn:hover {
            background-color: #16a34a;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }

        .action-btn {
            padding: 0.4rem 0.9rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
            margin: 0 0.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .back-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem auto 0;
            background-color: #166534;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.3s ease;
            width: fit-content;
        }

        .back-btn:hover {
            background-color: #15803d;
        }
    </style>
</head>
<body>

<header>
    <h1>Hostel Management System - Payment Records</h1>
</header>

<div class="dashboard-container">
    <div class="card">
        <h2><i class="fas fa-money-bill-wave"></i> Payment List</h2>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Student ID</th>
                        <th>Amount (Rs.)</th>
                        <th>Payment Date</th>
                        <th>Due Date</th>
                        <th>Penalty (Rs.)</th>
                        <th>Month</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                            $month = date("F Y", strtotime($row['Payment_date']));
                            $payDate = date("M d, Y", strtotime($row['Payment_date']));
                            $dueDate = date("M d, Y", strtotime($row['due_date']));
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Payment_ID']) ?></td>
                            <td><?= htmlspecialchars($row['Student_ID']) ?></td>
                            <td><?= number_format($row['amount'], 2) ?></td>
                            <td><?= $payDate ?></td>
                            <td><?= $dueDate ?></td>
                            <td><?= number_format($row['penalty'], 2) ?></td>
                            <td><?= $month ?></td>
                            <td>
                                <a href="edit_payment.php?id=<?= $row['Payment_ID'] ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_payment.php?id=<?= $row['Payment_ID'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this payment?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href="admin_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
