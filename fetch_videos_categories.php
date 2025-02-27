<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kidsworld";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if category_id is provided
if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    // Fetch video categories for the selected parent category
    $query = "SELECT id, video_category_name FROM videos WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $video_categories = [];
    while ($row = $result->fetch_assoc()) {
        $video_categories[] = $row;
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($video_categories);

    $stmt->close();
}

$conn->close();
?>
