<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM room ORDER BY Room_No";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HostelSync - Room List</title>
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
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-6xl">
      <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-green-700 mb-2">
          <i class="fas fa-bed mr-2"></i> Room List
        </h2>
        <p class="text-gray-600">View and manage all hostel rooms with real-time occupancy status.</p>
      </div>

      <?php if ($result->num_rows > 0): ?>
        <div class="overflow-x-auto">
          <table class="min-w-full table-auto border border-green-200 rounded-lg shadow-md">
            <thead>
              <tr class="bg-green-700 text-white">
                <th class="px-4 py-2 text-left">Room No</th>
                <th class="px-4 py-2 text-left">Capacity</th>
                <th class="px-4 py-2 text-left">Room Type</th>
                <th class="px-4 py-2 text-left">AC Type</th>
                <th class="px-4 py-2 text-left">Monthly Rent</th>
                <th class="px-4 py-2 text-left">Occupancy</th>
                <th class="px-4 py-2 text-left">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="bg-white border-b hover:bg-green-50">
                  <td class="px-4 py-2 font-semibold"><?= htmlspecialchars($row['Room_No']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['Capacity']) ?> persons</td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['Room_type']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($row['ac_type']) ?></td>
                  <td class="px-4 py-2">Rs. <?= number_format($row['Monthly_rent']) ?></td>
                  <td class="px-4 py-2"><?= $row['Occupied_count'] ?>/<?= $row['Capacity'] ?></td>
                  <td class="px-4 py-2">
                    <a href="update_room.php?Room_No=<?= urlencode($row['Room_No']) ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                      <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-center py-12">
          <i class="fas fa-bed text-6xl text-gray-300 mb-4"></i>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">No Rooms Found</h3>
          <p class="text-gray-500">There are currently no rooms in the system.</p>
        </div>
      <?php endif; ?>

      <div class="flex justify-center mt-6">
        <a href="warden_dashboard.php" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg shadow">
          <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
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