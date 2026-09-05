<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

$error = "";

// HANDLE LOGIN
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // CHECK ADMIN USER (assuming role column exists)
    $stmt = $conn->prepare("
        SELECT id, email, password_hash, full_name, role_id
        FROM users
        WHERE email = ? AND role_id = '1'
    ");

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // VALIDATION
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
    $error = "Invalid credentials.";
} elseif ($admin['role_id'] != 1) {
    $error = "You are not authorised as admin.";
} else {

    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['full_name'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['role_id'] = $admin['role_id'];

    header("Location: admin_dashboard.php");
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="stylesheet" href="admin.css">
</head>

<body>

<section class="login-page">

    <div class="login-panel card">

        <span class="eyebrow">Admin access</span>
        <h1>Admin Login</h1>
        <p>Restricted area. Only authorised administrators may continue.</p>

        <!-- ERROR MESSAGE -->
        <?php if (!empty($error)): ?>
            <div class="action-box" style="border-color: red;">
                <strong>Error</strong>
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form class="form-grid" method="POST" action="admin_login.php">

            <input class="input" type="email" name="email" placeholder="Admin email" required>

            <input class="input" type="password" name="password" placeholder="Password" required>

            <div class="button-row">
                <button class="button button-primary" type="submit">
                    Login
                </button>

                <a class="button button-secondary" href="index.php">
                    Back to site
                </a>
            </div>

        </form>

        <div class="action-box" style="margin-top:16px;">
            <strong>Note</strong>
            <p>Use your assigned admin credentials. Vendor accounts will not work here.</p>
        </div>

    </div>

</section>

</body>
</html>