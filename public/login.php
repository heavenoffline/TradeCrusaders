
<?php
session_start();
require_once __DIR__ . "/php/config.php";

$loggedIn = isset($_SESSION['user_id']);

class Login {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function authenticate($email, $password) {

        $sql = "SELECT id, email, password_hash, full_name FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            return "Database error.";
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return "Invalid email or password.";
        }

        if (!password_verify($password, $user['password_hash'])) {
            return "Invalid email or password.";
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'];

        header("Location: dashboard.php");
        exit;
    }
}

$login = new Login($conn);
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $login->authenticate($email, $password);

    if (is_string($result)) {
        $error = $result;
    }
}
?>

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

        <a class="brand" href="index.php">
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

<?php if (isset($_SESSION['user_id'])): ?>

    <!-- LOGGED IN STATE -->
    <span style="margin-right:10px;">
        Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
    </span>

    <a class="button button-secondary button-small" href="dashboard.php">
        Dashboard
    </a>
    <a class="button button-secondary button-small" href="browse.php">
        Browse listings    
    </a>
    <a class="button button-primary button-small" href="logout.php">
        Logout
    </a>

<?php else: ?>

    <!-- LOGGED OUT STATE -->
    <a class="button button-primary button-small" href="login.php">
        Login
    </a>

    <a class="button button-secondary button-small" href="register.php">
        Register
    </a>

<?php endif; ?>
</header>


<section class="login-page">
    <div class="login-panel card">

        <span class="eyebrow">Welcome back</span>
        <h1>Login to Trade Crusaders</h1>

        <p>Sign in to access your account, messages, and listings.</p>

        <form class="form-grid" method="post" action="login.php">

            <input class="input" type="hidden" name="redirect" value="">

            <input class="input" type="email" name="email" placeholder="Email address" required>

            <input class="input" type="password" name="password" placeholder="Password" required>

            <div class="button-row">
                <form><button class="button button-primary" type="submit">Login</button></form>
                <a class="button button-secondary" href="../index.html">Back home</a>
            </div>
        </form>

        <p style="margin-top: 16px; text-align: center;">
            Need an account?
            <a href="register.html">Create one here</a>
        </p>

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
