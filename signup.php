<?php
	session_start();
	if(strlen(trim($_POST["susername"]))!=0)//Takes non-empty usernames only
	{
		$default = 0;
		$connection = mysql_connect("localhost",'root','');
		$db = mysql_select_db("investokrypt",$connection);
		if(!empty($_POST["susername"]))
			$user = $_POST["susername"];
		if(!empty($_POST["spwd"]))
			$pwd = $_POST["spwd"];
		if(!empty($_POST["spwd2"]))
			$pwd2 = $_POST["spwd2"];
		if(!empty($_POST["email"]))
			$email = $_POST["email"];
		if($pwd == $pwd2)
		{
			if(!empty($_FILES['profileImg']['name']))
			{
				$target_dir = "profileImages/";
				$target_file = $target_dir . $user. "_sp" . $_FILES["profileImg"]["name"];
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				//TYPE CHECKING
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) 
				{
					echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed as Profile Image.')</script>";
					echo "<script>window.location = 'index.php'</script>";
					$uploadOk = 0;
				}
				//PRE-EXISTENCE CHECK
				if (file_exists($target_file)) 
				{
					echo "<script>alert('Some error occurred. Try changing the Image name')</script>";
					echo "<script>window.location = 'index.php'</script>";
					$uploadOk = 0;
				}
			}
			else
			{
				$target_file = "profileImages/default.png";
				$uploadOk = 1;
				$default = 1;
			}
				if($uploadOk == 1)
				{
					//STOPS CREATION OF SIMILAR USERNAMES/EMAILS
					$cquery = mysql_query("select * from users");
					while($row = mysql_fetch_array($cquery))
					{
						if($row["username"]==$user)
						{
							echo "<script>alert('An account already exists with username $user')</script>";
							echo "<script>window.location = 'index.php';</script>";
							$uploadOk = 0;
						}
						if($row["email"]==$email)
						{
							echo "<script>alert('$email is already associated with another account')</script>";
							echo "<script>window.location = 'index.php';</script>";
							$uploadOk = 0;
						}
					}
					if($default == 1 && $uploadOk == 1)
					{
						$pwd = password_hash($pwd, PASSWORD_DEFAULT);
						$query = mysql_query("insert into users (username, pwd, netamount, profileImg, email) values ('$user', '$pwd', 1000000, '$target_file', '$email')") or die("Some error occured. Try again.");
						$_SESSION["username"] = $user;
						$_SESSION["logged"] = true;
						echo "Account created successfully.";
						header( "Refresh:0; url=investment.php");
					}
					else if($default!=1 && move_uploaded_file($_FILES["profileImg"]["tmp_name"], $target_file)&&$uploadOk == 1) 
					{	
						$pwd = password_hash($pwd, PASSWORD_DEFAULT);
						$query = mysql_query("insert into users (username, pwd, netamount, profileImg, email) values ('$user', '$pwd', 1000000, '$target_file', '$email')") or die("Some error occured. Try again.");
						$_SESSION["username"] = $user;
						$_SESSION["logged"] = true;
						echo "Account created successfully.";
						header( "Refresh:1; url=investment.php");
					}	
					else //Error occurred in Photo upload. Generally due to file size.
					{
						echo "<script>alert('Error occurred while uploading profile photo. Try reducing the file size')</script>";
						echo "<script>window.location = 'index.php'</script>";
					}
				}
		} 
			
		//When passwords don't match
		else
		{
			echo "<script>alert('Passwords do not match. Try Again')</script>";
			echo "<script>window.location = 'index.php'</script>";
		}
	}
	else 
	{
		echo "<script>alert('Invalid Username')</script>";
		echo "<script>window.location = 'index.php'</script>";
	}	
?>