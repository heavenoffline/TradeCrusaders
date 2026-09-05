<?php
session_start();
require_once __DIR__ . "/php/config.php";

$product = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Crusaders - Payment</title>

    <link rel="stylesheet" href="style.css">

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
            <a href="dashboard.php">Sell</a>
        </nav>

        
        <div class="header-actions">

            <?php if (isset($_SESSION['user_id'])): ?>

                <span style="margin-right:10px;">
                    Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                </span>

                <a class="button button-secondary button-small" href="dashboard.php">
                    Dashboard
                </a>

                <a class="button button-primary button-small" href="logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a class="button button-primary button-small" href="login.php">
                    Login
                </a>

                <a class="button button-secondary button-small" href="register.php">
                    Register
                </a>

            <?php endif; ?>

        </div>
    </div>
</header>

<section class="product-page">

    <!-- LEFT SIDE -->
    <div class="product-gallery">

        <span class="eyebrow">Secure checkout</span>
        <h1>Review your order</h1>

        <div class="action-box" style="margin-top:16px;">
            <strong>Item</strong>
            <p>
                <?= htmlspecialchars($product['title'] ?? 'Sample Product Name'); ?>
            </p>
        </div>

        <div class="summary-grid" style="margin-top:16px;">
            <div class="summary-card">
                <span class="summary-label">Price</span>
                <strong class="summary-number">
                    R<?= number_format($product['price'] ?? 299.99, 2); ?>
                </strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Condition</span>
                <strong class="summary-number">
                    <?= htmlspecialchars($product['condition_label'] ?? 'Like New'); ?>
                </strong>
            </div>
        </div>

        <div class="action-box">
            <strong>Seller</strong>
            <p><?= htmlspecialchars($product['seller_id'] ?? 'Example Seller'); ?></p>
        </div>

        <div class="action-box">
            <strong>Total payable</strong>
            <p style="font-size:1.6rem;">
                R<?= number_format($product['price'] ?? 299.99, 2); ?>
            </p>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="product-details">

        <span class="eyebrow">Payment details</span>
        <h1>Complete purchase</h1>

        <form class="form-grid" method="POST" action="payment_successful.php?id=<?= $product['id'] ?>">

            <input class="input" type="text" placeholder="Full name on card" required>
            <input class="input" type="email" placeholder="Email for receipt" required>

            <input class="input" type="text" placeholder="Card number (XXXX XXXX XXXX XXXX)" maxlength="19" required>

            <div class="admin-grid">
                <input class="input" type="text" placeholder="MM/YY" required>
                <input class="input" type="text" placeholder="CVV" maxlength="4" required>
            </div>

            <input class="input" type="text" placeholder="Billing address" required>

            <div class="admin-grid">
                <input class="input" type="text" placeholder="City" required>
                <input class="input" type="text" placeholder="Postal code" required>
            </div>

            <div class="button-row">
                <button class="button button-primary" type="submit">
                    Pay now
                </button>

                <a class="button button-secondary" href="browse.php">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</section>

<footer class="site-footer">
    <div>
        <strong>Trade Crusaders</strong>
        <p>Peer-to-peer marketplace platform</p>
    </div>

    <div>
        <small>© <?php echo date('Y'); ?> Trade Crusaders</small>
    </div>
</footer>

</body>
</html>