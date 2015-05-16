<?php

	class UserProfile
	{
		private $userId;
		private $email;
        private $picPath;
		private $firstname;
		private $lastname;
        private $age;
		private $gender;
        private $homeTown;
        private $homeCountry;
        private $favPlace;
        private $hobbies;

		public function __construct($userId)
		{
			$this->userId = $userId;	
		}

		public static function createProfileWithParameters($userId, $email, $picPath, $firstname, $lastname, $age, $gender, $homeTown, $homeCountry, $favPlace, $hobbies)
		{
			$profile = new UserProfile($userId);
			$profile->setEmail($email);
            $profile->setPicPath($picPath);
			$profile->setFirstName($firstname);
			$profile->setLastName($lastname);
			$profile->setAge($age);
			$profile->setGender($gender);
            $profile->setHomeTown($homeTown);
            $profile->setHomeCountry($homeCountry);
            $profile->setFavPlace($favPlace);
            $profile->setHobbies($hobbies);
			return $profile;
		}

		public function getUserId() {
            return $this->userId;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }
        
        public function getEmail() {
        	return $this->email;
        }
        
        public function setEmail($email) {
        	$this->email = $email;
        }

        public function getPicPath() {
            return $this->picPath;
        }

        public function setPicPath($picPath) {
            $this->picPath = $picPath;
        }
        
        public function getFirstName() {
        	return $this->firstname;
        }
        
        public function setFirstName($firstname) {
        	$this->firstname = $firstname;
        }
        
        public function getLastName() {
        	return $this->lastname;
        }
        
        public function setLastName($lastname) {
        	$this->lastname = $lastname;
        }
                
        public function getAge() {
            return $this->age;
        }

        public function setAge($age) {
            $this->age = $age;
        }

        public function getGender() {
            return $this->gender;
        }        

        public function setGender($gender) {
            $this->gender = $gender;
        }

        public function getHomeTown() {
            return $this->homeTown;
        }

        public function setHomeTown($homeTown) {
            $this->homeTown = $homeTown;
        }
        
        public function getHomeCountry() {
            return $this->homeCountry;
        }

        public function setHomeCountry($homeCountry) {
            $this->homeCountry = $homeCountry;
        }

        public function getFavPlace() {
            return $this->favPlace;
        }

        public function setFavPlace($favPlace) {
            $this->favPlace = $favPlace;
        }

        public function getHobbies() {
        	return $this->hobbies;
        }
        
        public function setHobbies($hobbies) {
        	$this->hobbies = $hobbies;
        }
        
	}
?>