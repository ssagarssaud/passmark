<?php include("../includes/header.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="../style.css">
    <title>Signup</title>
</head>

<body>
    <div class="signup-container">
        <form id="signupForm">
            <h1>Welcome</h1>
            <p>Already have an account? <span><a href="./login.php">Log In</a></span></p>

            <div class="fullName">
                <div class="input-group">
                    <input type="text" name="firstName" placeholder="Enter First Name" required>
                </div>
                <div class="input-group">
                    <input type="text" name="lastName" placeholder="Enter Last Name" required>
                </div>
            </div>

            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button class="signup-btn" type="submit">Sign Up</button>

            <p id="responseMessage" style="color: red;"></p>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("signupForm").addEventListener("submit", async function(event) {
                event.preventDefault(); // Prevent default form submission

                let form = this;
                let formData = new FormData(form);
                let responseMessage = document.getElementById("responseMessage");

                // Client-side validation
                let email = formData.get("email");
                let password = formData.get("password");

                if (password.length < 6) {
                    Toastify({
                        text: "Password must be at least 6 character",
                        duration: 3000,
                        gravity: "bottom",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff416c, #0a0a0a)",
                    }).showToast();
                    return;
                }

                try {
                    let response = await fetch("register.php", {
                        method: "POST",
                        body: formData,
                    });

                    let result = await response.json();
                    console.log("Server Response:", result);

                    if (result.success) {
                        Toastify({
                            text: result.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #96c93d, #0a0a0a)",
                        }).showToast();
                        form.reset(); // Reset form

                        // Redirect after 1.5 seconds (optional)
                        setTimeout(() => window.location.href = "login.php", 1500);
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff416c, #0a0a0a)",
                        }).showToast();
                    }
                } catch (error) {
                    console.error("AJAX Error:", error);
                    responseMessage.style.color = "red";
                    responseMessage.textContent = "An error occurred.";
                }
            });
        });
    </script>
</body>

</html>