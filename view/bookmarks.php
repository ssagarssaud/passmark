<?php
include("../includes/header.php");
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM bookmarks WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>BookMarks</title>
</head>

<body>
    <div class="container dashboard-container">
        <button id="add-bookmark-btn" class="add-button">Add Bookmark</button>
        <div class="bookmark-list">
            <?php if ($bookmarks): ?>
                <?php foreach ($bookmarks as $bookmark): ?>
                    <div class="bookmark" data-id="<?php echo $bookmark['id']; ?>">
                        <img src="<?php echo htmlspecialchars($bookmark['website_photo']); ?>" alt="logo">

                        <h1 class="div"><?php echo htmlspecialchars($bookmark['website_name']); ?></h1>

                        <div class="link-div">
                            <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank">
                                <p><?php echo htmlspecialchars($bookmark['url']); ?></p>
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>

                        <i class="fa-regular fa-trash-can"></i>
                        <i class="fa-solid fa-pen-to-square edit-icon" data-id="<?php echo $bookmark['id']; ?>"
                            data-name="<?php echo htmlspecialchars($bookmark['website_name']); ?>"
                            data-url="<?php echo htmlspecialchars($bookmark['url']); ?>"
                            data-photo="<?php echo htmlspecialchars($bookmark['website_photo']); ?>"></i>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No bookmarks found.</p>
            <?php endif; ?>
        </div>

        <!-- Hidden Form For Adding Bookmark -->
        <form class="bookmark-form hidden" id="bookmarkForm" enctype="multipart/form-data">
            <i id="cross-icon-add-book" class="fa-solid fa-x"></i>
            <h1>Save Your Bookmarks</h1>
            <div class="input-group">
                <i class="fa-solid fa-link"></i>
                <input type="url" name="url" id="url" placeholder="Enter url" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-globe"></i>
                <input type="text" name="website_name" id="website_name" placeholder="Enter website name" required>
            </div>

            <div class="input-group">
                <input type="file" name="website_photo" id="website_photo" accept="image/*" required>
            </div>

            <button id="save-bookmark-btn" class="login-btn" type="submit">Save Bookmark</button>
        </form>
        <p id="responseMessage"></p>

        <!-- Hidden Form For Updating Bookmark -->
        <form class="bookmark-form hidden" id="editBookmarkForm" enctype="multipart/form-data">
            <i id="cross-icon-update-book" class="fa-solid fa-x"></i>
            <h1>Edit Bookmark</h1>
            <input type="hidden" name="bookmark_id" id="edit_bookmark_id">
            <div class="input-group">
                <i class="fa-solid fa-link"></i>
                <input type="url" name="url" id="edit_url" placeholder="Enter url" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-globe"></i>
                <input type="text" name="website_name" id="edit_website_name" placeholder="Enter website name" required>
            </div>
            <div class="input-group">
                <input type="file" name="website_photo" id="edit_website_photo" accept="image/*">
            </div>

            <button id="update-bookmark-btn" class="login-btn" type="submit">Update Bookmark</button>
        </form>

        <p id="responseMessage"></p>
    </div>

    <script>
        document.getElementById("bookmarkForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("add_bookmark.php", {
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

                        // Dynamically add the new bookmark to the list instead of reloading
                        let newBookmark = document.createElement("div");
                        newBookmark.classList.add("bookmark");
                        newBookmark.setAttribute("data-id", data.bookmark.id);
                        newBookmark.innerHTML = `
                <img src="${data.bookmark.website_photo}" alt="logo">
                <h1 class="div">${data.bookmark.website_name}</h1>
                <div class="link-div">
                    <a href="${data.bookmark.url}" target="_blank">
                        <p>${data.bookmark.url}</p>
                        <i class="fa-solid fa-link"></i>
                    </a>
                </div>
                <i class="fa-regular fa-trash-can"></i>
                <i class="fa-solid fa-pen-to-square edit-icon"
                    data-id="${data.bookmark.id}"
                    data-name="${data.bookmark.website_name}"
                    data-url="${data.bookmark.url}"
                    data-photo="${data.bookmark.website_photo}"></i>
            `;

                        document.querySelector(".bookmark-list").prepend(newBookmark);
                        document.getElementById("bookmarkForm").reset();
                        document.getElementById("bookmarkForm").classList.add("hidden");
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
        // Handle Add Button
        const addFormEl = document.getElementById("bookmarkForm")
        document.getElementById("add-bookmark-btn").addEventListener("click", () => {
            addFormEl.classList.toggle("hidden")
        })
        // Handle Edit Button Click
        document.querySelectorAll(".edit-icon").forEach(icon => {
            icon.addEventListener("click", function() {
                document.getElementById("editBookmarkForm").classList.toggle("hidden");
                document.getElementById("edit_bookmark_id").value = this.dataset.id;
                document.getElementById("edit_url").value = this.dataset.url;
                document.getElementById("edit_website_name").value = this.dataset.name;

            });
        });

        // Handle Update Form Submission
        document.getElementById("editBookmarkForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("update_bookmark.php", {
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

                        // Update the existing bookmark in the UI
                        let updatedBookmark = document.querySelector(`.bookmark[data-id="${data.bookmark.id}"]`);
                        updatedBookmark.querySelector("h1.div").textContent = data.bookmark.website_name;
                        updatedBookmark.querySelector(".link-div a").href = data.bookmark.url;
                        updatedBookmark.querySelector(".link-div p").textContent = data.bookmark.url;
                        updatedBookmark.querySelector("img").src = data.bookmark.website_photo;

                        document.getElementById("editBookmarkForm").classList.add("hidden");
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

        // DELETE OPERATION
        document.querySelectorAll(".fa-trash-can").forEach(icon => {
            icon.addEventListener("click", function() {
                const bookmarkElement = this.closest(".bookmark");
                const bookmarkId = bookmarkElement.dataset.id;

                if (confirm("Are you sure you want to delete this bookmark?")) {
                    fetch("delete_bookmark.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: `id=${bookmarkId}`
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

                                // Remove the bookmark from the UI
                                bookmarkElement.remove();
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


        document.getElementById("cross-icon-add-book").addEventListener("click", () => {
            document.getElementById("bookmarkForm").classList.add("hidden")
        })
        document.getElementById("cross-icon-update-book").addEventListener("click", () => {
            document.getElementById("editBookmarkForm").classList.add("hidden")
        })
    </script>
    <script src="./dashboard.js"></script>
</body>

</html>
<?php include("../includes/footer.html"); ?>