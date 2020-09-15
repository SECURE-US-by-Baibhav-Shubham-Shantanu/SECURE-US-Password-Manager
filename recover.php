<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer\src\Exception.php';
require 'PHPMailer\src\PHPMailer.php';
require 'PHPMailer\src\SMTP.php';

require_once 'pdo.php';
session_start();

$OTPsent = false;
$emailErr = false;
$otpErr = false;
$OTPverified = false;
$passErr = false;
$confPassErr = false;
$updated = false;

if (isset($_POST['sendOTP'])) {
	if (isset($_SESSION['email'])) {
		$email = $_SESSION['email'];
	}
	else {
		$email = $_POST['email'];
	}

	$sql = "SELECT * FROM login WHERE (email=:email)";
	$query = $conn->prepare($sql);
	$query->bindparam(':email',$email);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_ASSOC);

	if ($query->rowcount() > 0) {
		$OTPsent = true;
		$_SESSION['email'] = $result['email'];
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
		$mail->addAddress($_SESSION['email']);

		$mail->isHTML(true);
		$mail->Subject = 'OTP for Password Reset';
		$mail->Body    = "Your One Time Password is <b>".$otp."</b>";
		$mail->AltBody = 'Body in plain text for non-HTML mail clients';

		try {
			$mail->send();
			$_SESSION['otp'] = $otp;
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
	else {
		$emailErr = true;
	}
}

if (isset($_POST['submitOTP'])) {
	$OTPsent = true;
	if ($_POST['otp'] != $_SESSION['otp']) {
		$otpErr = true;
	}
	else {
		$OTPverified = true;
	}
}

if (isset($_POST['resetPass'])) {
	$OTPverified = true;
	$OTPsent = true;

	$password= $_POST['password'];
	$confPass = $_POST['confPassword'];
	$uppercase = preg_match('#[A-Z]#', $password);
	$lowercase = preg_match('#[a-z]#', $password);
	$number = preg_match('#[0-9]#', $password);
	$specialChars = preg_match('#[^\w]#', $password);
	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
		$passErr = true;
	}

	if ($password != $confPass) {
		$confPassErr = true;
	}

	if (!$passErr && !$confPassErr) {
		$sql = "UPDATE login SET password=:pass WHERE email=:email";
		$query = $conn->prepare($sql);
		$query->bindparam(':pass', $password);
		$query->bindparam(':email', $_SESSION['email']);
		$updated = $query->execute();
		session_destroy();
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
			<div class="recover-box" id="recoverbox">
        <h1 id="secureusaccountrecovery"><img src="images/logo1.png"> Account Recovery</h1>

				<?php if (!$OTPsent && !$OTPverified): ?>
					<form class="sendotp-group" id="sendotpgroup" method="post">
						<input type="email" class="input-field" placeholder="ENTER YOUR EMAIL ID"
						name="email" required><br>

						<?php if ($emailErr): ?>
							<div class="alert-box" id="alertbox1">
								<div class="triangle"></div>
								<div class="msg-box"><strong>Error:</strong> The <strong>Email ID</strong>
									you've entered <strong>doesn't match any account</strong>.
								</div>
							</div>
						<?php endif; ?>

						<button type="submit" class="submit-btn" name="sendOTP">SEND OTP</button>
					</form><br>
				<?php endif; ?>

				<?php if ($OTPsent && !$OTPverified): ?>
					<form class="enterotp-group" id="enterotpgroup" method="post">
						<div class="OTPsent-box" id="alertbox2">
							An <strong>OTP</strong> has been <strong>sent</strong> to your <strong>Email ID ()</strong>.
						</div>

						<input type="password" class="input-field" placeholder="ENTER SENT OTP" name="otp"><br>

						<?php if ($otpErr): ?>
							<div class="alert-box" id="alertbox3">
								<div class="triangle"></div>
								<div class="msg-box"><strong>Error:</strong> The <strong>OTP</strong>
									you've entered is <strong>incorrect</strong>.
								</div>
							</div>
						<?php endif; ?>

						<div class="reset-email">
							Entered wrong Email ID?
							<button type="button" class="reset-email-btn">RE-ENTER EMAIL ID</button>
						</div>

						<button type="submit" class="otp-submit-btn" name="submitOTP">SUBMIT OTP</button>
						<button type="reset" class="otp-submit-btn" name="sendOTP">RESEND OTP</button>
					</form><br>
				<?php endif; ?>

				<?php if ($OTPverified): ?>
					<form class="setpassword-group" id="setpasswordgroup" method="post">
						<input type="password" class="setpassword-field" placeholder="SETUP NEW MASTER PASSWORD"
						name="password" required><br>

						<?php if ($passErr): ?>
							<div class="password-strength-alert-box" id="alertbox4">
								<div class="triangle"></div>
								<div class="password-strength-msg-box"><strong>Error: </strong>The
									<strong>Password</strong> you've entered is <strong>weak</strong>.
									(Put atleast one uppercase, one lowercase, one special character,
									one digit, with minimum of total 8 characters)
								</div>
							</div>
						<?php endif; ?>

						<input type="password" class="setpassword-field" placeholder="CONFIRM NEW MASTER PASSWORD"
						name="confPassword" required><br>

						<?php if ($confPassErr): ?>
							<div class="confirm-password-alert-box" id="alertbox5">
								<div class="triangle"></div>
								<div class="msg-box">The <strong>Passwords</strong> you've entered
									<strong>doesn't match</strong>  with each other.
								</div>
							</div>
						<?php endif; ?>

						<?php if ($updated): ?>
							<div class="recovery-success-box" id="successbox1">
								<strong>Congratulations! </strong>Your ('s)<strong>Password</strong> is <strong>successfully reset</strong>.
								<strong><a href="index.php">Sign in</a></strong><br>
							</div>
						<?php endif; ?>

						<button type="submit" class="final-submit-btn" name="resetPass">RESET MASTER PASSWORD</button><br>
					</form>
				<?php endif; ?>

			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
			<script type="text/javascript">
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
