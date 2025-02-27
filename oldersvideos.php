<?php
session_start();

// Database connection using PDO
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'kidsworld';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch selected videos from URL parameters
$selected_videos = isset($_GET['videos']) ? explode(',', $_GET['videos']) : [];
$videos = [];

if (!empty($selected_videos)) {
    // Prepare query with placeholders
    $placeholders = implode(',', array_fill(0, count($selected_videos), '?'));
    $query = "SELECT video_title, video_url FROM videos WHERE video_title IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($selected_videos);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch latest screen time limit from settings
$query = "SELECT screen_time_limit FROM settings ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($query);
$screen_time_limit = $stmt->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Older Videos</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://media.tenor.com/sQDz_xy06NMAAAAM/magic-stars.gif');
            color: white;
        }
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
        }
        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            color: black;
        }
        .video-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .video-item {
            background-color: #e0f7fa;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 10px;
            text-align: center;
            height: 350px;
        }
        .video-item h3 {
            font-size: 1.1em;
            color: #00796b;
        }
        .video-player {
            width: 100%;
            height: 250px;
            border-radius: 5px;
            object-fit: cover;
        }
        .no-videos {
            text-align: center;
            font-size: 1.2em;
            color: #666;
        }
        #timer {
            font-size: 1.5em;
            color: #d32f2f;
            text-align: center;
            margin-bottom: 20px;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Older Videos</h1>
    </header>
    <main>
        <div id="timer">
            Remaining Time: <span id="time-remaining"><?php echo $screen_time_limit * 60; ?></span> seconds
        </div>

        <?php if (!empty($videos)): ?>
            <h2>Selected Videos</h2>
            <div class="video-list">
                <?php foreach ($videos as $video): ?>
                    <div class="video-item">
                        <h3><?php echo htmlspecialchars($video['video_title']); ?></h3>
                        <video controls class="video-player">
                            <source src="uploads/<?php echo basename(htmlspecialchars($video['video_url'])); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-videos">No videos available. Please select videos first.</p>
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

        // Alert and redirect to login page
        function alertRedirect() {
            alert('Screen time limit reached!');
            window.location.href = 'rating1.php';
        }
    </script>
</body>
</html>
