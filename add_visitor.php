<?php
session_start();
require 'db_connect.php';

// Allow only wardens
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

// Fetch student IDs for dropdown
$studentQuery = $conn->query("SELECT ID FROM user WHERE role = 'student'");
$studentOptions = '';
while ($row = $studentQuery->fetch_assoc()) {
    $studentOptions .= "<option value='" . $row['ID'] . "'>" . $row['ID'] . "</option>";
}

// Handle visitor submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitor_name = $_POST['visitor_name'];
    $student_id = $_POST['student_id'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO visitor_log (Student_ID, Visitor_name, Phonenumber) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $student_id, $visitor_name, $phone);

    if ($stmt->execute()) {
        $success = "✅ Visitor added successfully!";
    } else {
        $error = "❌ Failed to add visitor: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Add Visitor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-green-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-green-700 text-white p-6 shadow-md flex justify-between items-center">
        <h1 class="text-2xl font-bold">Hostel Management System</h1>
        <span class="text-green-100 font-medium">Warden Dashboard</span>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-2xl">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-green-700 mb-2">
                    <i class="fas fa-user-friends mr-2"></i> Add Visitor
                </h2>
                <p class="text-gray-600">Register a visitor and assign them to a student.</p>
            </div>

            <?php if ($error): ?>
                <p class="text-red-600 text-sm text-center mb-2 font-medium"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="text-green-600 text-sm text-center mb-2 font-medium"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Visitor Name</label>
                    <input type="text" name="visitor_name" required placeholder="Visitor full name" class="w-full border rounded-lg px-4 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                    <select name="student_id" required class="w-full border rounded-lg px-4 py-2">
                        <option value="">-- Select Student ID --</option>
                        <?= $studentOptions ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" required placeholder="Contact number" class="w-full border rounded-lg px-4 py-2">
                </div>

                <div class="flex flex-col items-center gap-3 mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-user-plus mr-2"></i> Add Visitor
                    </button>
                    <a href="warden_dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-green-700 text-white text-center py-4 text-sm">
        &copy; <?= date('Y') ?> HostelSync. All rights reserved.
    </footer>
</body>
</html>
