<?php
session_start();
require 'db_connect.php';

// Only wardens can access this
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

// Check if Room_No is passed
if (!isset($_GET['Room_No'])) {
    echo "Invalid request.";
    exit();
}

$room_no = $_GET['Room_No'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $capacity = $_POST['capacity'];
    $room_type = $_POST['room_type'];
    $ac_type = $_POST['ac_type'];
    $monthly_rent = $_POST['monthly_rent'];
    $occupied_count = $_POST['occupied_count'];

    if ($capacity < 1 || $monthly_rent < 0 || $occupied_count < 0) {
        $error = "❌ Please enter valid numbers.";
    } elseif ($occupied_count > $capacity) {
        $error = "❌ Occupied count cannot exceed room capacity.";
    } else {
        $stmt = $conn->prepare("UPDATE room SET Capacity=?, Room_type=?, ac_type=?, Monthly_rent=?, Occupied_count=? WHERE Room_No=?");
        $stmt->bind_param("issdis", $capacity, $room_type, $ac_type, $monthly_rent, $occupied_count, $room_no);
        if ($stmt->execute()) {
            $success = "✅ Room updated successfully!";
        } else {
            $error = "❌ Error updating room. Please try again.";
        }
        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT * FROM room WHERE Room_No = ?");
$stmt->bind_param("s", $room_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Room not found.";
    exit();
}

$room = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Update Room</title>
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
                    <i class="fas fa-door-open mr-2"></i> Update Room <?= htmlspecialchars($room_no) ?>
                </h2>
                <p class="text-gray-600">Modify room details and occupancy information.</p>
            </div>

            <?php if ($error): ?>
                <p class="text-red-600 text-sm text-center mb-2 font-medium"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="text-green-600 text-sm text-center mb-2 font-medium"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                        <input type="number" name="capacity" value="<?= htmlspecialchars($room['Capacity']) ?>" min="1" required class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Occupied Count</label>
                        <input type="number" name="occupied_count" value="<?= htmlspecialchars($room['Occupied_count']) ?>" min="0" required class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                        <select name="room_type" required class="w-full border rounded-lg px-4 py-2">
                            <option value="Single" <?= $room['Room_type'] == 'Single' ? 'selected' : '' ?>>Single Room</option>
                            <option value="Double" <?= $room['Room_type'] == 'Double' ? 'selected' : '' ?>>Double Room</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AC Type</label>
                        <select name="ac_type" required class="w-full border rounded-lg px-4 py-2">
                            <option value="ac" <?= $room['ac_type'] == 'ac' ? 'selected' : '' ?>>Air Conditioned</option>
                            <option value="non-ac" <?= $room['ac_type'] == 'non-ac' ? 'selected' : '' ?>>Non-AC</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Rent (Rs)</label>
                    <input type="number" step="0.01" name="monthly_rent" value="<?= htmlspecialchars($room['Monthly_rent']) ?>" min="0" required class="w-full border rounded-lg px-4 py-2">
                </div>

                <div class="flex flex-col items-center gap-3 mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-save mr-2"></i> Update Room
                    </button>
                    <a href="view_rooms.php" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Room List
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