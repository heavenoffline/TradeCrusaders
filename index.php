<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade Crusaders</title>

    <link rel="stylesheet" href="public/style.css">

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
            <a href="public/browse.php">Browse</a>
            <a href="public/dashboard.php">Sell</a>
        </nav>

        
        <div class="header-actions">

            <?php if (isset($_SESSION['user_id'])): ?>

                <span style="margin-right:10px;">
                    Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                </span>

                <a class="button button-secondary button-small" href="public/dashboard.php">
                    Dashboard
                </a>

                <a class="button button-primary button-small" href="public/logout.php">
                    Logout
                </a>

            <?php else: ?>

                <a class="button button-primary button-small" href="public/login.php">
                    Login
                </a>

                <a class="button button-secondary button-small" href="public/register.php">
                    Register
                </a>

            <?php endif; ?>

        </div>
    </div>
</header>

<main class="page-shell">

    <!-- HERO -->
    <section class="hero-grid">

        <div class="hero-copy">
            <span class="eyebrow">Trade Crusaders</span>

            <h1>Trade with confidence in a peer-to-peer marketplace.</h1>

            <p class="lead">
                Buy and sell goods quickly and safely with a simple marketplace system.
            </p>

            <div class="hero-actions">
                <a class="button button-primary" href="public/browse.php">Start browsing</a>
                <a class="button button-secondary" href="public/dashboard.php">Sell an item</a>
            </div>

            <div class="trust-strip">
                <div>
                    <strong>Secure</strong>
                    <span>Safe user transactions</span>
                </div>

                <div>
                    <strong>Fast</strong>
                    <span>Quick search and listings</span>
                </div>

                <div>
                    <strong>Simple</strong>
                    <span>Easy buying and selling</span>
                </div>
            </div>
        </div>

    </section>

    <!-- HOW IT WORKS -->
    <section class="section-block">

        <div class="section-heading">
            <span class="eyebrow">How it works</span>
            <h2>Simple steps to trade</h2>
        </div>

        <div class="step-list">

            <div class="step-item">
                <span>1</span>
                <div>
                    <h3>Browse listings</h3>
                    <p>Find items you want using categories or search.</p>
                </div>
            </div>

            <div class="step-item">
                <span>2</span>
                <div>
                    <h3>Contact sellers</h3>
                    <p>Ask questions and negotiate directly.</p>
                </div>
            </div>

            <div class="step-item">
                <span>3</span>
                <div>
                    <h3>Complete trade</h3>
                    <p>Agree on price and complete the transaction.</p>
                </div>
            </div>

        </div>

    </section>

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
</html>