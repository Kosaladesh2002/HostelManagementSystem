<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Add Room</title>
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
                    <i class="fas fa-door-open mr-2"></i> Add Room
                </h2>
                <p class="text-gray-600">Create a new room and set its configuration for student accommodation.</p>
            </div>

            <form method="POST" action="insert_room.php" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room Number</label>
                    <input type="text" name="room_no" required placeholder="e.g., R101" class="w-full border rounded-lg px-4 py-2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                        <input type="number" name="capacity" required min="1" max="6" placeholder="Number of students" class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Rent (Rs.)</label>
                        <input type="number" name="monthly_rent" required min="1000" placeholder="Amount in rupees" class="w-full border rounded-lg px-4 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                        <select name="room_type" required class="w-full border rounded-lg px-4 py-2">
                            <option value="">-- Select Type --</option>
                            <option value="Single">🛏️ Single</option>
                            <option value="Double">🛏️🛏️ Double</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">AC Type</label>
                        <select name="ac_type" required class="w-full border rounded-lg px-4 py-2">
                            <option value="">-- Select AC Type --</option>
                            <option value="ac">❄️ AC</option>
                            <option value="non-ac">🌿 Non-AC</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col items-center gap-3 mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-plus-circle mr-2"></i> Add Room
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