<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$currentEmail = $_SESSION['email'];
$success = '';
$error = '';

$stmt = $conn->prepare("SELECT id, name, email, phonenumber FROM user WHERE email = ?");
$stmt->bind_param("s", $currentEmail);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$studentId = $user['id'];
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newName = trim($_POST['name']);
    $newPhone = trim($_POST['phone']);
    $newEmail = trim($_POST['email']);
    $newPassword = trim($_POST['password']);

    if (empty($newName) || empty($newPhone) || empty($newEmail)) {
        $error = "Name, phone number, and email are required.";
    } else {
        $stmt = $conn->prepare("UPDATE user SET name=?, phonenumber=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $newName, $newPhone, $newEmail, $studentId);
        $stmt->execute();
        $stmt->close();

        if (!empty($newPassword)) {
            $stmt = $conn->prepare("UPDATE user SET password=? WHERE id=?");
            $stmt->bind_param("si", $newPassword, $studentId);
            $stmt->execute();
            $stmt->close();
        }

        $success = "Details updated successfully.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Edit My Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-blue-800 text-white p-4 shadow-md flex justify-between items-center">
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
        <h2 class="text-2xl font-semibold text-blue-700 flex items-center justify-center gap-2">
            <i class="fas fa-user-edit"></i> Edit My Details
        </h2>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-10">
        <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-md">
            <?php if ($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 flex items-center"><i class="fas fa-check-circle mr-2"></i><?php echo $success; ?></div>
            <?php endif; ?>
            <form action="" method="POST" class="space-y-5">
                <div>
                    <label class="block mb-1 text-gray-700 font-medium"><i class="fas fa-user mr-1"></i>Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block mb-1 text-gray-700 font-medium"><i class="fas fa-phone mr-1"></i>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phonenumber']); ?>" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block mb-1 text-gray-700 font-medium"><i class="fas fa-envelope mr-1"></i>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>
                <div>
                    <label class="block mb-1 text-gray-700 font-medium"><i class="fas fa-lock mr-1"></i>New Password (optional)</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Update Details
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white text-center py-4">
        <p>&copy; <?php echo date("Y"); ?> Hostel Management System. All rights reserved.</p>
    </footer>
</body>
</html>