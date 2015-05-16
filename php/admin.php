<?php
	require_once('html/HtmlBuilder.php');				// Helper object for creating HTML	
	require_once('db/DBHelper.php');
	require_once('obj/UserGroup.php');

	$error_msg = "";

	function mainscreen($error_msg = NULL)
	{
		HtmlBuilder::adminHTML($error_msg);
	}

	function manageUserProfileScreen($msg = NULL)
	{
		HtmlBuilder::manageUserProfileHTML($msg);
	}

	function createGroupScreen($error_msg)
	{
		HtmlBuilder::adminGroupHTML($error_msg);
	}

	function createUserScreen($error_msg = NULL)
	{
		HtmlBuilder::adminUserHTML($error_msg);
	}

	function manageGroupScreen($error_msg = NULL)
	{
		HtmlBuilder::manageGroupHTML($error_msg);
	}

	function manageUserScreen($error_msg = NULL)
	{
		HtmlBuilder::manageUserHTML($error_msg);
	}
	
	function managePermissionsScreen($msg = NULL) 
	{
		HtmlBuilder::managePermissionsHTML($msg);
	}
	
	function manageSessionsScreen($msg = NULL) 
	{
		HtmlBuilder::sessionUserHTML($msg);
	}

	function viewGroupChatScreen()
	{
		HtmlBuilder::viewGroupChatHTML(NULL);
	}

	function getUsers($term)
	{
		$data = array();

		$result = DBHelper::getUserDAO()->searchUserForGroup($term);
		while ( $row = mysqli_fetch_array($result)) 
		{
			$data[] = array
						(
							'label' => $row['username'], 
							'value' => $row['userid']
						);
		}

		echo json_encode($data);
	}

	function migrateSessionUser(){
		DBHelper::getUserDAO()->migrateSession($_POST['sessionName']);
		$msg = "success->Session ".$_POST['sessionName']." is migrated successfully.";
		manageSessionsScreen($msg);
	}

	function archiveUser($archive){
		DBHelper::getUserDAO()->archiveUser($_POST['userId'], $archive);
		if($archive == 1)
			$msg = "success->User ".$_POST['userName']." is archived successfully.";
		else
			$msg = "success->User ".$_POST['userName']." is unarchived successfully.";
		manageUserScreen($msg);
	}

	function archiveGroup($archive){
		DBHelper::getUserGroupDAO()->archiveGroup($_POST['groupId'], $archive);
		if($archive == 1)
			$msg = "success->Group ".$_POST['groupId']." is archived successfully.";
		else
			$msg = "success->Group ".$_POST['groupId']." is unarchived successfully.";
		manageGroupScreen($msg);
	}

	function validateBulkUserCreation(){

		$userarea = $_POST['userarea'];			

		if (empty($userarea)) {
			$msg = "Users are empty.";
			createUserScreen($msg);
			exit();
		}	

		$users = explode(PHP_EOL, $userarea);
		foreach ($users as $key => $value) {
			$inputs = explode(",", $value);
			if(count($inputs) != 4){
				$msg = "Syntax not correct at line # ".($key + 1);
				createUserScreen($msg);
				exit();
			}

			$userResult = DBHelper::getUserDAO()->countUserByUserName($inputs[0]);
			if($userResult > 0){
				$msg = "User with username ".$inputs[0]." at line # ".($key + 1)." already exist.";
				createUserScreen($msg);
				exit();
			}

			if (!empty($inputs[2])) {
				$group = DBHelper::getUserGroupDAO()->countGroupByName($inputs[2]);
				if($group == 0){
					$msg = "Current group name at line # ".($key + 1)." does not exist.";
					createUserScreen($msg);
					exit();
				}
			}
				
			if (!empty($inputs[3])) {
				$group = DBHelper::getUserGroupDAO()->countGroupByName($inputs[3]);
				if($group == 0){
					$msg = "Next group name at line # ".($key + 1)." does not exist.";
					createUserScreen($msg);
					exit();
				}	
			}
		}
	}

	function createUsers()
	{
		validateBulkUserCreation();

		$userarea = $_POST['userarea'];					

		$users = explode(PHP_EOL, $userarea);
		foreach ($users as $value) {
			$inputs = explode(",", $value);

			$user = User::createUserWithParameters($inputs[0], $inputs[1]);

			$group = DBHelper::getUserGroupDAO()->getGroupByName($inputs[2]);
			if($row = mysqli_fetch_array($group)){
				$groupid = $row['groupid'];
				$user->setTestGroupId($groupid);
			}

			$group = DBHelper::getUserGroupDAO()->getGroupByName($inputs[3]);
			if($row = mysqli_fetch_array($group)){
				$groupid = $row['groupid'];
				$user->setGroupId($groupid);
			}
			
			$user->setSession($_POST['session']);

			DBHelper::getUserDAO()->createBulkUsers($user);
		}

		$msg = "success->Users are created successfully.";
		createUserScreen($msg);
	}

	function createGroup()
	{
		global $error_msg;

		if (empty($_POST['groupName'])) {
			$error_msg = "Group name cannot be empty.";
			createGroupScreen($error_msg);
			exit();
		}

		if(DBHelper::getUserGroupDAO()->checkGroupExist($_POST['groupName']))
		{
			$error_msg = "User group '".$_POST['groupName']."' already exists.";
			createGroupScreen($error_msg);
		}
		else
		{
			$userArray = array();
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 7) == "selUser") {
					array_push($userArray, substr($key, 7));
				}
			}

			DBHelper::getUserGroupDAO()->createGroup($_POST['groupName'], $userArray);
			$error_msg = "success->User group '".$_POST['groupName']."' is successfully created.";
			mainscreen($error_msg);
		}
	}

	function deleteGroup()
	{
		DBHelper::getUserGroupDAO()->deleteGroup($_POST['groupId']);
		$error_msg = "success->User group '".$_POST['groupName']."' is successfully deleted.";
		mainscreen($error_msg);
	}

	function validateGroupPic()
	{
		if(isset($_FILES['browse']['name']) && $_FILES['browse']['name'] != "")
		{
			$msg = "";
			$pic = $_FILES['browse'];

			$error = $pic['error'];
			if ($error != 0 ) {
				$msg = "Error while uploading profile pic.";
			}

			$type = $pic['type'];
			if ($type != "image/gif" && $type != "image/jpeg" && $type != "image/pjpeg" && $type != "image/png") {
				$msg = "Supported file format are GIF, JPEG or PNG";	
			}

			$size = $pic['size'];
			if ($size > 1280472) {
				$msg = "Uploaded file is too large.";
			}

			$target = "users/groups/".$_POST['groupId'].".jpg";
			$source = $pic['tmp_name'];
			if(move_uploaded_file($source, $target)) {
				@unlink($source);
			} else {
				$msg = "Failed to move uploaded file.";
			}

			if($msg != "")
			{
				manageGroupScreen($msg);	
				exit;
			}			

			return $target;
		}
	}

	function updateUserGroup()
	{
		extract($_POST);

		$path = validateGroupPic();
		$group = new UserGroup($groupId);
		$group->setGenericIdentity( $gIdentity == "on" ? 1 : 0);
		$group->setGroupPic( $path);
		$group->setShowIdentity( $identity == "on" ? 1 : 0);
		$group->setShowProfile( $profile == "on" ? 1 : 0);
		$group->setShowPic( $profilePic == "on" ? 1 : 0);
		$group->setAllowUsers( $allowUsers == "on" ? 1 : 0);
		$group->setParams(implode(", ", $param));
		$group->setChatLogs($chatLogs);
		$group->setSendLogs($sendLogs);

		DBHelper::getUserGroupDAO()->updateChatSettings($group);
		
		$msg = "success->User group '".$_POST['groupName']."' is successfully updated.";
		manageGroupScreen($msg);
	}

	function changeGroupName()
	{
		global $error_msg;

		if(DBHelper::getUserGroupDAO()->checkGroupExist($_POST['groupName']))
		{
			$error_msg = "User group with group name '".$_POST['groupName']."' already exists.";
			manageGroupScreen($error_msg);
		}
		else
		{
			DBHelper::getUserGroupDAO()->changeGroupName($_POST['groupId'], $_POST['groupName']);
			$error_msg = "success->User group is successfully changed to '".$_POST['groupName']."' .";
			mainscreen($error_msg);
		}
	}

	function removeUserFromGroup()
	{
		DBHelper::getUserDAO()->removeUserFromGroup($_POST['userId']);
		$error_msg = "success->User is successfully removed from the group.";
		manageGroupScreen($error_msg);
	}

	function addUserToGroup()
	{
		DBHelper::getUserGroupDAO()->addUserToGroup($_POST['groupId'], $_POST['userId']);
		$error_msg = "success->User is successfully added to the group.";
		manageGroupScreen($error_msg);
	}

	function deleteUser()
	{
		DBHelper::getUserDAO()->deleteUser($_POST['userId']);
		$error_msg = "success->User '".$_POST['userName']."' is successfully deleted.";
		mainscreen($error_msg);
	}

	function updateUserRoles()
	{
		DBHelper::getPermissionDAO()->deleteUserRoles($_POST['userId']);

		foreach ($_POST as $key => $value) {
			if ($value == "on") {
				DBHelper::getPermissionDAO()->updateUserRoles($_POST['userId'], $key);
			}
		}

		$msg = "success->User roles are updated successfully.";
		managePermissionsScreen($msg);
	}

	function resetPassword()
	{
		?>
			<form method="post" action="<?=$_SERVER['SELF_PHP']?>">
				<input type="hidden" name="userId" value="<?=$_POST['userId']?>"/>
				<input type="hidden" name="userName" value="<?=$_POST['userName']?>"/>
				<table>
					<tr><td>User Fullname:</td><td><?=$_POST['userName']?></td></tr>
					<tr><td>New Password:</td><td><input name="password1" type="password"/></td></tr>
					<tr><td>Confirm Password:</td><td><input name="password2" type="password"/></td></tr>
					<tr><td></td><td><input type="submit" name="userAction" value="Update Password"/></td></tr>
				</table>
			</form>
		<?php
	}

	function updatePassword()
	{
		if (isset($_POST['password1']) && !empty($_POST['password1']) && ( $_POST['password1'] == $_POST['password2'] )) 
		{
			DBHelper::getUserDAO()->updatePassword($_POST['userId'], $_POST['password1']);
			$msg = "success->Password updated successfully.";
			mainScreen($msg);
		} 
		else 
		{	
			manageUserScreen("Either password is empty or do not match.");
		#	resetPassword("Either password is empty or do not match.");
		}
	}

	session_start();

	$req = $_SERVER['REQUEST_METHOD'];

	switch ($req) {

		case 'GET':

			extract($_GET);

			if (isset($action) && $action == "srchUser") 
			{
				getUsers($term);
			}
			else if (isset($action) && $action == "createGroups") 
			{
				createGroupScreen($error_msg);
			}
			else if (isset($action) && $action == "createUsers") 
			{
				createUserScreen($error_msg);
			}
			else if (isset($action) && $action == "manageGroups") 
			{
				manageGroupScreen($error_msg);
			}
			else if (isset($action) && $action == "manageUser") 
			{
				manageUserScreen();
			}
			else if (isset($action) && $action == "managePerm")
			{
				managePermissionsScreen();
			}
			else if (isset($action) && $action == "sessionUsers")
			{
				manageSessionsScreen();
			}
			else
			{
				mainScreen($error_msg);
			}
			
			break;
		
		case 'POST':

			extract($_POST);

			if (isset($groupAction) && $groupAction == "Create") 
			{
				createGroup();
			}
			else if (isset($groupAction) && $groupAction == "Delete") 
			{
				deleteGroup();
			}
			else if (isset($groupAction) && ($groupAction == "Edit" || $groupAction == "Settings" ))
			{
				manageGroupScreen();
			}
			else if (isset($groupAction) && $groupAction == "Change") 
			{
				changeGroupName();
			}
			else if (isset($groupAction) && $groupAction == "RemoveUser") 
			{
				removeUserFromGroup();
			}
			else if (isset($groupAction) && $groupAction == "AddUser") 
			{
				addUserToGroup();
			}
			else if (isset($groupAction) && $groupAction == "Save")  //($groupAction == "Show" || $groupAction == "Hide")) 
			{
				updateUserGroup();
			}
			else if (isset($groupAction) && $groupAction == "Chat Logs") 
			{
				viewGroupChatScreen();
			}
			else if (isset($groupAction) && ($groupAction == "Archive" OR $groupAction == "Unarchive"))
			{
				if($groupAction == "Archive")
					archiveGroup(1);
				else
					archiveGroup(0);
			}
			else if (isset($groupAction) && ($groupAction == "Get All Groups" || $groupAction == "Show Not Archived"))
			{
				manageGroupScreen();
			}
			else if (isset($roleAction) && $roleAction == "Update") 
			{
				updateUserRoles();
			}
			else if (isset($adminAction) && $adminAction == "Create Users") 
			{
				createUsers();
			}
			else if (isset($userAction) && ($userAction == "View" || $userAction == "Edit" || $userAction == "Delete"))
			{
				manageUserProfileScreen();
			} 
			else if (isset($userAction) && ($userAction == "Reset Password"))
			{
				resetPassword();
			}
			else if (isset($userAction) && ($userAction == "Archive" OR $userAction == "Unarchive"))
			{
				if($userAction == "Archive")
					archiveUser(1);
				else
					archiveUser(0);
			}
			else if (isset($userAction) && ($userAction == "Get All Users" || $userAction == "Show Not Archived"))
			{
				manageUserScreen();
			}
			else if (isset($userAction) && ($userAction == "Update Password"))
			{
				updatePassword();
			}
			else if (isset($sessionAction) && ($sessionAction == "View"))
			{
				manageSessionsScreen();
			}
			else if (isset($sessionAction) && ($sessionAction == "Migrate"))
			{
				migrateSessionUser();
			}			
			else
			{
				print_r($_POST);
				mainScreen($error_msg);
			}
			
			break;
	}
?>