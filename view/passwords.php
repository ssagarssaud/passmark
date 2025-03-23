<?php
include("../includes/header.php");
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

define("ENCRYPTION_KEY", "your-secret-key");
define("ENCRYPTION_IV", "1234567891011121");

function decryptPassword($encrypted_password)
{
    return openssl_decrypt($encrypted_password, "AES-128-CTR", ENCRYPTION_KEY, 0, ENCRYPTION_IV);
}

// Fetch passwords
$query = "SELECT * FROM passwords WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$passwords = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="../style.css?v=<?php echo time(); ?>">
    <title>Passwords</title>
</head>

<body>
    <div class="container dashboard-container">
        <button id="add-password-btn" class="add-button">Add Password</button>
        <div class="password-list">
            <?php if ($passwords): ?>
                <?php foreach ($passwords as $password): ?>
                    <div class="password" data-id="<?php echo $password['id']; ?>">
                        <h1><?php echo htmlspecialchars($password['site_name']); ?></h1>
                        <div class="link-div">
                            <a href="<?php echo htmlspecialchars($password['site_url']); ?>" target="_blank">
                                <p><?php echo htmlspecialchars($password['site_url']); ?></p>
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                        <p class="username-pass" onclick="copyToClipboard(this)">
                            <?php echo htmlspecialchars($password['username']); ?>
                        </p>

                        <span class="decrypted-password hidden-password password-user"
                            data-password="<?php echo htmlspecialchars(decryptPassword($password['encrypted_password'])); ?>"
                            onclick="copyToClipboard(this, true)">
                            <?php echo str_repeat("•", strlen(decryptPassword($password['encrypted_password']))); ?>
                        </span>
                        <i class="fa-solid fa-eye-slash toggle-visibility"></i>


                        <i class="fa-regular fa-trash-can delete-icon" data-id="<?php echo $password['id']; ?>"></i>
                        <i class="fa-solid fa-pen-to-square edit-icon"
                            data-id="<?php echo $password['id']; ?>"
                            data-site_name="<?php echo htmlspecialchars($password['site_name']); ?>"
                            data-site_url="<?php echo htmlspecialchars($password['site_url']); ?>"
                            data-username="<?php echo htmlspecialchars($password['username']); ?>"
                            data-password="<?php echo htmlspecialchars(decryptPassword($password['encrypted_password'])); ?>">
                        </i>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No passwords found.</p>
            <?php endif; ?>
        </div>

        <!-- Hidden Form For Adding Password -->
        <form class="password-form hidden" id="passwordForm">
            <i id="cross-icon-add-pass" class="fa-solid fa-x"></i>

            <h1>Save Your Passwords</h1>
            <div class="input-group">
                <i class="fa-solid fa-globe"></i>
                <input type="text" name="site_name" id="site_name" placeholder="Enter site name" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-link"></i>
                <input type="url" name="site_url" id="site_url" placeholder="Enter site URL" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Enter username" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" name="encrypted_password" id="encrypted_password" placeholder="Enter password" required>
                <i class="fa-solid fa-eye-slash toggle-password" id="add-togglePassword"></i>
            </div>
            <button id="save-password-btn" class="login-btn" type="submit">Save Password</button>
        </form>


        <!-- Hidden Form For Updating Password -->
        <form class="password-form hidden" id="updatePasswordForm">


            <i id="cross-icon-update-pass" class="fa-solid fa-x"></i>


            <h1>Update Password</h1>
            <input type="hidden" name="id" id="update_id">
            <div class="input-group">
                <i class="fa-solid fa-globe"></i>
                <input type="text" name="site_name" id="update_site_name" placeholder="Enter site name" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-link"></i>
                <input type="url" name="site_url" id="update_site_url" placeholder="Enter site URL" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" id="update_username" placeholder="Enter username" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" name="encrypted_password" id="update_encrypted_password" placeholder="Enter password" required>
                <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
            </div>
            <button id="update-password-btn" class="login-btn" type="submit">Update Password</button>
        </form>
    </div>

    <script>
        // Add Password Logic
        document.getElementById("passwordForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("add_password.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #96c93d, #0a0a0a)",
                        }).showToast();

                        // Dynamically add the new password entry to the UI
                        let newPassword = document.createElement("div");
                        newPassword.classList.add("password");
                        newPassword.setAttribute("data-id", data.password.id);
                        newPassword.innerHTML = `
                            <h1>${data.password.site_name}</h1>
                            <div class="link-div">
                                <a href="${data.password.site_url}" target="_blank">
                                    <p>${data.password.site_url}</p>
                                    <i class="fa-solid fa-link"></i>
                                </a>
                            </div>
                            <p>${data.password.username}</p>
                            <span class="decrypted-password">${data.password.encrypted_password}</span>
                            <i class="fa-solid fa-eye-slash"></i>
                            <i class="fa-regular fa-trash-can delete-icon"></i>
                            <i class="fa-solid fa-pen-to-square edit-icon"
                                data-id="${data.password.id}"
                                data-site_name="${data.password.site_name}"
                                data-site_url="${data.password.site_url}"
                                data-username="${data.password.username}"
                                data-password="${data.password.encrypted_password}">
                            </i>
                        `;

                        document.querySelector(".password-list").prepend(newPassword);
                        document.getElementById("passwordForm").reset();
                        document.getElementById("passwordForm").classList.add("hidden");
                    } else {
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff416c, #0a0a0a)",
                        }).showToast();
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        // Handle Add Password Button
        const addPasswordForm = document.getElementById("passwordForm")
        document.getElementById("add-password-btn").addEventListener("click", () => {
            addPasswordForm.classList.toggle("hidden")
        })


        // UPDATE PASSWORD LOGIC

        document.querySelectorAll(".edit-icon").forEach(button => {
            button.addEventListener("click", function() {
                document.getElementById("update_id").value = this.getAttribute("data-id");
                document.getElementById("update_site_name").value = this.getAttribute("data-site_name");
                document.getElementById("update_site_url").value = this.getAttribute("data-site_url");
                document.getElementById("update_username").value = this.getAttribute("data-username");
                document.getElementById("update_encrypted_password").value = this.getAttribute("data-password");

                document.getElementById("updatePasswordForm").classList.toggle("hidden");
            });
        });

        document.getElementById("updatePasswordForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("update_password.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #96c93d, #0a0a0a)",
                        }).showToast();

                        let passwordDiv = document.querySelector(`.password[data-id='${data.password.id}']`);
                        passwordDiv.querySelector("h1").innerText = data.password.site_name;
                        passwordDiv.querySelector("p").innerText = data.password.username;
                        passwordDiv.querySelector(".decrypted-password").innerText = data.password.encrypted_password;

                        document.getElementById("updatePasswordForm").reset();
                        document.getElementById("updatePasswordForm").classList.add("hidden");
                    } else {
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff416c, #0a0a0a)",
                        }).showToast();
                    }
                })
                .catch(error => console.error("Error:", error));
        });


        // SHOW AND HIDE THE PASSWORD IN THE UPDATE FORM
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordInput = document.getElementById("update_encrypted_password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                this.classList.add("fa-eye-slash");
                this.classList.remove("fa-eye");
            }
        });

        // SHOW AND HIDE THE PASSWORD IN ADD PASSWORD-FORM
        const passwordToggleIcon = document.getElementById("add-togglePassword")
        passwordToggleIcon.addEventListener("click", () => {
            let addFormInput = document.getElementById("encrypted_password");
            if (addFormInput.type === "password") {
                addFormInput.type = "text";
                passwordToggleIcon.classList.remove("fa-eye-slash");
                passwordToggleIcon.classList.add("fa-eye");
            } else {
                addFormInput.type = "password";
                passwordToggleIcon.classList.add("fa-eye-slash");
                passwordToggleIcon.classList.remove("fa-eye");
            }

        })

        // HIDING PASSWORD FOR EACH OF THE PASSWORD
        document.querySelectorAll(".toggle-visibility").forEach(icon => {
            icon.addEventListener("click", function() {
                let passwordSpan = this.previousElementSibling; // Get the password span
                let actualPassword = passwordSpan.getAttribute("data-password"); // Get actual password

                if (passwordSpan.textContent.includes("•")) {
                    // Show the actual password
                    passwordSpan.textContent = actualPassword;
                    this.classList.remove("fa-eye-slash");
                    this.classList.add("fa-eye");
                } else {
                    // Hide the password with dots matching length
                    passwordSpan.textContent = "•".repeat(actualPassword.length);
                    this.classList.remove("fa-eye");
                    this.classList.add("fa-eye-slash");
                }
            });
        });



        // DELETE PASSWORD

        document.querySelectorAll(".delete-icon").forEach(button => {
            button.addEventListener("click", function() {
                let passwordId = this.getAttribute("data-id");
                let passwordDiv = this.closest(".password");

                // Show confirmation before deleting
                if (confirm("Are you sure you want to delete this password?")) {
                    fetch("delete_password.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `id=${passwordId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                Toastify({
                                    text: data.message,
                                    duration: 3000,
                                    gravity: "bottom",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #ff416c, #0a0a0a)",
                                }).showToast();

                                // Remove the password entry from the UI
                                passwordDiv.remove();
                            } else {
                                Toastify({
                                    text: data.message,
                                    duration: 3000,
                                    gravity: "bottom",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #ff416c, #0a0a0a)",
                                }).showToast();
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });


        // CROSS
        document.getElementById("cross-icon-add-pass").addEventListener("click", () => {
            document.getElementById("passwordForm").classList.add("hidden")
        })
        document.getElementById("cross-icon-update-pass").addEventListener("click", () => {
            document.getElementById("updatePasswordForm").classList.add("hidden")
        })

        // copy to clipboard
        function copyToClipboard(element, isPassword = false) {
            let text = isPassword ? element.getAttribute('data-password') : element.innerText;

            navigator.clipboard.writeText(text).then(() => {
                Toastify({
                    text: "Copied: " + text,
                    duration: 3000,
                    gravity: "bottom",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #96c93d, #0a0a0a)",
                }).showToast();
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
    <script src="./dashboard.js"></script>
</body>

</html>