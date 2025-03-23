<?php
require_once "../database/database.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

define("ENCRYPTION_KEY", "your-secret-key");
define("ENCRYPTION_IV", "1234567891011121");

function encryptPassword($password)
{
    return openssl_encrypt($password, "AES-128-CTR", ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $site_name = $_POST['site_name'];
    $site_url = $_POST['site_url'];
    $username = $_POST['username'];
    $password = $_POST['encrypted_password'];

    $encrypted_password = encryptPassword($password);

    $query = "UPDATE passwords SET site_name = :site_name, site_url = :site_url, username = :username, encrypted_password = :encrypted_password WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':site_name', $site_name);
    $stmt->bindParam(':site_url', $site_url);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':encrypted_password', $encrypted_password);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Password updated successfully!", "password" => [
            "id" => $id,
            "site_name" => $site_name,
            "site_url" => $site_url,
            "username" => $username,
            "encrypted_password" => $password
        ]]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update password."]);
    }
}
