<?php
include '../db_connection.php'; // Include your database connection

// Query to fetch age group details
$sql = "SELECT id, category_name, category_description FROM categories ORDER BY id ASC";
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Error fetching age groups: " . $conn->error);
}

// Fetch data into an array
$ageGroups = [];
while ($row = $result->fetch_assoc()) {
    $ageGroups[] = $row;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Age Group Layout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    
    /* Resetting styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
        display: flex;
    
        align-items: center;
        flex-direction: column;
        min-height: 100vh;
    }
    
    /* Header Styling */
    header {
        background-color: #4c51bf;
        color: white;
        padding: 15px 0;
        text-align: center;
        width: 100%;
        margin-top: 0;
    }

    header h1 {
        margin: 0;
        font-size: 2rem;
    }

    /* Navigation Menu */
    nav ul {
        list-style: none;
        display: flex;
        justify-content: center;
        padding: 0;
    }

    nav ul li {
        margin: 0 15px;
    } 

    nav ul li a {
        color: white;
        text-decoration: none;
        font-size: 1rem;
    }

    nav ul li a:hover {
        color: #ffbb33;
    }

    /* Container styling */
    .container {
        display: flex;
        gap: 30px;
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    /* Styling for each age group box */
    .age-group {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        padding: 30px;
        border-radius: 10px;
        color: #ffffff;
        text-align: justify;
        text-decoration: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #333 !important; /* Default background color for contrast */
    }
    
    /* Text alignment */
    a.age-group h2 {
        font-size: 1.6rem;
        margin-bottom: 10px;
    }
    
    a.age-group p {
        font-size: 1.1rem;
        line-height: 1.4;
        max-width: 100%;
    }

    /* Vibrant background colors for each age group */
    .preschool {
        background-color: #0096FF; /* Bright blue */
    }
    
    .youngers {
        background-color: #FF8000; /* Bright orange */
    }
    
    .olders {
        background-color: #5A189A; /* Rich purple */
    }
    h2{
        text-transform: capitalize;
        font-size: 24px;
    }
    .age-group p{
        height: 290px;
    }
    .age-group a{
        background: #f58803;
        text-decoration: none;
        color: #fff;
        padding: 7px 10px;
        border-radius: 5px;
        margin-top: 20px;
        border: 1px solid #f58803;
    }
    .age-group a:hover{
        border: 1px solid #fa6600;;
        color: #f58803;
        background: #333;
    }
    /* Footer Styling */
    footer {
        background-color: #4c51bf;
        color: white;
        text-align: center;
        padding: 10px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }
    }
    
    @media (min-width: 768px) and (max-width: 1024px) {
        a.age-group {
            width: 100%;
        }
    }

    @media (min-width: 1024px) {
        a.age-group {
            width: 30%;
        }
    }
  </style>
</head>
<body>

  <!-- Header Section -->
  <header>
    <h1>Kids Learning World</h1>
    <nav>
      <ul>
        <li><a href="user_index.html">About</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main Content Section -->
  <div class="container my-5">
    <?php foreach ($ageGroups as $ageGroup): ?>
      <?php
        // Determine the link based on the category name
        $categoryName = strtolower(str_replace(' ', '', $ageGroup['category_name']));
        if ($categoryName == 'preschool') {
            $link = '../preschool.html';
        } elseif ($categoryName == 'youngers') {
            $link = '../youngers.html';
        } elseif ($categoryName == 'olders') {
            $link = '../olders.html';
        } else {
            $link = 'category_page.php?category=' . intval($ageGroup['id']);
        }
      ?>
      <div class="age-group <?php echo $categoryName; ?>">
        <h2><?php echo htmlspecialchars($ageGroup['category_name']); ?></h2>
        <p><?php echo htmlspecialchars($ageGroup['category_description']); ?></p>
        <a href="<?php echo $link; ?>"><strong>Click to explore more</strong></a> <!-- Added instruction to click -->
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Footer Section -->
  <footer>
    <p>&copy; 2024 Kids Learning World. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
