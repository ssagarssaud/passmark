<?php
// Set response content type to JSON
header('Content-Type: application/json');

// Include the database connection file
require_once "../database/database.php";

// Securely retrieve and sanitize user inputs
$firstName = trim(htmlspecialchars($_POST["firstName"] ?? ""));
$lastName = trim(htmlspecialchars($_POST["lastName"] ?? ""));
$email = trim(filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL));
$password = $_POST["password"] ?? "";

// Validate required fields
if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

// Ensure password length is at least 6 characters
if (strlen($password) < 6) {
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters."]);
    exit;
}

try {
    // Check if the email is already registered
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Email already registered."]);
        exit;
    }

    // Securely hash the password using BCRYPT
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");

    if ($stmt->execute([$firstName, $lastName, $email, $hashedPassword])) {
        session_start(); // Start a session for the user
        $userId = $pdo->lastInsertId(); // Get last inserted user ID

        // Store full user info in session
        $_SESSION["user"] = [
            "id" => $userId,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "email" => $email,
            "created_at" => date("Y-m-d H:i:s") // Current timestamp
        ];

        echo json_encode([
            "success" => true,
            "message" => "User created successfully!",
            "data" => $_SESSION["user"]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to register. Try again."]);
    }
} catch (PDOException $e) {
    // Handle any database errors
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
