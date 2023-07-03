
<?php 
	session_start();

	/*
	Mga kulang nalang is:
	-Design
	-Checking for duplicate values in database
	-File upload(yung profile)
	-Pag display ng errors
	
	--July 2--
	*/
	if(isset($_POST['signup']))
	{
		$errors = array();

		//Setting variables
		//strip_tags = tinatanggal mga <>
		//sha1 = encrypts password
		$fname = strip_tags($_POST['fname']);
		$minitial = strip_tags($_POST['minitial']);
		$lname = strip_tags($_POST['lname']);
		$email = $_POST['email'];
		$password = sha1(strip_tags($_POST['password']));
		$repassword = sha1(strip_tags($_POST['repassword']));
		$username = strip_tags($_POST['username']);
		$bio = strip_tags($_POST['bio']);
		$studNumber = strip_tags($_POST['studNum']);

		//ginawa kong array yung mga input para icheck sila isa isa for errors
		$inputs = array(
			"fname" => $fname,
			"minitial" => $minitial,
			"lname" => $lname,
			"email" => $email,
			"password" => $password,
			"repassword" => $repassword,
			"username" => $username,
			"bio" => $bio,
			"studNumber" => $studNumber
		);

		//Check for empty inputs(blank spaces)
		foreach($inputs as $key => $value)
		{
			if($key == "password"){//checks if re-entered password did not match password
				if($password != $repassword){
					$errors["password"] = "Your passwords do not match!";
				}
			}
			else{
				if(strlen(trim($value)) == 0)//checks if input is white/blank spaces
				{
					$errors[$key] = "Input can't be empty.";
				}
			}
		}



		//Preparing database connection
		$hostname = "localhost";//default value
		$userdb = "root";//default value
		$passdb = "";//password ng database, wala naman sakin kaya empty lang siya
		$dbname = "techbulletin";//dito papalitan niyo siya ng name ng db niyo, sakin kasi techbulletin name ng database ko sa laptop ko

		//connecting to database
		$conn = new mysqli($hostname, $userdb, $passdb, $dbname);

		//checking if connection found the database
		if($conn->connect_errno){
			echo "Failed to connect to Database(baka maling database name kay dbname).";
			$conn->close();
		}
		else{
			foreach($inputs as $key => $value)
			{
				if($key == "password" || $key == "repassword" || $key == "bio")
				{
					continue;
				}
				else
				{
					switch($key)
					{
						case "fname":
							$fname_sql = "SELECT user_fname FROM user WHERE user_fname = '$value'";
							$fname_dupe = $conn->query($fname_sql);
							if($fname_dupe->num_rows > 0)
							{
								$errors[$key] = $value . " already exists. <br>";
							}
							else
							{
								break;
							}
						case "minitial":
							$minitial_sql = "SELECT user_minitial FROM user WHERE user_minitial = '$value'";
							$minitial_dupe = $conn->query($fname_sql);
							if($minitial_dupe->num_rows > 0)
							{
								$errors[$key] = $value . "already exists. <br>";
							}
							else
							{
								break;
							}
						case "lname":
							$lname_sql = "SELECT user_lname FROM user WHERE user_lname = '$value'";
							$lname_dupe = $conn->query($lname_sql);
							if($lname_dupe->num_rows > 0)
							{
								$errors[$key] = $value . "already exists. <br>";
							}
							else
							{
								break;
							}
						case "email":
							$email_sql = "SELECT user_email FROM user WHERE user_email = '$value'";
							$email_dupe = $conn->query($email_sql);
							if($lname_dupe->num_rows > 0)
							{
								$errors[$key] = $value . "already exists. <br>";
							}
							else
							{
								break;
							}
						case "username":
							
						default:
							break;
					}
				}
			}
			$conn->close();

			if (!empty($errors))
			{
				foreach($errors as $key => $value)
				{
					echo $value;
				}
			}	
		}
		}
		else
		{
			//setting default value for all variables
			$fname = "";
			$minitial = "";
			$lname = "";
			$email = "";
			$password = "";
			$repassword = "";
			$username = "";
			$profile = "";
			$bio = "";
			$studNumber = "";
		}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Signup</title>
</head>
<body>
	<form method = "post" action="" enctype="multipart/form-data">
		Enter First Name:
		<input type="text" name="fname" placeholder="First Name" required>
		<br>
		Enter Middle Initial:
		<input type="text" name="minitial" placeholder="Middle Initial" required>
		<br>
		Enter Last Name:
		<input type="text" name="lname" placeholder="Last Name" required>
		<br>
		Enter Email:
		<input type="email" name="email" placeholder="Email" required>
		<br>
		Enter Password:
		<input type="password" name="password" placeholder="Password" required>
		<br>
		Re-enter Password:
		<input type="password" name="repassword" placeholder="Re-enter Password" required>
		<br>
		Enter Username:
		<input type="text" name="username" placeholder="Username" required>
		<br>
		Enter Bio:
		<input type="text" name="bio" placeholder="Bio" required>
		<br>
		Enter Student Number:
		<input type="text" name="studNum" placeholder="Student Number" required>
		<br>
		<button type="submit" name="signup">Signup</button>
	</form>
</body>
</html>
