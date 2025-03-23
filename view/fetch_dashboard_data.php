<?php
session_start();
require_once "../database/database.php";
// Database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Query to count total passwords for the user
$passwordQuery = "SELECT COUNT(*) AS totalPasswords FROM passwords WHERE user_id = ?";
$stmt = $conn->prepare($passwordQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$passwordResult = $stmt->get_result()->fetch_assoc();
$totalPasswords = $passwordResult['totalPasswords'];

// Query to count total bookmarks for the user
$bookmarkQuery = "SELECT COUNT(*) AS totalBookmarks FROM bookmarks WHERE user_id = ?";
$stmt = $conn->prepare($bookmarkQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookmarkResult = $stmt->get_result()->fetch_assoc();
$totalBookmarks = $bookmarkResult['totalBookmarks'];

// Return data as JSON
echo json_encode([
    "totalPasswords" => $totalPasswords,
    "totalBookmarks" => $totalBookmarks
]);
