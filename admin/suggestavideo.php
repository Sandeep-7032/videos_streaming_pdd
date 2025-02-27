<?php
include '../db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form inputs
    $category_id = $_POST['category-id'];
    $video_category_name = $_POST['video-category-name'];
    $video_title = $_POST['video-title'];
    $custom_category = !empty($video_category_name) ? $video_category_name : NULL;

    // Handle video upload
    if (isset($_FILES['video-url']) && $_FILES['video-url']['error'] == 0) {
        $target_dir = "../uploads/videos/"; // Directory to store uploaded videos
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if not exists
        }
        $video_file = $target_dir . basename($_FILES["video-url"]["name"]);

        // Validate video file type
        $allowed_types = ['video/mp4', 'video/avi', 'video/mkv'];
        if (in_array($_FILES['video-url']['type'], $allowed_types)) {
            // Move uploaded file to destination
            if (move_uploaded_file($_FILES["video-url"]["tmp_name"], $video_file)) {
                // Insert video details into the database
                $sql = "INSERT INTO video_suggestions (video_title, video_url, category_id, video_category_name, upload_date, custom_category)
                        VALUES (?, ?, ?, ?, NOW(), ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssiss", $video_title, $video_file, $category_id, $video_category_name, $custom_category);

                if ($stmt->execute()) {
                    $success_message = "Video uploaded and saved successfully!";
                } else {
                    $error_message = "Database error: " . $stmt->error;
                }
            } else {
                $error_message = "Error moving the uploaded file.";
            }
        } else {
            $error_message = "Invalid video format. Please upload mp4, avi, or mkv files.";
        }
    } else {
        $error_message = "No video file uploaded or an error occurred.";
    }
}

// Fetch categories for the dropdown
$sql = "SELECT id, category_name FROM categories GROUP BY category_name ORDER BY id ASC";
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Error fetching categories: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category and Video</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../pdd images/suggestionimage .jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Header styling */
        header {
            width: 100%;
            background-color: #4c51bf;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            flex: 1;
            text-align: center;
            font-size: 24px;
        }

        header a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            background-color: #5a67d8;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        header a:hover {
            background-color: #333;
        }

        form {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin-top: 80px; /* Offset for fixed header */
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #5a67d8;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #4c51bf;
        }

        .message {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            color: green;
        }

        .error {
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
            color: red;
        }

        footer {
            width: 100%;
            background-color: #4c51bf;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="../user/user_index.html">Home</a>
        <h1>Kids World</h1>
        <div></div>
    </header>

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data">
        <h2>Add Category and Video</h2>

        <!-- Success or Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="message"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Select existing category -->
        <label for="category-name">Category Name:</label>
        <select id="category-name" name="category-id" required>
            <option value="" disabled selected>Select a category</option>
            <?php
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
                }
            } else {
                echo "<option value='' disabled>Error fetching categories</option>";
            }
            ?>
        </select>

        <!-- Custom category name -->
        <label for="video-category-name">Video Category Name:</label>
        <input type="text" id="video-category-name" name="video-category-name" placeholder="Enter custom category name (optional)" />

        <!-- Video title -->
        <label for="video-title">Video Title:</label>
        <input type="text" id="video-title" name="video-title" required>

        <!-- Video upload -->
        <label for="video-url">Upload Video:</label>
        <input type="file" id="video-url" name="video-url" accept="video/*" required>

        <!-- Submit -->
        <button type="submit">Add Category and Video</button>
    </form>

    <!-- Footer -->
    <footer>
        &copy; 2024 Kids World. All Rights Reserved.
    </footer>
</body>
</html>
