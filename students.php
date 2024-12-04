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

    if ($action == 'add') {
        // Add student
        $rocketid = $_POST['rocketid'];
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
        $rocketid = $_POST['rocketid'];
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
        $rocketid = $_POST['rocketid'];

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

// Fetch all students
$result = $mysqli->query("SELECT rocketid, name, phone, address, active FROM student");

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
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        h2 {
            color: #4CAF50;
            margin-top: 40px;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 20px auto;
        }

        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Student Management</h1>

    <h2>Add Student</h2>
    <form method="post" action="students.php">
        <input type="hidden" name="action" value="add">
        <label for="rocketid">Rocket ID:</label>
        <input type="text" id="rocketid" name="rocketid" required><br>
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
        <input type="text" id="rocketid" name="rocketid" required><br>
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
        <input type="text" id="rocketid" name="rocketid" required><br>
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
</body>

</html>
