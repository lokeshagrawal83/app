<?php
	
	require_once('html/HtmlBuilder.php');					// Helper object for creating HTML
	require_once('db/DBHelper.php');
	require_once('util/SessionUtil.php');

	function mainScreen()
	{
		# Check for user profile completion is user is in identity group
		$showIdentity = FALSE;
		$result = DBHelper::getUserDAO()->getUserGroup($_SESSION['userid']);
		if ( $row = mysqli_fetch_array($result)) 
		{
			$showIdentity = $row['show_identity'];					
		}

		if ($showIdentity) 
		{
			$user = DBHelper::getUserDAO()->getUserById($_SESSION['userid']);

			if (empty($user->getFirstName())) {
				$msg = "User profile is incomplete.";
				HtmlBuilder::homeHTML($msg);
				exit();
			}
		}	

		HtmlBuilder::chatHTML();							
	}

  	function getLogs()
  	{
  		$data = DBHelper::getChatLogDAO()->getLogs(SessionUtil::getUserId());
  		while ( $row = mysqli_fetch_array($data) ) 
		{
			if ($row['logtype'] == 1) {
				echo "<span class='system'>".$row['username']." ".$row['msg']."</span><div class='logtime'>".substr($row['logtime'], 14)."</div><br>";
			}
			else
			{
				echo "<span class='uName'>".$row['username']."</span>: <span class='msg'>".$row['msg']."</span><div class='logtime'>".substr($row['logtime'], 14)."</div><br>";
			}
		}
  	}

  	function insertLogs()
  	{
		DBHelper::getChatLogDAO()->insertLog(SessionUtil::getUserId(), $_GET['msg']);	
  	}

  	function updateLogs()
  	{
		DBHelper::getChatLogDAO()->updateLog(SessionUtil::getUserId(), $_GET['msg']);	
  	}

	session_start();

	$req = $_SERVER['REQUEST_METHOD'];

	switch ($req) {

		case 'GET':

			if (isset($_GET['action'])) 
			{
				if ($_GET['action'] == "insert") 
					insertLogs();

				if ($_GET['action'] == "update") 
					updateLogs();

				getLogs();
			}
			else
			{
				mainScreen();
			}
			break;
	}
?>