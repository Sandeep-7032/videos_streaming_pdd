<?php
session_start();

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

// Get selected video names from URL
$selected_videos = isset($_GET['videos']) ? explode(',', $_GET['videos']) : [];
$videos = [];

if (!empty($selected_videos)) {
    // Fetch only the selected videos from the database
    $placeholders = implode(',', array_fill(0, count($selected_videos), '?'));
    $query = "SELECT video_title, video_url FROM videos WHERE video_title IN ($placeholders) AND category_name = 'Preschool'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat('s', count($selected_videos)), ...$selected_videos);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
    $stmt->close();
}

// Fetch latest screen time limit from settings
$screen_time_limit = 0;
$query = "SELECT screen_time_limit FROM settings ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);
if ($result && $row = $result->fetch_assoc()) {
    $screen_time_limit = $row['screen_time_limit'];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preschool Videos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://media.tenor.com/sQDz_xy06NMAAAAM/magic-stars.gif');
        }
        header {
            background-color: #ff9800;
            color: white;
            padding: 20px;
            text-align: center;
        }
        main {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .video-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .video-item {
            background-color: #ffecb3;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .video-item h3 {
            margin: 10px;
            text-align: center;
            font-size: 1.1em;
            color: #e65100;
        }
        .video-player {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-videos {
            text-align: center;
            font-size: 1.2em;
            color: #666;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #ff9800;
            color: white;
            margin-top: 30px;
        }
        #timer {
            font-size: 1.5em;
            color: #d32f2f;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Preschool Videos</h1>
    </header>
    <main>
        <div id="timer">
            Remaining Time: <span id="time-remaining"><?php echo $screen_time_limit * 60; ?></span> seconds
        </div>

        <?php if (!empty($videos)): ?>
            <h2>Videos</h2>
            <div class="video-list">
                <?php foreach ($videos as $video): ?>
                    <div class="video-item">
                        <h3><?php echo htmlspecialchars($video['video_title']); ?></h3>
                        <video controls class="video-player">
                            <source src="<?php echo 'uploads/' . basename($video['video_url']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-videos">No videos selected.</p>
        <?php endif; ?>
    </main>
    <footer>
        &copy; <?php echo date("Y"); ?> Kids World
    </footer>

    <script>
        const timeRemainingEl = document.getElementById('time-remaining');
        const videoPlayers = document.querySelectorAll('.video-player');
        let timeRemaining = parseInt(timeRemainingEl.textContent, 10);

        // Countdown Timer
        const countdown = setInterval(() => {
            if (timeRemaining > 0) {
                timeRemaining--;
                timeRemainingEl.textContent = timeRemaining;
            } else {
                clearInterval(countdown);
                stopVideos();
                alertRedirect();
            }
        }, 1000);

        // Stop all videos when time is up
        function stopVideos() {
            videoPlayers.forEach(video => {
                video.pause();
                video.currentTime = 0;
            });
        }

        // Alert and redirect to rating page
        function alertRedirect() {
            alert('Screen time limit reached!');
            window.location.href = 'rating1.php';
        }
    </script>
</body>
</html>
