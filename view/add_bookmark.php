<?php
session_start();
require_once "../database/database.php"; // Database connection

$response = ["status" => "error", "message" => "Invalid request"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_SESSION['user_id'])) {
            throw new Exception("User not logged in.");
        }

        // Validate and sanitize inputs
        $user_id = $_SESSION['user_id'];
        $url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
        $website_name = htmlspecialchars($_POST['website_name'], ENT_QUOTES, 'UTF-8');

        // File upload handling
        $website_photo = "../assets/default.png"; // Default image
        if (isset($_FILES['website_photo']) && $_FILES['website_photo']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['website_photo']['type'], $allowedTypes)) {
                $uploadDir = "../uploads/";
                $fileName = uniqid() . "_" . basename($_FILES['website_photo']['name']);
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['website_photo']['tmp_name'], $uploadPath)) {
                    $website_photo = $uploadPath;
                } else {
                    throw new Exception("File upload failed");
                }
            } else {
                throw new Exception("Invalid file type");
            }
        }

        if (!$url || !$website_name) {
            throw new Exception("Invalid form data");
        }

        // Insert data using PDO
        $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, url, website_name, website_photo, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $url, $website_name, $website_photo]);

        // Fetch the last inserted ID
        $bookmark_id = $pdo->lastInsertId();

        $response = [
            "status" => "success",
            "message" => "Bookmark added successfully!",
            "bookmark" => [
                "id" => $bookmark_id,
                "url" => $url,
                "website_name" => $website_name,
                "website_photo" => $website_photo
            ]
        ];
    } catch (Exception $e) {
        $response = ["status" => "error", "message" => $e->getMessage()];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;
