<?php
		session_start();
		if(isset($_SESSION['username']))
		{
			$previnvest = 0;
			$error = 0;
			$user = $_SESSION["username"];
			if(isset($_POST['bname']))
				$name = $_POST['bname'];
			if(!empty($_POST['bprice']))
			{
				$price = $_POST['bprice'];
				$price = substr($price,1);
			}
			else 
				$error = 1;
			
			if(!empty($_POST['bqty']) && strlen(trim($_POST["bqty"]))!=0)
				$qty = $_POST['bqty'];
			else 
				$error = 1;

			if($error == 0)
				{
					$connection = mysql_connect('localhost','root','') or die("Some error occurred. Try again");
					$db = mysql_select_db('investokrypt',$connection) or die("Some error occurred. Try again");
					$query = mysql_query("select * from Users", $connection) or die("Some error occurred. Try again");
					while($row = mysql_fetch_array($query))
					{
						if($row['username'] == $user)
						{
							$amt = $row['netamount'];	
							break;
						}
					}
					$query1 = mysql_query("select * from stocks where name = '$name'",$connection) or die("Some error occurred. Try again");//Checks whether previous investment exists
					while($row = mysql_fetch_array($query1))
					{
						if($row['username'] == $user)
						{
							$previnvest = 1;
							$init_price = $row["price"];
							$init_qty = $row["qty"];
							$init_spent = $row["spentamt"];
						}
					}
					$total = (float)$price * (float)$qty;
					if($total>$amt)
					{
						echo "<script>alert('Heyy ". $user . ", your account balance is not Sufficient.')</script>";
						echo "<script>window.location = 'investment.php';</script>";
					}
					else 
					{	
						$net = $amt - $total;
						if($previnvest == 1)
						{
							$total_stocks = $init_qty + $qty;
							$total_spent = $init_spent + $total;
							if($init_price == $price)
								mysql_query("update stocks set qty = '$total_stocks', spentamt='$total_spent' where username='$user' and name='$name'");
							else mysql_query("update stocks set qty = '$total_stocks', spentamt='$total_spent', price='$price' where username='$user' and name='$name'");
							mysql_query("update users set netamount='$net' where username='$user'", $connection);
							echo "<script>alert('$$total are successfully invested in $name.')</script>";
							echo "<script>window.location = 'investment.php';</script>";
						}
						else 
						{
							$insert_query = mysql_query("insert into stocks (username, name, price, qty, spentamt, profit) values ('$user', '$name', '$price', '$qty', '$total','0')");
							mysql_query("update users set netamount='$net' where username='$user'", $connection);
							echo "<script>alert('$$total are successfully invested in $name.')</script>";
							echo "<script>window.location = 'investment.php';</script>";
						}
					}
				}
			else
				{
						echo "<script>alert('Invalid Investment.')</script>";
						echo "<script>window.location = 'investment.php';</script>";
				}
		}
		else 
		{
				echo "<script>alert('Please Login.')</script>";
				echo "<script>window.location = 'index.php';</script>";
		}
?>
