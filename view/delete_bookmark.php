<?php
require_once "../database/database.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["id"]) || empty($_POST["id"])) {
        echo json_encode(["status" => "error", "message" => "Bookmark ID is missing."]);
        exit;
    }

    $bookmark_id = $_POST["id"];
    $user_id = $_SESSION['user_id'];

    // Ensure the bookmark belongs to the logged-in user before deleting
    $query = "DELETE FROM bookmarks WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $bookmark_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Bookmark deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete bookmark."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
