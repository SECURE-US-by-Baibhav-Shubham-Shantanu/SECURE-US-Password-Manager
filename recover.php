<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require_once 'pdo.php';

$emailErr = false;
$OTPErr = false;

if (isset($_POST['sendOTP'])) {
	$email = $_POST['email'];

	$sql = "SELECT * FROM login WHERE (email=:email)";
	$query = $conn->prepare($sql);
	$query->bindparam(':email',$email);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);

	if ($query->rowcount() > 0) {
		$mail = new PHPMailer(true);
		$otp = rand(100000, 999999);

		$mail->SMTPDebug = 0;
		$mail->isSMTP();
		$mail->Host       = 'smtp.gmail.com;';
		$mail->SMTPAuth   = true;
		$mail->Username   = 'emailforphp53@gmail.com';
		$mail->Password   = 'dummy.account';
		$mail->SMTPSecure = 'tls';
		$mail->Port       = 587;

		$mail->setFrom('emailforphp53@gmail.com', 'Secure Us');
		$mail->addAddress($result['email']);

		$mail->isHTML(true);
		$mail->Subject = 'OTP for login';
		$mail->Body    = "Your One Time Password is <b>".$otp."</b>";
		$mail->AltBody = 'Body in plain text for non-HTML mail clients';

		try {
			$mail->send();
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
	else {
		$emailErr = true;
	}
}
?>

<!DOCTYPE html>
<HEAD>
	<TITLE>SECURE US Account Recovery Page</TITLE>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/recover.css">
</HEAD>
<BODY>
	<div class="hero">
			<div class="recover-box" id="recoverbox" method="post">
        <h1 id="secureusaccountrecovery"><img src="images/logo1.png"> Account Recovery</h1>
				<form class="sendotp-group" id="sendotpgroup">
					<input type="text" class="input-field" placeholder="ENTER YOUR EMAIL ID"
					 name="email" required><br>

					<?php if ($emailErr): ?>
						<div class="alert-box" id="alertbox1">
				 			<div class="triangle"></div>
				 			<div class="msg-box"><strong>Error:</strong> The <strong>Email ID</strong> you've entered <strong>doesn't match any account</strong>.
							</div>
				 		</div>
					<?php endif; ?>

					<button type="submit" class="submit-btn" name="sendOTP" onclick="movetoenterotp()">SEND OTP</button>
				</form><br>
				<form class="enterotp-group" id="enterotpgroup">
					<?php if (!$emailErr): ?>
						<div class="OTPsent-box" id="alertbox2">
						 An <strong>OTP</strong> has been <strong>sent</strong> to your <strong>Email ID</strong>.
					 	</div>
					<?php endif; ?>

					<input type="password" class="input-field" placeholder="ENTER SENT OTP" name="otp" required><br>

					<?php if (1): ?>
						<div class="alert-box" id="alertbox3">
	 			 			<div class="triangle"></div>
	 			 			<div class="msg-box"><strong>Error:</strong> The <strong>OTP</strong> you've entered is <strong>incorrect</strong>.
	 						</div>
	 			 		</div>
					<?php endif; ?>

					<button type="submit" class="submit-btn" onclick="movetosetpassword()">SUBMIT OTP</button>
					<button type="button" class="submit-btn">RESEND OTP</button>
				</form><br>

				<form class="setpassword-group" id="setpasswordgroup">
					<input type="password" class="setpassword-field" placeholder="SETUP NEW MASTER PASSWORD"
					 name="password" required><br>

					<div class="password-strength-alert-box" id="alertbox4">
						<div class="triangle"></div>
						<div class="password-strength-msg-box"><strong>Error: </strong>The
						  <strong>Password</strong> you've entered is <strong>weak</strong>.
							(Put atleast one uppercase, one lowercase, one special character,
							one digit, with minimum of total 8 characters)
						</div>
					</div>

					<input type="password" class="setpassword-field" placeholder="CONFIRM NEW MASTER PASSWORD"
					 name="confPassword" required><br>
					 <div class="confirm-password-alert-box" id="alertbox5">
  			 			<div class="triangle"></div>
  			 			<div class="msg-box">The <strong>Passwords</strong> you've entered
								<strong>doesn't match</strong>  with each other.
  						</div>
  			 		</div>
						<div class="recovery-success-box" id="successbox1">
							<strong>Congratulations! </strong>Your (<output></output>'s)
							 <strong>Password</strong> is <strong>successfully reset</strong>.
							 <strong><a href="index.php">Sign in</a></strong><br>
						</div>
					<button type="submit" class="submit-btn">RESET MASTER PASSWORD</button><br>
				</form>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
			<script type="text/javascript">
				var x = document.getElementById("sendotpgroup");
				var y = document.getElementById("enterotpgroup");
				var z = document.getElementById("setpasswordgroup");
				function movetoenterotp(){
					x.style.left = "-585px";
					y.style.left = "0px";
				}
				function movetosetpassword(){
					y.style.top = "-480px";
					z.style.top = "-152px";
				}
				$(function(){
					setTimeout(function(){$("#alertbox1").fadeOut()},5000);
					setTimeout(function(){$("#alertbox3").fadeOut()},5000);
					setTimeout(function(){$("#alertbox4").fadeOut()},10000);
					setTimeout(function(){$("#alertbox5").fadeOut()},5000);
				})
			</script>
	</div>
</BODY>
</HTML>
