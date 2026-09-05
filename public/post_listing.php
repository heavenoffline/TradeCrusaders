<?php
session_start();
require_once __DIR__ . "/php/config.php";

// Protect page (must be logged in)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";

// HANDLE FORM SUBMISSION
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST['title'];
    $price = $_POST['price'];
    $category = $_POST['category_id'];
    $condition = $_POST['condition_label'];
    $description = $_POST['description'];
    $status = $_POST['status'] ?? 'active';

    // IMAGE UPLOAD
    $imageName = "";

    if (!empty($_FILES['image']['name'])) {

    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $tmp = $_FILES['image']['tmp_name'];

    // FILE PATH
    $uploadPath = __DIR__ . "/uploads/";

    // create folder if it doesn't exist although it should already exist
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // move file
    move_uploaded_file($tmp, $uploadPath . $imageName);
}

    // INSERT INTO DB
    $stmt = $conn->prepare("
        INSERT INTO listings 
        (seller_id, category_id, title, description, price,  condition_label, status, images)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iissdsss",
        $_SESSION['user_id'],
        $category,
        $title,
        $description,
        $price,
        $condition,
        $status,
        $imageName
    );

    if ($stmt->execute()) {
        $success = "Listing posted successfully!";
    } else {
        $error = "Failed to post listing.";
    }


        if (!$stmt) {
        die("Prepare failed: " . $conn->error);
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

<section class="seller-page">

    <!-- FORM CARD -->
    <div class="seller-card">

        <div>
            <span class="eyebrow">Create listing</span>
            <h1>Post a new item</h1>
            <p>Fill in the details below to publish your product.</p>
        </div>

        <form class="form-grid" action="post_listing.php" method="post" enctype="multipart/form-data">

            <!-- TITLE + PRICE -->
            <div class="admin-grid">
                <input class="input" type="text" name="title" placeholder="Item title" required>
                <input class="input" type="number" name="price" placeholder="Price (e.g. 1500)" step="0.01" required>
            </div>

            <!-- CATEGORY -->
            <div class="admin-grid">
                <select class="select" name="category_id" required>
                    <option value="">Select category</option>
                    <option value="1">Electronics</option>
                    <option value="2">Home</option>
                    <option value="3">Fashion</option>
                </select>

                <!-- CONDITION -->
                <select class="select" name="condition_label" required>
                    <option value="">Condition</option>
                    <option value="Brand new">Brand new</option>
                    <option value="Like new">Like new</option>
                    <option value="Used">Used</option>
                </select>
            </div>

            <!-- IMAGE-->
            <div class="admin-grid">
                <label>Product image</label>
                <input class="input" type="file" name="image" accept="image/*">
            </div>

            <!-- DESCRIPTION -->
            <textarea class="textarea"
                      name="description"
                      placeholder="Describe the item, condition, defects, shipping info..."
                      required></textarea>

            <!-- BUTTONS -->
            <div class="button-row">
                <button class="button button-primary" type="submit">
                    Publish listing
                </button>

                <button class="button button-secondary" type="submit" name="status" value="draft">
                    Save as draft
                </button>
            </div>

        </form>

    </div>

    <!-- SIDE INFO -->
    <aside class="admin-rail">

        <div class="summary-grid">

            <div class="summary-card">
                <span class="summary-label">My listings</span>
                <strong class="summary-number">--</strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Active</span>
                <strong class="summary-number">--</strong>
            </div>

        </div>

        <div class="section-heading" style="margin-top: 20px;">
            <span class="eyebrow">Tip</span>
            <h2>Good listings sell faster</h2>
        </div>

        <p style="margin-top:10px;">
            Use clear titles, honest condition labels, and good descriptions to increase sales.
        </p>

    </aside>

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