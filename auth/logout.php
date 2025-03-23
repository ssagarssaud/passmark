<?php
// Start the session to access session variables
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Check if the 'remember_me' cookie exists and delete it
if (isset($_COOKIE["remember_me"])) {
    setcookie("remember_me", "", time() - 3600, "/", "", true, true); // Set cookie expiration to the past (effectively deleting it)
}

// Redirect to the home page after logout
header("Location: /passmark/index.php"); // Full absolute path to ensure proper redirection
exit();
