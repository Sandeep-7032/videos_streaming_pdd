<?php
include '../db_connection.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $categoryId = $_POST['category-id']; // Get selected category ID
    $videoTitle = $_POST['video-title'];
    $videoCategoryName = $_POST['video-category-name']; // Custom category name (not needed in this case)

    // Handle video file upload
    if (isset($_FILES['video-url']) && $_FILES['video-url']['error'] == 0) {
        $videoFileName = $_FILES['video-url']['name'];
        $videoTmpName = $_FILES['video-url']['tmp_name'];
        $videoDestination = '../uploads/' . $videoFileName; // Destination directory

        // Move uploaded video to the server
        if (move_uploaded_file($videoTmpName, $videoDestination)) {
            // Fetch category name based on selected category ID
            $categoryQuery = "SELECT category_name FROM categories WHERE id = ?";
            $stmtCategory = $conn->prepare($categoryQuery);
            $stmtCategory->bind_param("i", $categoryId);
            $stmtCategory->execute();
            $stmtCategory->bind_result($categoryName);
            $stmtCategory->fetch();
            $stmtCategory->close();

            if ($categoryName) {
                // Insert video with category ID and category name into the videos table
                $sql = "INSERT INTO videos (video_title, video_url, category_id, category_name) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssis", $videoTitle, $videoDestination, $categoryId, $categoryName);

                // Execute query and check success
                if ($stmt->execute()) {
                    echo "Video added successfully!";
                } else {
                    echo "Error adding video: " . $stmt->error;
                }
            } else {
                echo "Category not found.";
            }
        } else {
            echo "Error uploading video file.";
        }
    } else {
        echo "No video file uploaded or an error occurred during upload.";
    }
}
?>
