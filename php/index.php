<?php

	require_once('html/HtmlBuilder.php');				// Helper object for creating HTML
	require_once('login/LoginHelper.php');				// Helper object for managing Logins
	require_once('util/SessionUtil.php');
	$error_msg = "";

	session_start();

	if (!SessionUtil::getUserId()) 					// If userid is not set, Login
	{
		if (isset($_POST['submit'])) 					// And user clicked on one of the Login button
		{
			try {
				$client = LoginHelper::getLogin();
				$client->login();

			} catch (Exception $e)
			{
				$error_msg = $e->getMessage();
			}
		}
	} 
	else 												// User Id is already set, forward user to home screen
	{
		header('Location: ' . SessionUtil::getHomeURL());
	}

	if (isset($_POST['signup'])) {						// New User Signup
		header('Location: ' . dirname($_SERVER['PHP_SELF']).'/signup.php');
	}

	HtmlBuilder::indexHTML($error_msg);
?>	