<?php

	require_once('DAO.php');
	require_once('obj/User.php');
	require_once('obj/UserProfile.php');
	require_once('obj/StudentProfile.php');

	class UserDAO extends DAO
	{
		
		public function updateLastLogin($userId)
		{
			$userId = $this->escapeParameter($userId);
			$query = "UPDATE users SET lastlogin = NOW() WHERE userid = '$userId'";
			mysqli_query($this->con, $query);			
		}

		public function getAllUsers($sort = 'userid')
		{
			$query = "SELECT u.*, g.groupName FROM users u LEFT JOIN usergroups g ON u.groupid = g.groupid ORDER BY ".$sort;

			$data = mysqli_query($this->con, $query);
			return $data;
		}

		public function getAllNotArchivedUsers($sort = 'userid')
		{
			$query = "SELECT u.*, g.groupName FROM users u LEFT JOIN usergroups g ON u.groupid = g.groupid WHERE u.archived = 0 ORDER BY ".$sort;

			$data = mysqli_query($this->con, $query);
			return $data;
		}

		public function checkUsernamePassword($username, $password)
		{
			$username = $this->escapeParameter($username);
			$password = $this->escapeParameter($password);
			$query = "SELECT * FROM users WHERE username = '$username' AND (password = SHA('$password') OR password = '$password' )";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$user = User::createUserWithData($data);
				return $user;
			} 
			else
			{
				return false;
			}
		}

		public function checkUserExistByEmail($email)
		{
			$email = $this->escapeParameter($email);
			$query = "SELECT * FROM users WHERE email = '$email'";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
				return true;
			else
				return false;
		}
		
		public function checkUserExistByUsername($username)
		{
			$email = $this->escapeParameter($username);
			$query = "SELECT * FROM users WHERE username = '$username'";
		
			$data = mysqli_query($this->con, $query);
		
			if (mysqli_num_rows($data) == 1)
				return true;
			else
				return false;
		}

		public function checkUserExistById()
		{

		}

		public function createNewUser(User $user)
		{
			$username = $user->getUserName();
			$password = $user->getPassword();

		#	$query = "INSERT INTO users (username, password, createlogin, lastlogin) VALUES ('$username', SHA('$password'), NOW(), NOW())";
			$query = "INSERT INTO users (username, password, createlogin, lastlogin) VALUES ('$username', '$password', NOW(), NOW())";

			mysqli_query($this->con, $query);

			return $this->getUserById(mysqli_insert_id($this->con));
		}

		public function createBulkUsers(User $user)
		{
			$username = $user->getUserName();
			$password = $user->getPassword();
			$nextGroup = $user->getGroupId();
			$currGroup = $user->getTestGroupId();
			$session = $user->getSession();

			$query = "INSERT INTO users (username, password ";

			if (!empty($currGroup)) {
				$query .= ", groupid";
			}
			
			if (!empty($nextGroup)) {
				$query .= ", next_groupid";
			}
			
			if (!empty($session)) {
				$query .= ", session";
			}			

			$query .= ", createlogin, lastlogin) ";
		
			$query .= "VALUES ('$username', '$password'";

			if (!empty($currGroup)) {
				$query .= ", $currGroup";
			}
		
			if (!empty($nextGroup)) {
				$query .= ", $nextGroup";
			}
			
			if (!empty($session)) {
				$query .= ", '$session'";
			}
	
			$query .= ", NOW(), NOW())";

			mysqli_query($this->con, $query);
		}

		public function getUserByEmail($email)
		{
			$email = $this->escapeParameter($email);
			$query = "SELECT * FROM users WHERE email = '$email'";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$user = User::createUserWithData($data);
				return $user;
			}
			else
			{
				return null;
			}
		}

		public function countUserByUserName($userName)
		{
			$userName = $this->escapeParameter($userName);
			$query = "SELECT * FROM users WHERE username = '$userName'";

			$data = mysqli_query($this->con, $query);

			return mysqli_num_rows($data);
		}

		public function getUserById($userId)
		{
			$userId = $this->escapeParameter($userId);
			$query = "SELECT * FROM users WHERE userid = '$userId'";

			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$user = User::createUserWithData($data);
				return $user;
			}
			else
			{
				return null;
			}
		}

		public function searchUser($srch)
		{
			$srch = $this->escapeParameter($srch);
			$query = "SELECT * FROM users WHERE firstname LIKE '".$srch."%'";
			$data = mysqli_query($this->con, $query);

		#	$Users = User::createArrayWithData($data);
			
			return $data;	
		}

		public function searchUserForGroup($srch)
		{
			$srch = $this->escapeParameter($srch);
			$query = "SELECT * FROM users WHERE groupid is null and ( firstname LIKE '".$srch."%' OR username LIKE '".$srch."%' )";
			$data = mysqli_query($this->con, $query);
			
			return $data;	
		}

		public function getUserTeam($userid)
		{
			$userid = $this->escapeParameter($userid);
			$query = "SELECT * FROM users WHERE groupid = (select groupid from users where userid = $userid) and userid <> $userid";

			$data = mysqli_query($this->con, $query);
			
			return $data;
		}

		public function getUserGroup($userid)
		{
			$userid = $this->escapeParameter($userid);
			$query = "SELECT groupid FROM users WHERE userid = '$userid'";
			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) == 1) 
			{
				$row = mysqli_fetch_array($data);
				$query = "SELECT * FROM usergroups WHERE groupid = '".$row['groupid']."'";
				return mysqli_query($this->con, $query);
			}
			else
				return $data;
		}

		public function removeUserFromGroup($userId)
		{
			$userId = $this->escapeParameter($userId);
			$query = "UPDATE users SET groupid = NULL WHERE userid = '$userId'";
			mysqli_query($this->con, $query);			
		}

		public function deleteUser($userId)
		{
			$userId = $this->escapeParameter($userId);
			$query = "DELETE FROM users WHERE userid = '$userId'";
			mysqli_query($this->con, $query);
		}

		public function archiveUser($userId, $archive)
		{
			$userId = $this->escapeParameter($userId);
			$archive = $this->escapeParameter($archive);
			$query = "UPDATE users SET archived = $archive WHERE userid = '$userId'";
			mysqli_query($this->con, $query);
		}

		public function updatePassword($userId, $password)
		{
			$userId = $this->escapeParameter($userId);
		#	$query = "UPDATE users SET password = SHA('$password') WHERE userid = '$userId'";
			$query = "UPDATE users SET password = '$password' WHERE userid = '$userId'";
			mysqli_query($this->con, $query);
		}

		public function updateUserProfile(UserProfile $profile)
		{
			$profile = $this->escapeProfileParameters($profile);
			$query = "UPDATE users SET ";
			$query .= "email = '".$profile->getEmail();
			$query .= "', firstname = '".$profile->getFirstName();
			$query .= "', lastname = '".$profile->getLastName();

			if($profile->getAge() && !empty($profile->getAge())){
				$query .= "', age = '".$profile->getAge();	
			}
			
			$query .= "', gender = '".$profile->getGender();
			$query .= "', hometown = '".$profile->getHomeTown();
			$query .= "', homecountry = '".$profile->getHomeCountry();
			$query .= "', favplace = '".$profile->getFavPlace();

			if ($profile->getPicPath()) {
				$query .= "', profilepath = '".$profile->getPicPath();
			}
			
			$query .= "', hobbies = '".$profile->getHobbies();
			$query .= "' WHERE userid = ".$profile->getUserId();

			mysqli_query($this->con, $query);
			
			return $this->getUserById($profile->getUserId());
		}

		public function updateStudentProfile(StudentProfile $profile)
		{
			$profile = $this->escapeStudentProfileParameters($profile);
			$query = "UPDATE users SET ";
			$query .= "major = '".$profile->getMajor();
			$query .= "', class = '".$profile->getStudentClass();
			$query .= "', gpa = '".$profile->getGPA();
			$query .= "', favclass = '".$profile->getFavClass();
			$query .= "', favprof = '".$profile->getFavProf();

			$query .= "' WHERE userid = ".$profile->getUserId();

			mysqli_query($this->con, $query);
			
			return $this->getUserById($profile->getUserId());
		}

		public function updateUserPicFromFB($path, $userId)
		{
			$path = $this->escapeParameter($path);
			$userId = $this->escapeParameter($userId);
			$query = "UPDATE users SET profilepath = '".$path."' WHERE userid = ".$userId;

			mysqli_query($this->con, $query);
		}

		public function updateProfileVisibility($userId, $param, $value)
		{
			$userId = $this->escapeParameter($userId);
			$param = $this->escapeParameter($param);
			$value = $this->escapeParameter($value);

			$query = "UPDATE users SET ".$param." = '".$value."' WHERE userid = ".$userId;
			mysqli_query($this->con, $query);
		}

		public function checkSessionName($session)
		{
			$session = $this->escapeParameter($session);

			$query = "SELECT * FROM users WHERE session = '".$session."'";
			$data = mysqli_query($this->con, $query);

			if (mysqli_num_rows($data) >= 1)
				return TRUE;
			else
				return FALSE; 
		}
		
		public function getAllSessions()
		{

			$query = "SELECT DISTINCT session, groupid, next_groupid FROM users WHERE session IS NOT NULL";
			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function getSessionUsers($session) {
			$session = $this->escapeParameter($session);

			$query = "SELECT * FROM users WHERE session ='".$session."'";
			$data = mysqli_query($this->con, $query);

			return $data;
		}

		public function migrateSession($session) {
			$session = $this->escapeParameter($session);

			$query = "UPDATE users SET groupid = next_groupid, next_groupid = NULL WHERE session = '".$session."'";
			mysqli_query($this->con, $query);
		}

		private function escapeProfileParameters(UserProfile $profile)
		{
			$profile->setUserId($this->escapeParameter($profile->getUserId()));
			$profile->setAge($this->escapeParameter($profile->getAge()));
			$profile->setGender($this->escapeParameter($profile->getGender()));
			return $profile;
		}

		private function escapeStudentProfileParameters(StudentProfile $profile)
		{
			$profile->setUserId($this->escapeParameter($profile->getUserId()));
			return $profile;
		}
	}
?>