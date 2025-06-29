<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT id, name FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc();
$student_id = $userInfo['id'];
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM payment WHERE Student_ID = ? ORDER BY Payment_date DESC");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - My Payments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
<header class="bg-blue-800 text-white p-4 shadow-md flex items-center justify-between">
    <h1 class="text-xl font-bold flex items-center gap-2">
        <i class="fas fa-building"></i> Hostel Management System
    </h1>
    <a href="student_dashboard.php" class="bg-white text-blue-800 px-4 py-2 rounded shadow hover:bg-gray-100 transition">
        <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
    </a>
</header>

<div class="bg-white shadow-md py-4 px-6 text-center mt-4 mx-4 rounded-lg">
    <h2 class="text-xl md:text-2xl font-semibold text-blue-700 flex items-center justify-center gap-2">
        <i class="fas fa-credit-card"></i> My Payment History
    </h2>
</div>

<main class="flex-grow flex items-center justify-center py-10">
    <div class="w-full max-w-5xl bg-white p-6 rounded-xl shadow-md overflow-x-auto">
        <?php if ($result->num_rows > 0): ?>
        <table class="w-full table-auto text-left border-collapse">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="px-4 py-3">Payment ID</th>
                    <th class="px-4 py-3">Amount (Rs.)</th>
                    <th class="px-4 py-3">Penalty</th>
                    <th class="px-4 py-3">Paid On</th>
                    <th class="px-4 py-3">Due Date</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="px-4 py-3 font-mono text-sm bg-slate-100 rounded text-slate-700">#<?= htmlspecialchars($row['Payment_ID']) ?></td>
                    <td class="px-4 py-3 font-semibold text-green-700">Rs. <?= number_format($row['amount'], 2) ?></td>
                    <td class="px-4 py-3 font-semibold <?= $row['penalty'] > 0 ? 'text-red-600' : 'text-green-600' ?>">
                        <?= $row['penalty'] > 0 ? 'Rs. ' . number_format($row['penalty'], 2) : 'Rs. 0.00' ?>
                    </td>
                    <td class="px-4 py-3 text-slate-600 font-medium">
                        <?= date("M d, Y", strtotime($row['Payment_date'])) ?>
                    </td>
                    <td class="px-4 py-3 text-slate-600 font-medium">
                        <?= date("M d, Y", strtotime($row['due_date'])) ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center text-slate-500 py-8">
            <i class="fas fa-receipt text-4xl mb-2"></i>
            <p>No payment records found.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<footer class="bg-blue-800 text-white text-center py-4">
    <p>&copy; <?= date("Y") ?> Hostel Management System. All rights reserved.</p>
</footer>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
