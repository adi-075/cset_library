<?php

// define variables and errors and set to empty values
   $authorErr = $titleErr = $publisherErr = "";
  $author = $title = $publisher = "";
  $bookinfo = NULL;
  
  if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) { 
    $id = $_POST['id'];

    if (empty($_POST["author"])) 
	{
        $authorErr = "Author name is required";
    } 
    else
	{
      $author = clean_input($_POST["author"]);
	// Check if name only contains letters and space.
      if (!preg_match("/^[a-zA-Z ]+$/",$author)) 
	  {
        $authorErr = "Only letters and space allowed";
      }
	}
	  
	if (empty($_POST["title"])) 
	{
		$titleErr = "Book title is required";
	} 
	else
	{
		$title = clean_input($_POST["title"]);
		// check if title is well-formed

		//May want to add regular expression to only allow certain characters?
	}
	if (empty($_POST["publisher"])) 
	{
		$publisherErr = "publisher name is required";
	} 
	else
	{ 
	  $publisher = clean_input($_POST["publisher"]);
	  // Check if publisher is well formed.
	  
	  //may want to add regular expression to only allow certain characters?
	}
	
    if ($authorErr == "" && $titleErr == "" && $publisherErr == "") 
	{	
		$inifile = parse_ini_file("../myproperties.ini");
		
		$conn = mysqli_connect($inifile["DBHOST"], $inifile["DBUSER"], $inifile["DBPASS"], $inifile["DBNAME"]) or die("Connection failed:" . mysqli_connect_error()) ;
		
		$stmt = $conn->prepare("UPDATE `book` SET `author` = ?, `title` = ?, `publisher` = ? WHERE `bookid` = ?");
		
		$returnval = $stmt->bind_param("sssi", $author, $title, $publisher, $id);
		//mysqli_stmt_bind_param($stmt, "i", $ID)
		
		if (!$stmt)
		{
			die("Failed to Edit: " . $conn->error);
		}
		
		$Edited = $stmt->execute();
		
		if ($Edited)
		{
			echo "Edited Successfully";
			header('Location: books.php');
			exit();
		}
		else
		{
			echo "Edited Failed";
		}
      }
    }

	if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['bookid'])) {    
	  $id = $_GET['bookid'];
		// fetch the user form the database and populate the work variables.
	  $inifile = parse_ini_file("../myproperties.ini");   
	  $conn = mysqli_connect($inifile["DBHOST"], $inifile["DBUSER"], $inifile["DBPASS"], $inifile["DBNAME"]) or die("Connection failed:" . mysqli_connect_error()) ;
	  $stmt = $conn->prepare("SELECT `author`, `title`, `publisher`, `bookid` FROM `book` WHERE bookid = ?");
	  $stmt->bind_param("i",$id);
	  $stmt->execute();
	  $result = $stmt->get_result(); // get the mysqli result
	  $bookinfo = $result->fetch_assoc(); // fetch data 
	  //var_dump($user);
		if (NULL != $bookinfo) {
		  $id = $bookinfo['bookid'];
		  $author = $bookinfo['author'];
		  $title = $bookinfo['title'];
		  $publisher = $bookinfo['publisher'];
		}
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
    <h1> Book Maintenance </h1>
    <div>
        <h2>Make Edit to Book</h2>
    </div>
	<?php 
        if (NULL == $bookinfo && $_SERVER['REQUEST_METHOD'] == "GET" ) {
            echo "<h2>Sorry book " . $bookinfo['bookid'] . " not found.</h2>";
			//var_dump($user);
        }
        else {
    ?>        

	<p><span class="error">* required field</span></p>
    <form action="editBook.php" method="POST">
		<input type="hidden" name="id" id = "id" value="<?= $id?>" />
        <label for="author">Author:</label><br/>
        <input type="text" name="author" id="author" value="<?php echo $author;?>" /> 
			<span class="error">* <?php echo $authorErr;?></span> <br/> <br/>
        <label for="title">Book Title:</label><br/>
        <input type="text" name="title" id="title" value="<?php echo $title;?>" />
			<span class="error">* <?php echo $titleErr;?></span> <br/> <br/>
        <label for="publisher">Publisher:</label><br/>
        <input type="text" name="publisher" id="publisher" value="<?php echo $publisher;?>" />
			<span class="error">* <?php echo $publisherErr;?></span> <br/> <br/>
        <input type="submit" name="submit" id="submit" />
    </form>
	<?php 
        }
    ?>

    <p>
      Or see currently registered <a href="books.php">books</a>.
    </p>

</body>
</html>
