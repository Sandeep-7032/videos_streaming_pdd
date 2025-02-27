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
?>

<?php
// Include the database connection
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // No hashing here
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $childname = mysqli_real_escape_string($conn, $_POST['childname']);
    $age = (int)$_POST['age'];
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $usertype = 2; // Default to regular user

    // Check if the email already exists
    $email_check = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($email_check);

    if ($result->num_rows > 0) {
        echo "Email already exists. Please try again with a different email.";
    } else {
        // Insert the user data into the database, assuming usertype 2 (regular user)
        $sql = "INSERT INTO users (username, email, password, mobile, child_name, age, dob, usertype)
                VALUES ('$username', '$email', '$password', '$mobile', '$childname', $age, '$dob', '$usertype')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New record created successfully');window.location.href='../login.php'</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
}
?>
