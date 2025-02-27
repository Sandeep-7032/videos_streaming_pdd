<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "kidsworld";

$conn = new mysqli($host, $user, $password, $dbname);

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

// Handle video deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_video_id'])) {
    $video_id = intval($_POST['delete_video_id']);
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch videos data
$videos = [];
$sql_videos = "SELECT v.id, v.video_title, v.upload_date, c.category_name 
              FROM videos v 
              LEFT JOIN categories c ON v.category_id = c.id";
$result_videos = $conn->query($sql_videos);

if ($result_videos->num_rows > 0) {
    while ($row = $result_videos->fetch_assoc()) {
        $row['time_ago'] = timeAgo($row['upload_date']);
        $videos[] = $row;
    }
}

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
            font-family: "Comic Sans MS", cursive, sans-serif;
            background: url('./pdd images/history.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4c51bf;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .history-container {
            max-width: 900px;
            margin: 100px auto 20px;
            background-color: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
            animation: slideIn 1s ease-in-out;
        }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .video-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 2px dotted #ffdab9;
        }
        .delete-button {
            background-color: #5a67d8;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button:hover { background-color: #ffa07a; }
    </style>
</head>
<body>
    <header>
        <a href="../pdd/admin/admin.php" class="home-btn">Home</a>
        <h1>Kids World</h1>
    </header>

    <div class="history-container">
        <h2>Video Watch History</h2>

        <?php if (!empty($videos)): ?>
            <?php foreach ($videos as $video): ?>
                <div class="video-item">
                    <div>
                        <p class="video-title"><?php echo htmlspecialchars($video['video_title']); ?></p>
                        <p>Category: <?php echo htmlspecialchars($video['category_name'] ?? 'Unknown'); ?></p>
                    </div>
                    <div>
                        <p><?php echo htmlspecialchars($video['time_ago']); ?></p>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_video_id" value="<?php echo $video['id']; ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No videos found in your watch history.</p>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Kids World | All Rights Reserved
    </footer>
</body>
</html>
