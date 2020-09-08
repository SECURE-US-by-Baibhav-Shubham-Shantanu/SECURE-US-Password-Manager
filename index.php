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
	<link rel="stylesheet" type="text/css" href="Index.css">
</HEAD>
<BODY>
	<div class="hero">
		<div class="main-box">
			<div class="form-box" id="formbox">
				<form class="signin-group" id="signinform"> 
					<h1 id="signintosecureus">Sign in to <img src="logo1.png"></h1>
					<input type="text" class="signininput-field" placeholder="ENTER USER ID" required><br>
			 		<input type="password" class="signininput-field" placeholder="ENTER MASTER PASSWORD" required><br>
					<input type="checkbox" class="signincheck-box"><span class="signinspan">Remember Master Password?<br></span>
					<button type="submit" class="signin-btn">SIGN IN</button><br>
					<a href="">Need help with Sign in?</a>
				</form>	
				<form class="signup-group" id="signupform"> 
					<h1 id="signupinsecureus">Sign up in <img src="logo1.png"></h1>
					<input type="text" class="signupinput-field" placeholder="ENTER YOUR NAME" required><br>
					<input type="email" class="signupinput-field" placeholder="ENTER YOUR EMAIL ID" required><br>
					<input type="text" class="signupinput-field" placeholder="ENTER A USERNAME" required><br>
			 		<input type="password" class="signupinput-field" placeholder="ENTER NEW MASTER PASSWORD" required><br>
			 		<input type="password" class="signupinput-field" placeholder="CONFIRM NEW MASTER PASSWORD" required><br>
					<input type="checkbox" class="signupcheck-box" required><span class="signupspan">I agree to the terms and conditions<br></span>
					<button type="submit" id="sbtn" class="signup-btn">SIGN UP</button><br>
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
		</script>
	</div>
</BODY>
</HTML>
