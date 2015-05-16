<?php

	class User
	{
		const UserId 		= 'userid';
		const Username		= 'username';
		const Email 		= 'email';
		const showEmail		= 'show_email';
		const FirstName 	= 'firstname';
		const showFirstName	= 'show_fname';
		const LastName 		= 'lastname';
		const showLastName	= 'show_lname';
		const ProfilePath 	= 'profilepath';
		const showProfile	= 'show_profile';
		const age 			= 'age';
		const showAge 		= 'show_age';
		const gender 		= 'gender';
		const showGender	= 'show_gender';
		const HomeTown 		= 'hometown';
		const showTown 		= 'show_town';
		const HomeCountry	= 'homecountry';
		const showCountry	= 'show_country';
		const FavPlace 		= 'favplace';
		const showPlace		= 'show_place';
		const hobbies		= 'hobbies';
		const showHobby		= 'show_hobby';
		const major 		= 'major';
		const showMajor		= 'show_major';
		const studentClass	= 'class';
		const showClass		= 'show_class';
		const GPA 	 		= 'gpa';
		const showGPA		= 'show_gpa';
		const FavClass 		= 'favclass';
		const showFavClass	= 'show_favclass';
		const FavProf 		= 'favprof';
		const showFavProf	= 'show_favprof';
		const CreateLogin 	= 'createlogin';
		const LastLogin 	= 'lastlogin';
		const GroupId		= 'groupid';
		const TestGroupId	= 'test_groupid';
		const Session 		= 'session';

		private $userid;
		private $username;
		private $email;
		private $showEmail;
		private $firstName;
		private $showFirstName;
		private $lastName;
		private $showLastName;
		private $password;
		private $profilePath;
		private $showProfile;
		private $age;
		private $showAge;
		private $gender;
		private $showGender;
		private $homeTown;
		private $showTown;
		private $homeCountry;
		private $showCountry;
		private $favPlace;
		private $showFavPlace;
		private $hobbies;
		private $showHobby;
		private $major;
		private $showMajor;
		private $studentClass;
		private $showClass;
		private $gpa;
		private $showGPA;
		private $favClass;
		private $showFavClass;
		private $favProf;
		private $showFavProf;
		private $lastLogin;
		private $groupId;
		private $testGroupId;
		private $session;		

		public function __construct()
		{

		}

		public static function createUserWithParameters($username, $password)
		{
			$user = new User();
			$user->username 		= $username;
			$user->password 		= $password;
			return $user;
		}

		public static function createUserWithData($data)
		{			
			$row = mysqli_fetch_array($data);
			$user = new User();
			$user->userid 		= $row[self::UserId];
			$user->username		= $row[self::Username];
			$user->email 		= $row[self::Email];
			$user->showEmail 	= $row[self::showEmail];
			$user->firstName 	= $row[self::FirstName];
			$user->showFirstName= $row[self::showFirstName];
			$user->lastName 	= $row[self::LastName];
			$user->showLastName = $row[self::showLastName];
			$user->profilePath 	= $row[self::ProfilePath];
			$user->showProfile 	= $row[self::showProfile];
			$user->age 			= $row[self::age];
			$user->showAge 		= $row[self::showAge];
			$user->gender		= $row[self::gender];
			$user->showGender	= $row[self::showGender];
			$user->homeTown 	= $row[self::HomeTown];
			$user->showTown 	= $row[self::showTown];
			$user->homeCountry 	= $row[self::HomeCountry];
			$user->showCountry 	= $row[self::showCountry];
			$user->favPlace 	= $row[self::FavPlace];
			$user->showPlace 	= $row[self::showPlace];
			$user->hobbies		= $row[self::hobbies];
			$user->showHobby 	= $row[self::showHobby];
			$user->major		= $row[self::major];
			$user->showMajor	= $row[self::showMajor];
			$user->studentClass = $row[self::studentClass];
			$user->showClass 	= $row[self::showClass];
			$user->gpa 			= $row[self::GPA];
			$user->showGPA 		= $row[self::showGPA];
			$user->favClass 	= $row[self::FavClass];
			$user->showFavClass = $row[self::showFavClass];
			$user->favProf  	= $row[self::FavProf];
			$user->showFavProf  = $row[self::showFavProf];
			$user->lastLogin 	= $row[self::LastLogin];
			$user->groupId 		= $row[self::GroupId];
			$user->testGroupId	= $row[self::TestGroupId];
			$user->session 		= $row[self::Session];
			return $user;
		}

		public static function createUserArrayWithData($data)					// Not called from anywhere
		{			
			$Users = array();

			while ($row = mysqli_fetch_array($data)) {
				$user = new User();
				$user->userid 		= $row[self::UserId];
				$user->email 		= $row[self::Email];
				$user->firstName 	= $row[self::FirstName];
				$user->lastName 	= $row[self::LastName];
				$user->profilePath 	= $row[self::ProfilePath];
				$user->lastLogin 	= $row[self::LastLogin];
				array_push($Users, $user);
			}
			
			return $Users;
		}

		public static function createArrayWithData($data)
		{			
			$Users = array();

			while ($row = mysqli_fetch_array($data)) {
				$user = array(
								self::UserId 		=> $row[self::UserId], 
								self::Email 		=> $row[self::Email], 
								self::FirstName 	=> $row[self::FirstName], 
								self::LastName 		=> $row[self::LastName],
								self::ProfilePath 	=> $row[self::ProfilePath],
								self::LastLogin 	=> $row[self::LastLogin]
							);
				
				array_push($Users, $user);
			}
			
			return $Users;
		}

		public function getId()
		{
			return $this->userid;
		}
		
		public function getUserName()
		{
			return $this->username;
		}

		public function getEmail()
		{
			return $this->email;
		}

		public function getShowEmail()
		{
			return $this->showEmail;
		}

		public function getFullName()
		{
			if (empty($this->firstName))
				return $this->username;
			else
				return $this->firstName. " ". $this->lastName;
		}

		public function getFirstName()
		{
			return $this->firstName;
		}

		public function getShowFirstName()
		{
			return $this->showFirstName;
		}

		public function getLastName()
		{
			return $this->lastName;
		}

		public function getShowLastName()
		{
			return $this->showLastName;
		}

		public function getPassword()
		{
			return $this->password;
		}

		public function getProfilePath()
		{
			return $this->profilePath;
		}

		public function getShowProfile()
		{
			return $this->showProfile;
		}

		public function getAge()
		{
			return $this->age;
		}

		public function getShowAge()
		{
			return $this->showAge;
		}

		public function getGender()
		{
			return $this->gender;
		}

		public function getShowGender()
		{
			return $this->showGender;
		}

		public function getHomeTown()
		{
			return $this->homeTown;
		}

		public function getShowHomeTown()
		{
			return $this->showTown;
		}

		public function getHomeCountry()
		{
			return $this->homeCountry;
		}

		public function getShowHomeCountry()
		{
			return $this->showCountry;
		}

		public function getFavPlace()
		{
			return $this->favPlace;
		}

		public function getShowFavPlace()
		{
			return $this->showFavPlace;
		}

		public function getHobbies()
		{
			return $this->hobbies;
		}

		public function getShowHobby()
		{
			return $this->showHobby;
		}

		public function getMajor()
		{
			return $this->major;
		}

		public function getShowMajor()
		{
			return $this->showMajor;
		}

		public function getStudentClass()
		{
			return $this->studentClass;
		}

		public function getShowClass()
		{
			return $this->showClass;
		}

		public function getGPA()
		{
			return $this->gpa;
		}

		public function getShowGPA()
		{
			return $this->showGPA;
		}

		public function getFavClass()
		{
			return $this->favClass;
		}

		public function getShowFavClass()
		{
			return $this->showFavClass;
		}

		public function getFavProf()
		{
			return $this->favProf;
		}

		public function getShowFavProf()
		{
			return $this->showFavProf;
		}

		public function getGroupId()
		{
			return $this->groupId;
		}

		public function setGroupId($groupId){
			$this->groupId = $groupId;
		}

		public function getTestGroupId()
		{
			return $this->testGroupId;
		}

		public function setTestGroupId($testGroupId){
			$this->testGroupId = $testGroupId;
		}

		public function getSession()
		{
			return $this->session;
		}

		public function setSession($session){
			$this->session = $session;
		}

		public static function getProfileParameters()
		{
			$parameters = array
							(
								self::age 			=> 'Age',
								self::gender		=> 'Gender',
								self::HomeTown 		=> 'Home Town',
								self::HomeCountry	=> 'Home Country',
								self::FavPlace 		=> 'Favorite Place',
								self::hobbies		=> 'Hobbies',
								self::major			=> 'Major',
								self::studentClass	=> 'Class Standing',
								self::GPA			=> 'GPA',
								self::FavClass		=> 'Favorite Class',
								self::FavProf		=> 'Favorite Professor'
							);
			return $parameters;
		}

		public static function getChatParameters()
		{
			$parameters = array
							(
								self::age 			=> 'Age',
								'Age'				=> self::showAge,
								self::gender		=> 'Gender',
								'Gender'			=> self::showGender,
								self::HomeTown 		=> 'Home Town',
								'Home Town'			=> self::showTown,
								self::HomeCountry	=> 'Home Country ',
								'Home Country '		=> self::showCountry,
								self::FavPlace 		=> 'Favorite Place',
								'Favorite Place'	=> self::showPlace,
								self::hobbies		=> 'Hobbies',
								'Hobbies'			=> self::showHobby,
								self::major			=> 'Major',
								'Major'				=> self::showMajor,
								self::studentClass	=> 'Class Standing ',
								'Class Standing '	=> self::showClass,
								self::GPA			=> 'GPA',
								'GPA'				=> self::showGPA,
								self::FavClass		=> 'Favorite Class ',
								'Favorite Class '	=> self::showFavClass,
								self::FavProf		=> 'Favorite Professor ',
								'Favorite Professor ' => self::showFavProf
							);
			return $parameters;
		}
	}
?>