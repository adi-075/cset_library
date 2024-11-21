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
</div>
    <!-- <h2>Library App Home</h2> -->
    <div class="app-home">
        <p>Maintain <a href="">Students</a>.</p>
        <p>Maintain <a href="books/books.php">books</a>.</p>
        <p><a href="">Checkout</a> a book.</p>
        <p><a href="">Return</a> a book.</p>
        <p>Report a book <a href="">history</a>.</p>
        <p>Application <a href="logout.php">logout</a>.</p>
    </div>
</body>

</html>
