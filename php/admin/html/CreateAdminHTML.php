<?php

	require_once('html/CreateHTML.php');									//  Paths are relative to PHP (calling) files.

	class CreateAdminHTML extends CreateHTML
	{
		private $error_msg;											// Error messages specific to page

		public function __construct($error_msg = '')
		{
			parent::__construct("Profile");
			$this->error_msg = $error_msg;
		}
		
		protected function createScripts()
		{
			?>
				<link rel="stylesheet" type="text/css" href="admin/html/css/homeAdmin.css">
				<script type="text/javascript" src="admin/html/js/homeAdmin.js"></script>
			<?php
		}

		protected function createUserInfo()
		{
			?>
				<div id="userinfo">
					Welcome <?php echo $_SESSION['fullname']; ?> ! <input type="button" OnClick="window.location='logout.php'" value="Logout"></input>
					<div id="links">
						<ul>
							<li><a href="home.php">Home</a></li>
							<li><a href="admin.php">Admin Home</a></li>
							<li><a href="#">Help</a></li>
						</ul>
					</div>
				</div>				<!-- End of userinfo div  -->
			<?php
		}

		protected function createPageBody()
		{
			?>	
				<div id="groupWrapper1">		<!-- Just to handle footer overlap -->
					<?php  
						$this->showErrors($this->error_msg);  					// Parent function call to show errors 
					?>
					<div id="adminGroup">
						<div class="adminChild">
							<a id="first" href="admin.php?action=createGroups">Create Groups</a>
						</div>
						<div class="adminChild">
							<a id="second" href="admin.php?action=manageGroups">Manage Groups</a>
						</div>
						<div class="adminChild">
							<a id="third" href="admin.php?action=manageUser">Manage Users</a>
						</div>
						<div class="adminChild">
							<a id="fourth" href="admin.php?action=managePerm">Manage Permissions</a>
						</div>
						<div class="adminChild">
							<a id="fifth" href="admin.php?action=createUsers">Create Users</a>
						</div>
						<div class="adminChild">
							<a id="sixth" href="admin.php?action=sessionUsers">Session Users</a>
						</div>
					</div>
				<div>
			<?php
		}
	}
?>