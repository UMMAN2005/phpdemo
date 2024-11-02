<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Status</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
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
            max-width: 300px;
            background-color: #fff;
        }
        .status {
            font-size: 1.2em;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
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
        <?php
        // Database connection settings
        $servername = "localhost"; // Change if your server is different
        $username = "your_username"; // Your database username
        $password = "your_password"; // Your database password
        $dbname = "your_database"; // Your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            echo "<div class='status error'>Connection failed: " . $conn->connect_error . "</div>";
        } else {
            echo "<div class='status success'>Connection successful!</div>";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>
