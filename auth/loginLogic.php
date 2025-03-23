<?php


header('Content-Type: application/json');

require_once "../database/database.php";

session_start();


$email = trim(filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL));
$password = $_POST["password"] ?? "";


if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

// Check if the email exists in the database (use prepared statements for SQL injection protection)
$stmt = $pdo->prepare("SELECT id, first_name, last_name, email, password, remember_token FROM users WHERE email = ?");
$stmt->execute([$email]);

// Fetch the user details from the database
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user exists and password matches
if (!$user || !password_verify($password, $user["password"])) {
    echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    exit;
}

// HTML Injection Protection: Escape output data before sending it to the client
$userFirstName = htmlspecialchars($user["first_name"]);
$userLastName = htmlspecialchars($user["last_name"]);

// Set session variables for the logged-in user
$_SESSION["user_id"] = $user["id"];
$_SESSION["email"] = $email;
$_SESSION["first_name"] = $userFirstName;
$_SESSION["last_name"] = $userLastName;

// Handle "Remember Me" functionality
if (isset($_POST["remember"]) && $_POST["remember"] === "on") {
    $rememberToken = bin2hex(random_bytes(32));
    // Generate a random token for the "Remember Me" functionality
    $hashedToken = password_hash($rememberToken, PASSWORD_DEFAULT); // Hash the token

    $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE email = ?");
    $stmt->execute([$hashedToken, $email]);


    // Set the "Remember Me" cookie for 30 days with the Secure and HttpOnly flags
    setcookie(
        "remember_me",
        $rememberToken,
        time() + (86400 * 30),
        "/",
        "",
        false,
        false
    );

   

    // Save the token in the database for the user
    $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE email = ?");
    $stmt->execute([$rememberToken, $email]);

    // Set the token in the session for immediate use
    $_SESSION["remember_token"] = $rememberToken;
}

// Return success message with user data in JSON format
echo json_encode([
    "success" => true,
    "message" => "Login successful!",
    "data" => [
        "user_id" => $user["id"],
        "first_name" => $userFirstName,
        "last_name" => $userLastName,
        "email" => $email
    ]
]);
