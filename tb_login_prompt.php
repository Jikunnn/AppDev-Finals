<?php
	if(isset($_POST['signup']))
	{
		header("Location: tb_signup.php");
		exit();
	}
	else if(isset($_POST['login']))
	{
		$errors = array();
		$username_email = strip_tags($_POST['username_email']);
		$password = sha1(strip_tags($_POST['password']));

		$inputs = array(
			"Username" => $username_email,
			"Password" => $password
		);

		foreach($inputs as $key => $value)
		{
			if(strlen(trim($value)) == 0 || empty($value)){
				$errors[$key] = "Input cannot be empty.";
			}
		}

		$hostname = "localhost";
		$userdb = "root";
		$passdb = "";
		$dbname = "techbulletin";

		if(empty($errors))
		{
			$conn = new mysqli($hostname, $userdb, $passdb, $dbname);
			if($conn->connect_errno){
				echo "Connection failed: " . $conn->connect_error;
				$conn->close();
			}
			else
			{
				$login_sql = "SELECT user_username, user_email, user_password FROM user WHERE (user_username = '$username_email' OR user_email = '$username_email') AND user_password = '$password'";
				$login = $conn->query($login_sql);
				if($login->num_rows == 1)
				{
					echo "User found!";
					$conn->close();
				}
				else
				{
					echo "User was not found!";
					$conn->close();
				}
			}
			
		}
	}

?>

<form method="post" action="">
	<input type="text" name="username_email" placeholder="Username or Email">
	<br>
	<input type="password" name="password" placeholder="Password">
	<br>
	<button type="submit" name="login">Login</button>
	<button type="submit" name="signup">Signup</button>
</form>