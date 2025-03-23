<?php
require_once "../database/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST["id"])) {
        echo json_encode(["status" => "error", "message" => "Missing password ID."]);
        exit;
    }

    $id = $_POST["id"];
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["status" => "error", "message" => "User not authenticated."]);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Ensure the password belongs to the logged-in user
    $query = "DELETE FROM passwords WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Password deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete password."]);
    }
}
