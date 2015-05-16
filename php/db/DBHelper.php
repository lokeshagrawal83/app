<?php

	require_once('UserDAO.php');
	require_once('UserGroupDAO.php');
	require_once('ChatLogDAO.php');
	require_once('admin/PermissionDAO.php');

	class DBHelper
	{
		public static function getUserDAO()
		{
			return new UserDAO();
		}

		public static function getUserGroupDAO()
		{
			return new UserGroupDAO();
		}

		public static function getChatLogDAO()
		{
			return new ChatLogDAO();
		}

		public static function getPermissionDAO()
		{
			return new PermissionDAO();
		}
	}
?>