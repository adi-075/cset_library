<?php
// Establish a connection to the database
$config = parse_ini_file("../myproperties.ini", true);
$dbConfig = $config['DB'];

if (!$dbConfig || !isset($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME'])) {
    die("Error: Unable to load database configuration from myproperties.ini");
}

$mysqli = new mysqli($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME']);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the bookid from the GET request
$bookid = isset($_GET['bookid']) ? $_GET['bookid'] : 0;

// Query to fetch the checkout history of the specific book
$sql = "SELECT checkoutid, bookid, rocketid, promise_date, return_date, create_dt, last_updated FROM checkout WHERE bookid = ? ORDER BY create_dt DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $bookid);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout History</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <div class="container">
        <h1>Checkout History for Book ID: <?php echo htmlspecialchars($bookid); ?></h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Checkout ID</th>
                        <th>Rocket ID</th>
                        <th>Promise Date</th>
                        <th>Return Date</th>
                        <th>Created At</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                        <td><?php echo htmlspecialchars($row['checkoutid'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['rocketid'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['promise_date'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['return_date'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['create_dt'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['last_updated'] ?? ''); ?></td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No checkout history found for this book.</p>
        <?php endif; ?>

        <p><a href="history.php" class="back-link">Back to Checkout History</a></p>
        
    </div>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$mysqli->close();
?>
