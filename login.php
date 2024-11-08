<?php
session_start();

// Load database configuration from myproperties.ini
$config = parse_ini_file("myproperties.ini", true)['DB'] ?? null;
if (!$config || !isset($config['DBHOST'], $config['DBUSER'], $config['DBPASS'], $config['DBNAME'])) {
    die("Error: Unable to load database configuration.");
}

// Establish database connection
$mysqli = new mysqli($config['DBHOST'], $config['DBUSER'], $config['DBPASS'], $config['DBNAME']);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user input from login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT passwordhash FROM user_authentication WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists in the database
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Set session variables to mark user as logged in
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;

            // Redirect to the main page
            header("Location: index.php");
            exit;
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid username or password.";
        }
    } else {
        // Username does not exist
        $_SESSION['error'] = "Invalid username or password.";
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
    <title>Login</title>
</head>

<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <?php
    // Display error message if there is one
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); // Clear the error after displaying it
    }
    ?>
    <p>Don't have an account? <a href="create_user.php">Create one here</a>.</p>
</body>

</html>