<?php
session_start();
include 'db_connect.php';

// Restrict access to admins only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle GET to fetch current values
if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM payment WHERE Payment_ID = ?");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payment = $result->fetch_assoc();
    if (!$payment) {
        echo "❌ Payment record not found.";
        exit();
    }
}

// Handle POST to update values
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_id = $_POST['payment_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $due_date = $_POST['due_date'];
    $penalty = $_POST['penalty'];

    $stmt = $conn->prepare("UPDATE payment SET amount = ?, Payment_date = ?, due_date = ?, penalty = ? WHERE Payment_ID = ?");
    $stmt->bind_param("dssdi", $amount, $payment_date, $due_date, $penalty, $payment_id);

    if ($stmt->execute()) {
        header("Location: view_payments.php");
        exit();
    } else {
        echo "❌ Error updating payment: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment - HostelSync</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: linear-gradient(to right, #e0f2f1, #a7ffeb);
        }

        header {
            background-color: #166534;
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 1.75rem;
            font-weight: 800;
        }

        .dashboard-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #d1fae5;
        }

        h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #166534;
            text-align: center;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-weight: 600;
            color: #374151;
        }

        input {
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .submit-btn, .back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .submit-btn {
            background-color: #22c55e;
            color: white;
        }

        .submit-btn:hover {
            background-color: #16a34a;
        }

        .back-btn {
            background-color: #166534;
            color: white;
        }

        .back-btn:hover {
            background-color: #14532d;
        }
    </style>
</head>
<body>

<header>
    <h1>Hostel Management System - Edit Payment</h1>
</header>

<div class="dashboard-container">
    <h2><i class="fas fa-edit"></i> Edit Payment</h2>

    <form method="POST">
        <input type="hidden" name="payment_id" value="<?= htmlspecialchars($payment['Payment_ID']) ?>">

        <label>Amount (Rs.)</label>
        <input type="number" step="0.01" name="amount" value="<?= htmlspecialchars($payment['amount']) ?>" required>

        <label>Payment Date</label>
        <input type="date" name="payment_date" value="<?= htmlspecialchars($payment['Payment_date']) ?>" required>

        <label>Due Date</label>
        <input type="date" name="due_date" value="<?= htmlspecialchars($payment['due_date']) ?>" required>

        <label>Penalty (Rs.)</label>
        <input type="number" step="0.01" name="penalty" value="<?= htmlspecialchars($payment['penalty']) ?>" required>

        <button type="submit" class="submit-btn">
            <i class="fas fa-save"></i> Update Payment
        </button>

        <a href="view_payments.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Payment Records
        </a>
    </form>
</div>

</body>
</html>

<?php $conn->close(); ?>
