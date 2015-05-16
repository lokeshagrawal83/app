<?php

	require_once('CreateHTML.php');									//  Paths are relative to PHP (calling) files.
	require_once('db/DBHelper.php');
	require_once('obj/User.php');

	class CreateHomeHTML extends CreateHTML
	{
		private $error_msg;
		
		public function __construct($error_msg)
		{
			parent::__construct("Welcome");
			$this->error_msg = $error_msg;
		}
		
		protected function createScripts()
		{
			?>
				<link rel="stylesheet" type="text/css" href="html/css/home.css">
				<script type="text/javascript" src="html/js/home.js"></script>
			<?php
		}

		protected function createUserInfo()
		{
			?>
				<div id="userinfo">
					Welcome <?php echo $_SESSION['fullname']; ?> ! <input type="button" OnClick="window.location='logout.php'" value="Logout"></input>
                    <div id="links">
	                    <ul>
                            <?php
                            	if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                            		echo "<li><a href=\"admin.php\">Admin Home</a></li>";
                            	}
                            ?>
                            <li><a href="#">Help</a></li>
                        </ul>
	                </div>
				</div>				<!-- End of userinfo div  -->
				
			<?php
		}

		protected function createPageBody()
		{
			$showIdentity = FALSE;
			$result = DBHelper::getUserDAO()->getUserGroup($_SESSION['userid']);
			if ( $row = mysqli_fetch_array($result)) 
			{
				$showIdentity = $row['show_identity'];					
			}
			?>
				<div id="groupWrapper1">		<!-- Just to handle footer overlap -->
					<?php  
						$this->showErrors($this->error_msg);  					// Parent function call to show errors 
					?>
					<div id="adminGroup">
						<?php
							if ($showIdentity) {
						?>
							<div class="adminChild">
								<a id="first" href="profile.php">Update Profile</a>
							</div>
						<?php
							}
						?>
						<div class="adminChild">
							<a id="second" href="chat.php">Goto Chat Room</a>
						</div>
						<?php
							if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
						?>
							<div class="adminChild">
								<a id="third" href="admin.php">Admin Console</a>
							</div>
						<?php
							}
						?>
						<!-- div class="adminChild">
							<a id="fourth" href="admin.php?action=managePerm">Manage Permissions</a>
						</div>
						<div class="adminChild">
							<a id="fifth" href="home.php">Go to My chat room</a>
						</div>
						<div class="adminChild">
							<a id="sixth" href="admin.php?action=manageRole">Manage Roles</a>
						</div -->
					</div>
				<div>
			<?php
		}
	}
?>