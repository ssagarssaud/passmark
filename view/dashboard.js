const profileElement = document.getElementById("user-profile");
const dropdownElement = document.getElementById("profile-dropdown");

dropdownElement.classList.add("hidden");
profileElement.addEventListener("click", () => {
  console.log("profile is clicked");
  dropdownElement.classList.toggle("hidden");
});


// Function to update dashboard data dynamically
function updateDashboardData() {
    fetch("fetch_dashboard_data.php") // Call the backend API
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }

            // Update UI with dynamic counts
            document.getElementById("password-count").innerText = data.totalPasswords + " +";
            document.getElementById("bookmark-count").innerText = data.totalBookmarks + " +";
        })
        .catch(error => console.error("Fetch error:", error));
}

// Run update immediately and refresh every 10 seconds
updateDashboardData();
setInterval(updateDashboardData, 10000);







