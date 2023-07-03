<?php
session_start();

/*
Mga kulang nalang is:
-Design
-Checking for duplicate values in the database
-File upload(yung profile)
-Pag display ng errors

--July 2--
*/
if (isset($_POST['signup'])) {
    $errors = array();

    // Setting variables
    // strip_tags = tinatanggal mga <>
    // sha1 = encrypts password
    $fname = strip_tags($_POST['fname']);
    $minitial = strip_tags($_POST['minitial']);
    $lname = strip_tags($_POST['lname']);
    $email = $_POST['email'];
    $password = sha1(strip_tags($_POST['password']));
    $repassword = sha1(strip_tags($_POST['repassword']));
    $username = strip_tags($_POST['username']);
    $bio = strip_tags($_POST['bio']);
    $studNumber = strip_tags($_POST['studNum']);

    
    $targetDir = "uploadpic"; // Directory to store uploaded files
    $targetFile = $targetDir . basename($_FILES["profile"]["name"]); // File path of the uploaded file
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is a valid image
    if (isset($_POST["signup"])) {
        $check = getimagesize($_FILES["profile"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $errors["profile"] = "File is not an image.";
            $uploadOk = 0;
        }
    }

    
    if (file_exists($targetFile)) {
        $errors["profile"] = "File already exists.";
        $uploadOk = 0;
    }

   
    if ($_FILES["profile"]["size"] > 500000) {
        $errors["profile"] = "File size is too large.";
        $uploadOk = 0;
    }

    // Allow only specific file formats (you can modify this list)
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        $errors["profile"] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Chinecheck kung goods yung upload, pag oo, pupunta siya sa directory (in my case yung "uploadpic" under $targetdir line 30)
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
            echo "File uploaded successfully.";
        } else {
            $errors["profile"] = "Error uploading file.";
        }
    }

    // Ginawa kong array yung mga input para icheck sila isa isa for errors
    $inputs = array(
        "fname" => $fname,
        "minitial" => $minitial,
        "lname" => $lname,
        "email" => $email,
        "password" => $password,
        "repassword" => $repassword,
        "username" => $username,
        "bio" => $bio,
        "studNumber" => $studNumber,
        "profile" => $targetFile
    );

    // Check for empty inputs (blank spaces)
    foreach ($inputs as $key => $value) {
        if ($key == "password") {
            // Checks if re-entered password did not match password
            if ($password != $repassword) {
                $errors["password"] = "Your passwords do not match!";
            }
        } else {
            if (strlen(trim($value)) == 0) {
                // Checks if input is whitespace/blank spaces
                $errors[$key] = "Input can't be empty.";
            }
        }
    }

    // Preparing database connection
    $hostname = "localhost"; // Default value
    $userdb = "root"; // Default value
    $passdb = ""; // Password ng database, wala naman sakin kaya empty lang siya
    $dbname = "techbulletin"; // Dito papalitan niyo siya ng name ng db niyo, sakin kasi techbulletin name ng database ko sa laptop ko

    // Connecting to the database
    $conn = new mysqli($hostname, $userdb, $passdb, $dbname);

    // Checking if the connection found the database
    if ($conn->connect_errno) {
        echo "Failed to connect to the Database(baka maling database name kay dbname).";
        $conn->close();
    } else {
        // If the errors array is empty, then insert the values
        if (empty($errors)) {
            $user_sql = "INSERT INTO user(user_fname,user_minitial,user_lname,user_email,user_password,user_username,user_bio,user_studnumber,user_profile)
						VALUES('$fname','$minitial','$lname','$email','$password','$username','$bio','$studNumber','$targetFile')";

            /*
            SQL Statement yung nasa taas and ang syntax niya is:
            INSERT INTO table_name(column,column,column...)
            VALUES(variable,variable,variable...)
            */
            $user_result = $conn->query($user_sql); // Inserts the values into the database
            $conn->close(); // Closes the database connection
            echo "Successfully registered. You can now proceed to login.";
        } else {
            // If the errors array has values, display them one by one
            foreach ($errors as $value) {
                echo $value;
            }
        }
    }
} else {
    // Setting default values for all variables
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
<form method="post" action="" enctype="multipart/form-data">
    Enter First Name:
    <input type="text" name="fname" placeholder="First Name" required><br>
    Enter Middle Initial:
    <input type="text" name="minitial" placeholder="Middle Initial" required><br>
    Enter Last Name:
    <input type="text" name="lname" placeholder="Last Name" required><br>
    Enter Email:
    <input type="email" name="email" placeholder="Email" required><br>
    Enter Password:
    <input type="password" name="password" placeholder="Password" required><br>
    Re-enter Password:
    <input type="password" name="repassword" placeholder="Re-enter Password" required><br>
    Enter Username:
    <input type="text" name="username" placeholder="Username" required><br>
    Enter Bio:
    <input type="text" name="bio" placeholder="Bio" required><br>
    Enter Student Number:
    <input type="text" name="studNum" placeholder="Student Number" required><br>
    Upload your picture here:
    <input type="file" name="profile" required><br>
    <button type="submit" name="signup">Signup</button>
</form>
</body>
</html>
