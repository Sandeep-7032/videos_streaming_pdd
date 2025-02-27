<?php
// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'kidsworld'; // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch video ratings and feedback
$ratings = [];
$sql = "SELECT * FROM video_ratings"; // Fetch all video ratings
$result = $conn->query($sql);
if ($result) {
    $ratings = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submissions for ratings management
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle deleting ratings
    if (isset($_POST['delete_rating'])) {
        $rating_id = $_POST['rating_id'];

        // Delete the rating from the database
        $stmt = $conn->prepare("DELETE FROM video_ratings WHERE id = ?");
        $stmt->bind_param("i", $rating_id);
        $stmt->execute();
        $stmt->close();

        // Reload the page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Handle updating ratings
    if (isset($_POST['update_rating'])) {
        $rating_id = $_POST['rating_id'];
        $new_rating = $_POST['new_rating'];

        // Update the rating in the database
        $stmt = $conn->prepare("UPDATE video_ratings SET video_rating = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_rating, $rating_id);
        $stmt->execute();
        $stmt->close();

        // Reload the page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // Handle updating feedback
    if (isset($_POST['update_feedback'])) {
        $rating_id = $_POST['rating_id'];
        $new_feedback = $_POST['new_feedback'];

        // Update the feedback in the database
        $stmt = $conn->prepare("UPDATE video_ratings SET video_feedback = ? WHERE id = ?");
        $stmt->bind_param("si", $new_feedback, $rating_id);
        $stmt->execute();
        $stmt->close();

        // Reload the page to reflect changes
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kids Video Admin Dashboard</title>
  <style>
    /* General Styles */
    body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('../pdd images/adminimage1.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    .admin-container {
      display: flex;
      flex: 1;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #4c51bf;
      color: white;
      display: flex;
      flex-direction: column;
      padding: 20px;
      height: 100vh;
      position: sticky;
      top: 0;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar button {
      background: none;
      border: none;
      color: white;
      text-align: left;
      padding: 10px 15px;
      font-size: 16px;
      cursor: pointer;
      margin-bottom: 5px;
    }

    .sidebar button:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    /* Main Content */
    .main-content {
      background: url('pdd images/signupimage.jpg') no-repeat center center fixed;
  background-size: cover;
      flex: 1;
      padding: 20px;
      margin-left: 250px; /* Space for the sidebar */
      
    }

    .card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
      display: none;
    }

    .visible-section {
      display: block;
    }

    .btn {
      background-color: #4CAF50;
      border: none;
      color: white;
      border-radius: 4px;
      cursor: pointer;
      padding: 10px 20px;
      margin-right: 10px;
    }

    .btn:hover {
      background-color: #45a049;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #ff9933;
      color: white;
    }

    /* Footer */
    footer {
      background-color: #5a67d8;
      color: white;
      padding: 20px;
      text-align: center;
      position: absolute;
      width: 100%;
      bottom: 0;
    }

    footer a {
      color: #ff9933;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Admin Menu</h2>
      <button onclick="showSection('dashboard')">Dashboard</button>
      <button onclick="showSection('manage-ratings')">Manage Ratings</button>
      <button onclick="window.location.href='./review_videos.php'">suggested video</button>
      <button onclick="window.location.href='add_video.php'">Add Videos</button>
      <button onclick="window.location.href='../adminhistory.php'">History</button>
      <button onclick="window.location.href='add_category.html'">Add Category</button>
      <button onclick="window.location.href='../login.php'">Logout</button>
    </div>

 <!-- Main Content -->
<div class="main-content" id="mainContent" style="background: url('pdd images/signupimage.jpg') no-repeat center center fixed; background-size: cover;">
  <div id="dashboard" class="card visible-section">
    <h2>Dashboard</h2>
    <p>Welcome to the admin panel! Use the sidebar to manage content.</p>
  </div>
</div>




      <div id="manage-ratings" class="card">
        <h2>Manage Ratings</h2>
        <table>
          <thead>
            <tr>
              <th>Video Title</th>
              <th>Rating</th>
              <th>Feedback</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ratings as $rating): ?>
              <tr>
                <td><?= htmlspecialchars($rating['video_title']) ?></td>
                <td>
                  <form method="POST" style="display: inline;">
                    <input type="hidden" name="rating_id" value="<?= $rating['id'] ?>">
                    <input type="number" name="new_rating" value="<?= $rating['video_rating'] ?>" min="1" max="5" required>
                    <button type="submit" name="update_rating" class="btn">Update</button>
                  </form>
                </td>
                <td>
                  <form method="POST" style="display: inline;">
                    <input type="hidden" name="rating_id" value="<?= $rating['id'] ?>">
                    <input type="text" name="new_feedback" value="<?= htmlspecialchars($rating['video_feedback'] ?? '') ?>" required>
                    <button type="submit" name="update_feedback" class="btn">Update</button>
                  </form>
                </td>
                <td>
                  <form method="POST" style="display: inline;">
                    <input type="hidden" name="rating_id" value="<?= $rating['id'] ?>">
                    <button type="submit" name="delete_rating" class="btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div id="history" class="card">
        <h2>History</h2>
        <p>Track the history of video ratings and actions.</p>
      </div>

      <div id="add-category" class="card">
        <h2>Add Category</h2>
        <p>Feature to add new categories will be implemented here.</p>
      </div>
    </div>
  </div>

  <footer>
    <p>&copy; 2024 Kids World. All Rights Reserved.</p>
  </footer>

  <script>
    function showSection(sectionId) {
      const sections = document.querySelectorAll('.card');
      sections.forEach(section => section.classList.remove('visible-section'));
      document.getElementById(sectionId).classList.add('visible-section');
    }
  </script>
</body>
</html>
