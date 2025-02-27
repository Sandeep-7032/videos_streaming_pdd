<?php session_start(); // Start the session to store the correct answer

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

// Fetch categories
$categories_query = "SELECT id, category_name FROM categories WHERE category_name = 'Olders'";
$categories_result = $conn->query($categories_query);
$categories = [];
if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Initialize video category names array
$video_category_names = [];

// Fetch video_category_name based on selected category
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    // Fetch video_category_name based on selected category
    $video_category_query = "SELECT DISTINCT video_category_name FROM videos WHERE category_id = ?";
    $stmt = $conn->prepare($video_category_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $video_category_names[] = $row['video_category_name'];
    }
    $stmt->close();
}

// Redirect to preschoolvideos.php after form submission
if (isset($_POST['video_category_names'])) {
    $selected_video_categories = $_POST['video_category_names'];
    $selected_categories_string = implode(',', $selected_video_categories); // Combine selected categories into a string
    // Redirect to preschoolvideos.php with the selected categories passed in the URL
    header("Location: preschoolvideos.php?categories=" . urlencode($selected_categories_string));
    exit;
}

// Generate a multiplication question if not already set
if (!isset($_SESSION['random_num1']) || !isset($_SESSION['random_num2'])) {
    $_SESSION['random_num1'] = rand(1, 10);
    $_SESSION['random_num2'] = rand(1, 10);
    $_SESSION['correct_answer'] = $_SESSION['random_num1'] * $_SESSION['random_num2'];
}

$is_parent_verified = false;

// Check the parent's answer
if (isset($_POST['multiplication_answer'])) {
    $user_answer = intval($_POST['multiplication_answer']);
    if ($user_answer === $_SESSION['correct_answer']) {
        $is_parent_verified = true;
        unset($_SESSION['random_num1'], $_SESSION['random_num2'], $_SESSION['correct_answer']); // Clear session variables on success
    } else {
        $error_message = "Incorrect answer. Please try again.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Categories and Videos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('pdd images/signupimage.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        header {
            background-color: #4c51bf;
            color: white;
            padding: 20px;
            text-align: center;
        }
        main {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        ul, .checkbox-group {
            list-style: none;
            padding: 0;
        }
        li, .checkbox-group label {
            background-color: #5a67d8;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-right: 10px;
            text-transform: capitalize;
            margin-bottom: 10px;
        }
        button{
            background-color: #564f9d;
            padding: 10px 15px;
            color:white;
            font-weight: bold;
        }
        button:hover{
            background-color: white;
            color:#564f9d;
            font-weight: bold;
        }
        li a, .checkbox-group input[type="submit"] {
            color: white;
            background-color: #5a67d8;
            text-decoration: none;
            border: none;
            background: none;
            font: inherit;
            cursor: pointer;
        }
        .checkbox-group {
            padding: 10px 10px 10px 0;
            border-radius: 0;
            margin-bottom: 20px;
        }
        .checkbox-group input[type="submit"] {
            margin-top: 10px;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .show-btn {
            background-color: #5a67d8;
            padding: 10px 15px;
            color:white;
            font-weight: bold;
        }
        .show-btn:hover{
            background-color: white;
            color:#564f9d;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Kids World: Video Categories</h1>
    </header>
    <main>
        <!-- Categories -->
        <h2>Available Categories</h2>
        <?php if (!empty($categories)): ?>
            <ul>
                <?php foreach ($categories as $category): ?>
                    <li>
                        <a href="?category_id=<?php echo htmlspecialchars($category['id']); ?>">
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>

        <!-- Parental Controls -->
        <h2>Parental Controls</h2>
        <?php if (!$is_parent_verified): ?>
            <form method="POST">
                <p>Please solve the following question to access the content:</p>
                <p><strong><?php echo $_SESSION['random_num1'] . " x " . $_SESSION['random_num2'] . " = ?"; ?></strong></p>
                <input type="number" name="multiplication_answer" required>
                <button>Submit</button>
            </form>
            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Video Category Names -->
        <?php if ($is_parent_verified && !empty($video_category_names)): ?>
            <h2>Select Video Categories</h2>
            <form method="POST">
                <div class="checkbox-group">
                    <?php foreach ($video_category_names as $video_category_name): ?>
                        <label>
                            <input type="checkbox" name="video_category_names[]" value="<?php echo htmlspecialchars($video_category_name); ?>">
                            <?php echo htmlspecialchars($video_category_name); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <center><input type="submit" value="Show Videos" class="show-btn"></center>
            </form>
        <?php elseif (isset($_GET['category_id']) && $is_parent_verified): ?>
            <p>No video categories found for this category.</p>
        <?php endif; ?>
    </main>
</body>
</html>
