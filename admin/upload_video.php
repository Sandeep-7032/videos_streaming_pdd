<?php
include '../db_connection.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $categoryId = $_POST['category-id'];
    $customCategory = $_POST['video-category-name'] ?? null;
    $videoTitle = $_POST['video-title'];
    
    // Video file handling
    $videoFile = $_FILES['video-url'];
    $uploadDir = '../uploads/';
    $filePath = $uploadDir . basename($videoFile['name']);
    
    // Move the uploaded file to the correct directory
    if (move_uploaded_file($videoFile['tmp_name'], $filePath)) {
        // Insert video details into the database
        $sql = "INSERT INTO videos (category_id, video_category_name, video_title, file_path, reviewed, custom_category, upload_date) 
                VALUES (?, ?, ?, ?, 0, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $categoryId, $videoTitle, $filePath, $customCategory);
        
        if ($stmt->execute()) {
            echo 'Video successfully uploaded and stored for review!';
        } else {
            echo 'Failed to insert video details into database.';
        }
    } else {
        echo 'Failed to upload video file.';
    }
}
?>
