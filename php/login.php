<?php
	// Starting session
	session_start();

	// Include connection to db
	require_once('do_not_open_password_inside.php');

	// Grabbing email and password from post method
	$email = $_POST['email'];
	$pw = $_POST['passwd'];

	// Query grabbing every detail about the user related to the email address
	$sql = "SELECT * FROM users WHERE email = '$email'";
	$result = $mysqli->query($sql);

	// Checks if user with corresponding email is found
	if (isset($result))
	{
		// Fetch password and salt for later comparison
		$result = $result->fetch_assoc();
		$salt = $result["salt"];
		$passwd = $result["pw"];

		// Encrypt the entered password with the same salt as the stored password to compare entered password with stored encrypted password.
		$hPW1 = crypt($pw, $salt);

		// Check if passwords match (if user entered the correct pw)
		if($hPW1 == $passwd)
		{
			// Adds every detail but password about the logged in user to the seesion
			$_SESSION['uid'] = $result["UserID"];
			$_SESSION['uname'] = $result["uname"];
			$_SESSION['email'] = $result["email"];
			$_SESSION['admin_status'] = $result["admin_status"];

			// Relocation to home screen with a login status of success
			header("Location: ../?page=tournaments&login=success");
		}
		else
		{
			// If the passwords does not match, return to home screen with a login status of failure
			header("Location: ../?page=login&login=failure");
		}
	}
	else
	{
		// If there is no user with a corresponding email address, relocate to home screen with a login status of failure
		header("Location: ../?page=login&login=failure");
	}
?>
