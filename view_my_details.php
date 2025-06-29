<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$currentEmail = $_SESSION['email'];

$stmt = $conn->prepare("SELECT id, name, email, phonenumber FROM user WHERE email = ?");
$stmt->bind_param("s", $currentEmail);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - My Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-blue-800 text-white p-4 shadow-md flex items-center justify-between">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-building"></i>
            Hostel Management System
        </h1>
        <a href="student_dashboard.php" class="bg-white text-blue-800 px-4 py-2 rounded shadow hover:bg-gray-100 transition">
            <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
        </a>
    </header>

    <!-- Page Label -->
    <div class="bg-white shadow-md py-4 px-6 text-center mt-4 mx-4 rounded-lg">
        <h2 class="text-xl md:text-2xl font-semibold text-blue-700 flex items-center justify-center gap-2">
            <i class="fas fa-id-card"></i> My Personal Details
        </h2>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-10">
        <div class="w-full max-w-3xl bg-white p-6 rounded-xl shadow-md overflow-x-auto">
            <table class="w-full table-auto text-left border-collapse">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-4 py-3">Field</th>
                        <th class="px-4 py-3">Information</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="border-b">
                        <td class="px-4 py-3 font-medium"><i class="fas fa-id-badge mr-2 text-blue-600"></i>Student ID</td>
                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($user['id']); ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-3 font-medium"><i class="fas fa-user mr-2 text-blue-600"></i>Full Name</td>
                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($user['name']); ?></td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-3 font-medium"><i class="fas fa-envelope mr-2 text-blue-600"></i>Email</td>
                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($user['email']); ?></td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-medium"><i class="fas fa-phone mr-2 text-blue-600"></i>Phone Number</td>
                        <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($user['phonenumber']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white text-center py-4">
        <p>&copy; <?php echo date("Y"); ?> Hostel Management System. All rights reserved.</p>
    </footer>
</body>
</html>