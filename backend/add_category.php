<?php
// Database connection
$host = "localhost";
$dbname = "kidsworld";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $categoryName = $_POST['category-name'];
        $categoryDescription = $_POST['category-description'];

        // Insert category into database
        $sql = "INSERT INTO categories (category_name, category_description) VALUES (:name, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $categoryName, 'description' => $categoryDescription]);

        echo "<script>alert('Category added successfully!'); window.location.href='../admin/add_category.html';</script>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
// Fetch categories from the database
$sql = "SELECT id, category_name FROM categories";
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Error fetching categories: " . $conn->error);
}
?>
