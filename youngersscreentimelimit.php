<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parental Controls</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('pdd images/siginupimage.avif');
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .header {
            background-color: #4c51bf;
            color: #ffffff;
            padding: 10px;
            position: fixed;
            top: 0;
            width: 100%;
            text-align: center;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            margin: 0;
        }

        .header .home-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #5a67d8;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .header .home-btn:hover {
            background-color: #5a67d8;
        }

        .footer {
            background-color: #4c51bf;
            color: #ffffff;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }

        .container {
            margin-top: 70px;
            text-align: center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #multiplication-form,
        #screen-time-form {
            display: none;
        }

        label {
            font-weight: bold;
        }

        input[type="number"] {
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }

        .message {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header class="header">
        <a href="user/selection.php" class="home-btn">Home</a>
        <h1>Kids World Parental Controls</h1>
    </header>

    <div class="container">
        <h1>Parental Controls</h1>
        <button id="parental-controls-btn">Access Parental Controls</button>

        <!-- Multiplication Question Form -->
        <div id="multiplication-form">
            <p>Please answer this multiplication question to proceed:</p>
            <label id="question"></label>
            <input type="number" id="answer" placeholder="Your answer">
            <button id="check-answer-btn">Submit Answer</button>
            <p class="error" id="error-msg"></p>
        </div>

        <!-- Screen Time Limit Form -->
        <div id="screen-time-form">
            <p>Set Screen Time Limit (in minutes):</p>
            <form id="screen-time-form-el" method="POST">
                <input type="number" name="screen_time_limit" id="screen_time_limit" placeholder="Enter time (e.g., 30)" required>
                <button type="submit">Set Screen Time</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Kids World. All Rights Reserved.</p>
    </footer>

    <script>
        const parentalControlsBtn = document.getElementById("parental-controls-btn");
        const multiplicationForm = document.getElementById("multiplication-form");
        const questionLabel = document.getElementById("question");
        const answerInput = document.getElementById("answer");
        const checkAnswerBtn = document.getElementById("check-answer-btn");
        const errorMsg = document.getElementById("error-msg");
        const screenTimeForm = document.getElementById("screen-time-form");

        let correctAnswer;

        // Generate a random multiplication question
        function generateQuestion() {
            const num1 = Math.floor(Math.random() * 10) + 1;
            const num2 = Math.floor(Math.random() * 10) + 1;
            correctAnswer = num1 * num2;
            questionLabel.textContent = `${num1} x ${num2} = ?`;
        }

        // Handle the Parental Controls button click
        parentalControlsBtn.addEventListener("click", () => {
            generateQuestion();
            multiplicationForm.style.display = "block";
            parentalControlsBtn.style.display = "none";
        });

        // Handle answer submission
        checkAnswerBtn.addEventListener("click", () => {
            const userAnswer = parseInt(answerInput.value, 10);
            if (userAnswer === correctAnswer) {
                errorMsg.textContent = "";
                multiplicationForm.style.display = "none";
                screenTimeForm.style.display = "block";
            } else {
                errorMsg.textContent = "Incorrect answer. Try again!";
            }
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $screenTimeLimit = intval($_POST['screen_time_limit']);

        // Database connection
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password
        $dbname = "kidsworld";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert screen time limit into the database
        $sql = "INSERT INTO settings (screen_time_limit) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $screenTimeLimit);

        if ($stmt->execute()) {
            echo "<script>alert('Screen time limit set successfully!'); window.location.href = 'youngerscontent.php';</script>";
        } else {
            echo "<script>alert('Error: Unable to set screen time limit.'); window.history.back();</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>

</html>
