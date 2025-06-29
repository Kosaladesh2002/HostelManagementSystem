<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['warden', 'admin'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$name = htmlspecialchars($_SESSION['name']);
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HostelSync - Student List</title>

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #f0fdf4, #dcfce7);
      margin: 0;
    }
    .header {
      background: linear-gradient(135deg, #166534, #22c55e);
      color: white;
      padding: 1.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    .btn-elegant {
      background: #16a34a;
      color: white;
      font-weight: 600;
      border-radius: 6px;
      padding: 0.5rem 1.2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    .btn-back {
      border: 2px solid #15803d;
      background: #15803d;
      color: white;
      font-weight: 600;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      border: 2px solid #15803d;
    }
    th {
      background: #15803d;
      color: white;
      padding: 1rem;
      text-align: left;
      border-right: 1px solid #14532d;
    }
    td {
      padding: 0.875rem;
      border-top: 1px solid #d1fae5;
      border-right: 1px solid #bbf7d0;
    }
    td:last-child, th:last-child {
      border-right: none;
    }
  </style>
</head>

<body>
  <header class="header">
    <h1>Hostel Management System</h1>
    <span class="text-lg font-semibold">Student List</span>
  </header>

  <main class="py-12 px-4 max-w-7xl mx-auto">
    <div class="glass-card">
      <h2 class="text-3xl font-bold text-green-800 mb-6 text-center">📄 Student List</h2>
      <div class="overflow-x-auto">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Stay (months)</th>
              <th>Room</th>
              <th>Payments</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if ($role === 'admin') {
                $sql = "SELECT s.ID, u.name, u.email, u.phonenumber, s.Duration_of_stay, a.Room_No
                        FROM student s 
                        JOIN user u ON s.ID = u.ID
                        LEFT JOIN assigned_to a ON s.ID = a.Student_ID";
                $stmt = $conn->prepare($sql);
            } else {
                $sql = "SELECT s.ID, u.name, u.email, u.phonenumber, s.Duration_of_stay, a.Room_No
                        FROM student s 
                        JOIN user u ON s.ID = u.ID
                        LEFT JOIN assigned_to a ON s.ID = a.Student_ID
                        WHERE s.Warden_ID = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $user_id);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
              <tr>
                <td><?= htmlspecialchars($row["ID"]); ?></td>
                <td><?= htmlspecialchars($row["name"]); ?></td>
                <td><?= htmlspecialchars($row["email"]); ?></td>
                <td><?= htmlspecialchars($row["phonenumber"]); ?></td>
                <td><?= htmlspecialchars($row["Duration_of_stay"]); ?></td>
                <td><?= htmlspecialchars($row["Room_No"] ?? 'Not Assigned'); ?></td>
                <td>
                  <a href="view_payment_student.php?id=<?= htmlspecialchars($row['ID']); ?>" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                    <i class="fas fa-money-bill-wave mr-1"></i> View
                  </a>
                </td>
                <td>
                  <a href="edit_student.php?id=<?= htmlspecialchars($row['ID']); ?>" class="bg-yellow-400 text-white px-3 py-1 rounded-md text-sm hover:bg-yellow-500">
                    <i class="fas fa-edit mr-1"></i> Edit
                  </a>
                </td>
              </tr>
            <?php endwhile; else: ?>
              <tr>
                <td colspan="8" class="text-center text-slate-500 py-4">No students found.</td>
              </tr>
            <?php endif; $stmt->close(); ?>
          </tbody>
        </table>
      </div>

      <div class="text-center mt-6">
        <a href="<?= ($role === 'admin') ? 'admin_dashboard.php' : 'warden_dashboard.php'; ?>" class="btn-elegant btn-back">
          <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
      </div>
    </div>
  </main>
</body>
</html>
