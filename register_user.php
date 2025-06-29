<?php
session_start();
include 'db_connect.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $role     = $_POST['role'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($phone) || empty($role) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!in_array($role, ['warden', 'admin'])) {
        $error = "Only 'warden' or 'admin' roles are allowed.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $prefix = ($role === 'warden') ? 'W' : 'A';
            $like = $prefix . '%';

            $stmt = $conn->prepare("SELECT ID FROM user WHERE ID LIKE ? ORDER BY ID DESC LIMIT 1");
            $stmt->bind_param("s", $like);
            $stmt->execute();
            $res = $stmt->get_result();

            $lastNum = ($row = $res->fetch_assoc()) ? intval(substr($row['ID'], 1)) + 1 : 1;
            $newId = $prefix . str_pad($lastNum, 2, '0', STR_PAD_LEFT);
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO user (ID, name, email, phonenumber, role, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $newId, $name, $email, $phone, $role, $password);

            if ($stmt->execute()) {
                $role_stmt = $conn->prepare("INSERT INTO $role (ID) VALUES (?)");
                $role_stmt->bind_param("s", $newId);
                $role_stmt->execute();
                $role_stmt->close();
                $success = "$role registered successfully with ID: $newId";
            } else {
                $error = "Error: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
        $check->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Register | Hostel Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        .bg-image {
            background-image: url('images/bb.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-image bg-fixed backdrop-blur-sm">

    <!-- Header -->
    <header class="bg-green-900 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-user-plus"></i>
            Hostel Management System
        </h1>
        <a href="login.php" class="bg-white text-green-900 px-4 py-2 rounded shadow hover:bg-gray-100 transition">
            <i class="fas fa-arrow-left mr-1"></i>Back to Login
        </a>
    </header>

    <!-- Label -->
    <div class="text-white bg-green-800 py-6 px-6 text-center shadow-md">
        <h2 class="text-3xl font-bold flex items-center justify-center gap-3">
            <i class="fas fa-user-shield text-yellow-400"></i>
            Register Admin or Warden
        </h2>
    </div>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-10 px-4">
        <div class="w-full max-w-md bg-white bg-opacity-95 p-8 rounded-2xl shadow-2xl backdrop-blur-md">
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-4">
                    <i class="fas fa-check-circle mr-2"></i><?= $success ?>
                </div>
            <?php elseif ($error): ?>
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded mb-4">
                    <i class="fas fa-exclamation-circle mr-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                <input type="email" name="email" placeholder="Email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                <input type="text" name="phone" placeholder="Phone Number" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                <select name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                    <option value="">Select Role</option>
                    <option value="warden">Warden</option>
                    <option value="admin">Admin</option>
                </select>
                <input type="password" name="password" placeholder="Password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
                <button type="submit"
                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>Register
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-green-900 text-white text-center py-4">
        &copy; <?= date('Y') ?> Hostel Management System. All rights reserved.
    </footer>
</body>
</html>
