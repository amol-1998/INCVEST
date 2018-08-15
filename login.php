<?php
	session_start();
	$exists = 0;
	$connection = mysql_connect('localhost','root','');
	$db = mysql_select_db('investokrypt',$connection);
	if(!empty($_POST["username"]))
		$user = $_POST["username"];
	if(!empty($_POST["pwd"]))
		$pwd = $_POST["pwd"];
	if(strlen(trim($user))!=0)
		{
			$query = mysql_query("select * from Users", $connection);
			while($row = mysql_fetch_array($query))
			{
				if($row['username'] == $user)
				{
					if(password_verify($pwd, $row['pwd']))
					{
						$_SESSION["username"] = $user;
						$_SESSION["logged"] = true;
						$exists = 1;
						echo "Successful Login.";
						header( "Refresh:0; url=investment.php");
					}
					else{
						$exists = 1;
						echo "<script>alert('Incorrect Password')</script>";
						session_unset();
						session_destroy();
						echo "<script>window.location = 'index.php';</script>";
					}		
				}
			}
			if($exists == 0)
			{
				session_unset();
				session_destroy();
				echo "<script>alert('No such account exists')</script>";
				echo "<script>window.location = 'index.php';</script>";
			}
		}
		else 
		{
			session_unset();
			session_destroy();
			echo "<script>alert('Incorrect Username/Password')</script>";
			echo "<script>window.location = 'index.php';</script>";
		}
?>