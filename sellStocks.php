<?php 
	/*Till now we don't keep track of the records which are deleted*/
	session_start();
	if(isset($_POST["rstocks"]) && strlen(trim($_POST["rstocks"]))!=0 && isset($_POST["rqty"]) && isset($_POST["sellp"]) && strlen(trim($_POST["sellp"]))!=0)
	{
		$cc = $_POST["rstocks"];
		$qty = $_POST["rqty"];
		$sp = $_POST["sellp"];
		$stockPrice = (float)substr($sp,1);
	}
	else
	{
		echo "<script>alert('Void Selling. Try again. !!!')</script>";
		echo "<script>window.location = 'investment.php';</script>";
		die();
	}
	if(isset($_SESSION["username"]))
	{
		$exists = 0;
		$user = $_SESSION["username"];
		$connection = mysql_connect("localhost","root","");
		$db = mysql_select_db("investokrypt",$connection);
		$query = mysql_query("select * from stocks where username='$user'");
		$query1 = mysql_query("select * from users where username='$user'");
				
		while($row = mysql_fetch_array($query))
		{
			if($row["name"]==$cc && $row["username"]==$user)
			{
				$exists = 1;
				$quantity = (int)$row["qty"];
				if($qty > $quantity)
				{
					echo "<script>alert('You have only $quantity shares in $cc !!!')</script>";
					echo "<script>window.location = 'investment.php';</script>";
					die();
				}
				else
				{
					$net_spent = $row["spentamt"];
					$profit = (float)$row["profit"];
					$StockSellingPrice = (float)$stockPrice * (float)$qty;
					break;
				}
			}
		}
		while($row = mysql_fetch_array($query1))
		{
			if($row["username"]==$user)
			{
				$remAmt = $row["netamount"];
			}
		}
		if($exists==1)
		{
			$final_amt = $remAmt + $StockSellingPrice; //Updating the netamount by adding the stock price.
			$query3 = mysql_query("update users set netamount='$final_amt' where username='$user'",$connection);
			if($qty == $quantity)
			{
				$query4 = mysql_query("delete from stocks where name='$cc' and username='$user'");
				$net_profit = $StockSellingPrice - ($qty/$quantity)*$net_spent;
			}
			else 
			{
				$rem_stocks = $quantity - $qty;
				$net_profit = $StockSellingPrice - ($qty/$quantity)*$net_spent;//the -ve term indicate the avg cost of purchasing that many stocks.
				$profit += $net_profit;
				$net_spent = $net_spent - ($qty/$quantity)*$net_spent;
				mysql_query("update stocks set qty='$rem_stocks', profit='$profit', spentamt='$net_spent' where name='$cc' and username='$user'");
			}
			if($net_profit<0)
				echo "<script>alert('$qty Coins of $cc worth $$StockSellingPrice sold with a LOSS of $" . abs($net_profit) . "')</script>";
			else
				echo "<script>alert('$qty shares of $cc worth $$StockSellingPrice sold with a PROFIT of $" . abs($net_profit) . "')</script>";
			echo "<script>window.location = 'investment.php';</script>";
		}
		else
		{
			echo "<script>alert('No $cc Coins found!!!')</script>";
			echo "<script>window.location = 'investment.php';</script>";
		}
		
	}
	else
	{
		echo "<script>alert('Please Login.')</script>";
		echo "<script>window.location = 'index.php';</script>";
	}
?>