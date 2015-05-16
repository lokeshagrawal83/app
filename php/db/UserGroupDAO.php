<?php

	require_once('DAO.php');
	require_once('obj/User.php');
	require_once('obj/UserGroup.php');

	class UserGroupDAO extends DAO
	{
		public function createGroup($groupName, $usersArray)
		{
			$groupName = $this->escapeParameter($groupName);
			$query = "INSERT into usergroups (groupName) VALUES ('$groupName')";
			mysqli_query($this->con, $query);			

			$query = "SELECT groupid FROM usergroups WHERE groupName = '$groupName'";
			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$row = mysqli_fetch_array($data);
				$groupid = $row['groupid'];

				foreach ($usersArray as $value) 
				{
					$query = "UPDATE users SET groupid = '$groupid' WHERE userid = '$value'";
					mysqli_query($this->con, $query);				
				}
			}
		}

		public function checkGroupExist($groupName)
		{
			$groupName = $this->escapeParameter($groupName);
			$query = "SELECT * FROM usergroups WHERE groupName = '$groupName'";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
				return true;
			else
				return false;
		}

		public function getAllGroups()
		{
			$query = "SELECT * FROM usergroups";
			$data = mysqli_query($this->con, $query);
			return $data;
		}

		public function getGroupById($groupId)
		{
			$groupId = $this->escapeParameter($groupId);
			$query = "SELECT * FROM usergroups WHERE groupId = '$groupId'";

			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function getGroupByName($groupName)
		{
			$groupName = $this->escapeParameter($groupName);
			$query = "SELECT * FROM usergroups WHERE groupName = '$groupName'";

			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function countGroupByName($groupName)
		{
			$groupName = $this->escapeParameter($groupName);
			$query = "SELECT * FROM usergroups WHERE groupName = '$groupName'";

			$data = mysqli_query($this->con, $query);

			return mysqli_num_rows($data);
		}

		public function getAllUsersInGroup($groupId)
		{
			$groupId = $this->escapeParameter($groupId);
			$query = "SELECT * FROM users WHERE groupId = '$groupId'";

			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function deleteGroup($groupId)
		{
			$groupId = $this->escapeParameter($groupId);
			$query = "DELETE FROM usergroups WHERE groupid = '$groupId'";
			mysqli_query($this->con, $query);
		}

		public function changeGroupName($groupId, $groupName)
		{
			$groupId = $this->escapeParameter($groupId);
			$groupName = $this->escapeParameter($groupName);
			$query = "UPDATE usergroups SET groupName = '$groupName' WHERE groupid = '$groupId'";
			mysqli_query($this->con, $query);
		}

		public function addUserToGroup($groupId, $userId)
		{
			$groupId = $this->escapeParameter($groupId);
			$userId = $this->escapeParameter($userId);
			$query = "UPDATE users SET groupid = '$groupId' WHERE userid = '$userId'";
			mysqli_query($this->con, $query);
		}

		public function archiveGroup($groupId, $archive)
		{
			$groupId = $this->escapeParameter($groupId);
			$archive = $this->escapeParameter($archive);
			$query = "UPDATE usergroups SET archived = $archive WHERE groupid = '$groupId'";
			mysqli_query($this->con, $query);
		}

		public function getAllNotArchivedGroups()
		{
			$query = "SELECT * FROM usergroups WHERE archived = 0 ";
			$data = mysqli_query($this->con, $query);
			return $data;
		}

		public function updateChatSettings(UserGroup $group)
		{
			$query = "UPDATE usergroups SET ";
			$query .= "show_profile = '".$group->getShowProfile();
			$query .= "', generic_identity = '".$group->getGenericIdentity();

			if ($group->getGroupPic()) {
				$query .= "', group_pic = '".$group->getGroupPic();
			}

			$query .= "', show_identity = '".$group->getShowIdentity();
			$query .= "', show_pic = '".$group->getShowPic();			
			$query .= "', allow_users = '".$group->getAllowUsers();			
			$query .= "', profile_params = '".$group->getParams();
			$query .= "', chatlogs = '".$group->getChatLogs();
			$query .= "', sendlogs = '".$group->getSendLogs();
			$query .= "' WHERE groupid = ".$group->getGroupId();

			mysqli_query($this->con, $query);
		}
	}
?>