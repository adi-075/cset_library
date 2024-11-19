<?php
	if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['bookid'])) 
	{
		$id = $_GET['bookid'];
		
		$inifile = parse_ini_file("../myproperties.ini");
		$conn = mysqli_connect($inifile["DBHOST"], $inifile["DBUSER"], $inifile["DBPASS"], $inifile["DBNAME"]) or die("Connection failed:" . mysqli_connect_error()) ;

		if(!$conn)
		{
			die("Failed to connect: " . mysqli_connect_error());	
		}
		
		$stmt = $conn->prepare("DELETE FROM `book` WHERE `bookid` = ?");
		$returnval = $stmt->bind_param("i", $id);
		//mysqli_stmt_bind_param($stmt, "i", $id)
		
		if (!$stmt)
		{
			die("Failed to Delete: " . $conn->error);
		}
		
		$deleted = $stmt->execute();
		
		if ($deleted)
		{
			echo "Deleted Successfully";
			header('Location: books.php');
			exit();
		}
	}
	
?>