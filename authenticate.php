<?php
session_start();

// Load database configuration
$config = parse_ini_file("myproperties.ini", true)['DB'] ?? null;
if (!$config || !isset($config['DBHOST'], $config['DBUSER'], $config['DBPASS'], $config['DBNAME'])) {
    die("Error: Unable to load database configuration.");
}

// Establish database connection
$mysqli = new mysqli($config['DBHOST'], $config['DBUSER'], $config['DBPASS'], $config['DBNAME']);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve and prepare user input
$username = $_POST['username'];
$password = $_POST['password'];
$stmt = $mysqli->prepare("SELECT passwordhash FROM user_authentication WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

// Verify user existence and password
if ($stmt->num_rows === 1) {
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    }
}

// Handle invalid login
$_SESSION['error'] = "Invalid username or password.";
$stmt->close();
$mysqli->close();
header("Location: login.php");
exit;
?>