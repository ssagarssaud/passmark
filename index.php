<?php
include("./includes/header.php");

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: view/dashboard.php");
    exit();
}

// Include database connection
require_once "./database/database.php";

// Check if the remember_me cookie exists
if (isset($_COOKIE["remember_me"])) {
    // Get the remember token from the cookie
    $rememberToken = $_COOKIE["remember_me"];

    // Fetch the user with the matching remember token from the database
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email FROM users WHERE remember_token = ?");
    $stmt->execute([$rememberToken]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If a matching user is found, log them in automatically
    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["last_name"] = $user["last_name"];

        // Redirect to the dashboard
        header("Location: view/dashboard.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="icon" type="image/png" href="assets/favicon_io/favicon-32x32.png"> -->
    <link rel="shortcut icon" href="./assets/favicon_io/favicon.png">
    <link rel="stylesheet" href="style.css">
    <title>PassMark</title>
</head>

<body>
    <div class="container">
        <section class="hero-section">
            <p class="badge">
                Get Secured 🔒
            </p>
            <div class="head">
                <h1>
                    PassMark:Manage Passwords & Bookmarks.
                </h1>
                <p>Securely store and manage passwords & bookmarks with PassMark. Enjoy encryption, quick search, and seamless access—all in one place!</p>
                <button onclick="window.location.href='./auth/signup.php'" class="sign-up headerBtn hero-btn">Get Started</button>
            </div>
        </section>
        <!-- Feature Section -->
        <section id="feature" class="features-section">
            <h1 class="our-feature">Our Services</h1>
            <div class="all-features">
                <div class="features">
                    <h2>🔐</h2>
                    <h1>Manage</h1>
                    <p class="feature-name">Password</p>
                    <p>Securely store and organize all your passwords in one place with encryption for maximum security.</p>
                </div>
                <div class="features">
                    <h2>📚</h2>
                    <h1>Manage</h1>
                    <p class="feature-name">Bookmark</p>
                    <p>Easily save, organize, and access your favorite websites with an intuitive bookmarking system.</p>
                </div>
                <div class="features">
                    <h2>🌍</h2>
                    <h1>Access</h1>
                    <p class="feature-name">Everywhere</p>
                    <p>Access your saved passwords and bookmarks from any device with a secure login.</p>
                </div>
            </div>
        </section>
        <!-- About US -->
        <section id="about" class="about-us">
            <h1 class="our-feature">About</h1>
            <p class="about-us-para">Welcome to PassMark.<span class="about-span1"> your secure solution for managing passwords and bookmarks. Our app ensures your sensitive data is safely stored with encryption, while offering easy access to your bookmarks across devices. With a focus on security and convenience, we aim to help you keep your digital life </span> <span class="about-span2">organized and protected.</span></p>
        </section>

        <!-- Contact Us -->

        <section class="contact-us">
            <h1 class="our-feature">Thank You</h1>
            <div class="connect">
                <input type="email" placeholder="Enter your email">
                <button onclick="window.location.href='./auth/signup.php'" class="sign-up headerBtn">Sign Up</button>
            </div>
        </section>
    </div>
</body>

</html>

<?php
include("./includes/footer.html")
?>