<?php
session_start();
include 'db_connect.php';

// Restrict access to admins and wardens
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'warden')) {
    header("Location: login.php");
    exit();
}

// Check if ID is passed
$error = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch student data with prepared statement
    $sql = "SELECT user.name, user.email, user.phonenumber, student.Duration_of_stay 
            FROM user 
            INNER JOIN student ON user.ID = student.ID 
            WHERE user.ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $error = "❌ Student not found.";
    }
    $stmt->close();
} else {
    $error = "❌ No student ID provided.";
}

$role = $_SESSION['role'];
$name = htmlspecialchars($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - HostelSync</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-green-50 font-inter">
    <header class="bg-green-700 text-white py-4 px-6 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex justify-between items-center max-w-6xl mx-auto">
            <h1 class="text-2xl font-bold">Hostel Management System</h1>
            <span class="text-green-100 font-medium">Edit Student</span>
        </div>
    </header>

    <main class="pt-24 pb-8 px-4 flex justify-center items-start min-h-screen">
        <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-xl border-t-4 border-green-600">
            <h2 class="text-3xl font-bold text-center text-green-800 mb-2 flex items-center justify-center gap-2">
                ✏️ Edit Student Details
            </h2>
            <p class="text-center text-gray-500 mb-6">Welcome, <?php echo $name . " (" . ucfirst($role) . ")"; ?>!</p>

            <?php if ($error): ?>
                <div class="text-red-600 font-medium text-center mb-4"><?php echo $error; ?></div>
            <?php elseif (isset($row)): ?>
                <form method="POST" action="update_student.php" class="space-y-4">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <div>
                        <label for="name" class="block text-gray-700 font-medium">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="w-full mt-1 p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400" required>
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 font-medium">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" class="w-full mt-1 p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400" required>
                    </div>
                    <div>
                        <label for="phone" class="block text-gray-700 font-medium">Phone:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phonenumber']); ?>" class="w-full mt-1 p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400" required>
                    </div>
                    <div>
                        <label for="duration" class="block text-gray-700 font-medium">Duration of Stay:</label>
                        <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($row['Duration_of_stay']); ?>" class="w-full mt-1 p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-400" required>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-md transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Update Student
                    </button>
                </form>
            <?php endif; ?>

            <div class="text-center mt-6">
                <a href="<?php echo $role === 'admin' ? 'admin_dashboard.php' : 'view_students.php'; ?>" class="inline-flex items-center gap-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded-md transition">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </main>
</body>
</html>
