<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List App</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f0f2f5;
        }
        .container {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            background-color: #fff;
        }
        .task {
            margin: 5px 0;
        }
        .success, .error {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .success {
            color: #4CAF50;
            background-color: #e8f5e9;
        }
        .error {
            color: #f44336;
            background-color: #ffebee;
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

    // Display tasks
    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li class='task'>{$row['task']} 
                  <a href='?delete={$row['id']}'>Delete</a>
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

    $conn->close();
    ?>
</div>
</body>
</html>
