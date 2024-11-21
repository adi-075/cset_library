<?php
// Load database configuration from myproperties.ini
$config = parse_ini_file("myproperties.ini", true);
$dbConfig = $config['DB'];

// Check if configuration was successfully loaded
if (!$dbConfig || !isset($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME'])) {
    die("Error: Unable to load database configuration from myproperties.ini");
}

// Establish database connection using MySQLi
$mysqli = new mysqli($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME']);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an SQL statement to insert the new user
    $stmt = $mysqli->prepare("INSERT INTO user_authentication (username, passwordhash, create_dt) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $username, $hashedPassword);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Display success message
        echo "User created successfully!";
        echo "<br>Redirecting to Login Page in 3 seconds...";

        // Use JavaScript to redirect after a 5-second delay
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 3000);
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <title>Create User</title>
</head>

<body>
    <h2 style="color: #111;">Create New Admin Account</h2>
    <form action="create_user.php" method="post">
        <b><label for="username">USERNAME</label></b>
        <input type="text" id="username" name="username" required><br><br>

        <b><label for="password">PASSWORD</label></b>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Create User">
    </form>
</body>
</html>

<style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            background: linear-gradient(135deg, #f0f4ff, #dbeafe); 
            font-family: Arial, sans-serif; 
        }

        /* Form styling */
        form {
            background-color: #ffffff; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); 
            width: 100%; 
            max-width: 400px; 
            text-align: center;
        }

        form h2 {
            color: #007BFF; 
            margin-bottom: 20px; 
            font-size: 1.5rem; 
        }

        form input[type="text"],
        form input[type="password"] {
            width: 90%; 
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 1rem;
        }

        form input[type="submit"] {
            background-color: #007BFF; 
            color: white; 
            border: none;
            padding: 10px 20px;
            border-radius: 5px; 
            font-size: 1rem;
            cursor: pointer; 
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            color: red;
            margin-top: 15px;
        }
</style>