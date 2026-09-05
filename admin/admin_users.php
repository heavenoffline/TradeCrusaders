<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

/* -----------------------------
   SECURITY CHECK (ADMIN ONLY)
------------------------------*/
if (!isset($_SESSION['admin_id']) || $_SESSION['role_id'] != 1) {
    header("Location: admin_login.php");
    exit();
}

/* -----------------------------
   CREATE USER
------------------------------*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_user'])) {

    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = (int)$_POST['role_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        INSERT INTO users (full_name, email, password_hash, role_id, status)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssis", $name, $email, $password, $role_id, $status);
    $stmt->execute();

    header("Location: admin_users.php");
    exit();
}

/* -----------------------------
   DELETE USER
------------------------------*/
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: admin_users.php");
    exit();
}

/* -----------------------------
   GET USERS
------------------------------*/
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Management</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<section class="admin-page">

<!-- LEFT: USER LIST -->
<div class="admin-table">

    <div class="section-heading">
        <span class="eyebrow">Admin panel</span>
        <h1>User management</h1>
        <p>Manage all registered users and administrators.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php if ($result && $result->num_rows > 0): ?>

            <?php while ($row = $result->fetch_assoc()): ?>

                <tr>

                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>

                    <td>
                        <?php
                        if ($row['role_id'] == 1) echo "Admin";
                        elseif ($row['role_id'] == 2) echo "Moderator";
                        else echo "Vendor";
                        ?>
                    </td>

                    <td>
                        <span class="status-pill">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>

                    <td class="row-actions">

                        <a class="button button-secondary button-small"
                           href="edit_user.php?id=<?= $row['id'] ?>">
                           Edit
                        </a>

                        <a class="button button-secondary button-small"
                           href="admin_users.php?delete=<?= $row['id'] ?>"
                           onclick="return confirm('Delete this user?')">
                           Delete
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr><td colspan="5">No users found</td></tr>

        <?php endif; ?>

        </tbody>
    </table>

</div>

<!-- RIGHT: CREATE USER -->
<aside class="admin-rail">

    <div class="section-heading">
        <span class="eyebrow">Create user</span>
        <h2>Add new user / admin</h2>
        <p>Fill in details to create a new account.</p>
    </div>

    <form class="form-grid" method="POST">

        <input class="input" type="text" name="full_name" placeholder="Full name" required>

        <input class="input" type="email" name="email" placeholder="Email address" required>

        <input class="input" type="password" name="password" placeholder="Password" required>

        <select class="select" name="role_id" required>
            <option value="0">Vendor</option>
            <option value="2">Moderator</option>
            <option value="1">Admin</option>
        </select>

        <select class="select" name="status" required>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="disabled">Disabled</option>
        </select>

        <div class="button-row">
            <button class="button button-primary" type="submit" name="create_user">
                Create user
            </button>
        </div>

    </form>

    <div class="action-box">
        <strong>Note</strong>
        <p>Admins have full system access. Moderators can manage content only.</p>
    </div>

</aside>

</section>

</body>
</html>