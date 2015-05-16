<?php

	require_once('CreateAdminHTML.php');
	require_once('db/DBHelper.php');

	class ManageSessionUserHTML extends CreateAdminHTML
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
				
			<?php
		}

		protected function createPageBody()
		{
			$this->showErrors($this->error_msg); 
			$result = DBHelper::getUserDAO()->getAllSessions();
			echo "<div id=\"tableDisplay\">";
			echo "<h1>Sessions</h1>";
			echo "<table width=\"600px\" border=\"1px\">";
			echo "<th align=\"center\">Seession Name</th>";
			echo "<th align=\"center\">Actions</th>";
			while ($row = mysqli_fetch_array($result)) 
			{
				$user = DBHelper::getUserDAO()->getUserById($row['userid']);
				echo "<form method=\"post\" action=\"".$_SERVER['SELF_PHP']."\">";
				echo "<input type=\"hidden\" name=\"sessionName\" value=\"".$row['session']."\" />";
				echo "<tr>";
				echo "<td width=\"200px\" align=\"center\">".$row['session']."</td>";
				echo "<td width=\"200px\" align=\"center\">";
				echo "<input type=\"submit\" name=\"sessionAction\" value=\"View\" />";
				if(!empty($row['next_groupid']))
					echo "<input type=\"submit\" name=\"sessionAction\" value=\"Migrate\" /></td>";
				else
					echo "<input type=\"submit\" name=\"sessionAction\" disabled value=\"Migrated\" /></td>";
				echo "</tr>";
				echo "</form>";
			}
			echo "</table>";
			echo "</div>";

			if (isset($_POST['sessionName']) && !empty($_POST['sessionName'])) {
				$result = DBHelper::getUserDAO()->getSessionUsers($_POST['sessionName']);
				?>
					<div id="sessionDetails">
						<fieldset>
							<legend align="left">Session Details</legend>
							<table border="1px">
								<tr>
									<th align="center">Session Name</th><th align="center">Username</th>
									<th align="center">Current Group</th><th align="center">Next Group</th>
								</tr>
								<?php
									while ($session = mysqli_fetch_array($result)) {
										echo "<tr><td width=\"200px\" align=\"center\">".$session['session']."</td>";

										echo "<td width=\"200px\" align=\"center\">".$session['username']."</td>";

										$groupData = DBHelper::getUserGroupDAO()->getGroupById($session['groupid']);
										$group = mysqli_fetch_array($groupData);
										echo "<td width=\"200px\" align=\"center\">".$group['groupName']."</td>";
										
										$groupData = DBHelper::getUserGroupDAO()->getGroupById($session['next_groupid']);
										$group = mysqli_fetch_array($groupData);
										echo "<td width=\"200px\" align=\"center\">".$group['groupName']."</td></tr>";
									}
								?>
							</table>
						</fieldset>
						<br/>
					</div>
				<?php
			}
		}
	}
?>