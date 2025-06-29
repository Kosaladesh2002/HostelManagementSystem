<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT id, name, email, phonenumber FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$userInfo = $result->fetch_assoc();
$studentId = $userInfo['id'];
$stmt->close();

$stmt = $conn->prepare("SELECT amount, Payment_date, due_date FROM payment WHERE Student_ID = ? ORDER BY Payment_date DESC");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$payments = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - HostelSync</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-gray-100 to-blue-100 flex flex-col min-h-screen font-inter">
    <!-- Header -->
    <header class="bg-blue-800 text-white p-4 shadow-md flex items-center justify-between">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-building"></i>
            Hostel Management System
        </h1>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-slate-100">Student Portal</span>
            <a href="logout.php" class="bg-red-600 px-4 py-2 rounded shadow hover:bg-red-700 transition">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>

    <!-- Label Bar -->
    <div class="bg-blue-700 text-white py-6 px-6 text-center shadow-md">
        <h2 class="text-3xl font-bold tracking-tight flex items-center justify-center gap-3">
            <img src="images/student.png" class="h-8 w-8" alt="Dashboard Icon">
            Student Dashboard
        </h2>
    </div>

    <!-- Main Content -->
    <main class="flex-grow p-6 max-w-5xl mx-auto">
        <section class="mb-10">
            <div class="bg-white bg-opacity-90 p-6 rounded-2xl shadow-lg">
                <h3 class="text-2xl font-bold text-blue-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-user"></i> Welcome, <?= htmlspecialchars($userInfo['name']) ?>!
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-lg bg-gray-100">
                        <p class="font-semibold text-gray-600">Full Name</p>
                        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($userInfo['name']) ?></p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-100">
                        <p class="font-semibold text-gray-600">Email</p>
                        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($userInfo['email']) ?></p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-100">
                        <p class="font-semibold text-gray-600">Phone Number</p>
                        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($userInfo['phonenumber']) ?></p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-100">
                        <p class="font-semibold text-gray-600">Student ID</p>
                        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($studentId) ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-10">
            <div class="bg-white bg-opacity-90 p-6 rounded-2xl shadow-lg">
                <h3 class="text-2xl font-bold text-green-600 mb-4 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave"></i> Payment History
                </h3>
                <?php if ($payments->num_rows > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php while ($row = $payments->fetch_assoc()): ?>
                            <li class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-semibold text-gray-800">Rs. <?= number_format($row['amount'], 2) ?></p>
                                    <p class="text-sm text-gray-500">Paid: <?= date("M d, Y", strtotime($row['Payment_date'])) ?> | Due: <?= date("M d, Y", strtotime($row['due_date'])) ?></p>
                                </div>
                                <span class="text-green-600 font-bold">✔ Paid</span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-center text-gray-500">No payment records found.</p>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <div class="bg-white bg-opacity-90 p-6 rounded-2xl shadow-lg">
                <h3 class="text-2xl font-bold text-yellow-600 mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="view_my_details.php" class="bg-blue-800 text-white px-5 py-3 rounded-lg shadow hover:bg-blue-900">
                        <i class="fas fa-eye mr-2"></i> View My Details
                    </a>
                    <a href="view_my_payments.php" class="bg-green-600 text-white px-5 py-3 rounded-lg shadow hover:bg-green-700">
                        <i class="fas fa-receipt mr-2"></i> View Payments
                    </a>
                    <a href="edit_my_details.php" class="bg-purple-600 text-white px-5 py-3 rounded-lg shadow hover:bg-purple-700">
                        <i class="fas fa-edit mr-2"></i> Edit Details
                    </a>
                    <a href="report_complaint.php" class="bg-yellow-600 text-white px-5 py-3 rounded-lg shadow hover:bg-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Report Issue
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white text-center py-4 mt-auto">
        <p>&copy; <?= date("Y") ?> Hostel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
