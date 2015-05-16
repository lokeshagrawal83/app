<?php
	require_once('Login.php');
	require_once('db/DBHelper.php');
	require_once('util/SessionUtil.php');

	class WebLogin extends Login
	{
		function __construct()
		{

		}

		public function login()														// Nothin in Login as checkUserInputAndRedirect takes care of it.
		{
			if (!empty($_POST)) {
				extract($_POST);
			}

			if (!empty($username) && !empty($password))											// Checks mandatory user inputs
			{
				$user = DBHelper::getUserDAO()->checkUsernamePassword($username, $password);
				if ($user)		// If user already exist then login.
				{
					SessionUtil::setSessionVariable($user);
			
					DBHelper::getUserDAO()->updateLastLogin(SessionUtil::getUserId());
				#	DBHelper::getChatLogDAO()->insertLog(SessionUtil::getUserId(), "Logged in.", 1);

					header('Location: ' . SessionUtil::getHomeURL());
				}
				else {																			// Else notify user to enter correct credentials.
					throw new Exception("Please enter valid username and Password.");
				}
			}		
			else {
				throw new Exception("Please enter username and Password.");
			}			
		}

		public function logout()								// Logs out the user 
		{			
		#	DBHelper::getChatLogDAO()->insertLog(SessionUtil::getUserId(), "Logged out.", 1);

			$logout = $home_url = 'http://' . $_SERVER['HTTP_HOST'] .dirname($_SERVER['PHP_SELF']).'/index.php';		// Redirect user to index page.

			SessionUtil::destroySessionVariable();

			return $logout;
		}
	}
?>