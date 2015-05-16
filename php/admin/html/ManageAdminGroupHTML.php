<?php

	require_once('CreateAdminHTML.php');									//  Paths are relative to PHP (calling) files.

	class ManageAdminGroupHTML extends CreateAdminHTML
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
				<link rel="stylesheet" type="text/css" href="admin/html/css/manageGroup.css">
				<script type="text/javascript" src="admin/html/js/manageGroup.js"></script>
			<?php
		}

		protected function createPageBody()
		{
			$archived = TRUE;

			if (isset($_POST['groupAction']) && $_POST['groupAction'] == 'Get All Groups') {
				$archived = FALSE;
				$result = DBHelper::getUserGroupDAO()->getAllGroups();
			} else {
				$result = DBHelper::getUserGroupDAO()->getAllNotArchivedGroups();
			}
			
			?>	
				<div id="groupWrapper1">		<!-- Just to handle footer overlap -->
					<?php  
						$this->showErrors($this->error_msg);  					// Parent function call to show errors
					?>
					<div id="tableDisplay">
						<?php
							echo "<form method=\"post\" action=\"".$_SERVER['SELF_PHP']."\">";

							if ($archived) {
								echo "<input type=\"submit\" name=\"groupAction\" value=\"Get All Groups\" />";
							} else {
								echo "<input type=\"submit\" name=\"groupAction\" value=\"Show Not Archived\" />";
							}

							echo "</form>";
						?>
						<table border="1px">
							<th align="center">Group Name</th>
							<th align="center"> Actions </th>
							<th align="center"> User Profile </th>
							<?php
								while ($row = mysqli_fetch_array($result)) {
								?>
									<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										<tr>
											<td width="100px" align="center"><?php echo $row['groupName']; ?></td>
											<td width="300px" align="center">
												<input type="submit" name="groupAction" value="Edit" />
												<input type="submit" name="groupAction" value="Delete" />
												<input type="submit" name="groupAction" value="Chat Logs" />
												<?php
													if ($row['archived'] == 0) {
														echo "<input type=\"submit\" name=\"groupAction\" value=\"Archive\" /></td>";
													} else {
														echo "<input type=\"submit\" name=\"groupAction\" value=\"Unarchive\" /></td>";
													}
												?>
											</td>
											<td width="100px" align="center">
												<input type="submit" name="groupAction" value="Settings" />												
											</td>
										</tr>
										<input type="hidden" name="groupId" value="<?php echo $row['groupid']; ?>">
										<input type="hidden" name="groupName" value="<?php echo $row['groupName']; ?>">
									</form>
								<?php
								}
							?>
						</table>
					</div>
					<?php
						if (isset($_POST['groupId']) && $_POST['groupId'] != "") 
						{
							$result = DBHelper::getUserGroupDAO()->getGroupById($_POST['groupId']);
							$row = mysqli_fetch_array($result);
							$params = explode(",", $row['profile_params']);
							
							echo "<div id=\"groupWrapper\">";							

							if ($_POST['groupAction'] == "Settings" || $_POST['groupAction'] == "Save") {	
					?>
								<h1>Manage Group Chat</h1>
								<div id="settings">
									<table  width="480px">
										<form enctype="multipart/form-data" method="Post" action="admin.php" >
											<tr>
												<td colspan=2><fieldset>
													<legend align="left">Profile Settings:</legend>
													<table>		
													<tr id="genericIdentity">
														<td>Generic Identity:</td>														
														<?php															
															if ($row['generic_identity'] == TRUE) 
															{
																echo "<td align=\"left\"> <input id=\"gIdentity\" name=\"gIdentity\" type=\"checkbox\" checked /></td>";
															}
															else
															{
																echo "<td align=\"left\"> <input id=\"gIdentity\" name=\"gIdentity\" type=\"checkbox\" /></td>";
															}
														?>
													</tr>	
													<tr id="groupPic">
														<td>Group Pic:</td>														
														<td align="left"><img width="100px" height="100px" src="<?=$row['group_pic'] ?>"/></td>
													</tr>	
													<tr id="browsePic">
														<td></td>														
														<td align="left"><input type="file" name="browse"/></td>
													</tr>									
													<tr id="showIdentity">
														<td>Show Identity:</td>														
														<?php															
															if ($row['show_identity'] == TRUE) 
															{
																echo "<td align=\"left\"> <input id=\"identity\" name=\"identity\" type=\"checkbox\" checked /></td>";
															}
															else
															{
																echo "<td align=\"left\"> <input id=\"identity\" name=\"identity\" type=\"checkbox\" /></td>";
															}
														?>
													</tr>
													<tr id="profile">									
														<td>Show Profile:</td>
														<?php 
															if ($row['show_profile'] == TRUE) 
															{
																echo "<td align=\"left\"> <input name=\"profile\" type=\"checkbox\" checked /></td>";
															}
															else
															{
																echo "<td align=\"left\"> <input name=\"profile\" type=\"checkbox\" /></td>";
															}
														?>														
													</tr>
													<tr id="pic">
														<td>Show Profile Pic:</td>
														<?php 
															if ($row['show_pic'] == TRUE) 
															{
																echo "<td align=\"left\"> <input name=\"profilePic\" type=\"checkbox\" checked /></td>";
															}
															else
															{
																echo "<td align=\"left\"> <input name=\"profilePic\" type=\"checkbox\" /></td>";
															}
														?>												
													</tr>
													<tr id="allowUsers">
														<td>Allow Users:</td>														
														<?php															
															if ($row['allow_users'] == TRUE) 
															{
																echo "<td align=\"left\"> <input name=\"allowUsers\" type=\"checkbox\" checked /></td>";
															}
															else
															{
																echo "<td align=\"left\"> <input name=\"allowUsers\" type=\"checkbox\" /></td>";
															}
														?>
													<tr>
													<tr id="param1">
														<td>Choose Parameters:</td>
														<td><?=$this->getProfileParameters($params[0]);?></td>
													</tr>
													<tr id="param2">
														<td></td>
														<td><?=$this->getProfileParameters($params[1]);?></td>
													</tr>
													<tr id="param3">
														<td></td>
														<td><?=$this->getProfileParameters($params[2]);?></td>
													</tr>
													</table>
												</fieldset></td>
											</tr>
											<tr>
												<td colspan=2><fieldset>
													<legend align="left">Chat Settings:</legend>
													<table>
													<tr>
														<td>Chat Messages:</td>
														<td align="left">
															<select name="chatLogs">
																<?php
																	if ($row['chatlogs'] == TRUE) 
																	{
																		echo "<option value=\"1\" selected>All Messages</option>";
																		echo "<option value=\"0\">Latest Message</option>";
																	} 
																	else 
																	{
																		echo "<option value=\"1\">All Messages</option>";
																		echo "<option value=\"0\" selected>Latest Message</option>";
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td>Send Messages:</td>
														<td>
															<select name="sendLogs">
																<?php
																	if ($row['sendlogs'] == TRUE) 
																	{
																		echo "<option value=\"1\" selected>Complete Messages</option>";
																		echo "<option value=\"0\">Partial Message</option>";
																	} 
																	else 
																	{
																		echo "<option value=\"1\">Complete Messages</option>";
																		echo "<option value=\"0\" selected>Partial Message</option>";
																	}
																?>																
															</select>
														</td>
													</tr>													
													</table>
												</fieldset></td>
											</tr>
											<tr/><tr/><tr/>											
											<input type="hidden" name="groupId" value="<?php echo $row['groupid']; ?>">
											<input type="hidden" name="groupName" value="<?php echo $row['groupName']; ?>">
											<tr><td></td><td><input type="submit" name="groupAction" value="Save" /></td></tr>
										</form>
									</table>
								</div>
					<?php
							} else {
					?> 
								<h1>Manage Group</h1>
								<div id="group">
									<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										<input type="hidden" name="groupId" value="<?php echo $_POST['groupId']; ?>">
										<label for="groupName"> Group Name:</label>
										<input id="groupName" name="groupName" value="<?php echo $row['groupName']; ?>">
										<input id="editGroupBtn" name="groupAction" type="submit" value="Change" />
									</form>
									<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
										<input type="hidden" name="groupId" value="<?php echo $_POST['groupId']; ?>">
										<input type="hidden" name="userId" id="userId" />
										<label for="srchUser"> Search User:</label>
										<input id="srchUser" type="text" />
										<button id="addUserBtn" name="groupAction" value="AddUser">Add</button>
									</form>
									<div id="users">
										<?php
											$result2 = DBHelper::getUserGroupDAO()->getAllUsersInGroup($_POST['groupId']);
											while ($row2 = mysqli_fetch_array($result2)) {
												?>
													<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
														<input type="hidden" name="groupId" value="<?php echo $_POST['groupId']; ?>">
														<div class="userrow">
															<input type="text" readonly value="<?php echo $row2['username'] ; ?>" />
															<input type="hidden" name="userId" value="<?php echo $row2['userid']; ?>" />
															<button name="groupAction" value="RemoveUser">Remove</button>
														</div>
													</form>
												<?php
											}
										?>											
									</div>
									<div id="actions">
										<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
											<input type="submit" value="Cancel" />
										</form>
									</div>
								</div>
							
					<?php 
							}
							echo "</div>";
						} 
					?>
				</div>
			<?php	
		}

		private function getProfileParameters( $value )
		{
			$params = User::getProfileParameters();	

			$value = trim($value);
			
			echo "<select name=\"param[]\">";

			foreach ($params as $key => $param) 
			{
				$str = "<option value=\"".$key."\"";
				
				if ($value == $key) 
				{
					$str .= " selected";
				}

				$str .= ">".$param."</option>";
				echo $str;
			}

			echo "</select>";		
		}
	}
?>