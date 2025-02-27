<?php
// Include database connection
include '../db_connection.php';
session_start();

// Switch to 'kidsworld' database
$conn->select_db('kidsworld');

// Handle Accept and Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $conn->real_escape_string($_POST['id']);

    if ($action === 'accept') {
        // Fetch the video details from video_suggestions table
        $fetchSQL = "SELECT * FROM video_suggestions WHERE id = $id";
        $result = $conn->query($fetchSQL);
        
        if ($result && $row = $result->fetch_assoc()) {
            // Insert video details into 'videos' table using the correct column names
            // Note: Change the source field names if necessary, here we assume that
            // video_suggestions table has fields: video_title, video_url, category_id,
            // video_category_name (which will map to category_name in videos), and upload_date.
            $insertSQL = "INSERT INTO videos (video_title, video_url, category_id, category_name, upload_date)
                          VALUES (
                              '" . $conn->real_escape_string($row['video_title']) . "',
                              '" . $conn->real_escape_string($row['video_url']) . "',
                              '" . $conn->real_escape_string($row['category_id']) . "',
                              '" . $conn->real_escape_string($row['video_category_name']) . "',
                              '" . $conn->real_escape_string($row['upload_date']) . "'
                          )";

            if ($conn->query($insertSQL)) {
                // Delete the suggestion after successful insertion
                $deleteSQL = "DELETE FROM video_suggestions WHERE id = $id";
                $conn->query($deleteSQL);
            } else {
                echo "Error inserting video: " . $conn->error;
            }
        }
    } elseif ($action === 'reject') {
        // Delete video suggestion if rejected
        $deleteSQL = "DELETE FROM video_suggestions WHERE id = $id";
        $conn->query($deleteSQL);
    }
}

// Fetch all video suggestions
$sqlFetch = "SELECT * FROM video_suggestions ORDER BY id DESC";
$result = $conn->query($sqlFetch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Video Suggestions</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: url('../pdd images/suggestionimage .jpg') no-repeat center center fixed;
            background-size: cover; 
        }
        /* Header Styles */
        header {
            background-color: #4c51bf;
            color: white;
            padding: 15px 20px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        header h1 {
            margin: 0;
            flex-grow: 1;
            text-align: center;
            font-size: 24px;
        }
        header a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            background-color: #5a67d8;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        header a:hover {
            background-color: #434190;
        }
        /* Footer Styles */
        footer {
            background-color: #4c51bf;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 14px;
        }
        /* Content */
        .container {
            margin-top: 80px;
            margin-bottom: 50px;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #fff;
        }
        .video-container { 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            padding: 15px; 
            margin-bottom: 20px; 
            background-color: #fff; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        h3 { margin: 0; color: #007bff; }
        p { margin: 5px 0; color: #555; }
        video { display: block; margin: 10px auto; border-radius: 5px; }
        .buttons { margin-top: 10px; text-align: center; }
        .btn {
            display: inline-block; 
            padding: 8px 15px; 
            margin: 5px; 
            color: #fff; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        .accept { background-color: #28a745; }
        .reject { background-color: #dc3545; }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="admin.php">Home</a>
        <h1>Kids World</h1>
        <div></div> <!-- Empty div for layout -->
    </header>

    <!-- Main Content -->
    <div class="container">
        <h2>Review Video Suggestions</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="video-container">
                    <h3>Video Title: <?= htmlspecialchars($row['video_title'] ?? 'Untitled'); ?></h3>
                    <p><strong>Category ID:</strong> <?= htmlspecialchars($row['category_id'] ?? 'N/A'); ?></p>
                    <p><strong>Category Name:</strong> <?= htmlspecialchars($row['video_category_name'] ?? 'N/A'); ?></p>
                    <p><strong>Uploaded Date:</strong> <?= htmlspecialchars($row['upload_date'] ?? 'Unknown'); ?></p>
                    <p>
                        <strong>Video File:</strong>
                        <?php if (!empty($row['video_url'])): ?>
                            <a href="<?= htmlspecialchars($row['video_url']); ?>" target="_blank">Download</a>
                        <?php else: ?>
                            <span style="color: red;">No file available</span>
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($row['video_url']) && file_exists($row['video_url'])): ?>
                        <video width="600" controls>
                            <source src="<?= htmlspecialchars($row['video_url']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php else: ?>
                        <p style="color: red; text-align: center;">Video file not found or unavailable.</p>
                    <?php endif; ?>
                    <div class="buttons">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button type="submit" name="action" value="accept" class="btn accept">Accept</button>
                            <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: gray;">No video suggestions available.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        &copy; <?php echo date("Y"); ?> Kids World. All Rights Reserved.
    </footer>
</body>
</html>
