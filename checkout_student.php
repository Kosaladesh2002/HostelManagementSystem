<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];

    $stmt = $conn->prepare("SELECT Room_No FROM assigned_to WHERE Student_ID = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $room_no = $row['Room_No'];

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("DELETE FROM assigned_to WHERE Student_ID = ?");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM student WHERE ID = ?");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM user WHERE ID = ?");
            $stmt->bind_param("s", $student_id);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE room SET Occupied_count = Occupied_count - 1 WHERE Room_No = ?");
            $stmt->bind_param("s", $room_no);
            $stmt->execute();

            $conn->commit();
            $success = "✅ Student successfully checked out from Room $room_no.";
        } catch (Exception $e) {
            $conn->rollback();
            $error = "❌ Error during checkout: " . $e->getMessage();
        }
    } else {
        $error = "❌ Student not assigned to any room.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Checkout Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-green-50 min-h-screen flex flex-col">
    <header class="bg-green-700 text-white p-6 shadow-lg flex justify-between items-center">
        <h1 class="text-2xl font-bold">Hostel Management System</h1>
        <span class="text-green-100 font-medium">Warden Dashboard</span>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-8">
        <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-xl">
            <h2 class="text-2xl font-bold text-center text-green-700 mb-4">
                <i class="fas fa-sign-out-alt mr-2"></i> Student Check-Out
            </h2>
            <p class="text-center text-gray-600 mb-6">Select a student to remove their room assignment and account.</p>
            <?php if ($error): ?><p class="text-red-600 text-center font-medium mb-2"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <?php if ($success): ?><p class="text-green-600 text-center font-medium mb-2"><?= htmlspecialchars($success) ?></p><?php endif; ?>

            <form method="POST" class="space-y-4">
                <select name="student_id" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="">-- Select Student --</option>
                    <?php
                    $res = $conn->query("SELECT u.ID, u.name, a.Room_No FROM user u JOIN assigned_to a ON u.ID = a.Student_ID WHERE u.role = 'student' ORDER BY u.name");
                    while ($row = $res->fetch_assoc()) {
                        echo "<option value='{$row['ID']}'>{$row['ID']} - {$row['name']} (Room {$row['Room_No']})</option>";
                    }
                    ?>
                </select>

                <div class="flex flex-col items-center gap-3 mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-sign-out-alt mr-2"></i> Check Out Student
                    </button>
                    <a href="warden_dashboard.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-green-700 text-white text-center py-4 text-sm">
        &copy; <?= date('Y') ?> HostelSync. All rights reserved.
    </footer>
</body>
</html>
