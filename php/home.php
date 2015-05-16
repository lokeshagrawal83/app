<?php
	
	require_once('html/HtmlBuilder.php');					// Helper object for creating HTML
	require_once('db/DBHelper.php');
	require_once('util/SessionUtil.php');

	function mainScreen()
	{
		HtmlBuilder::homeHTML(NULL);							
	}

	function getUserDetails()
	{
		$user = DBHelper::getUserDAO()->getUserById($_SESSION['userid']);
		$arr = array(
			'userid' => $user->getId(), 
			'username' => $user->getUsername(),
			'email' => $user->getEmail(), 
			'firstname' => $user->getFirstName(), 
			'lastname' => $user->getLastName(),
			'gender' => $user->getGender(),
			'age' => $user->getAge());
		echo json_encode($arr);
	}

	session_start();

	$req = $_SERVER['REQUEST_METHOD'];

	switch ($req) {

		case 'GET':

			if (isset($_GET['action'])) 
			{
				if ($_GET['action'] == "user") 
					getUserDetails();
			}
			else
			{
				mainScreen();
			}
			break;
	}

?>