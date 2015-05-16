<?php

	require_once('CreateHTML.php');									//  Paths are relative to PHP (calling) files.
	require_once('db/DBHelper.php');
	require_once('obj/User.php');

	class CreateChatHTML extends CreateHTML
	{
		public function __construct()
		{
			parent::__construct("Welcome");
		}
		
		protected function createScripts()
		{
			?>
				<link rel="stylesheet" type="text/css" href="html/css/chat.css">
				<script type="text/javascript" src="html/js/chat.js"></script>
				<script type="text/javascript" src="../js/tinymce/tinymce.min.js"></script>
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
	                    	<li><a href="profile.php">My Profile</a></li>
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
			$user = DBHelper::getUserDAO()->getUserById($_SESSION['userid']);
			$result = DBHelper::getUserDAO()->getUserGroup($_SESSION['userid']);
			if (mysqli_num_rows($result) == 0) 
			{
				echo "You are not assigned to any group. Please contact Administrator. Click <a href=\"logout.php\">here</a> to Logout.";
				exit;
			} else {
				while ( $row = mysqli_fetch_array($result)) 
				{
					$genericIdentity = $row['generic_identity'];
					$groupPic = $row['group_pic'];
					$showIdentity = $row['show_identity'];
					$showProfile = $row['show_profile'];	
					$showPic = $row['show_pic'];
					$params = explode(",", $row['profile_params']);
					$sendLogs = $row['sendlogs'];
				}
			}

			$result = DBHelper::getUserDAO()->getUserTeam($_SESSION['userid']);
			if (mysqli_num_rows($result) == 0) 
			{
				echo "No other member is assigned to this group. Please contact Administrator. Click <a href=\"logout.php\">here</a> to Logout.";
				exit;
			}
				
			?>
				<div id="homeBody">
					<?php	
						if ($showIdentity) 
						{							
							echo "<div id=\"team\">";
							echo "<div id=\"teamh1\">";
							echo "<h1>Team Info</h1>";
							echo "</div>";
						
							echo "<div id=\"teamDetails\">";
							while ( $row = mysqli_fetch_array($result)) 
							{
								echo "<form method=\"post\" action=\"profile.php\">";
								echo "<input type=\"hidden\" name=\"userId\" value=\"".$row['userid']."\" />";
								echo "<input type=\"hidden\" name=\"userAction\" value=\"View\" />";
								echo "<div class=\"member\">";

								if ($showPic && $row[User::showProfile]) {
									echo "<img src=\"".$row['profilepath']."\" />";
								} else {
									echo "<img src=\"users/default.jpg\" />";
								}
								
								echo "<div id=\"profile\">";
							
								if ($showProfile) {
									echo "<a href=\"profile.php?userId=".$row['userid']."\" class=\"profileName\">".$row['username']."</a>";
									# echo "<span class=\"profileBtn\"><input type=\"submit\" value=\"View Profile\"></span>";
								} else {
									echo "<span class=\"profileName\">".$row['username']."</span>";
								}
								
								echo "<table>";

								if ($showProfile) {									

									$labels = User::getChatParameters();

									foreach ($params as $value) 
									{
										$value = trim($value);

										$uValue = $row[$value];
									/*
										if (strlen($uValue) >= 10) {
											$uValue = substr($uValue, 0, 6)."...";
										}
									*/
										if ($row[$labels[$labels[$value]]]) {
											echo "<tr><td class=\"title\">".$labels[$value]."</td><td class=\"value\"><b>:</b> ".$uValue."</td></tr>";	
										}
										
									}
								}

								echo "</table>";
								echo "</div>";
								echo "</div>";
								echo "</form>";
							}
							echo "</div>";	
							echo "</div>";			// End of team body div
						} else if ($genericIdentity) 
						{							
							echo "<div id=\"team\">";
							echo "<div id=\"teamh1\">";
							echo "<h1>Team Info</h1>";
							echo "</div>";
						
							echo "<div id=\"teamDetails\">";
							while ( $row = mysqli_fetch_array($result)) 
							{
								echo "<form method=\"post\" action=\"profile.php\">";
								echo "<input type=\"hidden\" name=\"userId\" value=\"".$row['userid']."\" />";
								echo "<input type=\"hidden\" name=\"userAction\" value=\"View\" />";
								echo "<div class=\"member\">";

								echo "<img src=\"".$groupPic."\" />";
								echo "<div id=\"profile\">";
								echo "<span class=\"profileName\">".$row['username']."</span>";

								echo "</div>";
								echo "</div>";
								echo "</form>";
							}
							echo "</div>";	
							echo "</div>";			// End of team body div
						}
					?>
					<div id="chat" <?php if(!$showIdentity && !$genericIdentity)	echo "class=\"noidentity\""; ?>>
						<input id="sendLogs" type="hidden" value="<?=$sendLogs?>"/>
						<div id="chath1">
							<h1>Group Chat Area</h1>
						</div>
						<form name="chatForm" >
							<div id="area">
								LOADING CHATLOGS PLEASE WAIT... 
							</div>
							<div id="msg">
								<textarea id="textarea" name="message"></textarea>
							</div>
							<button id="send" onClick=" return submitChat();">Send</button>
						</form>
					</div>			<!-- End of home chat div-->
				</div>				<!-- End of home body div-->
			<?php
		}
	}
?>