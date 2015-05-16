<?php
	
	require_once('login/LoginHelper.php');

	session_start();
	
	if (isset($_SESSION['userid'])) 									// Need to check what if session is expired ? -- Lokesh
	{
		$client = LoginHelper::getLogin($_SESSION['submit']);					
		header('Location: ' . $client->logout());
	} 
	else
	{
		echo "<h1>You are not Logged in.</h1>";
		exit;
	}
?>