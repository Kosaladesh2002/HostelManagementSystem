<?php
session_start();
require_once 'db_connect.php';

// Only admin or warden can access this
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'warden' && $_SESSION['role'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

// Handle status update if requested
if (isset($_GET['action']) && $_GET['action'] === 'resolve' && isset($_GET['id'])) {
    $complaint_id = intval($_GET['id']);

    // Update complaint status to resolved
    $update_stmt = $conn->prepare("UPDATE complaint SET Status = 'Resolved' WHERE Complaint_ID = ?");
    $update_stmt->bind_param("i", $complaint_id);

    if ($update_stmt->execute()) {
        $success_message = "Complaint #$complaint_id has been marked as resolved successfully!";
    } else {
        $error_message = "Failed to update complaint status. Please try again.";
    }
    $update_stmt->close();
}

// Fetch complaints with student names and status
$complaints = $conn->query("SELECT c.Complaint_ID, c.Complaint_type, c.Description, c.Student_ID, c.Status, u.name AS student_name FROM complaint c JOIN user u ON c.Student_ID = u.ID ORDER BY c.Complaint_ID ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - View Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-green-50 font-sans">
    <header class="bg-green-700 text-white py-4 px-6 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">Hostel Management System</h1>
        <span class="font-semibold text-sm capitalize"><?php echo $_SESSION['role']; ?> Dashboard</span>
    </header>

    <main class="max-w-6xl mx-auto p-6">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-2xl font-semibold text-green-800 mb-4 text-center"><i class="fas fa-exclamation-circle mr-2"></i>Student Complaints</h2>

            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <?php if ($complaints->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-green-700 text-sm">
                        <thead class="bg-green-700 text-white">
                            <tr>
                                <th class="px-4 py-2 border-r">ID</th>
                                <th class="px-4 py-2 border-r">Student</th>
                                <th class="px-4 py-2 border-r">Type</th>
                                <th class="px-4 py-2 border-r">Description</th>
                                <th class="px-4 py-2 border-r">Status</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $complaints->fetch_assoc()): ?>
                            <tr class="border-t border-green-200">
                                <td class="px-4 py-2 border-r font-medium text-gray-800"><?= $row['Complaint_ID'] ?></td>
                                <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['student_name']) ?> (<?= $row['Student_ID'] ?>)</td>
                                <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['Complaint_type']) ?></td>
                                <td class="px-4 py-2 border-r"><?= htmlspecialchars($row['Description']) ?></td>
                                <td class="px-4 py-2 border-r font-semibold <?= $row['Status'] === 'Pending' ? 'text-yellow-500' : 'text-green-600' ?>">
                                    <?= $row['Status'] === 'Pending' ? 'Pending' : 'Resolved' ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if ($row['Status'] === 'Pending'): ?>
                                        <a href="view_complaints.php?action=resolve&id=<?= $row['Complaint_ID'] ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold inline-flex items-center"><i class="fas fa-check-circle mr-1"></i>Resolve</a>
                                    <?php else: ?>
                                        <span class="text-green-600 font-semibold"><i class="fas fa-check"></i> Done</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500">No complaints found.</p>
            <?php endif; ?>

            <div class="text-center mt-6">
                <a href="<?= ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'warden_dashboard.php' ?>" class="bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-6 rounded inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </main>
</body>
</html>
