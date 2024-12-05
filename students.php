<?php
// Load database configuration from myproperties.ini
$config = parse_ini_file("./myproperties.ini", true);
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

// Handle form submissions for adding, editing, or inactivating students
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $rocketid = $_POST['rocketid'];
    
    // Regex pattern for rocketid (starts with 'R' followed by exactly 8 digits)
    $rocketidPattern = "/^R\d{8}$/";

    // Check if the rocketid matches the required pattern
    if (!preg_match($rocketidPattern, $rocketid)) {
        die("Error: Rocket ID must start with 'R' followed by exactly 8 digits (e.g., R00000009).");
    }

    if ($action == 'add') {
        // Add student
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $stmt = $mysqli->prepare("INSERT INTO student (rocketid, name, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $rocketid, $name, $phone, $address);
        if ($stmt->execute()) {
            echo "Student added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action == 'edit') {
        // Edit student
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $stmt = $mysqli->prepare("UPDATE student SET name = ?, phone = ?, address = ? WHERE rocketid = ?");
        $stmt->bind_param("ssss", $name, $phone, $address, $rocketid);
        if ($stmt->execute()) {
            echo "Student updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action == 'inactivate') {
        // Inactivate student
        $stmt = $mysqli->prepare("UPDATE student SET active = 0 WHERE rocketid = ?");
        $stmt->bind_param("s", $rocketid);
        if ($stmt->execute()) {
            echo "Student inactivated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all active students
$result = $mysqli->query("SELECT rocketid, name, phone, address, active FROM student WHERE active = 1");

// Check if query was successful
if (!$result) {
    die("Error fetching students: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
  
    <style>
        body {
            background-color: #ebe9e1;
            color: #e43d12;
            font-family: "Arial", serif;
            margin: 0;
            padding: 0;
        }

        h1,
        h2 {
            color: #f6385e;
            font-weight: 700;
            font-style: normal;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            border: 2px solid #e43d12;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #111;
        }

        form input,
        form button {
            display: block;
            width: calc(100% - 20px);
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form button {
            background-color: #e43d12;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #d6536d;
        }

        table {
            font-family: "Arial", serif;
            font-weight: bold;
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f6385e;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: #e43d12;
        }

        a:hover {
            color: #d6536d;
        }

        a:visited {
            color: #6604fa;
        }
    </style>

</head>

<body>
    <h1>Student Management</h1>

    <h2>Add Student</h2>
    <form method="post" action="students.php">
        <input type="hidden" name="action" value="add">
        <label for="rocketid">Rocket ID:</label>
        <input type="text" id="rocketid" name="rocketid" required pattern="^R\d{8}$" title="Rocket ID must start with 'R' followed by exactly 8 digits (e.g., R00000009)"><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>
        <button type="submit">Add Student</button>
    </form>

    <h2>Edit Student</h2>
    <form method="post" action="students.php">
        <input type="hidden" name="action" value="edit">
        <label for="rocketid">Rocket ID:</label>
        <input type="text" id="rocketid" name="rocketid" required pattern="^R\d{8}$" title="Rocket ID must start with 'R' followed by exactly 8 digits (e.g., R00000009)"><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>
        <button type="submit">Edit Student</button>
    </form>

    <h2>Inactivate Student</h2>
    <form method="post" action="students.php">
        <input type="hidden" name="action" value="inactivate">
        <label for="rocketid">Rocket ID:</label>
        <input type="text" id="rocketid" name="rocketid" required pattern="^R\d{8}$" title="Rocket ID must start with 'R' followed by exactly 8 digits (e.g., R00000009)"><br>
        <button type="submit">Inactivate Student</button>
    </form>

    <h2>Student List</h2>
    <table>
        <tr>
            <th>Rocket ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['rocketid']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td class="<?php echo $row['active'] ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo $row['active'] ? "Active" : "Inactive"; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <!-- Return to Home Page Link -->
    <a href="index.php" class="home-link">Back to Home Page</a>
</body>

</html>
