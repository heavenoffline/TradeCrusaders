<?php

session_start();
require_once __DIR__ . "/php/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Count pending listings for the logged-in user
$pendingStmt = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM listings 
    WHERE seller_id = ? AND status = 'Pending'
");

$pendingStmt->bind_param("i", $_SESSION['user_id']);
$pendingStmt->execute();

$pendingResult = $pendingStmt->get_result();
$pendingRow = $pendingResult->fetch_assoc();

$pendingCount = $pendingRow['total'];

// Count live listings for the logged-in user
$stmt = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM listings 
    WHERE seller_id = ? AND status = 'Live'
");

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$liveCount = $row['total'];
?>




<?php ?>

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


<!-- MAIN PAGE WRAPPER -->
<main class="seller-page">

    <div class="seller-card">

        <div>
            <span class="eyebrow">Seller dashboard</span>
            <h1>List products</h1>
            <p>Create, update, and manage your listings.</p>
        </div>

        <div class="button-row" style="margin-top:10px;">
            <a class="button button-primary" href="post_listing.php">
                + Create New Listing
            </a>
        </div>

        <div class="summary-grid" style="margin-top:20px;">

            <div class="summary-card">
                <span class="summary-label">Live listings</span>
                <strong class="summary-number">
                    <?php echo $liveCount; ?>
                </strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Pending review</span>
                <strong class="summary-number">
                    <?php echo $pendingCount; ?>
                </strong>
            </div>

        </div>

    </div>

    <div class="section-heading" style="margin-top: 20px;">
        <span class="eyebrow">My inventory</span>
        <h2>Track your listings</h2>
    </div>

    <?php
    $sql = "SELECT * FROM listings WHERE seller_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
    ?>

        <div class="listing-row">

            <div>
                <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                <small>R <?php echo htmlspecialchars($row['price']); ?></small>
            </div>

            <span class="status-pill">
                <?php echo htmlspecialchars($row['status'] ?? 'Live'); ?>
            </span>

            <div class="button-row">
                <a class="button button-secondary"
                   href="edit_listing.php?id=<?php echo $row['id']; ?>">
                    Edit
                </a>
            </div>

        </div>

    <?php
        }

    } else {
        echo "<p>No listings found.</p>";
    }
    ?>

</main>


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