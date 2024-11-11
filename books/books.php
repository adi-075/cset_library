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

        // Establish database connection using MySQLi
        $mysqli = new mysqli($dbConfig['DBHOST'], $dbConfig['DBUSER'], $dbConfig['DBPASS'], $dbConfig['DBNAME']);

        // Check for connection errors
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Query to fetch book data
        $sql = "SELECT bookid, title, author, publisher, active, create_dt, last_updated FROM book";
        $result = $mysqli->query($sql);

        // Display count of books found
        if ($result && $result->num_rows > 0) {
            echo "<p>" . $result->num_rows . " books found.</p>";
        } else {
            echo "<p>No books found.</p>";
        }
        ?>

        <div class="filter-section">
            <label for="filter">Filter:</label>
            <input type="text" id="filter" name="filter">
            <label for="column">Column:</label>
            <select id="column" name="column">
                <option value="">--Select Column--</option>
                <option value="title">Title</option>
                <option value="author">Author</option>
                <option value="publisher">Publisher</option>
                <option value="active">Active</option>
            </select>
            <button type="button">Filter</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>bookid</th>
                    <th>title</th>
                    <th>author</th>
                    <th>publisher</th>
                    <th>active</th>
                    <th>create_dt</th>
                    <th>last_updated</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display data from the query
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['bookid'] . "</td>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['author'] . "</td>";
                        echo "<td>" . $row['publisher'] . "</td>";
                        echo "<td>" . ($row['active'] == 1 ? 'Active' : 'Inactive') . "</td>";
                        echo "<td>" . $row['create_dt'] . "</td>";
                        echo "<td>" . $row['last_updated'] . "</td>";
                        echo "<td><a href='#' class='status-link'>Active Status</a></td>";
                        echo "</tr>";
                    }
                }
                $mysqli->close();
                ?>
            </tbody>
        </table>

        <p><a href="../index.php" class="back-link">Back to home page.</a></p>
    </div>
</body>

</html>