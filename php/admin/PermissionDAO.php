<?php
	require_once('db/DAO.php');

	class PermissionDAO extends DAO
	{
		public function isAdmin($userId)
		{
			$userId = $this->escapeParameter($userId);
			
			$query  = "SELECT * FROM user_roles u, roles r WHERE u.roleid = r.roleid AND u.userid = ".$userId." AND r.roleid =";
			$query .= "(SELECT roleid from roles WHERE rolename = 'admin')";

			$data = mysqli_query($this->con, $query);


			if (mysqli_num_rows($data) == 1) 	
				return TRUE;
			else
				return FALSE;
		}

		public function getAllRoles() {
			$query = "SELECT * FROM roles ORDER BY roleid";
			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function checkUserRole($userId, $roleId) {

			$userId = $this->escapeParameter($userId);
			$roleId = $this->escapeParameter($roleId);

			$query = "SELECT * FROM user_roles WHERE userid=".$userId." AND roleid=".$roleId;
			$data = mysqli_query($this->con, $query);


			if (mysqli_num_rows($data) == 1) 	
				return TRUE;
			else
				return FALSE;
		}

		public function deleteUserRoles($userId)
		{
			$userId = $this->escapeParameter($userId);

			$query = "DELETE FROM user_roles WHERE userid=".$userId."";
			$data = mysqli_query($this->con, $query);
		}

		public function updateUserRoles($userId, $roleId) 
		{
			$userId = $this->escapeParameter($userId);
			$roleId = $this->escapeParameter($roleId);

			$query = "INSERT INTO user_roles VALUES(".$userId.",".$roleId.")";
			$data = mysqli_query($this->con, $query);
		}
	}
?>