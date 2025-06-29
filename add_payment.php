<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';
$message_class = '';

$monthly_fee = 1000.00;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id       = $_POST['student_id'];
    $amount           = floatval($_POST['amount']);
    $payment_date     = $_POST['payment_date'];
    $due_date         = $_POST['due_date'];
    $month_of_payment = date('F Y', strtotime($payment_date));
    $paid             = 1;

    $stmt = $conn->prepare("SELECT SUM(amount) AS total_paid FROM payment WHERE Student_ID = ? AND month_of_payment = ?");
    $stmt->bind_param("ss", $student_id, $month_of_payment);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $total_paid = floatval($res['total_paid']) + $amount;
    $stmt->close();

    $days_late = max((strtotime($payment_date) - strtotime($due_date)) / (60 * 60 * 24), 0);
    $penalty = ($days_late > 0) ? ($days_late * 100) : 0;

    $remaining_due = max($monthly_fee - $total_paid, 0);
    if ($remaining_due == 0) {
        $status = "Paid in Full";
    } elseif ($days_late > 0) {
        $status = "Late Payment";
    } else {
        $status = "Partially Paid";
    }

    $stmt = $conn->prepare("INSERT INTO payment (amount, Payment_date, due_date, paid, penalty, month_of_payment, Student_ID, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("dsssisss", $amount, $payment_date, $due_date, $paid, $penalty, $month_of_payment, $student_id, $status);

    if ($stmt->execute()) {
        $message = "✅ Payment recorded successfully.<br>Penalty: Rs. $penalty<br>Remaining Due: Rs. $remaining_due<br>Status: $status";
        $message_class = 'success';
    } else {
        $message = "❌ Failed to add payment: " . htmlspecialchars($stmt->error);
        $message_class = 'error';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HostelSync - Add Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 min-h-screen font-sans">
    <header class="bg-green-800 text-white py-4 shadow-md fixed w-full top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Hostel Management System</h1>
            <span class="text-green-100 text-sm">Add Payment</span>
        </div>
    </header>

    <main class="pt-28 flex justify-center">
        <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-lg mx-4">
            <h2 class="text-2xl font-bold text-green-700 mb-6 text-center flex items-center justify-center gap-2">
                <i class="fas fa-money-bill-wave"></i> Add Payment
            </h2>

            <?php if ($message): ?>
                <div class="<?= $message_class === 'success' ? 'text-green-600' : 'text-red-600' ?> font-medium mb-4 text-center">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label for="student_id" class="block font-medium text-gray-700">Student:</label>
                    <select name="student_id" id="student_id" required class="mt-1 w-full border border-gray-300 rounded-md p-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Select Student --</option>
                        <?php
                        $students = $conn->query("SELECT s.ID, u.name FROM student s INNER JOIN user u ON s.ID = u.ID WHERE u.role = 'student'");
                        while ($row = $students->fetch_assoc()) {
                            echo "<option value='" . $row['ID'] . "'>" . htmlspecialchars($row['name']) . " (ID: " . $row['ID'] . ")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label for="amount" class="block font-medium text-gray-700">Amount (Rs.):</label>
                    <input type="number" name="amount" step="0.01" required class="mt-1 w-full border border-gray-300 rounded-md p-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="payment_date" class="block font-medium text-gray-700">Payment Date:</label>
                    <input type="date" name="payment_date" required class="mt-1 w-full border border-gray-300 rounded-md p-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div>
                    <label for="due_date" class="block font-medium text-gray-700">Due Date:</label>
                    <input type="date" name="due_date" required class="mt-1 w-full border border-gray-300 rounded-md p-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white py-2 px-4 rounded-md shadow text-lg font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Submit Payment
                </button>

                <div class="text-center mt-4">
                    <a href="admin_dashboard.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </main>

    <footer class="text-center text-xs text-gray-600 py-6 mt-8">
        &copy; <?= date('Y') ?> HostelSync • All rights reserved.
    </footer>
</body>
</html>

<?php $conn->close(); ?>
