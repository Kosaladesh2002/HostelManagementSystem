<?php
session_start();
require 'db_connect.php';

// Allow only wardens
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

$warden_id = $_SESSION['user_id'];

// Fetch visitor logs for students under this warden
$sql = "
    SELECT v.Student_ID, v.Visitor_name, v.Phonenumber
    FROM visitor_log v
    JOIN student s ON v.Student_ID = s.ID
    WHERE s.Warden_ID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $warden_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Visitor List</title>
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
        <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-5xl">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-bold text-green-700 mb-2">
                    <i class="fas fa-users mr-2"></i> Visitor List
                </h2>
                <p class="text-gray-600">View all visitors registered for your students.</p>
            </div>

            <div class="overflow-x-auto mt-6 rounded-lg border border-green-200">
                <table class="min-w-full table-auto">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold"><i class="fas fa-id-badge mr-2"></i>Student ID</th>
                            <th class="px-6 py-3 text-left font-semibold"><i class="fas fa-user mr-2"></i>Visitor Name</th>
                            <th class="px-6 py-3 text-left font-semibold"><i class="fas fa-phone mr-2"></i>Phone Number</th>
                            <th class="px-6 py-3 text-left font-semibold"><i class="fas fa-cog mr-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-green-50 border-b">
                                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['Student_ID']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['Visitor_name']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-800"><?= htmlspecialchars($row['Phonenumber']) ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="delete_visitor.php?log_id=<?= htmlspecialchars($row['Student_ID']) ?>" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow text-sm" onclick="return confirm('Are you sure you want to delete this visitor record?');">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-6 italic text-gray-500">
                                    <i class="fas fa-info-circle mr-2"></i> No visitors found for your students.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center mt-6">
                <a href="warden_dashboard.php" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded shadow text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Warden Panel
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-green-700 text-white text-center py-4 text-sm">
        &copy; <?= date('Y') ?> HostelSync. All rights reserved.
    </footer>
</body>
</html>
