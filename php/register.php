<?php
	// Includes connection to db
	require_once('do_not_open_password_inside.php');

	// Checks if registration imput fields are empty
	if (empty($_POST['uname']) || empty($_POST['passwd']) || empty($_POST['email']))
	{
		// Return to home page with registration faliure
		header("Location: ../?page=login&registration=failure");
		die();
	}
	else {
		$name = $_POST['uname'];
		$pw = $_POST['passwd'];
		$mail = $_POST['email'];
	}

	// Escapes to stop sql injection and html injection
	$name = $mysqli->real_escape_string($name);
	$mail = $mysqli->real_escape_string($mail);
	$name = htmlspecialchars($name);
	$mail = htmlspecialchars($mail);

	// Generate salt for password encryption
	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	$salt = "$5\$rounds=5000$" . $salt . "$";

	// Encrypting password
	$hPW = crypt($pw, $salt);

	// Inserts the new user into db
	$sql = "INSERT INTO users (uname, email, pw, salt) VALUES ('$name', '$mail', '$hPW', '$salt')";
	$mysqli->query($sql) or die($mysqli->error);

	// Returns to the home page with registration success
	header("Location: ../?page=tournaments&registration=success");
	die();
?>
