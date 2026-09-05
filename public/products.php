<?php
session_start();
include __DIR__ . "/php/config.php";

// GET PRODUCT ID
$id = $_GET['id'] ?? 0;

// FETCH PRODUCT
$stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - Trade Crusaders</title>

    <link rel="stylesheet" href="style.css">
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

<!-- PRODUCT PAGE -->
<section class="product-page">

    <!-- IMAGE -->
    <div class="product-gallery">

        <img src="uploads/<?php echo htmlspecialchars($product['images']); ?>"
             alt=""
             style="width:100%; max-height:400px; object-fit:cover;">

        <div class="detail-grid" style="margin-top: 16px;">

            <div class="detail-chip">
                <span>Condition</span>
                <strong><?php echo htmlspecialchars($product['condition_label']); ?></strong>
            </div>

            <div class="detail-chip">
                <span>Category</span>
                <strong><?php echo htmlspecialchars($product['category_id']); ?></strong>
            </div>

        </div>

    </div>

    <!-- DETAILS -->
    <div class="product-details">

        <h1><?php echo htmlspecialchars($product['title']); ?></h1>

        <p class="detail-price">
            R <?php echo number_format($product['price'], 2); ?>
        </p>

        <p>
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </p>

        <div class="action-box" style="margin-top: 16px;">
            <strong>Status:</strong>
            <?php echo htmlspecialchars($product['status']); ?>
        </div>

        <div class="button-row" style="margin-top: 16px;">

    <a class="button button-primary"
       href="payment.php?id=<?php echo $product['id']; ?>">
        Buy now
    </a>

    <a class="button button-secondary" href="browse.php">
        Back to listings
    </a>

</div>

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

</body>
</html>