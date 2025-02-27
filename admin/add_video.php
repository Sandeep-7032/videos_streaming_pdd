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
            background: url('../pdd images/suggestionimage1.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            background-color: #4c51bf;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            text-align: center;
            flex: 1;
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

        footer {
            width: 100%;
            background-color: #4c51bf;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <a href="admin.php">Home</a>
        <h1>Kids World</h1>
    </header>

    <form method="POST" action="../backend/add_videos.php" enctype="multipart/form-data">
        <h2>Add Category and Video</h2>
        
        <label for="category-name">Category Name:</label>
        <select id="category-name" name="category-id" required>
            <option value="" disabled selected>Select a category</option>
            <?php
            include '../db_connection.php'; // Include DB connection
            $sql = "SELECT id, category_name FROM categories GROUP BY category_name ORDER BY id ASC";
            $result = $conn->query($sql);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
                }
            } else {
                echo "<option value='' disabled>Error fetching categories</option>";
            }
            ?>
        </select>

        <label for="video-category-name">Video Category Name:</label>
        <input type="text" id="video-category-name" name="video-category-name" placeholder="Enter custom category name (optional)" />

        <label for="video-title">Video Title:</label>
        <input type="text" id="video-title" name="video-title" required>

        <label for="video-url">Upload Video:</label>
        <input type="file" id="video-url" name="video-url" accept="video/*" required>

        <button type="submit">Add Category and Video</button>
    </form>

    <footer>
        &copy; 2024 Kids World. All Rights Reserved.
    </footer>
</body>
</html>
