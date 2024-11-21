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
    <title>Login Page</title>
</head>

<body>
   <form action="login.php" method="post">
        <h2>LIBRARY LOGIN</h2>
        <label for="username"><b>USERNAME</b></label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password"><b>PASSWORD</b></label><br>
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
    <!-- <p>Are you an Administrator?<a href="create_user.php">Create an Account here.</a>.</p> -->
</body>

</html>

<style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
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