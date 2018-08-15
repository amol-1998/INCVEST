<?php 
	session_start();
	if(isset($_SESSION["username"]) && ($_SESSION["logged"]==true))
	{
		$user = $_SESSION["username"];
		echo "<script>alert('Heyy $user, you have successfully logged In. To go back, Logout')</script>";
		echo "<script>window.location = 'investment.php';</script>";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="login.css" type="text/css">
	<link rel="stylesheet" href="cropper.css" type="text/css">
	<script src="cropper.js">
		
	</script>
	
	<link rel="shortcut-icon" href="favicon.ico">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
	<script>
		function closandopn(id1,id2)
		{
			id1.style.display = "none";
			id2.style.display = "block";
		}
		//Changes the value displayed on upload button
		function change(id)
		{
			var fullPath = document.getElementById('profileImg').value;
			var filename = fullPath.replace(/^.*[\\\/]/, '');
			id.innerHTML = filename;	
		}
		//Add the custom warning message for length < 4 characters
		document.addEventListener("DOMContentLoaded", function() {
			var elements = document.getElementsByClassName("min4");
			for (var i = 0; i < elements.length; i++) {
				elements[i].oninvalid = function(e) {
					e.target.setCustomValidity("");
					if (!e.target.validity.valid) {
						e.target.setCustomValidity("Minimum 4 characters");
					}
				};
				elements[i].oninput = function(e) {
					e.target.setCustomValidity("");
				};
			}
		})
		
	</script>
	<script>
		$(document).ready(function(){
			$('.un').keypress(function(e){
				if((e.which>=65&&e.which<=90) || (e.which>=97&&e.which<=122) || (e.which>=48&&e.which<=57) || (e.which==8) || (e.which==95))
				{
				}
				else {
					return false;
				}
			});
		});
	</script>
</head>
<body>
	<!--
	<section id="preview">
		<img src="logo.png" id="imaag">
		<span style="font-size:100px; color:green;">X</span>
		<input type="submit" value="Create Account"><br/>
		<p id="note"><a href="Javascript:void(0)" onclick="closandopn(preview, loginModal)">OPEN</a></p>
	</section>
	-->
	<section id="loginModal">
		<div class="amodal">
			<h1>LOGIN</h1>
			<form action="login.php" method="post" autocomplete = "off">
				<input type="text" name="username" placeholder="Username" pattern=".{4,}" class="min4" required maxlength="18"><!--<span id="lc" style="display:none">Only a-z, A-Z, 0-9 and _ are allowed</span>--><br/>
				<input type="password" name="pwd" placeholder="Password" pattern=".{4,}" class="min4" maxlength="20" required><br/>
				<p><a href="#">Forgot Password?</a></p>
				<input type="submit" value="Login"><br/>
				<p id="note">Don't have an account?<a href="Javascript:void(0)" onclick="closandopn(loginModal,signupModal)"> Sign up</a></p>
			</form>
		</div>
	</section>
		<section id="signupModal">
		<div class="amodal">
			<h1>SIGNUP</h1>
			<form action="signup.php" method="post" autocomplete="off" enctype="multipart/form-data">
				<input type="text" id="susername" pattern=".{4,}" class="min4" name="susername" placeholder="Username" required maxlength="18"><br/>
				<input type="email" name="email" placeholder="Email Address" maxlength="50">
				<input type="password" name="spwd" pattern=".{4,}" class="min4" placeholder="Password" maxlength="18" required><br/>
				<input type="password" name="spwd2" pattern=".{4,}" class="min4" placeholder="Confirm Password" maxlength="20"><br/>
				<input type="file" name="profileImg" id="profileImg" style="display:none;" onchange="change(lab);"><br/>
				<label for="profileImg" id="lab">Upload Profile Photo</label>
				<input type="submit" value="Create Account"><br/>
				<p id="note">Already have an account?<a href="Javascript:void(0)" onclick="closandopn(signupModal,loginModal)"> Login</a></p>
			</form>
		</div>
	</section>
	
</body>
</html>
