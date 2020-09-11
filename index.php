<?php
require_once 'pdo.php';
if (isset($_POST['signin'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$sql = "SELECT * FROM login WHERE (username=:user) AND (password=:pass)";
	$query = $conn->prepare($sql);
	$query->bindparam(':user',$username);
	$query->bindparam(':pass',$password);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);
	if ($query->rowcount() > 0) {
		echo $result['email'];
	}
	else {
		echo "Incorrect login credentials";
	}
}

if (isset($_POST['signup'])) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password= $_POST['password'];
	$confPass = $_POST['confPassword'];
	$error = false;

	$sql = "SELECT * FROM login WHERE (username=:user)";
	$query = $conn->prepare($sql);
	$query->bindparam(':user', $username);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);
	if ($query->rowcount() > 0) {
		echo "Username already taken";
		$error = true;
	}

	$sql = "SELECT * FROM login WHERE (email=:email)";
	$query = $conn->prepare($sql);
	$query->bindparam(':email', $email);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);
	if ($query->rowcount() > 0) {
		echo "EmailID already registered";
		$error = true;
	}

	if ($password != $confPass) {
		echo "Passwords do not match";
		$error = true;
	}

	if (!$error) {
		$sql = "INSERT INTO login (name, email, username, password) VALUES
		 (:name, :email, :user, :pass)";
		$query = $conn->prepare($sql);
		$query->bindparam(':name', $name);
		$query->bindparam(':email', $email);
		$query->bindparam(':user', $username);
		$query->bindparam(':pass', $password);
		$query->execute();
	}
}
?>

<!DOCTYPE html>
<HEAD>
	<TITLE>SECURE US Sign in/Sign up Page</TITLE>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/index.css">
</HEAD>
<BODY>
	<div class="hero">
		<div class="main-box">
			<div class="form-box" id="formbox">
				<form class="signin-group" id="signinform" method="post">
					<h1 id="signintosecureus">Sign in to <img src="images/logo1.png"></h1>
					<input type="text" class="signininput-field" placeholder="ENTER USER ID"
					 name="username" required><br>

					<input type="password" class="signininput-field" placeholder="ENTER MASTER PASSWORD"
					 name="password" required><br>
					<input type="checkbox" class="signincheck-box"><span class="signinspan">Remember Master Password?<br></span>

					<div class="signin-alert-box" id="alertbox1">
			 			<strong>Error: </strong>The <strong>Credentials</strong> you've entered <strong>doesn't match any account</strong>.<br>
			 		</div>

					<button type="submit" class="signin-btn" name="signin">SIGN IN</button><br>
					<a href="recover.html">Need help with Sign in?</a>
				</form>
				<form class="signup-group" id="signupform" method="post">
					<h1 id="signupinsecureus">Sign up in <img src="images/logo1.png"></h1>
					<input type="text" class="signupinput-field" placeholder="ENTER YOUR NAME"
					 name="name" required><br>
					<input type="email" class="signupinput-field" placeholder="ENTER YOUR EMAIL ID"
					 name="email" required><br>

					<div class="email-at-box" id="alertbox2">
 			 			<div class="triangle"></div>
 			 			<div class="msg-box"><strong>Error: </strong>The <strong>Email ID</strong> you've entered is <strong>already registered</strong>.
 						</div><br>
 			 		</div>

					<input type="text" class="signupinput-field" placeholder="ENTER A USERNAME"
					 name="username" required><br>

					 <div class="userid-at-box" id="alertbox3">
  			 			<div class="triangle"></div>
  			 			<div class="msg-box"><strong>Error: </strong>The <strong>User ID</strong> you've entered is <strong>already taken</strong>.
  						</div><br>
  			 		</div>

				 	<input type="password" class="signupinput-field" placeholder="SETUP NEW MASTER PASSWORD"
					 name="password" required><br>

					 <div class="password-strength-alert-box" id="alertbox4">
  			 			<div class="triangle"></div>
  			 			<div class="password-strength-msg-box"><strong>Error: </strong>The <strong>Password</strong> you've entered is
								<strong>weak</strong>. (Put atleast one uppercase, one lowercase, one
								special character, one digit, with minimum of total 8 characters)
  						</div><br>
  			 		</div>

				 	<input type="password" class="signupinput-field" placeholder="CONFIRM NEW MASTER PASSWORD"
					 name = "confPassword" required><br>

					 <div class="confirm-password-alert-box" id="alertbox5">
  			 			<div class="triangle"></div>
  			 			<div class="msg-box"><strong>Error: </strong>The <strong>Passwords</strong> you've entered <strong>doesn't match</strong>  with each other.
  						</div><br>
  			 		</div>

					<input type="checkbox" class="signupcheck-box" required><span class="signupspan">
						I agree to the terms and conditions<br></span>
					<div class="signup-success-box" id="successbox1">
				 		<strong>Congratulations! </strong>Your <strong>account</strong> is <strong>successfully registered</strong>.<br>
				 	</div>
					<button type="submit" id="sbtn" class="signup-btn" name="signup">SIGN UP</button><br>
			</form>
			</div>
			<div class="toggle-box" id="hfbox">
				<div class="signuptoggle-group" id="sutg">
					<h1 id="hellofriend">Hello, Friend!</h1><br>
					<p id="startthejourney">Enter your personal details<br>and start journey with us!</p><br>
					<button type="submit" class="signupage-btn" onclick="signup()">SIGN UP</button><br>
				</div>
				<div class="signintoggle-group" id="sitg">
					<h1 id="welcomeback">Welcome Back!</h1><br>
					<p id="pleaselogin">To keep connected with us please<br>login with your personal info</p><br>
					<button type="submit" class="signinpage-btn" onclick="signin()">SIGN IN</button><br>
				</div>
			</div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			var x = document.getElementById("signinform");
			var y = document.getElementById("signupform");
			var p = document.getElementById("formbox");
			var q = document.getElementById("hfbox");
			var r = document.getElementById("sutg");
			var s = document.getElementById("sitg");
			function signup(){
				x.style.left = "-475px";
				y.style.left = "125px";
				p.style.left = "400px";
				p.style.borderRadius = "0 8px 8px 0";
				q.style.left = "-300px";
				q.style.borderRadius = "8px 0 0 8px";
				r.style.left = "600px";
				s.style.left = "150px";
			}
			function signin(){
				x.style.left = "125px";
				y.style.left = "725px";
				p.style.left = "0px";
				p.style.borderRadius = "8px 0 0 8px";
				q.style.left = "700px";
				q.style.borderRadius = "0 8px 8px 0";
				r.style.left = "-150px";
				s.style.left = "-600px";
			}
			$(function(){
				setTimeout(function(){$("#alertbox2").fadeOut()},5000);
				setTimeout(function(){$("#alertbox3").fadeOut()},5000);
				setTimeout(function(){$("#alertbox4").fadeOut()},10000);
				setTimeout(function(){$("#alertbox5").fadeOut()},5000);
			})
		</script>

	</div>
</BODY>
</HTML>
