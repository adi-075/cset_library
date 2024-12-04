<?php

// define variables and errors and set to empty values
   $studentidErr = $returnErr = "";
   $bookid = $studentid = $return = "";
  $checkoutinfo = NULL;
  
  if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) { 
    $bookid = $_POST['id'];

    if (empty($_POST["studentid"])) 
	{
        $studentidErr = "Student Rocket ID is required";
    } 
    else
	{
      $studentid = clean_input($_POST["studentid"]);
	// Check if name only contains letters and space.
      if (!preg_match("/^R\d{8}$/",$studentid))
	  {
        $studentidErr = "Must be in format RXXXXXXXX";
      }
	}
	  
	if (empty($_POST["return"])) 
	{
		$returnErr = "Promised Return Date Required";
	} 
	else
	{
		$return = $_POST["return"];
		// check if title is well-formed
		$dateArray = date_parse($return);
		if(!checkdate($dateArray['month'], $dateArray['day'], $dateArray['year']))
		{
			$returnErr = "Enter date in format mm/dd/yyyy";
		}
		//May want to add regular expression to only allow certain characters?
	}

	
    if ($studentidErr == "" && $returnErr == "") 
	{	
		$inifile = parse_ini_file("../myproperties.ini");
		
		$conn = mysqli_connect($inifile["DBHOST"], $inifile["DBUSER"], $inifile["DBPASS"], $inifile["DBNAME"]) or die("Connection failed:" . mysqli_connect_error()) ;
		
		$stmt = $conn->prepare("INSERT INTO `checkout` (`bookid`, `rocketid`, `promise_date`) VALUES (?, ?, ?)");
		
		$returnval = $stmt->bind_param("sss", $bookid, $studentid, $return);
		//mysqli_stmt_bind_param($stmt, "i", $ID)
		
		if (!$stmt)
		{
			die("Failed to checkout: " . $conn->error);
		}
		
		$checkedout = $stmt->execute();
		
		if ($checkedout)
		{
			echo "Checkedout Successfully";
			header('Location: checkout.php');
			exit();
		}
		else
		{
			echo "Checkout Failed";
		}
      }
    }

	if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['bookid'])) {    
	  $bookid = $_GET['bookid'];
  }

  // Distrust all user input
  function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<style> .error {color: #FF0000;} </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Info</title>
</head>

	  
 
<body bgcolor="FFFFFF">
    <h1> Checkout a Book </h1>
	<link rel="stylesheet" href="../styles/style.css">
    <div>
        <h2>Checkout Book</h2>
    </div>
	<?php 
        if (NULL == $bookid && $_SERVER['REQUEST_METHOD'] == "GET" ) {
            echo "<h2>Sorry book " . $bookid . " not found.</h2>";
			var_dump($checkoutinfo);
        }
        else {
    ?>        

	<p><span class="error">* required field</span></p>
    <form action="checkoutdetails.php" method="POST">
		<input type="hidden" name="id" id = "id" value="<?= $bookid?>" />
        <label for="studentid">Student ID:</label><br/>
        <input type="text" name="studentid" id="studentid" value="<?php echo $studentid;?>" /> 
			<span class="error">* <?php echo $studentidErr;?></span> <br/> <br/>
        <label for="return">Promised Return Date (eg. yyyy-mm-dd):</label><br/>
        <input type="Date" name="return" id="return" value="<?php echo $return;?>" />
			<span class="error">* <?php echo $returnErr;?></span> <br/> <br/>
        <input type="submit" name="submit" id="submit" />
    </form>
	<?php 
        }
    ?>

    <p>
      Or see current available books <a href="checkout.php">books</a>.
    </p>

</body>
</html>
