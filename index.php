<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSET Library Homepage</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body style="font-family: Arial, sans-serif;">
    <div class="hero" style="text-align: center;">
      <h1>Welcome to the Library App</h1>
		  <link rel="stylesheet" href="styles/style.css">
    </div>
  
    <div class="app-home">
        <div class="maintain">
            <h3>
                * Maintain
            </h3>
            <ul>
                <li>
                    <p>
                        <a href="students.php">Students</a>
                    </p>
                </li>
                <li>
                    <p>
                        <a href="books/books.php">Books</a>
                    </p>
                </li>
                <li>
                    <p>
                        <a href="create_user.php">Create Admin Account</a>
                    </p>
                </li>
            </ul>
        </div>
        <br>
        <div class="book-mgmt">
            <h3>
                * Book Management
            </h3>
            <ul>
                <li>
                    <p>
                        <a href="checkout/checkout.php">Checkout Books</a>
                    </p>
                </li>
                <li>
                    <p>
                        <a href="books/return.php">Return Book</a>
                    </p>
                </li>
                <li>
                    <p>
                        <a href="books/book-history.php">View Book History</a>
                    </p>
                </li>
            </ul>
            <p>
                <a href="logout.php">Logout</a>
            </p>
        </div>
    </div>
</body>

</html>