<?php
$host = "localhost"; // Your DB host
$user = "root";      // Your DB username
$password = "";      // Your DB password
$dbname = "kidsworld"; // Your DB name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include the database connection
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if email exists in the users table
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, start the session
        session_start();
        $user = $result->fetch_assoc();

        // Check user type
        if ($user['usertype'] == 1) {
            // Admin login
            $_SESSION['admin'] = $user;
            echo "<script>alert('Admin login successful!'); window.location.href='admin/admin.php';</script>";
        } else {
            // Regular user login
            $_SESSION['user'] = $user;
            echo "<script>alert('User login successful!'); window.location.href='user/user_index.html';</script>";
        }
    } else {
        // Incorrect email or password
        echo "<script>alert('Invalid email or password. Please try again.'); window.location.href='../login.php';</script>";
    }

    // Close the connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('pdd images/loginimage.avif') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #4c51bf;
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .navbar a:hover {
            background-color: #5a67d8;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin-top: 60px;
        }
        .form-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #5a67d8;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .bottom-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .bottom-buttons button {
            width: 100%;
        }
        .countdown {
            margin-top: 15px;
            font-size: 18px;
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.html" onclick="goHome()">Go to Home</a>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="form-section">
            <h2>Login Form</h2>
            <form id="loginForm" action="" method="POST">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                
                <div class="bottom-buttons">
                    <button type="submit" id="loginBtn">Login</button>
                    <button type="button" onclick="redirectToSignup()">Click Here to Signup</button>
                </div>
            </form>
            <div class="countdown" id="countdown"></div>
        </div>
    </div>

    <script>
        // Function to handle redirection to signup page
        function redirectToSignup() {
            window.location.href = "signup.html"; // Redirects to signup.html
        }
    
        // Function to handle redirection to home page
        function goHome() { 
            window.location.href = "index1.html"; // Redirects to home page
        }
    </script>
</body>
</html>
