<?php

	require_once('ChatService.php');

	class ServiceHelper {

		public static function getChatService()
		{
			return new ChatService();
		}
	}	

?>