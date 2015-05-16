<?php

	require_once('CreateAdminHTML.php');									//  Paths are relative to PHP (calling) files.

	class ManagePermissionsHTML extends CreateAdminHTML
	{
		private $error_msg;											// Error messages specific to page

		public function __construct($msg = '')
		{
			parent::__construct();
			$this->error_msg = $msg;
		}
		
		protected function createScripts()
		{
			?>
				<link rel="stylesheet" type="text/css" href="admin/html/css/manageUser.css">
				<script type="text/javascript" src="admin/html/js/manageUser.js"></script>
			<?php
		}

		protected function createPageBody()
		{
			?>
				<div id="permWrapper">

				<?php
					
					$this->showErrors($this->error_msg);  					// Parent function call to show errors 
					
					echo "<table border=\"1px\">";	
					echo "<th>Users\Roles</th>";

					$roles = DBHelper::getPermissionDAO()->getAllRoles();
					while ($row = mysqli_fetch_array($roles)) {
						echo "<th>".$row['rolename']."</th>";
					}
				
					echo "<th>Action</th>";

					$users = DBHelper::getUserDAO()->getAllUsers();

					while ($user = mysqli_fetch_array($users)) 
					{
						echo "<tr>";
						echo "<form method=\"Post\" action=\"".$_SERVER['SELF_PHP']."\">";
						echo "<td>".$user['username']."</td>";
						$roles = DBHelper::getPermissionDAO()->getAllRoles();
						while ($role = mysqli_fetch_array($roles)) 
						{
							if (DBHelper::getPermissionDAO()->checkUserRole($user['userid'], $role['roleid'])) 
							{
								echo "<td><input type=\"checkbox\" name=\"".$role['roleid']."\" checked /></td>";
							}
							else
							{
								echo "<td><input type=\"checkbox\" name=\"".$role['roleid']."\" /></td>";
							}
						}
						echo "<input type=\"hidden\" name=\"userId\" value=\"".$user['userid']."\" />";
						echo "<td><input type=\"submit\" name=\"roleAction\" value=\"Update\" /></td>";
						echo "</form>";
						echo "</tr>";
					}
					echo "</table>";
				?>
				</div>
			<?php
		}
	}
?>