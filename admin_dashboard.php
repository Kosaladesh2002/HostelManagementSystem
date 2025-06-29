<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION['name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - HostelSync</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #f0fdf4, #d1fae5);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    header {
      background: linear-gradient(135deg, #047857, #065f46);
      color: white;
      padding: 1.5rem 2rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    header h1 {
      font-size: 2rem;
      font-weight: 800;
    }
    .welcome {
      text-align: center;
      margin-top: 2rem;
      font-size: 1.5rem;
      font-weight: 600;
      color: #047857;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .welcome i {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
      color: #047857;
    }
    .dashboard-container {
      max-width: 1280px;
      margin: auto;
      padding: 2rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2rem;
    }
    .dashboard-card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
      text-align: center;
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
    }
    .dashboard-card i {
      font-size: 2rem;
      margin-bottom: 0.75rem;
      color: #10b981;
    }
    .dashboard-card img {
      height: 80px;
      margin: 0 auto 1rem;
    }
    .dashboard-card h2 {
      font-size: 1.25rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: #065f46;
    }
    .dashboard-card a {
      display: inline-block;
      margin-top: 0.75rem;
      font-weight: 600;
      background-color: #047857;
      color: white;
      padding: 0.5rem 1.25rem;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.3s;
    }
    .dashboard-card a:hover {
      background-color: #065f46;
    }
    .footer {
      margin-top: auto;
      text-align: center;
      padding: 1rem;
      background-color: #047857;
      color: white;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>
  <header>
    <div class="flex justify-between items-center">
      <h1>Hostel Management System</h1>
      <span class="text-slate-200 font-medium">Admin Dashboard</span>
    </div>
  </header>

  <div class="welcome">
    <i class="fas fa-user-shield"></i>
    <p>Welcome, <?php echo $name; ?> 👋</p>
  </div>

  <div class="dashboard-container">
    <div class="dashboard-card">
      <img src="images/student.png" alt="Manage Users">
      <i class="fas fa-users"></i>
      <h2>Manage Users</h2>
      <a href="view_users.php">Go to Users</a>
    </div>
    <div class="dashboard-card">
      <img src="images/occupancy.png" alt="Manage Rooms">
      <i class="fas fa-door-open"></i>
      <h2>Manage Rooms</h2>
      <a href="view_rooms.php">Go to Rooms</a>
    </div>
    <div class="dashboard-card">
      <img src="images/money.png" alt="Payments">
      <i class="fas fa-file-invoice-dollar"></i>
      <h2>Payments</h2>
      <a href="view_payments.php">View Payments</a>
    </div>
    <div class="dashboard-card">
      <img src="images/stamp.png" alt="Approve Students">
      <i class="fas fa-user-edit"></i>
      <h2>Approve Students</h2>
      <a href="approve_student.php">Approve Now</a>
    </div>
    <div class="dashboard-card col-span-2">
      <img src="images/complaint.png" alt="Complaints">
      <i class="fas fa-clipboard-list"></i>
      <h2>View Complaints</h2>
      <a href="view_complaints.php">View Complaints</a>
    </div>
    <div class="dashboard-card col-span-2">
      <img src="images/check-out.png" alt="Logout">
      <i class="fas fa-arrow-right"></i>
      <h2>Logout</h2>
      <a href="logout.php">Sign Out</a>
    </div>
  </div>

  <footer class="footer">
    &copy; <?php echo date("Y"); ?> HostelSync. All rights reserved.
  </footer>
</body>
</html>
