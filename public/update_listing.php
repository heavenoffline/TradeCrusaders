<?php
session_start();
require_once __DIR__ . "/../public/php/config.php";

/* -----------------------------
   GET LISTING ID
------------------------------*/
$id = $_POST['id'] ?? $_GET['id'] ?? 0;
$id = (int)$id;

if ($id <= 0) {
    die("Invalid listing ID.");
}

/* -----------------------------
   FETCH CURRENT LISTING
------------------------------*/
$stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    die("Listing not found.");
}

/* -----------------------------
   HANDLE UPDATE
------------------------------*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $condition_label = $_POST['condition_label'];
    $status = $_POST['status'];

    /* -----------------------------
       IMAGE UPLOAD
    ------------------------------*/
    $imageName = $listing['images']; // keep old image by default

    if (!empty($_FILES['image']['name'])) {

        $imageName = time() . "_" . basename($_FILES['image']['name']);

        // upload path
        $uploadPath = __DIR__ . "/../uploads/";

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath . $imageName);
    }

    /* -----------------------------
       UPDATE DATABASE
    ------------------------------*/
    $update = $conn->prepare("
        UPDATE listings
        SET title = ?, description = ?, price = ?, category_id = ?, condition_label = ?, status = ?, images = ?
        WHERE id = ?
    ");

    if (!$update) {
        die("Prepare failed: " . $conn->error);
    }

    $update->bind_param(
        "ssdisssi",
        $title,
        $description,
        $price,
        $category_id,
        $condition_label,
        $status,
        $imageName,
        $id
    );

    if ($update->execute()) {

        // redirect back to edit page
        header("Location: edit_listing.php?id=" . $id . "&updated=1");
        exit();

    } else {
        die("Failed to update listing.");
    }
}

/* -----------------------------
   SUCCESS MESSAGE
------------------------------*/
$success = "";
if (isset($_GET['updated'])) {
    $success = "Listing updated successfully!";
}
?>