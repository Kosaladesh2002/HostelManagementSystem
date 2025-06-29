<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $duration = $_POST['duration'];
    $password = $_POST['password'];
    $room_no  = $_POST['room_no'];
    $assignment_date = date('Y-m-d');
    $warden_id = $_SESSION['user_id'];

    $check = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "❌ Email already registered.";
    } else {
        $room_check = $conn->prepare("SELECT Capacity, Occupied_count FROM room WHERE Room_No = ?");
        $room_check->bind_param("s", $room_no);
        $room_check->execute();
        $room_result = $room_check->get_result();

        if ($room_result->num_rows === 0) {
            $error = "❌ Invalid room selected.";
        } else {
            $room_data = $room_result->fetch_assoc();
            if ($room_data['Occupied_count'] >= $room_data['Capacity']) {
                $error = "❌ Selected room is already full. Please choose another.";
            } else {
                $get_max = $conn->query("SELECT MAX(ID) AS max_id FROM user WHERE ID LIKE 'S%'");
                $row = $get_max->fetch_assoc();
                $last_id = $row['max_id'];
                $new_id = $last_id ? 'S' . str_pad((int)substr($last_id, 1) + 1, 2, '0', STR_PAD_LEFT) : 'S01';

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO user (ID, name, email, phonenumber, role, password) VALUES (?, ?, ?, ?, 'student', ?)");
                $stmt->bind_param("sssss", $new_id, $name, $email, $phone, $hashedPassword);
                if ($stmt->execute()) {
                    $stmt2 = $conn->prepare("INSERT INTO student (ID, Duration_of_stay, Warden_ID) VALUES (?, ?, ?)");
                    $stmt2->bind_param("sss", $new_id, $duration, $warden_id);
                    $stmt2->execute();

                    $stmt3 = $conn->prepare("INSERT INTO assigned_to (Student_ID, Room_No, Assignment_date) VALUES (?, ?, ?)");
                    $stmt3->bind_param("sss", $new_id, $room_no, $assignment_date);
                    $stmt3->execute();

                    $update = $conn->prepare("UPDATE room SET Occupied_count = Occupied_count + 1 WHERE Room_No = ?");
                    $update->bind_param("s", $room_no);
                    $update->execute();

                    $success = "✅ Student added with ID $new_id and assigned to Room $room_no!";
                } else {
                    $error = "❌ Failed to add student. Try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Add Student</title>
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
                <i class="fas fa-user-plus mr-2"></i> Add New Student
            </h2>
            <?php if ($error): ?><p class="text-red-600 text-center font-medium mb-2"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <?php if ($success): ?><p class="text-green-600 text-center font-medium mb-2"><?= htmlspecialchars($success) ?></p><?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" required class="w-full px-4 py-2 border rounded-lg">
                <input type="email" name="email" placeholder="Email Address" required class="w-full px-4 py-2 border rounded-lg">
                <input type="text" name="phone" placeholder="Phone Number" required class="w-full px-4 py-2 border rounded-lg">
                <input type="text" name="duration" placeholder="Duration of Stay (Months)" required class="w-full px-4 py-2 border rounded-lg">
                <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded-lg">

                <select name="room_no" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="">-- Select Room --</option>
                    <?php
                    $all_rooms = $conn->query("SELECT Room_No, Capacity, Occupied_count FROM room");
                    while ($room_option = $all_rooms->fetch_assoc()) {
                        $r_no = $room_option['Room_No'];
                        $cap = $room_option['Capacity'];
                        $occ = $room_option['Occupied_count'];
                        $full = ($occ >= $cap);
                        $disabled = $full ? 'disabled' : '';
                        $label = "Room $r_no ($occ/$cap" . ($full ? " - Full" : "") . ")";
                        echo "<option value='$r_no' $disabled>$label</option>";
                    }
                    ?>
                </select>

                <div class="flex flex-col items-center gap-3 mt-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow">
                        <i class="fas fa-plus-circle mr-2"></i> Add Student
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
