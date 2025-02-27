// Slideshow Logic
let currentSlide = 0;
const slides = document.querySelectorAll(".slide");

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle("active", i === index);
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
}

// Initial display of the first slide
showSlide(currentSlide);

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'flex';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none';
}

// Dropdown Menu Toggle
let isLoggedIn = false; // Change this variable to simulate login/logout status

function toggleMenu() {
    const dropdown = document.getElementById("dropdownMenu");
    if (dropdown) {
        // Toggle dropdown visibility
        dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Update the login/logout option based on isLoggedIn status
    const authOption = document.getElementById("authOption");
    if (authOption) {
        authOption.innerHTML = isLoggedIn
            ? '<a href="#" onclick="logout()">Logout</a>'
            : '<a href="login.html">Login</a>';
    }
}

// Show Settings Modal
function showSettings() {
    openModal('settingsModal');
}

// Logout Functionality
function logout() {
    isLoggedIn = false;
    alert("Logged out successfully!");
    toggleMenu();
}

// Event listener for the "Let's Get Started" button
const getStartedBtn = document.getElementById('getStartedBtn');
if (getStartedBtn) {
    getStartedBtn.addEventListener('click', function () {
        window.location.href = 'login.html'; // Redirect to the login/signup page
    });
}

// Optional: Close dropdown if clicking outside
document.addEventListener('click', (event) => {
    const dropdown = document.getElementById("dropdownMenu");
    const menuButton = document.querySelector('.menu-btn');

    if (!menuButton.contains(event.target) && dropdown && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none'; // Hide the dropdown if clicked outside
    }
});
