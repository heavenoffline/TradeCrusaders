<?php
require_once __DIR__ . "/php/config.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // VALIDATION
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    }
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }
    else {

        // CHECK IF EMAIL EXISTS
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already exists.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $defaultRole = 3; // adjust based on role_id table

$stmt = $conn->prepare("
    INSERT INTO users (full_name, email, password_hash, role_id)
    VALUES (?, ?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

        $stmt->bind_param("sssi", $full_name, $email, $hash, $defaultRole);

            if ($stmt->execute()) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Database error: " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Crusaders</title>

    <link rel="stylesheet" href="style.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>

<!-- HEADER -->
<header class="site-header">
    <div class="header-inner">

        <a class="brand" href="../index.php">
            <span class="brand-mark">TC</span>
            <span>
                <strong>Trade Crusaders</strong>
                <small>Buy. Sell. Trade.</small>
            </span>
        </a>

        <nav class="site-nav">
            <a href="../index.php">Home</a>
            <a href="browse.php">Browse</a>
            <a href="products.php">Sell</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </nav>

        <div class="header-actions">
            <a class="button button-primary button-small" href="login.php">Login</a>
        </div>

    </div>
</header>

<section class="login-page">
    <div class="login-panel card">

<?php if (!empty($error)): ?>
    <p style="color:red; text-align:center;">
        <?php echo $error; ?>
    </p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color:green; text-align:center;">
        <?php echo $success; ?>
    </p>
<?php endif; ?>

        <span class="eyebrow">Create your account</span>
        <h1>Join Trade Crusaders</h1>

        <p>Register to browse, message sellers, and list your items.</p>

        <form class="form-grid" method="post" action="register.php">

            <input class="input" type="text" name="full_name" placeholder="Full name" required>

            <input class="input" type="email" name="email" placeholder="Email address" required>

            <input class="input" type="password" name="password" placeholder="Password" required>

            <input class="input" type="password" name="confirm_password" placeholder="Confirm password" required>

            <div class="button-row">
                <button class="button button-primary" type="submit">Create account</button>
                <a class="button button-secondary" href="../index.php">Back home</a>
            </div>

        </form>

    </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
    <div>
        <strong>Trade Crusaders</strong>
        <p>Peer-to-peer marketplace platform</p>
    </div>

    <div>
        <small>© <?php echo date('Y'); ?> Trade Crusaders</small>
    </div>
</footer>