<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$studentId = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = trim($_POST['type']);
    $description = trim($_POST['description']);

    if (empty($type) || empty($description)) {
        $error = "❌ Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO complaint (Complaint_type, Description, Student_ID) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $type, $description, $studentId);
        if ($stmt->execute()) {
            $success = "✅ Complaint submitted successfully.";
        } else {
            $error = "❌ Failed to submit complaint. Please try again later.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Complaint - Hostel Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-100 to-blue-100 flex flex-col min-h-screen font-inter">
    <!-- Header -->
    <header class="bg-blue-800 text-white text-center py-4 mt-auto">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-building"></i>
            Hostel Management System
        </h1>
        <a href="student_dashboard.php" class="bg-white text-slate-800 px-4 py-2 rounded shadow hover:bg-gray-100 transition">
            <i class="fas fa-arrow-left mr-1"></i>Back to Dashboard
        </a>
    </header>

    <!-- Label Section -->
    <div class="bg-gradient-to-r from-slate-700 to-slate-800 text-white py-6 px-6 text-center shadow-md">
        <h2 class="text-3xl font-bold tracking-tight flex items-center justify-center gap-3">
            <i class="fas fa-tools text-orange-400"></i>
            Report a Complaint
        </h2>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-10">
        <div class="w-full max-w-2xl bg-white bg-opacity-90 p-8 rounded-2xl shadow-lg backdrop-blur-md">
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i><?= $success ?>
                </div>
            <?php elseif ($error): ?>
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-list mr-2 text-slate-600"></i>Complaint Type
                    </label>
                    <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Select Complaint Type --</option>
                        <option value="Maintenance">🔧 Maintenance</option>
                        <option value="Cleanliness">🧹 Cleanliness</option>
                        <option value="Noise">🔊 Noise</option>
                        <option value="Security">🛡️ Security</option>
                        <option value="Other">📝 Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-comment-alt mr-2 text-slate-600"></i>Description
                    </label>
                    <textarea name="description" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none min-h-[120px]" placeholder="Provide detailed information about the issue..."></textarea>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-yellow-500 text-white py-2 rounded-xl hover:shadow-lg transition font-semibold">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Complaint
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white text-center py-4 mt-auto">
        <p>&copy; <?php echo date("Y"); ?> Hostel Management System. All rights reserved.</p>
    </footer>
</body>
</html>
