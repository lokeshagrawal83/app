<?php

	require_once('CreateAdminHTML.php');
	require_once('db/DBHelper.php');

	class CreateAdminUserHTML extends CreateAdminHTML
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
				<link rel="stylesheet" type="text/css" href="admin/html/css/createUser.css">
				<script type="text/javascript" src="admin/html/js/createUser.js"></script>
			<?php
		}

		protected function createPageBody()
		{
			?>	
				<div id="groupWrapper1">		<!-- Just to handle footer overlap -->
					<?php  
						$this->showErrors($this->error_msg);  					// Parent function call to show errors
					?>
					<div id="groupWrapper">
						<h1>Create Users</h1>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<fieldset>
								<legend align="left">Session Settings:</legend>
								<table>
									<tr><td>Session Name:</td><td align="left"><input name="session" type="text" value="<?=$_POST['session']?>"/></td></tr>
									<!--tr><td>Current Group:</td><td align="left"><?=$this->getUserGroups("curr")?></td></tr>
									<tr><td>Next Group:</td><td align="left"><?=$this->getUserGroups("next")?></td></tr -->
									<tr><td colspan="2"><textarea id="userarea" name="userarea"><?=$_POST['userarea']?></textarea></td></tr>
									<tr><td></td><td><input type="submit" name="adminAction" value="Create Users" /></td></tr>
								</table>
							</fieldset>
						</form>
					</div>
				</div>
			<?php	
		}

		private function getUserGroups($area){

			$result = DBHelper::getUserGroupDAO()->getAllGroups();
			echo "<select name=\"".$area."\"><option selected></option>";
			while ( $row = mysqli_fetch_array($result)) 
			{
				echo "<option value=\"".$row['groupid']."\">".$row['groupName']."</option>";
			}
			echo "</select>";
		}
	}
?>