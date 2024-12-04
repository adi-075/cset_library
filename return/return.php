<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <div class="container">
        <h1>Return Book</h1>

        <?php
        // Load database configuration from myproperties.ini
        $config = parse_ini_file("../myproperties.ini", true);
        $dbConfig = $config['DB'];

        // Check if configuration was successfully loaded
        if (!$dbConfig || !isset($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME'])) {
            die("Error: Unable to load database configuration from myproperties.ini");
        }

        // Initialize filter variables
        $filterValue = isset($_GET['filter']) ? $_GET['filter'] : '';
        $filterColumn = isset($_GET['column']) ? $_GET['column'] : '';

        // Establish database connection using MySQLi
        $mysqli = new mysqli($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME']);

        // Check for connection errors
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Query to fetch book data
        $sql = "SELECT title, author, publisher, book.create_dt, checkout.promise_date, checkout.return_date, book.bookid, checkout.rocketid, checkout.checkoutid
				FROM checkout JOIN book
				ON book.bookid = checkout.bookid
				WHERE book.active = 1 AND checkout.promise_date IS NOT NULL AND checkout.return_date IS NULL";
        /*if (!empty($filterValue) && !empty($filterColumn)) {
            $sql .= " WHERE $filterColumn LIKE '%" . $mysqli->real_escape_string($filterValue) . "%'";
        }*/

        $result = $mysqli->query($sql);
		$checkedout = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Display count of books found
        if ($result && $result->num_rows > 0) {
            echo "<p>" . $result->num_rows . " books found.</p>";
        } else {
            echo "<p>No books are checked out.</p>";
        }
        ?>

        <!-- Filter Form -->
        <div class="filter-section">
            <form method="GET" action="">
                <label for="filter">Filter:</label>
                <input type="text" id="filter" name="filter" value="<?php echo htmlspecialchars($filterValue); ?>">
                <label for="column">Column:</label>
                <select id="column" name="column">
                    <option value="">--Select Column--</option>
                    <option value="title" <?php if ($filterColumn == 'title')
                        echo 'selected'; ?>>Title</option>
                    <option value="author" <?php if ($filterColumn == 'author')
                        echo 'selected'; ?>>Author</option>
                    <option value="publisher" <?php if ($filterColumn == 'publisher')
                        echo 'selected'; ?>>Publisher
                    </option>
                    <option value="active" <?php if ($filterColumn == 'active')
                        echo 'selected'; ?>>Active</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                   <!-- <th>Checkout ID</th>-->
                    <th>Book ID</th>
                    <th>Student ID</th>
					<th>Title</th>
					<th>Publisher</th>
                    <th>Promised Return Date</th>
					<th>Return</th>
                </tr>
			<?php 
			foreach($checkedout as $row): 
				if($row['promise_date'] != NULL && $row['return_date'] == NULL)
				{?>
				<tr>
					<!-- <td><?php echo htmlspecialchars($row['checkoutid']); ?></td>-->
					<td><?php echo htmlspecialchars($row['bookid']); ?></td>
					<td><?php echo htmlspecialchars($row['rocketid']); ?></td>
					<td><?php echo htmlspecialchars($row['title']); ?></td>
					<td><?php echo htmlspecialchars($row['publisher']); ?></td>
					<td><?php echo htmlspecialchars($row['promise_date']); ?></td>
				    <td><a href="returnUpdate.php?bookid=<?= $row['bookid'] ?>&checkoutid=<?= $row['checkoutid'] ?>">Return</a></td>
				</tr>
				<?php } ?>
            </thead>
			<?php endforeach; ?>
        </table>

        <p><a href="../index.php" class="back-link">Back to home page.</a></p>
    </div>
</body>

</html>