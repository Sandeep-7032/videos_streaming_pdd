<?php
include 'db_connection.php';
session_start();

// Fetch the screen time limit from the database
$sql = "SELECT screen_time_limit FROM settings WHERE id = 1"; // Adjust if needed based on your settings table
$result = $conn->query($sql);
$screenTimeLimit = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $screenTimeLimit = $row['screen_time_limit'];
}

// Check if the user has exceeded the screen time
if (isset($_SESSION['limit_reached_time'])) {
    $current_time = time();
    $limit_reached_time = $_SESSION['limit_reached_time'];

    // If the user has exceeded their screen time, check the 3-minute block
    if ($current_time - $limit_reached_time < $screenTimeLimit) {
        // Calculate the remaining time before login can be attempted again
        $_SESSION['remaining_time'] = $screenTimeLimit - ($current_time - $limit_reached_time);
        header("Location: ../login.html");
        exit();
    } else {
        // Remove the limit reached time if 3 minutes are over
        unset($_SESSION['limit_reached_time']);
        unset($_SESSION['remaining_time']);
    }
}

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check credentials in the database
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION["logged_in"] = true;
        $userInfo = $result->fetch_assoc();
        $_SESSION["id"] = $userInfo["id"];
        $_SESSION["username"] = $userInfo["username"];
        $usertype = $userInfo["usertype"];

        // Redirect based on user type
        if ($usertype == "1") {
            header("Location: ../admin/admin.php");
            exit();
        } elseif ($usertype == "2") {
            header("Location: ../user/user_index.html");
            exit();
        }
    } else {
        // Invalid credentials, show error message
        echo "<script>alert('Invalid Username or Password');window.location.href='../login.html';</script>";
    }
}

$conn->close();
?>
