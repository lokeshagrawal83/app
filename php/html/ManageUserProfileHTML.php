<?php

	require_once('CreateHTML.php');									//  Paths are relative to PHP (calling) files.
	require_once('obj/User.php');

	class ManageUserProfileHTML extends CreateHTML
	{
		private $msg;											// Error messages specific to page

		public function __construct($msg = '')
		{
			parent::__construct("User Profile");
			$this->msg = $msg;
		}
		
		protected function createScripts()
		{
			?>
				<link rel="stylesheet" type="text/css" href="html/css/userProfile.css">
				<script type="text/javascript" src="html/js/userProfile.js"></script>
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
                            <?php
                            	if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                            		echo "<li><a href=\"admin.php\">Admin</a></li>";
                            	}
                            ?>
                        </ul>
	                </div>
				</div>
			<?php
		}

		protected function createPageBody()
		{
			$this->showErrors($this->msg);
		#	$admin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
		#	$userId = isset($_REQUEST['userId'])? $_REQUEST['userId']: $_SESSION['userid'];
		#	$userSelf = ($userId == $_SESSION['userid']);
		#	$user = DBHelper::getUserDAO()->getUserById($userId);

			?>
				<div id="userProfile">
					<form enctype="multipart/form-data" method="Post" action="profile.php">
					
						<?php 
					#		if (isset($_POST['page']) && $_POST['page'] == "Two")  
					#		{
					#			$this->createSecondProfilePage(); 
					#		} 
					#		else
					#		{	
								$this->createFirstProfilePage(); 
					#		}
						?>		
					</form>
				</div>
			<?php
		}

		private function createFirstProfilePage() 
		{
			$admin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
			$userId = isset($_REQUEST['userId'])? $_REQUEST['userId']: $_SESSION['userid'];
			$userSelf = ($userId == $_SESSION['userid']);
			$user = DBHelper::getUserDAO()->getUserById($userId);

			echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" /> ";

			$allowUser = FALSE;
			$result = DBHelper::getUserDAO()->getUserGroup($_SESSION['userid']);
			if ( $row = mysqli_fetch_array($result)) 
			{
				$allowUser = $row['allow_users'];					
			}

			$showHide = $admin || ($userSelf && $allowUser);

			?>
				<div id="userImg">
					<table><tr><td><label for="pic">Profile Pic:</label></td>
					<?php
						if($showHide)
						{
							echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
								echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
								echo "<input type=\"hidden\" name=\"param\" value=\"".User::showProfile."\" />";
								echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";										
								if ($user->getShowProfile() == TRUE) {
									echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
								} else {
									echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
								}
							echo "</form>";
						}
					?>
					</tr></table>					
					<img src="<?php echo $user->getProfilePath(); ?>">
					<div id="profileBtn">
						<?php
							if ($userSelf) {
						?>								
								<input type="file" name="pic"/><br>
								---------- OR --------------
								<input type="submit" name="fbBtn" value="Fetch from Facebook" /><br>
						<?php
							}
						?>
					</div>
				</div>
				<div id="userDetails">
					<table id="myTable">
						<tr>
							<td class="label">Username:</td>
							<td class="data"><?=$user->getUsername()?></td>
						</tr>
						<tr>
							<td class="label">Email*:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input id=\"email\" name=\"email\" value=\"".$user->getEmail()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getEmail()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showEmail."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";										
										if ($user->getShowEmail() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">First Name:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"firstname\" value=\"".$user->getFirstName()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getFirstName()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showFirstName."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
										if ($user->getShowFirstName() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">Last Name:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"lastname\" value=\"".$user->getLastName()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getLastName()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showLastName."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
										if ($user->getShowLastName() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">Age:</td>
							<?php
								if($userId == $_SESSION['userid'])
									echo "<td class=\"data\"><input name=\"age\" value=\"".$user->getAge()."\" /></td>";
								else if ($admin || ($user->getShowAge() == TRUE))
									echo "<td class=\"data\">".$user->getAge()."</td>";
							
								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showAge."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
										if ($user->getShowAge() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">Gender:</td>
							<td class="data">
								<?php
									if($userSelf)
									{
										if ($user->getGender() == "Male") {
											echo "<input type=\"radio\" name=\"gender\" checked value=\"Male\">Male<br>";
											echo "<input type=\"radio\" name=\"gender\" value=\"F\">Female";
										}
										else if ($user->getGender() == "Female") {
											echo "<input type=\"radio\" name=\"gender\" value=\"Male\">Male<br>";
											echo "<input type=\"radio\" name=\"gender\" checked value=\"Female\">Female";
										}
										else {
											echo "<input type=\"radio\" name=\"gender\" value=\"Male\">Male<br>";
											echo "<input type=\"radio\" name=\"gender\" value=\"Female\">Female";
										}
									}
									else if ($admin || ($user->getShowGender() == TRUE))
									{
										if ($user->getGender() == "Male") {
											echo "Male";													
										}
										else if ($user->getGender() == "Female") {													
											echo "Female";
										}
										else {
											echo "Unknown";
										}
									}
								
									if($showHide)
									{
										echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showGender."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
										if ($user->getShowGender() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
										echo "</form>";
									}
								?>
							</td>
						</tr>
						<tr>
							<td class="label">Hometown:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"homeTown\" value=\"".$user->getHomeTown()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getHomeTown()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showTown."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
										if ($user->getShowHomeTown() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">Home Country:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"homeCountry\" value=\"".$user->getHomeCountry()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getHomeCountry()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
										echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
										echo "<input type=\"hidden\" name=\"param\" value=\"".User::showCountry."\" />";
										echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
										if ($user->getShowHomeCountry() == TRUE) {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
										} else {
											echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
										}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">Favourite Place:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"favPlace\" value=\"".$user->getFavPlace()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getFavPlace()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showPlace."\" />";
									echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
									if ($user->getShowFavPlace() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label2">Major:</td>									
							<?php
								if($userId == $_SESSION['userid'])
									echo "<td class=\"data\"><input name=\"major\" value=\"".$user->getMajor()."\" /></td>";
								else if ($admin || ($user->getShowMajor() == TRUE))
									echo "<td class=\"data\">".$user->getMajor()."</td>";
							
								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showMajor."\" />";
									if ($user->getShowMajor() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label2">Class Standing:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"class\" value=\"".$user->getStudentClass()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getStudentClass()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showClass."\" />";
									if ($user->getShowClass() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label2">GPA:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"gpa\" value=\"".$user->getGPA()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getGPA()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showGPA."\" />";
									if ($user->getShowGPA() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label2">Favourite Class:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"favClass\" value=\"".$user->getFavClass()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getFavClass()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showFavClass."\" />";
									if ($user->getShowFavClass() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label2">Favourite Professor:</td>
							<?php
								if($userSelf)
									echo "<td class=\"data\"><input name=\"favProf\" value=\"".$user->getFavProf()."\" /></td>";
								else
									echo "<td class=\"data\">".$user->getFavProf()."</td>";

								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showFavProf."\" />";
									if ($user->getShowFavProf() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
						<tr>
							<td class="label">Hobbies:</td>
							<?php
								if($userSelf) 
								{
									echo "<td class=\"data\"><input name=\"hobby[]\" value=\"".$user->getHobbies()."\" /><button id=\"addHobby\">Add</button></td>";
								} else {
									echo "<td class=\"data\">".$user->getHobbies()."</td>";
								} 
								
								if($showHide)
								{
									echo "<form method=\"Post\" action=\"".$_SERVER['PHP_SELF']."\">";
									echo "<input type=\"hidden\" name=\"userId\" value=\"".$user->getId()."\" />";
									echo "<input type=\"hidden\" name=\"param\" value=\"".User::showHobby."\" />";
									echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
									if ($user->getShowHobby() == TRUE) {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Hide\" /></td>";												
									} else {
										echo "<td><input type=\"submit\" name=\"profileAction\" value=\"Show\" /></td>";
									}
									echo "</form>";
								}
							?>
						</tr>
					</table>
					<div id="updateBtn">
						<?php

							if($userSelf) {
								echo "<input type=\"hidden\" name=\"profilePage\" value=\"One\" />";
								echo "<input type=\"submit\" name=\"userAction\" value=\"Update\" />";
							}

					#		echo "<input type=\"hidden\" name=\"page\" value=\"Two\" />";
					#		echo "<input id=\"nextBtn\" type=\"submit\" name=\"userAction\" value=\"Next\" />";
						?>
					</div>
					<br/>
				</div>
			<?php
		}

/*		private function createSecondProfilePage() 
		{
			$admin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
			$userId = isset($_REQUEST['userId'])? $_REQUEST['userId']: $_SESSION['userid'];
			$userSelf = ($userId == $_SESSION['userid']);
			$user = DBHelper::getUserDAO()->getUserById($userId);

			$allowUser = FALSE;
			$result = DBHelper::getUserDAO()->getUserGroup($_SESSION['userid']);
			if ( $row = mysqli_fetch_array($result)) 
			{
				$allowUser = $row['allow_users'];					
			}

			$showHide = $admin || ($userSelf && $allowUser);

			?>
				<div id="userImg">
					<img src="<?php echo $user->getProfilePath(); ?>">
				</div>
				<div id="userDetails">
					<table id="myTable2">
						
					</table>
					<div id="updateBtn">
						<?php
							echo "<input type=\"submit\" name=\"userAction\" value=\"Back\" />";
							if($userSelf) {
								echo "<input type=\"hidden\" name=\"page\" value=\"Two\" />";								
								echo "<input type=\"submit\" name=\"userAction\" value=\"Update\" />";
							}
						?>
					</div>
				</div>
			<?php
		}
*/	}
?>