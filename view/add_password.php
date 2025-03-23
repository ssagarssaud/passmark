<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not authenticated."]);
    exit;
}

define("ENCRYPTION_KEY", "your-secret-key"); 
define("ENCRYPTION_IV", "1234567891011121"); 

function encryptPassword($password)
{
    return openssl_encrypt($password, "AES-128-CTR", ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}

$user_id = $_SESSION['user_id'];
$site_name = $_POST['site_name'];
$site_url = $_POST['site_url'];
$username = $_POST['username'];
$plain_password = $_POST['encrypted_password'];

$encrypted_password = encryptPassword($plain_password);

// Insert into database
$query = "INSERT INTO passwords (user_id, site_name, site_url, username, encrypted_password, created_at) 
          VALUES (:user_id, :site_name, :site_url, :username, :encrypted_password, NOW())";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindParam(':site_name', $site_name, PDO::PARAM_STR);
$stmt->bindParam(':site_url', $site_url, PDO::PARAM_STR);
$stmt->bindParam(':username', $username, PDO::PARAM_STR);
$stmt->bindParam(':encrypted_password', $encrypted_password, PDO::PARAM_STR);

if ($stmt->execute()) {
    $lastId = $pdo->lastInsertId();
    echo json_encode([
        "status" => "success",
        "message" => "Password saved successfully!",
        "password" => [
            "id" => $lastId,
            "site_name" => htmlspecialchars($site_name),
            "site_url" => htmlspecialchars($site_url),
            "username" => htmlspecialchars($username),
            "encrypted_password" => htmlspecialchars($plain_password) // Sending back decrypted password
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save password."]);
}
