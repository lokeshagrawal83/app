<?php

	require_once('CreateAdminHTML.php');									//  Paths are relative to PHP (calling) files.

	class ManageAdminUserHTML extends CreateAdminHTML
	{
		private $error_msg;											// Error messages specific to page

		public function __construct($error_msg = '')
		{
			parent::__construct();
			$this->error_msg = $error_msg;
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
			$this->showErrors($this->error_msg); 
			$archived = TRUE;

			$sort = "userid";

			if (isset($_POST['Sort'])) {
				$sort = $_POST['Sort'];
			}

			if (isset($_POST['userAction']) && $_POST['userAction'] == 'Get All Users') {
				$archived = FALSE;
				$result = DBHelper::getUserDAO()->getAllUsers($sort);
			} else {
				$_POST['userAction'] = 'Show Not Archived';
				$result = DBHelper::getUserDAO()->getAllNotArchivedUsers($sort);
			}

			echo "<div id=\"tableDisplay\">";
			echo "<form method=\"post\" action=\"".$_SERVER['SELF_PHP']."\">";
			echo "<input type=\"hidden\" name=\"userAction\" value=\"".$_POST['userAction']."\" />";
			if ($archived) {
				echo "<input type=\"submit\" name=\"userAction\" value=\"Get All Users\" />";
			} else {
				echo "<input type=\"submit\" name=\"userAction\" value=\"Show Not Archived\" />";
			}
			
			
			echo "<table border=\"1px\">";
			if ($sort == "username ASC") {
				echo "<th align=\"center\">Username <input name=\"Sort\" value=\"username DESC\" type=\"image\" src=\"../img/sort.gif\" alt=\"Submit\" width=\"24\" height=\"24\"></th>";
			} else {
				echo "<th align=\"center\">Username <input name=\"Sort\" value=\"username ASC\" type=\"image\" src=\"../img/sort.gif\" alt=\"Submit\" width=\"24\" height=\"24\"></th>";
			}
			
			echo "<th align=\"center\">Password</th>";

			if ($sort == "groupName ASC") {
				echo "<th align=\"center\">Group Name <input name=\"Sort\" value=\"groupName DESC\" type=\"image\" src=\"../img/sort.gif\" alt=\"Submit\" width=\"24\" height=\"24\"></th>";
			} else {
				echo "<th align=\"center\">Group Name <input name=\"Sort\" value=\"groupName ASC\" type=\"image\" src=\"../img/sort.gif\" alt=\"Submit\" width=\"24\" height=\"24\"></th>";
			}

			if ($sort == "createlogin ASC") {
				echo "<th align=\"center\">Create Time <input name=\"Sort\" value=\"createlogin DESC\" type=\"image\" src=\"../img/sort.gif\" alt=\"Submit\" width=\"24\" height=\"24\"></th>";
			} else {
				echo "<th align=\"center\">Create Time <input name=\"Sort\" value=\"createlogin ASC\" type=\"image\" src=\"../img/sort.gif\" alt=\"Submit\" width=\"24\" height=\"24\"></th>";
			}	
					
			echo "<th align=\"center\">Actions</th>";
			echo "</form>";
			while ($row = mysqli_fetch_array($result)) 
			{
				$user = DBHelper::getUserDAO()->getUserById($row['userid']);
				echo "<form method=\"post\" action=\"".$_SERVER['SELF_PHP']."\">";
				echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
				echo "<input type=\"hidden\" name=\"userName\" value=\"".$user->getUserName()."\" />";
				echo "<tr>";
				echo "<td align=\"center\">".$user->getUserName()."</td>";
				echo "<td align=\"center\">".substr($row['password'], 0, 12)."</td>";
				echo "<td align=\"center\">".$row['groupName']."</td>";
				echo "<td align=\"center\">".date('Y-m-d H:i:s', strtotime($row['createlogin']))."</td>";
				echo "<td align=\"center\">";
				echo "<input type=\"submit\" name=\"userAction\" value=\"View\" />";
				echo "<input type=\"submit\" name=\"userAction\" value=\"Edit\" />";
				echo "<input type=\"submit\" name=\"userAction\" value=\"Reset Password\" />";
				if ($row['archived'] == 0) {
					echo "<input type=\"submit\" name=\"userAction\" value=\"Archive\" /></td>";
				} else {
					echo "<input type=\"submit\" name=\"userAction\" value=\"Unarchive\" /></td>";
				}
				echo "</tr>";
				echo "</form>";
			}
			echo "</table>";
			echo "</div>";
		}
	}
?>