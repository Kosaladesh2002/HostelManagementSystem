<?php
session_start();

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "<p class='text-red-500'>❌ Invalid request.</p><a href='manage_users.php' class='text-blue-500 hover:underline'>← Back to Manage Users</a>";
    exit();
}

$user_id = $_GET['id'];
$success = '';
$error = '';

// Fetch user info
$stmt = $conn->prepare("SELECT * FROM user WHERE ID = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "<p class='text-red-500'>❌ Invalid user ID.</p><a href='manage_users.php' class='text-blue-500 hover:underline'>← Back to Manage Users</a>";
    exit();
}

$user = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);

    $check = $conn->prepare("SELECT * FROM user WHERE email = ? AND ID != ?");
    $check->bind_param("ss", $new_email, $user_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $error = "❌ Email already in use.";
    } else {
        $update = $conn->prepare("UPDATE user SET name = ?, email = ?, phonenumber = ? WHERE ID = ?");
        $update->bind_param("ssss", $new_name, $new_email, $new_phone, $user_id);
        if ($update->execute()) {
            $success = "✅ User updated successfully.";
            $user['name'] = $new_name;
            $user['email'] = $new_email;
            $user['phonenumber'] = $new_phone;
        } else {
            $error = "❌ Update failed.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User - HostelSync</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #e0f2f1, #a7ffeb);
      margin: 0;
    }
    header {
      background-color: #166534;
      color: white;
      padding: 1.25rem 2rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    header h1 {
      font-size: 1.75rem;
      font-weight: 800;
    }
    .form-box {
      background-color: white;
      max-width: 500px;
      margin: 3rem auto;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      border: 1px solid #d1fae5;
    }
    .form-box h2 {
      color: #166534;
      font-size: 1.75rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .form-group label {
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: block;
      color: #064e3b;
    }
    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #cbd5e1;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      font-size: 0.875rem;
    }
    .form-group input:focus {
      outline: none;
      border-color: #16a34a;
      box-shadow: 0 0 0 3px rgba(22, 165, 74, 0.2);
    }
    .submit-btn {
      background-color: #166534;
      color: white;
      padding: 0.75rem;
      border: none;
      width: 100%;
      border-radius: 0.5rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }
    .submit-btn:hover {
      background-color: #15803d;
    }
    .back-link {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
      color: #166534;
      font-weight: 500;
      text-decoration: none;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .success {
      color: #16a34a;
      text-align: center;
      margin-bottom: 1rem;
    }
    .error {
      color: #dc2626;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <header>
    <h1>Hostel Management System</h1>
  </header>

  <div class="form-box">
    <h2><i class="fas fa-user-edit"></i> Edit User</h2>

    <?php if ($success) echo "<div class='success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
      <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>

      <div class="form-group">
        <label>Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phonenumber']) ?>" required>
      </div>

      <button type="submit" class="submit-btn">
        <i class="fas fa-save"></i> Update
      </button>
    </form>

    <a href="view_users.php" class="back-link">
      <i class="fas fa-arrow-left"></i>&nbsp;Back to Manage Users
    </a>
  </div>
</body>
</html>
