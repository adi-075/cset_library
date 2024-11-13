<?php
  // define variables and errors and set to empty values
  $authorErr = $titleErr = $publisherErr = "";
  $author = $title = $publisher = "";

  if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
	// validation logic of posted values will go here
  
	// if all validation is then OK, database insert logic will go here
	// (reimplement insert.php and adjust as necessary)
	
	// Upon successful insert, we redirect to the users.php page 
	// (bypassing the insert.php) page

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
		
		$stmt = $conn->prepare("INSERT INTO `book` (`author`, `title`, `publisher`) VALUES (?, ?, ?)");
		
		$stmt->bind_param("sss", $author, $title, $publisher);

		$qstat = $stmt->execute();
		
		if($qstat) 
		{
		  //echo "DB Insert successful";
		  header('Location: books.php');
		  exit();
		}
		else 
		{
		  var_dump($authorErr);
		  var_dump($titleErr);
		  var_dump($publisherErr);
		}
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
    <title>Registration Form</title>
</head>

<body bgcolor="FFFFFF">
    <h1> Book Maintenance </h1>
    <div>
        <h2>Add Book to Library Database</h2>
    </div>
	
	<p><span class="error">* required field</span></p>
    <form action="InsertBook.php" method="POST">
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

    <p>
      Or see currently registered <a href="books.php">books</a>.
    </p>

</body>
</html>