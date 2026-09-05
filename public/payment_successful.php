<?php
session_start();
require_once __DIR__ . "/php/config.php";

/* -----------------------------
   GET LISTING ID
------------------------------*/
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* -----------------------------
   FETCH LISTING
------------------------------*/
$stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$listing = $result->fetch_assoc();

if (!$listing) {
    die("Invalid order.");
}

/* -----------------------------
   MARK AS SOLD
------------------------------*/
$update = $conn->prepare("UPDATE listings SET status = 'sold' WHERE id = ?");
$update->bind_param("i", $id);
$update->execute();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<section class="product-page">

    <!-- LEFT -->
    <div class="product-gallery">

        <span class="eyebrow">Payment successful</span>
        <h1>Thank you for your purchase</h1>

        <div class="action-box" style="margin-top:16px; border-color: rgba(52, 211, 153, 0.4);">
            <strong>Transaction complete</strong>
            <p>Your payment has been processed successfully.</p>
        </div>

        <div class="summary-grid" style="margin-top:16px;">
            <div class="summary-card">
                <span class="summary-label">Order status</span>
                <strong class="summary-number">Confirmed</strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Payment</span>
                <strong class="summary-number">Approved</strong>
            </div>
        </div>

        <div class="action-box">
            <strong>What happens next?</strong>
            <p>The seller will be notified and will prepare your item.</p>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="product-details">

        <span class="eyebrow">Order summary</span>
        <h1>Your order</h1>

        <div class="action-box">
            <strong>Item purchased</strong>
            <p><?= htmlspecialchars($listing['title']); ?></p>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <span class="summary-label">Amount paid</span>
                <strong class="summary-number">
                    R<?= number_format($listing['price'], 2); ?>
                </strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Payment method</span>
                <strong class="summary-number">Card</strong>
            </div>
        </div>

        <div class="action-box">
            <strong>Order ID</strong>
            <p>#TC-<?= $listing['id']; ?></p>
        </div>

        <div class="button-row">
            <a class="button button-primary" href="browse.php">
                Continue shopping
            </a>

            <a class="button button-secondary" href="dashboard.php">
                View account
            </a>
        </div>

    </div>

</section>

</body>
</html>