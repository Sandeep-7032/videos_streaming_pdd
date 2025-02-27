<?php
// Database connection
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

// Function to convert date to "time ago" format
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return $diff . " seconds ago";
    if ($diff < 3600) return floor($diff / 60) . " minutes ago";
    if ($diff < 86400) return floor($diff / 3600) . " hours ago";
    if ($diff < 2592000) return floor($diff / 86400) . " days ago";
    return date("M d, Y", $time);
}

// Handle video deletion if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video_id'])) {
    $video_id = intval($_POST['delete_video_id']);
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch videos data with category name (if applicable)
$videos = [];
$sql_videos = "SELECT v.id, v.video_title, COALESCE(c.category_name, 'Uncategorized') AS video_category_name, v.upload_date 
               FROM videos v 
               LEFT JOIN categories c ON v.category_id = c.id";
$result_videos = $conn->query($sql_videos);

if ($result_videos->num_rows > 0) {
    while ($row = $result_videos->fetch_assoc()) {
        $row['time_ago'] = timeAgo($row['upload_date']);
        $videos[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids World - Watch History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4c51bf;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.5em;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
        }
        .video-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .delete-button:hover {
            background-color: darkred;
        }
        footer {
            background-color: #4c51bf;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>Kids World - Watch History</header>
    <div class="container">
        <h2>Your Video History</h2>
        <?php if (!empty($videos)): ?>
            <?php foreach ($videos as $video): ?>
                <div class="video-item">
                    <div>
                        <strong><?php echo htmlspecialchars($video['video_title']); ?></strong><br>
                        Category: <?php echo htmlspecialchars($video['video_category_name']); ?><br>
                        <small><?php echo htmlspecialchars($video['time_ago']); ?></small>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="delete_video_id" value="<?php echo $video['id']; ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No videos found in your watch history.</p>
        <?php endif; ?>
    </div>
    <footer>&copy; <?php echo date("Y"); ?> Kids World | All Rights Reserved</footer>
</body>
</html>
