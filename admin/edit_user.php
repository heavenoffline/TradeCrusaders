<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

/* -----------------------------
   RBAC SECURITY CHECK
------------------------------*/
if (!isset($_SESSION['admin_id']) || $_SESSION['role_id'] != 1) {
    header("Location: admin_login.php");
    exit();
}

/* -----------------------------
   GET USER ID
------------------------------*/
if (!isset($_GET['id'])) {
    header("Location: admin_users.php");
    exit();
}

$user_id = (int)$_GET['id'];

/* -----------------------------
   FETCH USER
------------------------------*/
$stmt = $conn->prepare("SELECT id, full_name, email, role_id, status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

/* -----------------------------
   UPDATE USER (RBAC PROTECTED)
------------------------------*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role_id = (int)$_POST['role_id'];
    $status = $_POST['status'];

    /* RBAC RULE:
       Only SUPER ADMIN (role_id = 1) can assign roles
    */
    if ($_SESSION['role_id'] != 1) {
        die("You are not allowed to change roles.");
    }

    $update = $conn->prepare("
        UPDATE users
        SET full_name = ?, email = ?, role_id = ?, status = ?
        WHERE id = ?
    ");

    if (!$update) {
        die("Prepare failed: " . $conn->error);
    }

    $update->bind_param(
        "ssisi",
        $name,
        $email,
        $role_id,
        $status,
        $user_id
    );

    $update->execute();

    header("Location: admin_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<section class="admin-page">

<div class="admin-rail">

    <div class="section-heading">
        <span class="eyebrow">RBAC Control</span>
        <h2>Edit User</h2>
        <p>Update user details and permissions.</p>
    </div>

    <form class="form-grid" method="POST">

        <input class="input"
               type="text"
               name="full_name"
               value="<?= htmlspecialchars($user['full_name']) ?>"
               required>

        <input class="input"
               type="email"
               name="email"
               value="<?= htmlspecialchars($user['email']) ?>"
               required>

        <!-- ROLE (RBAC CONTROLLED) -->
        <select class="select" name="role_id">

            <option value="0" <?= $user['role_id'] == 0 ? 'selected' : '' ?>>
                Vendor
            </option>

            <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>
                Moderator
            </option>

            <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>
                Admin
            </option>

        </select>

        <!-- STATUS -->
        <select class="select" name="status">

            <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>
                Active
            </option>

            <option value="pending" <?= $user['status'] == 'pending' ? 'selected' : '' ?>>
                Pending
            </option>

            <option value="disabled" <?= $user['status'] == 'disabled' ? 'selected' : '' ?>>
                Disabled
            </option>

        </select>

        <div class="button-row">

        <button class="button button-primary" type="submit">
            Save Changes
        </button>

        <a class="button button-secondary" href="admin_users.php">
            Cancel
        </a>

        <a class="button button-secondary" href="admin_dashboard.php">
            Admin Dashboard
        </a>
       </div>

    </form>

</div>

</section>

</body>
</html>