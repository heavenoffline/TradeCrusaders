<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

/* -----------------------------
   GET LISTING ID
------------------------------*/
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("Invalid listing ID. Please access this page from the admin panel.");
}
/* -----------------------------
   FETCH LISTING
------------------------------*/
$stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();

if (!$listing) {
    die("Listing not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Listing - Trade Crusaders</title>

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
            <a href="index.php">Home</a>
            <a href="browse.php">Browse</a>
            <a href="products.php">Sell</a>
        </nav>

        <div class="header-actions">
            <a class="button button-secondary button-small" href="dashboard.php">Dashboard</a>
        </div>

    </div>
</header>

<section class="product-page">

    <!-- LEFT SIDE -->
    <div class="product-gallery">

        <span class="eyebrow">Edit listing</span>
        <h1>Update your item</h1>
        <p>Make changes to your listing details.</p>

        <!-- CURRENT IMAGE -->
        <div class="product-image product-image-two" style="min-height: 320px;">
            <img src="uploads/<?php echo htmlspecialchars($listing['images']); ?>" alt="Listing image">
        </div>

        <div class="detail-grid" style="margin-top: 16px;">
            <div class="detail-chip">
                <span>Status</span>
                <strong><?php echo htmlspecialchars($listing['status']); ?></strong>
            </div>

            <div class="detail-chip">
                <span>Category</span>
                <strong><?php echo htmlspecialchars($listing['category_id']); ?></strong>
            </div>

            <div class="detail-chip">
                <span>Price</span>
                <strong>R <?php echo number_format($listing['price'], 2); ?></strong>
            </div>
        </div>

        <div class="button-row" style="margin-top: 18px;">
            <a class="button button-secondary" href="browse.php">Cancel</a>
            <button class="button button-primary" type="submit" form="editListingForm">
                Save changes
            </button>
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="product-details">

        <form id="editListingForm" class="form-grid" method="post" action="update_listing.php" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?php echo $listing['id']; ?>">

            <span class="eyebrow">Listing details</span>

            <input class="input" type="text" name="title"
                   value="<?php echo htmlspecialchars($listing['title']); ?>" required>

            <textarea class="textarea" name="description" required><?php echo htmlspecialchars($listing['description']); ?></textarea>

            <input class="input" type="number" name="price"
                   value="<?php echo htmlspecialchars($listing['price']); ?>" required>

            <select class="select" name="category_id">
                <option value="1" <?php if($listing['category_id']==1) echo "selected"; ?>>Electronics</option>
                <option value="2" <?php if($listing['category_id']==2) echo "selected"; ?>>Home</option>
                <option value="3" <?php if($listing['category_id']==3) echo "selected"; ?>>Fashion</option>
            </select>

            <select class="select" name="condition_label">
                <option value="Brand new" <?php if($listing['condition_label']=="Brand new") echo "selected"; ?>>Brand new</option>
                <option value="Like new" <?php if($listing['condition_label']=="Like new") echo "selected"; ?>>Like new</option>
                <option value="Used" <?php if($listing['condition_label']=="Used") echo "selected"; ?>>Used</option>
            </select>

            <select class="select" name="status">
                <option value="active" <?php if($listing['status']=="active") echo "selected"; ?>>Active</option>
                <option value="draft" <?php if($listing['status']=="draft") echo "selected"; ?>>Draft</option>
                <option value="sold" <?php if($listing['status']=="sold") echo "selected"; ?>>Sold</option>
            </select>

            <label class="eyebrow">Replace image</label>
            <input class="input" type="file" name="image" accept="image/*">

        </form>

    </div>

</section>

</body>
</html>