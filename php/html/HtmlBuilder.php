<?php

	require_once('db/DBHelper.php');
	require_once('util/SessionUtil.php');
	require_once('CreateSignupHTML.php');							// Paths are relative to PHP (calling) files.
	require_once('CreateHomeHTML.php');							// and not relative to this file.
	require_once('CreateChatHTML.php');	
	require_once('CreateIndexHTML.php');
	require_once('admin/html/CreateAdminHTML.php');
	require_once('admin/html/CreateAdminGroupHTML.php');
	require_once('admin/html/CreateAdminUserHTML.php');
	require_once('admin/html/ManageAdminGroupHTML.php');
	require_once('admin/html/ManageAdminUserHTML.php');
	require_once('admin/html/ManageSessionUserHTML.php');
	require_once('admin/html/ManagePermissionsHTML.php');
	require_once('admin/html/ViewGroupChatHTML.php');
	require_once('ManageUserProfileHTML.php');

	class HtmlBuilder
	{
		private static function checkUserLogin(){						// Module for checking user authenticaton. 
			session_start();
			if (!SessionUtil::getUserId()) 	
		  	{
		  		echo "<h1>You are not Logged in. Please click <a href='index.php'>here</a> to login.</h1>";
		  		exit;
		  	}
		}

		private static function checkAdminAuthorization()
		{
			session_start();
			if (!DBHelper::getPermissionDAO()->isAdmin(SessionUtil::getUserId())) 	
		  	{
		  		echo "<h1>You are not authorized to view this page. Please click <a href='home.php'>here</a> to go back to home page.</h1>";
		  		exit;
		  	}
		}

		public static function signupHTML($error_msg)					// Signup HTML page creation.
		{
			$client = new CreateSignupHTML($error_msg);
			$client->createHTML();
		}

		public static function indexHTML($error_msg)					// Index HTML page creation.
		{
			$client = new CreateIndexHTML($error_msg);
			$client->createHTML();
		}

		public static function homeHTML($error_msg)								// Home HTML page creation.
		{
			self::checkUserLogin();
			$client = new CreateHomeHTML($error_msg);
			$client->createHTML();
		}

		public static function chatHTML()								// Chat HTML page creation.
		{
			self::checkUserLogin();
			$client = new CreateChatHTML();
			$client->createHTML();
		}

		public static function manageUserProfileHTML($error_msg = NULL)
		{
			self::checkUserLogin();			
			$client = new ManageUserProfileHTML($error_msg);
			$client->createHTML();	
		}

		public static function adminHTML($error_msg)
		{
			self::checkAdminAuthorization();
			$client = new CreateAdminHTML($error_msg);
			$client->createHTML();	
		}

		public static function adminGroupHTML($error_msg)
		{
			self::checkAdminAuthorization();
			$client = new CreateAdminGroupHTML($error_msg);
			$client->createHTML();	
		}

		public static function adminUserHTML($error_msg)
		{
			self::checkAdminAuthorization();
			$client = new CreateAdminUserHTML($error_msg);
			$client->createHTML();	
		}

		public static function manageGroupHTML($error_msg)
		{
			self::checkAdminAuthorization();
			$client = new ManageAdminGroupHTML($error_msg);
			$client->createHTML();	
		}

		public static function manageUserHTML($error_msg)
		{
			self::checkAdminAuthorization();
			$client = new ManageAdminUserHTML($error_msg);
			$client->createHTML();	
		}

		public static function sessionUserHTML($error_msg)
		{
			self::checkAdminAuthorization();
			$client = new ManageSessionUserHTML($error_msg);
			$client->createHTML();	
		}

		public static function managePermissionsHTML($msg)
		{
			self::checkAdminAuthorization();
			$client = new ManagePermissionsHTML($msg);
			$client->createHTML();	
		}

		public static function viewGroupChatHTML()
		{
			self::checkAdminAuthorization();
			$client = new ViewGroupChatHTML();
			$client->createHTML();	
		}
	}
?>