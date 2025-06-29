<?php
session_start(); // Start session at the top to avoid headers error
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>HostelSync - Professional Hostel Management</title>

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      overflow-x: hidden;
    }
    .hero-section {
      background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(30, 41, 59, 0.85)), url('images/bb.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.4s ease;
    }
    .glass-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 32px 64px rgba(0, 0, 0, 0.15);
    }
    .btn-elegant {
      transition: all 0.3s;
      padding: 1rem 2.5rem;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      position: relative;
      overflow: hidden;
    }
    .btn-elegant::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }
    .btn-elegant:hover::before {
      left: 100%;
    }
    .btn-elegant:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    .swiper {
      width: 100%;
      height: 400px;
    }
    .swiper-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    footer {
      background-color: #0f172a;
    }
  </style>
</head>
<body class="bg-slate-50">

<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-50 bg-black px-6 py-4 shadow-md flex justify-between items-center">
  <div class="flex items-center gap-4">
    <img src="images/logo.png" alt="University Logo" class="h-10 w-10 rounded-full border border-white shadow"/>
    <h1 class="text-2xl font-extrabold text-white">Hostel Management System</h1>
  </div>
  <span class="text-sm font-medium text-white">Professional Management Solution</span>
</header>

<!-- Image Slider -->
<section class="mt-20">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide"><img src="images/4.jpg" /></div>
      <div class="swiper-slide"><img src="images/5.jpg" /></div>
      <div class="swiper-slide"><img src="images/6.jpg" /></div>
    </div>
  </div>
</section>

<!-- Hero Section -->
<section class="hero-section flex items-center justify-center py-20">
  <div class="hero-content z-10 max-w-4xl px-6 text-center">
    <div class="glass-card p-10 rounded-2xl shadow-xl">
      <h2 class="text-5xl font-bold text-slate-800 mb-4">YOU ARE WELCOME !</h2>
      <p class="text-xl text-slate-600 mb-6">Hostel management - University Of Jaffna</p>

      <div class="grid md:grid-cols-3 gap-4 text-sm mb-8">
        <div class="bg-blue-50 p-4 rounded-lg shadow">
          <i class="fas fa-users text-blue-600 text-lg mb-2"></i>
          <p class="font-semibold text-blue-800 text-xs">Student Management</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg shadow">
          <i class="fas fa-bed text-green-600 text-lg mb-2"></i>
          <p class="font-semibold text-green-800 text-xs">Smart Room Allocation</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg shadow">
          <i class="fas fa-clipboard-check text-purple-600 text-lg mb-2"></i>
          <p class="font-semibold text-purple-800 text-xs">Visitor Tracking</p>
        </div>
      </div>

      <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="flex justify-center gap-6">
          <a href="login.php" class="bg-blue-600 text-white btn-elegant shadow">
            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
          </a>
          <a href="register_user.php" class="bg-green-600 text-white btn-elegant shadow">
            <i class="fas fa-user-plus mr-2"></i>Sign Up
          </a>
        </div>
        <p class="text-slate-500 mt-4 text-sm">Trusted by hostels worldwide • Secure • Reliable • Professional</p>
      <?php else: ?>
        <div class="mb-4">
          <h3 class="text-xl font-semibold text-slate-700 mb-2">Welcome back, Administrator!</h3>
          <p class="text-base text-slate-600 mb-4">Your hostel management dashboard is ready.</p>
        </div>
        <a href="logout.php" class="inline-block bg-red-600 text-white btn-elegant shadow">
          <i class="fas fa-sign-out-alt mr-2"></i>Secure Logout
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Announcement Section -->
<section class="py-10 bg-white">
  <div class="max-w-5xl mx-auto px-6 text-center">
    <h3 class="text-2xl font-bold text-slate-800 mb-4">
      <i class="fas fa-bullhorn text-yellow-500 mr-2"></i>Latest Announcements
    </h3>
    <ul class="text-slate-600 space-y-3">
      <li><i class="fas fa-circle text-xs text-blue-400 mr-2"></i> Hostel applications for the next semester are open until <strong>July 20</strong>.</li>
      <li><i class="fas fa-circle text-xs text-blue-400 mr-2"></i> Room maintenance will take place on <strong>August 1st - 3rd</strong>.</li>
      <li><i class="fas fa-circle text-xs text-blue-400 mr-2"></i> New visitor log policy starts from <strong>July 10</strong>.</li>
    </ul>
  </div>
</section>

<!-- Footer -->
<footer class="text-white text-center py-6 bg-slate-900 mt-10">
  <p class="text-sm text-slate-400">&copy; <?= date("Y") ?> HostelSync. All rights reserved.</p>
  <div class="mt-2 space-x-4 text-xs">
    <a href="#" class="hover:text-blue-400"><i class="fab fa-facebook"></i></a>
    <a href="#" class="hover:text-blue-300"><i class="fab fa-twitter"></i></a>
    <a href="#" class="hover:text-pink-400"><i class="fab fa-instagram"></i></a>
  </div>
</footer>

<!-- SwiperJS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper(".mySwiper", {
    loop: true,
    effect: "fade",
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
  });
</script>
</body>
</html>
