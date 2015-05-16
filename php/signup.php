<?php

	require_once('html/HtmlBuilder.php');							// Helper object for creating HTML
	require_once('db/DBHelper.php');
	require_once('obj/User.php');

	function mainScreen($msg = NULL)
	{
		HtmlBuilder::signupHTML($msg);								// Error messages are passed to HTML
	}

	function createLogin()
	{
		$msg = "";

		extract($_POST);

		if (!empty($username) && !empty($password1))				// Check for empty fields
		{
			if($password1 != $password2)																	// Check for password mismatch
			{
				$msg = "Password do not match.";
			}
			else
			{
			    if (!DBHelper::getUserDAO()->checkUserExistByUsername($username)) 
				{
				    $user = User::createUserWithParameters($username, $password1);
				    $user = DBHelper::getUserDAO()->createNewUser($user);
				    SessionUtil::setSessionVariable($user);

					header('Location: ' . dirname($_SERVER['PHP_SELF']).'/profile.php?userId='.$user->getId());			
			    }
			    else 
			    {
			        $msg = "Username is already registered. Login <a href='index.php'>here</a>";
			    }
			}
		}	
		else
		{
			$msg = "Mandatory fields are missing.";
		}		

		mainScreen($msg);
	}


	session_start();

	$req = $_SERVER['REQUEST_METHOD'];

	switch ($req) {

		case 'GET':

			extract($_GET);

			mainScreen();

			break;
		
		case 'POST':

			extract($_POST);

			if (isset($signup) && $signup == "Create Login") {
				
				createLogin();
			}

			break;
	}
?>