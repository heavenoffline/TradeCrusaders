<?php
session_start();
include __DIR__ . "/php/config.php";

$sql = "SELECT * FROM listings WHERE status='active'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Crusaders</title>

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

<!-- PAGE CONTENT -->
<section class="browse-toolbar">
    <div>
        <span class="eyebrow">Browse the market</span>
        <h1>Find listings</h1>
    </div>

    <input class="search-box" type="search"
           placeholder="Search for headphones, cars, desks..."
           aria-label="Search listings">
</section>

<section class="filter-bar">
    <button class="filter-chip active" type="button">All</button>
    <button class="filter-chip" type="button">Electronics</button>
    <button class="filter-chip" type="button">Home</button>
</section>

<section class="section-block">

    <div class="listing-grid">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <article class="card listing-card">

                    <div class="listing-image">
                        <img src="uploads/<?php echo htmlspecialchars($row['images']); ?>" alt="">
                    </div>

                    <div>
                        <p class="product-tag">
                            <?php echo htmlspecialchars($row['category_id']); ?>
                        </p>

                        <h3>
                            <?php echo htmlspecialchars($row['title']); ?>
                        </h3>

                        <p>Active listing</p>
                    </div>

                    <div class="listing-meta">
                        <strong>
                            R <?php echo number_format($row['price'], 2); ?>
                        </strong>

                        <span>
                            <?php echo htmlspecialchars($row['condition_label']); ?>
                        </span>
                    </div>

                    <div class="button-row">
                        <a class="button button-primary"
                           href="products.php?id=<?php echo $row['id']; ?>">
                           View listing
                        </a>
                    </div>

                </article>

            <?php } ?>

        <?php else: ?>

            <p>No listings available.</p>

        <?php endif; ?>

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