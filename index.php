<?php
// PHP code to handle database connection and form submission

// Database credentials
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "student_planner_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $subject = $conn->real_escape_string($_POST['subject']);
    $task = $conn->real_escape_string($_POST['task']);
    $due_date = $conn->real_escape_string($_POST['due_date']);

    // SQL query to insert data into the 'tasks' table
    $sql = "INSERT INTO tasks (subject, task_description, due_date) VALUES (?, ?, ?)";

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $subject, $task, $due_date);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $message = "New record created successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection at the end of the script
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Study Planner</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }
        .form-card {
            background: rgba(45, 45, 60, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .form-input, .form-textarea {
            background-color: rgba(60, 60, 80, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 flex items-center justify-center min-h-screen p-4">

    <div class="relative w-full max-w-xl mx-auto p-6 rounded-3xl bg-gray-900 text-center shadow-lg transition-all duration-300">
        
        <!-- Glow effect behind the card -->
        <div class="absolute inset-0.5 rounded-3xl z-0" style="background: linear-gradient(to right, #8A2BE2, #4B0082); filter: blur(25px); opacity: 0.6; transition: opacity 0.5s;"></div>

        <div class="form-card relative z-10 p-8 rounded-3xl">
            <h1 class="text-4xl font-bold mb-8 text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">
                Student Study Planner
            </h1>
            
            <?php if (isset($message)): ?>
                <div class="bg-green-500 text-white p-3 rounded-md mb-4">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-400 mb-1 text-left">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="e.g. Mathematics" class="form-input w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors duration-200" required>
                </div>

                <div>
                    <label for="task" class="block text-sm font-medium text-gray-400 mb-1 text-left">Task</label>
                    <textarea id="task" name="task" rows="4" placeholder="Describe the study task..." class="form-textarea w-full p-3 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors duration-200" required></textarea>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-400 mb-1 text-left">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-input w-full p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors duration-200" required>
                </div>

                <button type="submit" class="w-full text-white font-semibold py-3 px-4 rounded-full bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-300 transform hover:scale-105">
                    Add To Planner
                </button>
            </form>
        </div>
    </div>

</body>
</html>
