<?php
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'kidsworld';

// Create a database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['formType'] ?? '';

    if ($formType === 'rateVideo') {
        // Collect form data
        $videoTitle = $_POST['video-title'] ?? '';
        $videoRating = $_POST['video-rating'] ?? '';
        $videoFeedback = $_POST['video-feedback'] ?? '';

        // Validate inputs
        if (!empty($videoTitle) && !empty($videoRating) && !empty($videoFeedback)) {
            // Prepare SQL statement to avoid SQL injection
            $stmt = $conn->prepare("INSERT INTO video_ratings (video_title, video_rating, video_feedback) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $videoTitle, $videoRating, $videoFeedback);

            // Execute and check success
            if ($stmt->execute()) {
                $message = "Your rating for '$videoTitle' was submitted successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Please fill out all fields.";
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Feedback Form</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('image.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            color: #333;
        }
        header {
            background-color: #4c51bf;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 2em;
        }
        .feedback-container {
            background-color: white;
            margin: 30px auto;
            padding: 20px;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        input, select, textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-submit {
            background-color: #4c51bf;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #3b3f9e;
        }
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        Kids World - Rate a Video
    </header>

    <!-- Feedback Form -->
    <div class="feedback-container">
        <h2>Rate an Existing Video</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="formType" value="rateVideo">

            <label for="video-title">Video Title:</label>
            <input type="text" id="video-title" name="video-title" placeholder="Enter the video title" required>

            <label for="video-rating">Your Rating:</label>
            <select id="video-rating" name="video-rating" required>
                <option value="" disabled selected>Select a rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>

            <label for="video-feedback">Additional Feedback:</label>
            <textarea id="video-feedback" name="video-feedback" rows="4" placeholder="Share your thoughts" required></textarea>

            <button type="submit" class="btn-submit">Submit Rating</button>
        </form>
    </div>
</body>
</html>
