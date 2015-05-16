<?php

	require_once('DAO.php');

	class ChatLogDAO extends DAO
	{

		public function insertLog($userId, $msg, $logType = 0)
		{
			$userId = $this->escapeParameter($userId);
			$msg = $this->escapeParameter($msg);

			$query = "SELECT * FROM chatlogs WHERE userid = ".$userId." AND editing = 1";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$query = "UPDATE chatlogs SET msg = '$msg', logtime = NOW(), editing = 0 WHERE userid = ".$userId." AND editing = 1";
			} 
			else
			{
				$query = "INSERT INTO chatlogs (userid, groupid, msg, logtype, editing, logtime) SELECT $userId, groupid, '$msg', $logType, 0, NOW() FROM users WHERE userid = ".$userId;
			}

			mysqli_query($this->con, $query);
		}

		public function updateLog($userId, $msg, $logType = 0)
		{
			$userId = $this->escapeParameter($userId);
			$msg = $this->escapeParameter($msg);

			$query = "SELECT * FROM chatlogs WHERE userid = ".$userId." AND editing = 1";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$query = "UPDATE chatlogs SET msg = '$msg', logtime = NOW() WHERE userid = ".$userId." AND editing = 1";
			} 
			else
			{
				$query = "INSERT INTO chatlogs (userid, groupid, msg, logtype, editing, logtime) SELECT $userId, groupid, '$msg', $logType, 1, NOW() FROM users WHERE userid = ".$userId;
			}

			mysqli_query($this->con, $query);
		}

		public function getLogs($userId)
		{
			$query = "SELECT g.chatlogs FROM usergroups g, users u WHERE g.groupid = u.groupid AND u.userid = ".$userId;
			$data = mysqli_query($this->con, $query);

			$row = mysqli_fetch_array($data);
			if ($row[0] == 1) {
				return $this->getAllLogs($userId);
			}
			else
			{
				return $this->getRecentLogs($userId);
			}
		}

		public function getAllLogs( $userId )
		{
			
			$query  = "SELECT * FROM ( ";
		//	$query .= "SELECT c.logid, concat(COALESCE(u.firstname, ''), ' ', COALESCE(u.lastname, '')) AS username, c.msg, c.logtype, c.logtime ";
			$query .= "SELECT c.logid, u.username AS username, c.msg, c.logtype, c.logtime ";
			$query .= "FROM chatlogs c, users u WHERE c.userid = u.userid and c.groupid =( ";
			$query .= "SELECT groupid FROM users WHERE userid = ".$userId." ) ORDER BY logid DESC"; // limit 20";
			$query .= ") AS chats order by chats.logid";
	
			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function getRecentLogs( $userId )
		{

			$query  = "SELECT * FROM ( ";
		//	$query .= "SELECT c.logid, c.userid, concat(COALESCE(u.firstname, ''), ' ', COALESCE(u.lastname, '')) AS username, c.msg, c.logtype, c.logtime ";
			$query .= "SELECT c.logid, c.userid, u.username AS username, c.msg, c.logtype, c.logtime ";
			$query .= "FROM chatlogs c, users u WHERE c.userid = u.userid and c.groupid =( ";
			$query .= "SELECT groupid FROM users WHERE userid = ".$userId." ) ORDER BY logtime DESC) AS chats group by chats.userid order by chats.logid";
	
			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function getGroupLogs($groupId)
		{
		//	$query = "SELECT concat(u.firstname, ' ', COALESCE(u.lastname, '')) AS username, c.msg, c.logtype, c.logtime ";
			$query = "SELECT u.username, c.msg, c.logtype, c.logtime ";
			$query .= "FROM chatlogs c, users u WHERE c.userid = u.userid and c.groupid =".$groupId;
			$data = mysqli_query($this->con, $query);

			return $data;
		}

	}