<?php

	require_once('html/HtmlBuilder.php');				// Helper object for creating HTML	
	require_once('obj/UserProfile.php');
	require_once('obj/StudentProfile.php');
	require_once('util/FBUtil.php');

	function manageUserProfileScreen($msg = NULL)
	{
		HtmlBuilder::manageUserProfileHTML($msg);
	}

	function validateProfilePic()
	{
		if(isset($_FILES['pic']['name']) && $_FILES['pic']['name'] != "")
		{
			$msg = "";
			$pic = $_FILES['pic'];

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

			$target = "users/".$_POST['userId'].".jpg";
			$source = $pic['tmp_name'];
			if(move_uploaded_file($source, $target)) {
				@unlink($source);
			} else {
				$msg = "Failed to move uploaded file.";
			}

			if($msg != "")
			{
				manageUserProfileScreen($msg);	
				exit;
			}			

			return $target;
		}
	}

	function validateUserInput()
	{
		extract($_POST);

		if (isset($email) && !empty($email)) {
			$user = DBHelper::getUserDAO()->getUserByEmail($email);
			if($user && $user->getId() != $_POST['userId']){
				manageUserProfileScreen("Email is already registered.");
				exit;
			}
		} else {
			manageUserProfileScreen("Email is mandatory field.");
			exit;
		}
	/*
		if (!isset($age) || empty($age) ) {
			manageUserProfileScreen("Age is required.");	
			exit;
		}

		if (!isset($gender) || empty($gender) ) {
			manageUserProfileScreen("Gender is required.");	
			exit;
		}

		if (!isset($major) || empty($major) ) {
			manageUserProfileScreen("Major is required.");	
			exit;
		}
	*/		
	}

	function updateUserProfile1()
	{
		$userSelf = ($_POST['userId'] == $_SESSION['userid']);
		$msg = "";
		
		if ($userSelf) 
		{
			validateUserInput();
			$path = validateProfilePic();
			$hobbies = implode(", ", $_POST['hobby']);

			$profile = new UserProfile($_POST['userId']);
			$profile->setEmail($_POST['email']);
			$profile->setPicPath($path);
			$profile->setFirstName($_POST['firstname']);
			$profile->setLastName($_POST['lastname']);
			$profile->setAge($_POST['age']);
			$profile->setGender($_POST['gender']);
			$profile->setHomeTown($_POST['homeTown']);
			$profile->setHomeCountry($_POST['homeCountry']);
			$profile->setFavPlace($_POST['favPlace']);
			$profile->setHobbies($hobbies);
			$user = DBHelper::getUserDAO()->updateUserProfile($profile);

			$profile = new StudentProfile($_POST['userId']);
			$profile->setMajor($_POST['major']);
			$profile->setStudentClass($_POST['class']);
			$profile->setGPA($_POST['gpa']);
			$profile->setFavClass($_POST['favClass']);
			$profile->setFavProf($_POST['favProf']);

			DBHelper::getUserDAO()->updateStudentProfile($profile);

			
			$_SESSION['fullname'] = $user->getFullName();

			$msg = "success->User profile is successfully updated. Click <a href=\"home.php\">here</a> to go to home page.";
		}
		
		manageUserProfileScreen($msg);		
	}

	function updateUserProfile2()
	{
		$userSelf = ($_POST['userId'] == $_SESSION['userid']);

		if ($userSelf) 
		{
			$profile = new StudentProfile($_POST['userId']);
			$profile->setMajor($_POST['major']);
			$profile->setStudentClass($_POST['class']);
			$profile->setGPA($_POST['gpa']);
			$profile->setFavClass($_POST['favClass']);
			$profile->setFavProf($_POST['favProf']);

			DBHelper::getUserDAO()->updateStudentProfile($profile);
		}

		$msg = "success->User profile is successfully updated. Click <a href=\"home.php\">here</a> to go to home page.";
		manageUserProfileScreen($msg);		
	}

	function updateProfileVisibility()
	{
		DBHelper::getUserDAO()->updateProfileVisibility($_POST['userId'], $_POST['param'], $_POST['profileAction'] == "Show" ? 1 : 0);

		$msg = "success->User profile is successfully updated.";
		manageUserProfileScreen($msg);		
	}

	session_start();

	$req = $_SERVER['REQUEST_METHOD'];

	switch ($req) {

		case 'POST':

			extract($_POST);
		
			if (isset($userAction) && $userAction == "Next")
			{
				updateUserProfile1();
			} 
			else if (isset($userAction) && $userAction == "Back")
			{
				$_POST['page'] = "One";
				manageUserProfileScreen();
			}
			else if (isset($userAction) && $userAction == "Update")
			{
			#	if ($_POST['profilePage'] == "One") {
			#		$_POST['page'] = "One";
					updateUserProfile1();
			#	} else{
			#		updateUserProfile2();
			#	}
			}
			else if (isset($profileAction))
			{
		#		if($_POST['profilePage'] == "One")
		#			$_POST['page'] = "One";
		#		else
		#			$_POST['page'] = "Two";

				updateProfileVisibility();
			}
			else if (isset($fbBtn))
			{
				$fbutil = new FBUtil();
				$fbutil->login();
			}

			break;

		case 'GET':
			
			extract($_GET);

			if (isset($fbBtn)) 
			{
				$fbutil = new FBUtil();
				$path = $fbutil->getProfilePic();
			} 
			else 
			{
				manageUserProfileScreen();
			}

			break;
	}
?>