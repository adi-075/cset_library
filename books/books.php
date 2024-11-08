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
            max-width: 900px;
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
        <p>3 books found.</p>

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
                <tr>
                    <td>1</td>
                    <td>PHP and MySQL Web Development</td>
                    <td>Welling and Thompson</td>
                    <td>Addison Wesley</td>
                    <td>1</td>
                    <td>2024-11-03 22:37:53</td>
                    <td>2024-11-03 22:37:53</td>
                    <td><a href="#" class="status-link">Active Status</a></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Programming Rust</td>
                    <td>Blandy, et. al.</td>
                    <td>O'Reilly</td>
                    <td>1</td>
                    <td>2024-11-03 22:37:53</td>
                    <td>2024-11-03 22:37:53</td>
                    <td><a href="#" class="status-link">Active Status</a></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Database System Concepts</td>
                    <td>Silberschatz, et. al.</td>
                    <td>McGraw Hill</td>
                    <td>1</td>
                    <td>2024-11-03 22:37:53</td>
                    <td>2024-11-03 22:37:53</td>
                    <td><a href="#" class="status-link">Active Status</a></td>
                </tr>
            </tbody>
        </table>

        <p><a href="../index.php" class="back-link">Back to home page.</a></p>
    </div>
</body>

</html>