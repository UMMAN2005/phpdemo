<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List App</title>
    <style>
        /* Body Styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            color: #333;
        }
        
        /* Container Styling */
        .container {
            text-align: center;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            background-color: #ffffff;
            animation: fadeIn 0.6s ease-out;
        }
        
        /* Title Styling */
        h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        /* Form and Input Styling */
        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            flex-grow: 1;
            outline: none;
            transition: border-color 0.3s ease;
        }
        
        input[type="text"]:focus {
            border-color: #66a6ff;
        }
        
        button {
            padding: 10px 15px;
            border: none;
            background-color: #66a6ff;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        button:hover {
            background-color: #5891e4;
        }
        
        /* Task List Styling */
        ul {
            list-style-type: none;
            padding: 0;
        }
        
        .task {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 5px 0;
            padding: 10px;
            border-radius: 8px;
            background-color: #f4f6f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        
        .task:hover {
            transform: scale(1.02);
        }
        
        /* Success and Error Styling */
        .success, .error {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .success {
            color: #4CAF50;
            background-color: #e8f5e9;
        }
        
        .error {
            color: #f44336;
            background-color: #ffebee;
        }
        
        /* Rename Form Inline Styling */
        form[style='display:inline;'] input[type="text"] {
            width: auto;
            padding: 5px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
        }

        form[style='display:inline;'] button {
            padding: 5px 10px;
            font-size: 0.9em;
            background-color: #ff6f61;
        }
        
        /* Fade-in Animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
    </style>
</head>
<body>
<div class="container">
    <h1>Todo List</h1>
    <form method="POST">
        <input type="text" name="task" placeholder="Add new task" required>
        <button type="submit" name="add">Add</button>
    </form>
    
    <?php
        $servername = "localhost"; // Change if your server is different
        $username = "your_username"; // Your database username
        $password = "your_password"; // Your database password
        $dbname = "your_database"; // Your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("<div class='error'>Connection failed: " . $conn->connect_error . "</div>");
    }

    // Create tasks table if it doesn't exist
    $createTableSql = "CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task VARCHAR(255) NOT NULL
    )";
    
    if ($conn->query($createTableSql) === TRUE) {
        // Table created successfully
    } else {
        echo "<div class='error'>Error creating table: " . $conn->error . "</div>";
    }

    // Add task
    if (isset($_POST['add'])) {
        $task = $conn->real_escape_string($_POST['task']);
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>Task added successfully!</div>";
        } else {
            echo "<div class='error'>Error adding task: " . $conn->error . "</div>";
        }
    }

    // Delete task
    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);
        $sql = "DELETE FROM tasks WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>Task deleted successfully!</div>";
        } else {
            echo "<div class='error'>Error deleting task: " . $conn->error . "</div>";
        }
    }
// Display tasks
$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='task'>{$row['task']} 
              <form method='GET' style='display:inline;'>
                  <button type='submit' name='delete' value='{$row['id']}' style='color: red; background: none; border: none; cursor: pointer;'>X</button>
              </form>
              <form method='POST' style='display:inline;'>
                  <input type='text' name='task' placeholder='Rename task' required>
                  <input type='hidden' name='id' value='{$row['id']}'>
                  <button type='submit' name='rename'>Rename</button>
              </form>
              </li>";
    }
    echo "</ul>";
} else {
    echo "<div>No tasks found.</div>";
}

    // Rename task
    if (isset($_POST['rename'])) {
        $id = intval($_POST['id']);
        $task = $conn->real_escape_string($_POST['task']);
        $sql = "UPDATE tasks SET task = '$task' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='success'>Task renamed successfully!</div>";
        } else {
            echo "<div class='error'>Error renaming task: " . $conn->error . "</div>";
        }
    }



    $conn->close();
    ?>
</div>
</body>
</html>
