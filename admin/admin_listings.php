<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

// Protect admin page
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* -----------------------------
   DELETE LISTING
------------------------------*/
if (isset($_GET['delete'])) {

    $listingId = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM listings WHERE id = ?");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $listingId);
    $stmt->execute();

    header("Location: admin_listings.php");
    exit();
}

/* -----------------------------
   GET LISTINGS
------------------------------*/

$sql = "
SELECT
    listings.*,
    users.full_name
FROM listings
LEFT JOIN users
ON listings.seller_id = users.id
ORDER BY listings.id DESC
";

$result = $conn->query($sql);

/* -----------------------------
   STATS
------------------------------*/

$active = $conn->query("SELECT COUNT(*) AS total FROM listings WHERE status='active'")
               ->fetch_assoc()['total'];

$draft = $conn->query("SELECT COUNT(*) AS total FROM listings WHERE status='draft'")
              ->fetch_assoc()['total'];

$sold = $conn->query("SELECT COUNT(*) AS total FROM listings WHERE status='sold'")
            ->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Listings</title>

<link rel="stylesheet" href="admin.css">
</head>

<body>

<section class="admin-page">

<div class="product-details">

<span class="eyebrow">Admin Panel</span>
<h1>Manage Listings</h1>

<p>Review and manage marketplace listings.</p>

<div class="admin-stack" style="margin-top:20px;">

<?php if($result->num_rows > 0): ?>

<?php while($row = $result->fetch_assoc()): ?>

<div class="listing-row">

<div>

<strong>
<?php echo htmlspecialchars($row['title']); ?>
</strong>

<small>

Category:
<?php echo htmlspecialchars($row['category_id']); ?>

•

R<?php echo number_format($row['price'],2); ?>

•

Seller:
<?php echo htmlspecialchars($row['full_name']); ?>

</small>

</div>

<?php

$status = strtolower($row['status']);

$class = "";

switch($status){

case "active":
$class = "status-live";
break;

case "draft":
$class = "status-pending";
break;

case "sold":
$class = "";
break;

}

?>

<span class="status-pill <?php echo $class; ?>">
<?php echo ucfirst($status); ?>
</span>

<div class="button-row" style="margin-top:0;">

<a class="button button-secondary button-small"
href="../public/product.php?id=<?php echo $row['id']; ?>">
View
</a>

<a class="button button-primary button-small"
href="edit_listing.php?id=<?php echo $row['id']; ?>">
Edit
</a>

<a class="button button-secondary button-small"
href="admin_listings.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this listing?')">
Delete
</a>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<p>No listings found.</p>

<?php endif; ?>

</div>

</div>

<!-- RIGHT PANEL -->

<aside class="admin-rail">

<span class="eyebrow">Listing Controls</span>

<h2>Overview</h2>

<div class="summary-grid">

<div class="summary-card">

<span class="summary-label">Active</span>

<strong class="summary-number">
<?php echo $active; ?>
</strong>

</div>

<div class="summary-card">

<span class="summary-label">Draft</span>

<strong class="summary-number">
<?php echo $draft; ?>
</strong>

</div>

<div class="summary-card">

<span class="summary-label">Sold</span>

<strong class="summary-number">
<?php echo $sold; ?>
</strong>

</div>

</div>

<div class="button-row" style="margin-top:20px;">

<a class="button button-primary"
href="admin_dashboard.php">
Dashboard
</a>

<a class="button button-secondary"
href="logout.php">
Logout
</a>

</div>

</aside>

</section>

</body>
</html>