<?php
include("../includes/header.php");
require_once "../database/database.php";


// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

try {
    // Fetch password count for the user
    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalPasswords FROM passwords WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $passwordResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPasswords = $passwordResult['totalPasswords'];

    // Fetch bookmark count for the user
    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalBookmarks FROM bookmarks WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $bookmarkResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalBookmarks = $bookmarkResult['totalBookmarks'];
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Dashboard</title>
</head>

<body>
    <div class="container dashboard-container main-dashboard">
        <h1>Your Passwords and Bookmarks in One Place</h1>
        
        <div class="analytics-container">

            <!-- Bookmark Analytics -->
            <div class="bookmark-analytics">
                <div class="top">
                    <h1>Your Bookmarks Analytics</h1>
                    <p>Updated just now</p>
                </div>
                <div class="number-div">
                    <p class="number" id="bookmark-count"><?php echo $totalBookmarks; ?> </p> <!-- Dynamic Count -->
                    <i class="fa-solid fa-light fa-arrow-trend-up fa-3x"></i>
                </div>
                <div class="bottom">
                    <div class="visit">
                        <h1>Visit</h1>
                        <a href="../view/bookmarks.php"> <i class="fa-solid fa-link"></i></a>

                    </div>
                    <p>Updated just now</p>
                </div>
            </div>
            <!-- Password Analytics -->

            <div class="password-analytics">
                <div class="top">
                    <h1>Your Password Analytics</h1>
                    <p>Updated just now</p>
                </div>
                <div class="number-div">
                    <p class="number" id="password-count"><?php echo $totalPasswords; ?> </p> <!-- Dynamic Count -->
                    <i class="fa-solid fa-light fa-arrow-trend-up fa-3x"></i>
                </div>
                <div class="bottom">
                    <div class="visit">
                        <h1>Visit</h1>
                        <a href="../view/passwords.php"> <i class="fa-solid fa-link"></i></a>
                    </div>
                    <p>Updated just now</p>
                </div>
            </div>
            
        </div>
    </div>

    <script src="dashboard.js"></script>
</body>

</html>

<?php include("../includes/footer.html"); ?>