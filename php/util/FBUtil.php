<?php

	require_once('../lib/Facebook/FacebookSession.php');					// Path relative to PHP calling files							
	require_once('../lib/Facebook/FacebookRequest.php');								
	require_once('../lib/Facebook/FacebookResponse.php');
	require_once('../lib/Facebook/FacebookSDKException.php');
	require_once('../lib/Facebook/FacebookRequestException.php');
	require_once('../lib/Facebook/FacebookRedirectLoginHelper.php');
	require_once('../lib/Facebook/FacebookAuthorizationException.php');
	require_once('../lib/Facebook/GraphObject.php');
	require_once('../lib/Facebook/GraphUser.php');
	require_once('../lib/Facebook/GraphSessionInfo.php');
	require_once('../lib/Facebook/Entities/AccessToken.php');
	require_once('../lib/Facebook/HttpClients/FacebookCurl.php');
	require_once('../lib/Facebook/HttpClients/FacebookHttpable.php');
	require_once('../lib/Facebook/HttpClients/FacebookCurlHttpClient.php');

	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphUser;
	use Facebook\GraphSessionInfo;
	use Facebook\FacebookHttpable;
	use Facebook\FacebookCurlHttpClient;
	use Facebook\FacebookCurl;

	class FBUtil {

		private $app_id = '819934654736764';		// production
	#	private $app_id = '877068335647052';	
		private $app_secret = 'eee00ba2a47734c13b7c64f51cc4708b';	// production
	#	private $app_secret = 'e4b913db0209bb55de5908923f1722a6';
		private $redirect_url;
		private $helper;

		function __construct()
		{
			FacebookSession::setDefaultApplication($this->app_id, $this->app_secret);
			$this->getHelper();
		}

		private function getHelper()
		{
			$this->redirect_url = 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/profile.php';
			$this->helper = new FacebookRedirectLoginHelper($this->redirect_url."?fbBtn=true");
		}

		public function login(){

			header('Location: '.$this->helper->getLoginUrl(array('email')));
		}

		public function getProfilePic(){

			$sess = $this->helper->getSessionFromRedirect();
			
			if (isset($_SESSION['fb_token'])) {
				$sess = new FacebookSession($_SESSION['fb_token']);
			}

			if(isset($sess))
			{
		
				$request = new FacebookRequest($sess, 'GET', '/me');
				$response = $request->execute();
				$graph = $response->getGraphObject(GraphUser::classname());
				$name = $graph->getName();
				$id = $graph->getId();
				$image = "http://graph.facebook.com/".$id."/picture?type=large";

				$userId = $_SESSION['userid'];
				DBHelper::getUserDAO()->updateUserPicFromFB($image, $userId);

				$logout = $this->helper->getLogoutUrl($sess, $this->redirect_url);
				header('Location: ' . $logout);

			}
		}
	}
?>