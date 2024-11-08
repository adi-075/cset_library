<?php

session_start();

	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid'])&& isset($_POST['password']) && !isset($_POST['logout']))
	{
		// if the user has just tried to log in
		$userid = $_POST['userid'];
		$password = $_POST['password'];
		$isAuthenticated = false;

		// Password hash
		//$hash = password_hash($password, PASSWORD_DEFAULT);
		
		$inifile = parse_ini_file("myproperties.ini");   
		$connection = new mysqli($inifile["DBHOST"], $inifile["DBUSER"], $inifile["DBPASS"], $inifile["DBNAME"]) 
				  or die("Connection failed:" . mysqli_connect_error()) ;
	
		$stmt = $connection->prepare("SELECT `passwordhash` FROM `user_authentication` WHERE `username` = ?");
				
			
		$stmt->bind_param("s", $userid);
		
		$stmt->execute();
		
		$result = $stmt->get_result(); // get the mysqli result
		$user = $result->fetch_assoc(); // fetch data  
		if (password_verify($password, $user['passwordhash'])) 
		{
			$isAuthenticated = true;
		}
		
		if ($isAuthenticated) {
 		$_SESSION['valid_user'] = $userid;
		
    	}
    	else {
      		unset($_SESSION['valid_user']);
    	}


		$connection->close();
	}else
	{
		unset($_SESSION['valid_user']);
	}

?>


<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <style type="text/css">
      label {
         width: 125px;
         float: left;
         text-align: left;
         font-weight: bold;
      }
      input {
         border: 1px solid #000;
         padding: 3px;
      }
      button {
         margin-top: 12px;
      }
    </style>
  </head>
  <body>
    <h1>Home Page</h1>

		<?php
		  if (isset($_SESSION['valid_user']) && $_SESSION['valid_user'] != "") {
			  var_dump($_SESSION);
			 echo '<p>You are logged in as: '.$_SESSION['valid_user'].' <br />';
			 echo '<a href="logout.php">Log out</a></p>';
			 header('Location: index.php');
		  }
		  else if (isset($userid)) {
			  // if they've tried and failed to log in
			  echo '<p>Could not log you in.  Bad username or password?</p>';
		  }
		  else {
			  // they have not tried to log in yet or have logged out
			  echo '<p>You are not logged in.</p>';
		  }    
		?>

	<?php
		if(isset($_SESSION['valid_user']) == null)
		{
	?>
    <form action="login.php" method="post">
      <p>
        <label for="userid">UserID:</label>
        <input type="text" name="userid" id="userid" size="30"/>
      </p>
      <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" size="30"/>
      </p>   
      <button type="submit" name="login">Login</button>
    </form>

	<?php
		}
	?>
  </body>
</html>