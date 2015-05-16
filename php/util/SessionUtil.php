<?php

	require_once('db/DBHelper.php');

	class SessionUtil {

		private function __construct() {

		}

		public static function getUserId() {

			if (!isset($_SESSION['userid'])) 					
			{
		    	if (isset($_COOKIE['userid'])) 
		    	{
			    	$_SESSION['userid'] = $_COOKIE['userid'];
		    	}
		  	}	

		  	return $_SESSION['userid'];
		}

		public static function setSessionVariable($user) {

			$userId = $user->getId();													
			$fullname = $user->getFullName();

			$_SESSION['userid'] = $userId;
			$_SESSION['fullname'] = $fullname;
			$_SESSION['isAdmin'] = DBHelper::getPermissionDAO()->isAdmin($userId);

			setcookie('userid', $userId, time() + (60 * 60 * 24 * 30));
			setcookie('fullname', $fullname, time() + (60 * 60 * 24 * 30));
		}

		public static function destroySessionVariable() {

			$_SESSION = array();										

			isset($_COOKIE[session_name()]) 	? setcookie(session_name(),     '', time() - 3600)	: "" ;
			isset($_COOKIE['userid']) 			? setcookie('userid', 			'', time() - 3600)	: "" ;
			
			session_destroy();
		}

		public static function getHomeURL()
		{
			$redirectURL = 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/home.php';

		#	$_SESSION['isAdmin'] ? $redirectURL .= '/admin.php': $redirectURL .= '/home.php';

			return $redirectURL;
		}
	}
?>