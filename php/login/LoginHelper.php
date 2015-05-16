<?php

	require_once('WebLogin.php');

	class LoginHelper
	{
		public static function getLogin($context = NULL)				// Simple factory pattern to get desired Login class
		{
			switch ($context) 
			{
				case 'Login':
					$client = new WebLogin();
					break;

				case 'uaLogin':
					$client = new UALogin();
					break;
				
				case 'fbLogin':
					$client = new FBLogin();
					break;

				default:
					$client = new WebLogin();
					break;
			}
			return $client;
		}
	}
?>