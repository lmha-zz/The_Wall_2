<?php
require_once('process.php');
require_once('connection.php');

unset($_SESSION['first_name']);
unset($_SESSION['user_id']);
$_SESSION['logged_in'] = false;


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CodingDojo Wall</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<div id="body_wrapper">
		<div id="header_wrapper">
			<h1>CodingDojo Wall</h1>
			<p class="welcome_msg">Please log in below</p>
		</div>

		<div id="content_wrapper">
			<div id="login_wrapper">
				<?php
				if(isset($_SESSION['login_errors'])) {
					foreach ($_SESSION['login_errors'] as $error) {
						?>
						<p class='error'><?= $error ?></p>
						<?php
					}
					unset($_SESSION['login_errors']);
				}
				?>
				<h1>Login:</h1>
				<form action="process.php" method="post">
					<label>Email: <input type="text" name="email" placeholder="Email"></label>
					<label>Password: <input type="password" name="password" placeholder="Password"></label>
					<input type="submit" name="login" value="Log In">
				</form>
			</div>

			<div id="register_wrapper">
				<?php
				if(isset($_SESSION['register_success'])) {
					foreach ($_SESSION['register_success'] as $success) {
						?>
						<p class='success'><?= $success ?></p>
						<?php
					}
					unset($_SESSION['register_success']);
				}
				if(isset($_SESSION['register_errors'])) {
					foreach ($_SESSION['register_errors'] as $error) {
						?>
						<p class='error'><?= $error ?></p>
						<?php
					}
					unset($_SESSION['register_errors']);
				}
				?>
				<h1>Register:</h1>
				<form action="process.php" method="post">
					<label>First Name: <input type="text" name="first_name" placeholder="First Name"></label>
					<label>Last Name: <input type="text" name="last_name" placeholder="Last Name"></label>
					<label>Email: <input type="text" name="email" placeholder="Email"></label>
					<label>Password: <input type="password" name="password" placeholder="Password"></label>
					<label>Confirm Password: <input type="password" name="password_confirmation" placeholder="Confirm Password"></label>
					<input type="submit" name="register" value="Register">
				</form>
			</div>
		</div>

	</div>


</body>
</html>