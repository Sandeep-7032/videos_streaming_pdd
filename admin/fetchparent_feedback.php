<?php
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kidsworld';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from `video_ratings`
$sqlRatings = "SELECT id, video_title, video_rating, video_feedback, submitted_at FROM video_ratings";
$resultRatings = $conn->query($sqlRatings);

// Fetch data from `video_suggestions`
$sqlSuggestions = "SELECT id, new_title, new_description, new_category, uploaded_file, submitted_at FROM video_suggestions";
$resultSuggestions = $conn->query($sqlSuggestions);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback and Suggestions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Video Feedback</h1>
    <?php if ($resultRatings->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Video Title</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Submitted At</th>
            </tr>
            <?php while ($row = $resultRatings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['video_title']; ?></td>
                    <td><?php echo $row['video_rating']; ?></td>
                    <td><?php echo $row['video_feedback']; ?></td>
                    <td><?php echo $row['submitted_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No feedback found.</p>
    <?php endif; ?>

    <h1>Video Suggestions</h1>
    <?php if ($resultSuggestions->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Uploaded File</th>
                <th>Submitted At</th>
            </tr>
            <?php while ($row = $resultSuggestions->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['new_title']; ?></td>
                    <td><?php echo $row['new_description']; ?></td>
                    <td><?php echo $row['new_category']; ?></td>
                    <td><?php echo $row['uploaded_file'] ? $row['uploaded_file'] : 'N/A'; ?></td>
                    <td><?php echo $row['submitted_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No suggestions found.</p>
    <?php endif; ?>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
