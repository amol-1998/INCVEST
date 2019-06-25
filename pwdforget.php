<?php
	if(isset($_POST["submit"])){
$connection = mysql_connect('localhost',"root","");
$db = mysql_select_db('investokrypt',$connection);		
$query = mysql_query("select * from users where username = $user");
while($row = mysql_fetch_array($query))
{
	if($row['username'] == $user)
	{
	}
}
		
$user = $_POST["user"];
require 'PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer();
$mail->isSMTP();
$mail->debug = 1;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = "465";
$mail->isHTML();
$mail->Username = "arunkumar123@tutanota.com";
$mail->Password = "Professionalskit";
$mail->SetFrom("noreplyincvest@gmail.com");
$mail->Subject = "Query for Shipperz";

$mail->Body = "<b>Query:</b><br/>" . "<i>" . $query . "</i>" . " <br/><br/>Reply to: <br/>" ."<b>". $sn ." ". $en . "</b>" . "<br/>" . $email;
$mail->AddAddress("amolpriyavardhan1@gmail.com");
$response = $mail->Send();
if($response != 1)
{
	echo "<script>alert('Message couldn\'t be received. Please try again!!!');</script>"; //alert NOT WORKING
	echo "<script>window.location = 'contact.html';</script>";
} 
else
{
	echo "<script>alert('Message received successfully. We\'ll get back to you soon.');</script>"; //alert NOT WORKING
	echo "<script>window.location = 'contact.html';</script>";
}
}
?>
<head>
	<title>Forgot Password - INCVEST</title>
	<link rel = "stylesheet" href = "fpwd.css" type = "text/css">
</head>
<body>
	<div id="fpbox">
		<h1>Forgot Password</h1>
		<form action="pwdforget.php" method="post" autocomplete="off">
			<input type="text" placeholder="Enter your Username" name="user">
			<input type="submit" value="Submit" name="submit">
		</form>
	</div>

</body>