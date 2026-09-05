<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

/*
    SECURITY CHECK (IMPORTANT)
    - Only allow logged-in admin users
    - You should store a role in session like: $_SESSION['role'] = 'admin'
*/

if (!isset($_SESSION['admin_id']) || $_SESSION['role_id'] != 1) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="admin.css">
</head>

<body>

<section class="admin-page">

    <!-- LEFT MAIN PANEL -->
    <div class="product-details">

        <span class="eyebrow">Admin dashboard</span>
        <h1>System overview</h1>

        <p>Manage users, listings, reports, and marketplace activity.</p>

        <!-- Quick stats -->
        <div class="summary-grid" style="margin-top:18px;">

            <div class="summary-card">
                <span class="summary-label">Total users</span>
                <strong class="summary-number">128</strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Active listings</span>
                <strong class="summary-number">54</strong>
            </div>

            <div class="summary-card">
                <span class="summary-label">Reports</span>
                <strong class="summary-number">7</strong>
            </div>

        </div>

        <!-- Management actions -->
        <div class="section-heading" style="margin-top:26px;">
            <span class="eyebrow">Quick actions</span>
            <h2>Manage platform</h2>
        </div>

        <div class="step-list">

            <div class="step-item">
                <div>
                    <h3>Manage users</h3>
                    <p>View, suspend, or promote accounts.</p>
                </div>
            </div>

            <div class="step-item">
                <div>
                    <h3>Moderate listings</h3>
                    <p>Approve, remove, or flag marketplace items.</p>
                </div>
            </div>

            <div class="step-item">
                <div>
                    <h3>Review reports</h3>
                    <p>Handle user complaints and disputes.</p>
                </div>
            </div>

        </div>

    </div>

    <!-- RIGHT SIDEBAR -->
    <aside class="admin-rail">

        <span class="eyebrow">Live activity</span>
        <h2>Recent events</h2>

        <div class="listing-row">
            <div>
                <strong>New user registered</strong>
                <small>2 minutes ago</small>
            </div>
            <span class="status-pill status-live">New</span>
        </div>

        <div class="listing-row">
            <div>
                <strong>Listing flagged</strong>
                <small>15 minutes ago</small>
            </div>
            <span class="status-pill status-pending">Review</span>
        </div>

        <div class="listing-row">
            <div>
                <strong>Report resolved</strong>
                <small>1 hour ago</small>
            </div>
            <span class="status-pill">Done</span>
        </div>

        <!-- Admin shortcuts -->
        <div class="section-heading" style="margin-top:20px;">
            <span class="eyebrow">Shortcuts</span>
            <h2>Navigation</h2>
        </div>

        <div class="button-row">
            <a class="button button-primary" href="admin_users.php">Users</a>
            <a class="button button-secondary" href="admin_listings.php">Listings</a>
        </div>

        <div class="button-row" style="margin-top:10px;">
            <a class="button button-secondary" href="admin_logout.php">Logout</a>
        </div>

    </aside>

</section>

</body>
</html>