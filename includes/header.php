<?php session_start(); ?>
<?php $current_page = basename($_SERVER['SCRIPT_NAME']); // Get the current page name
?>
<header>
    <div class="logo">
        <?php if (isset($_SESSION["user_id"])): ?>
            <span><a href="../view/dashboard.php">PassMark</a></span>
        <?php else: ?>
            <span><a href="/passmark/index.php">PassMark</a></span>
        <?php endif; ?>
    </div>
    <div class="nevigation">
        <?php if (isset($_SESSION["user_id"])): ?>
            <ul>
                <li><a href="../view/bookmarks.php" class="<?= $current_page == 'bookmarks.php' ? 'active' : '' ?>">BookMarks</a></li>
                <li><a href="../view/passwords.php" class="<?= $current_page == 'passwords.php' ? 'active' : '' ?>">Passwords</a></li>
                <li><a href="../view/drive.php" class="<?= $current_page == 'drive.php' ? 'active' : '' ?>">Your Backups</a></li>
            </ul>
        <?php else: ?>
            <ul>
                <li><a href="/passmark/index.php">Home</a></li>
                <li><a href="#feature">Features</a></li>
                <li><a href="#about">About</a></li>
            </ul>
        <?php endif; ?>
    </div>


    <?php if (isset($_SESSION["user_id"])): ?>
        <!-- User is signed in, show profile button -->
        <div class="user-profile" id="user-profile">
            <img src="../assets/user-profile.png" alt="user-profile">
        </div>

        <div class="profile-dropdown" id="profile-dropdown">
            <div>
                <div class="profile-verified">
                    <p class="user-name"><?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?></p>
                    <img src="../assets/verified-badge.png" alt="Verified Badge">
                </div>
                <p><?php echo $_SESSION["email"]; ?></p>
                <button onclick="window.location.href='/passmark/auth/logout.php'" class="logout headerBtn">Log Out</button>
            </div>
        </div>
    <?php else: ?>
        <div class="header-buttons">
            <!-- User is not signed in, show log in and sign up buttons -->
            <button onclick="window.location.href='/passmark/auth/login.php'" class="sign-in headerBtn">Log In</button>
            <button onclick="window.location.href='/passmark/auth/signup.php'" class="sign-up headerBtn">Sign Up</button>
        </div>
    <?php endif; ?>
</header>