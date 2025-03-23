<?php
require_once "../database/database.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$user_id = $_SESSION['user_id'];
$bookmark_id = $_POST['bookmark_id'] ?? null;
$url = $_POST['url'] ?? null;
$website_name = $_POST['website_name'] ?? null;
$website_photo = null;

// Validate inputs
if (!$bookmark_id || !$url || !$website_name) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit();
}

// Check if bookmark belongs to user
$query = "SELECT * FROM bookmarks WHERE id = :bookmark_id AND user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['bookmark_id' => $bookmark_id, 'user_id' => $user_id]);
$bookmark = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bookmark) {
    echo json_encode(["status" => "error", "message" => "Bookmark not found or unauthorized."]);
    exit();
}

// Handle image upload if a new one is provided
if (!empty($_FILES['website_photo']['name'])) {
    $upload_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES["website_photo"]["name"]);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES["website_photo"]["tmp_name"], $target_file)) {
        $website_photo = $target_file;
    } else {
        echo json_encode(["status" => "error", "message" => "Error uploading image."]);
        exit();
    }
} else {
    $website_photo = $bookmark['website_photo']; // Keep existing photo if not updated
}

// Update the bookmark in the database
$update_query = "UPDATE bookmarks SET url = :url, website_name = :website_name, website_photo = :website_photo WHERE id = :bookmark_id AND user_id = :user_id";
$update_stmt = $pdo->prepare($update_query);
$update_success = $update_stmt->execute([
    'url' => $url,
    'website_name' => $website_name,
    'website_photo' => $website_photo,
    'bookmark_id' => $bookmark_id,
    'user_id' => $user_id
]);

if ($update_success) {
    echo json_encode([
        "status" => "success",
        "message" => "Bookmark updated successfully!",
        "bookmark" => [
            "id" => $bookmark_id,
            "url" => $url,
            "website_name" => $website_name,
            "website_photo" => $website_photo
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update bookmark."]);
}
