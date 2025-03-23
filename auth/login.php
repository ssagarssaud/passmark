<?php include("../includes/header.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Log In</title>
</head>

<body>
    <div class="container login-container">
        <form id="loginForm">
            <h1>Welcome back</h1>
            <p>Don't have an account yet ? <span><a href="./signup.php">Sign Up</a></span></p>

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="checkbox">
                <div>
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <p>Forget Password ?</p>
            </div>

            <button class="login-btn" type="submit">Log In</button>
            <p id="responseMessage" style="color: red;"></p>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loginForm").addEventListener("submit", async function(event) {
                event.preventDefault(); 

                let form = this;
                let formData = new FormData(form);
                let responseMessage = document.getElementById("responseMessage");

                try {
                   
                    let response = await fetch("loginLogic.php", {
                        method: "POST",
                        body: formData,
                    });

                 
                    const textResponse = await response.text(); 
                    console.log("Response Text: ", textResponse); 
                    let result = JSON.parse(textResponse); 

                    
                    if (result.success) {
                        Toastify({
                            text: result.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #96c93d, #0a0a0a)",
                        }).showToast();

                       
                        setTimeout(() => window.location.href = "../view/dashboard.php", 1500);
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