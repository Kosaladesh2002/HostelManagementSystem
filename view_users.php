<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';
$query = "SELECT * FROM user";
if ($role_filter !== 'all') {
    $query .= " WHERE role = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $role_filter);
} else {
    $stmt = $conn->prepare($query);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users - HostelSync</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f0fdf4;
    }
    .custom-select {
      background-color: #ecfdf5;
      border: 2px solid #bbf7d0;
      border-radius: 8px;
      padding: 0.6rem 1rem;
      font-size: 1rem;
      color: #065f46;
      appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23065f46' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 0.75rem center;
      background-repeat: no-repeat;
      background-size: 1.25em 1.25em;
      padding-right: 2.5rem;
    }
    .role-badge {
      padding: 4px 12px;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
    }
    .role-admin { background-color: #fef3c7; color: #92400e; }
    .role-student { background-color: #d1fae5; color: #065f46; }
    .role-warden { background-color: #bae6fd; color: #1e40af; }
  </style>
</head>
<body>

<!-- Header -->
<header class="bg-green-700 text-white p-5 shadow-md">
  <div class="max-w-6xl mx-auto flex justify-between items-center">
    <h1 class="text-xl font-bold tracking-wide">Hostel Management System</h1>
    <span class="text-sm font-medium">Admin Panel</span>
  </div>
</header>

<!-- Main -->
<main class="max-w-6xl mx-auto py-8 px-4">
  <!-- Page Title -->
  <div class="mb-6 text-center">
    <h2 class="text-3xl font-bold text-green-800 mb-1"><i class="fas fa-users-cog mr-2"></i>Manage Users</h2>
    <p class="text-gray-600">View, filter, and manage all users from the system.</p>
  </div>

  <!-- Filter -->
  <div class="bg-white p-4 rounded-xl shadow mb-6">
    <form method="GET" class="flex flex-wrap items-center justify-center gap-4">
      <label for="role" class="font-medium text-green-900">Filter by Role:</label>
      <select name="role" class="custom-select" onchange="this.form.submit()">
        <option value="all" <?= $role_filter === 'all' ? 'selected' : '' ?>>All</option>
        <option value="student" <?= $role_filter === 'student' ? 'selected' : '' ?>>Student</option>
        <option value="warden" <?= $role_filter === 'warden' ? 'selected' : '' ?>>Warden</option>
        <option value="admin" <?= $role_filter === 'admin' ? 'selected' : '' ?>>Admin</option>
      </select>
    </form>
  </div>

  <!-- Table -->
  <div class="bg-white p-6 rounded-xl shadow overflow-x-auto">
    <table class="min-w-full text-sm text-center">
      <thead class="bg-green-600 text-white uppercase">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Phone</th>
          <th class="px-4 py-2">Role</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr class="border-b hover:bg-green-50">
          <td class="py-2"><?= htmlspecialchars($row['ID']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['phonenumber']) ?></td>
          <td>
            <span class="role-badge role-<?= $row['role'] ?>">
              <?= ucfirst($row['role']) ?>
            </span>
          </td>
          <td class="space-x-2">
            <a href="edit_user.php?id=<?= urlencode($row['ID']) ?>" class="inline-block px-3 py-1 bg-yellow-400 hover:bg-yellow-500 text-white rounded">
              <i class="fas fa-edit"></i> Edit
            </a>
            <?php if ($row['role'] !== 'admin'): ?>
            <a href="delete_user.php?id=<?= urlencode($row['ID']) ?>" onclick="return confirm('Are you sure?')" class="inline-block px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">
              <i class="fas fa-trash"></i> Delete
            </a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <!-- Back -->
  <div class="text-center mt-6">
    <a href="admin_dashboard.php" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded transition">
      <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
    </a>
  </div>
</main>

</body>
</html>
