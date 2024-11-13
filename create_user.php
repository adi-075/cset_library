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
    <title>Create User</title>
</head>

<body>
    <h2>Create New User</h2>
    <form action="create_user.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Create User">
    </form>
</body>

</html>