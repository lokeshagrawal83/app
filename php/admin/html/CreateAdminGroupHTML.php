<?php

	require_once('CreateAdminHTML.php');									//  Paths are relative to PHP (calling) files.

	class CreateAdminGroupHTML extends CreateAdminHTML
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
				<link rel="stylesheet" type="text/css" href="admin/html/css/createGroup.css">
				<script type="text/javascript" src="admin/html/js/createGroup.js"></script>
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
						<h1>Create Group</h1>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<div id="group">
								<label for="groupName"> Group Name:</label>
								<input id="groupName" type="text" name="groupName" value="<?php echo $_POST['groupName']; ?>" /><br>
								<label for="srchUser"> Search User:</label>
								<input id="srchUser" type="text" />
								<button id="addUserBtn">Add</button>
								<div id="users">
									<!-- div class="userrow">
										<input type="text" readonly value="Lokesh Agrawal" />
										<button>Remove</button>
									</div -->
								</div>
								<div id="actions">
									<input id="createGroupBtn" name="groupAction" type="submit" value="Create" />
									<button id="cancelGroupBtn">Cancel</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			<?php	
		}
	}
?>