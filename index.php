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
	<title>IncVest - Home</title>
	<meta charset="utf-8">
	<meta name="author" content="amol priyavardhan, Amol">
	<meta name="keyword" content="Cryptocurrency tracker, cryptocurency register, awareness, cryptocurency">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<link rel="shortcut icon" href="icon1.ico"/>
	<link rel="stylesheet" href="home.css" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

	<script>
		//The ham icon and its function
		$(document).ready(function(){
			var degree1 = 0;
			var degree2 = 90;
			$("#hamc").click(function(){
				$("#list").toggle('slide',{direction: 'right'}, 500);
				degree1 += 45;
				degree2 += 45;
				$("#l1").css('transform', 'rotate(' + degree1 + 'deg)');
				$("#l2").css("transform","rotate(" + degree2 + "deg)");
			});
		});
	</script>
	<script>
		//For changing the headers
			$(document).ready(function(){
				var heads = $(".heads");
				var hnum = -1;
				function showNew(){
					hnum++;
					heads.eq(hnum % heads.length).fadeIn(1000).delay(10000).fadeOut(1000,showNew);
				}
				showNew();
			});
	</script>
	<script>
		//For changing the paragraphs
			$(document).ready(function(){
				var paras = $(".paras");
				var pnum = -1;
				function showNew(){
					pnum++;
					paras.eq(pnum % paras.length).fadeIn(1000).delay(10000).fadeOut(1000,showNew);
				}
				showNew();
			});
	</script>
	<script type="text/javascript">
	//for scrolling to login/signup
	function gotodiv(id)
	{
		var xpos = 0;
		var ypos = parseInt(document.documentElement.scrollTop);
		var target = parseInt(id.offsetTop);
		var interval = 5;
		setInterval(changes,1);
		function changes(){
			if(ypos == target)
				clearInterval();
			else if(ypos+interval >= target)
			{
				
				ypos = target;
				window.scrollTo(xpos,ypos);
				clearInterval();
			}
			else
			{
				ypos += interval;
				window.scrollTo(xpos,ypos);
			}
		}
	}
	</script>
	
	<script>
		//Beneath is OLD
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
		<div class="fps" id="hp">
			<header>
				<div class="container">
					<div id="logoc">
						<img src="a.png" alt="Logo of IncVest" id="logo"/>
					</div>
					<div id="hamr">
						<div id="hamc">
							<div id="l1" class="lines"></div>
							<div id="l2" class="lines"></div>
						</div>
					</div>
				</div>
				<div >
						<ul id="list">
							<li><a href="javascript:void(0);" onclick="gotodiv(loginModal);">Login</a></li>
							<li><a href="JavaScript:void(0);" onclick="gotodiv(signupModal);">Signup</a></li>
							<li><a href="jointeam.html">Join Team</a></li>
							<li><a href="#">Follow Us</a></li>
							<li><a href="tnc.html">TnC</a></li>
						</ul>
				</div>
			</header>
			<section id="main" class="container">
				<div>
					<h1 class="heads">Keep track of your Earned Cryptocurencies</h1>
					<h1 class="heads" style="display:none;">Or, learn about the Market of Cryptocurencies</h1>
					<h1 class="heads" style="display:none;">All by Live Monitoring the market with our platform</h1>
				</div>
				<div>
					<p class="paras">Often situations may arise when you just want to get a quick overview of your Coins and know their Exact Market value all in one place, well we are here for you</p>
					<p class="paras" style="display:none;" >Or, you may a newbie in the field of Cyrptocurrencies and want to learn about how the Coin prices change almost every hour, well you can now practially learn by investing Virtual Money </p>
					<p class="paras" style="display:none;">We provide, to our users, Real Time Prices of more than 1500 Cryptocurencies and a Platfrom where Users can learn about the UPs and DOWNs in Cryptocurrency Market</p>
				</div>
			</section>
			<div id="lm" class="container">
				<a href="about.html">Learn more</a>
			</div>
		</div>
		<hr/>
		<section id="loginModal" class="fps">
			<div class="container">
				<h1>LOGIN</h1>
				<ul>
					<li><a href="javascript:void(0)"><i class="fa fa-google" style="color:#db3236;"></i> Login with Google</a></li>
					<li style="margin-left:20%;"><a href="javascript:void(0)"><i class="fa fa-facebook" style="color:#3B5998;"></i> Login with Facebook</a></li>
				</ul>
				<span>OR</span>
				<form action="login.php" method="post" autocomplete = "off">
					<input type="text" name="username" placeholder="Username" pattern=".{4,}" class="min4" required maxlength="18">
					<input type="password" name="pwd" placeholder="Password" pattern=".{4,}" class="min4" maxlength="20" required>
					<p><a href="pwdforget.php">Forgot Password?</a></p>
					<input type="submit" value="Login"><br/>
				</form>
			</div>
		</section>
		<hr/>
	<section id="signupModal" class="fps">
		<div class="container">
			<h1>SIGNUP</h1>
			<form action="signup.php" method="post" autocomplete="off" enctype="multipart/form-data">
				<input type="text" id="susername" pattern=".{4,}" class="min4" name="susername" placeholder="Username" required maxlength="18">
				<input type="email" name="email" placeholder="Email Address" maxlength="50">
				<input type="password" name="spwd" pattern=".{4,}" class="min4" placeholder="Password" maxlength="18" required>
				<input type="password" name="spwd2" pattern=".{4,}" class="min4" placeholder="Confirm Password" maxlength="20">
				<input type="file" name="profileImg" id="profileImg" style="display:none;" onchange="change(lab);">
				<label for="profileImg" id="lab">Upload Profile Photo</label>
				<input type="submit" value="Create Account"><br/>
			</form>
		</div>
	</section>
		
</body>
</html>
