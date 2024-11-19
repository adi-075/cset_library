<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Maintenance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin: auto;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
        }

        .filter-section {
            margin-bottom: 20px;
        }

        .filter-section label,
        .filter-section input,
        .filter-section select {
            margin-right: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px 12px;
            text-align: left;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        .status-link {
            color: blue;
            text-decoration: none;
        }

        .status-link:hover {
            text-decoration: underline;
        }

        .back-link {
            text-decoration: none;
            color: blue;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Book Maintenance</h1>

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
        $sql = "SELECT bookid, title, author, publisher, active, create_dt, last_updated FROM book";
        if (!empty($filterValue) && !empty($filterColumn)) {
            $sql .= " WHERE $filterColumn LIKE '%" . $mysqli->real_escape_string($filterValue) . "%'";
        }

        $result = $mysqli->query($sql);
		$books = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Display count of books found
        if ($result && $result->num_rows > 0) {
            echo "<p>" . $result->num_rows . " books found.</p>";
        } else {
            echo "<p>No books found.</p>";
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
                    <th>Bookid</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Date Added</th>
                    <th>Date of Last Update</th>
					<th>Edit Book Info</th>
                    <th>Delete Book</th>
                </tr>
			<?php 
			foreach($books as $row): 
				if($row['active'] == 1)
				{?>
				<tr>
					<td><?php echo htmlspecialchars($row['bookid']); ?></td>
					<td><?php echo htmlspecialchars($row['title']); ?></td>
					<td><?php echo htmlspecialchars($row['author']); ?></td>
					<td><?php echo htmlspecialchars($row['publisher']); ?></td>
					<td><?php echo htmlspecialchars($row['create_dt']); ?></td>
					<td><?php echo htmlspecialchars($row['last_updated']); ?></td>
				    <td><a href="edit.php?id=<?= $row['bookid']; ?>">Edit </a></td>

				    <td><a href="deleteBook.php?bookid=<?= $row['bookid']; ?>">Delete</a></td>  	  
				</tr>
				<?php } ?>
            </thead>
			<?php endforeach; ?>
        </table>

		<p><a href="InsertBook.php" class="back-link">Add a book</a> to the library Database</p>
        <p><a href="../index.php" class="back-link">Back to home page.</a></p>
    </div>
</body>

</html>