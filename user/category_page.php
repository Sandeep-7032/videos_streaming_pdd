<?php
include '../db_connection.php'; // Include your database connection

// Get the category ID from the URL
$categoryId = isset($_GET['category']) ? intval($_GET['category']) : null;

if ($categoryId) {
    // Fetch the videos for the specified category ID
    $stmt = $conn->prepare("SELECT video_title, video_url FROM videos WHERE category_id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any videos are found
    if ($result->num_rows > 0) {
        $videos = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $videos = [];
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Category ID not specified!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .video-container {
            margin-bottom: 20px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .video-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }
        video {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($videos)): ?>
            <h1 class="my-4">Videos for Category ID: <?php echo htmlspecialchars($categoryId); ?></h1>
            <?php foreach ($videos as $video): ?>
                <div class="video-container">
                    <div class="video-title"><?php echo htmlspecialchars($video['video_title']); ?></div>
                    <video controls>
                        <source src="<?php echo htmlspecialchars($video['video_url']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h1 class="my-4">No Videos Found</h1>
            <p>There are no videos available for this category. Please check back later!</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
