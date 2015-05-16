<?php

	class StudentProfile
	{
		private $userId;
		private $major;
		private $studentClass;
		private $gpa;
		private $favClass;
		private $favProf;
		
		public function __construct($userId)
		{
			$this->userId = $userId;	
		}

		public static function createProfileWithParameters($userId, $major, $studentClass, $gpa, $favProf, $favClass)
		{
			$profile = new StudentProfile($userId);
            $profile->setMajor($major);
            $profile->setStudentClass($studentClass);
            $profile->setGPA($gpa);
            $profile->setFavClass($favClass);
            $profile->setFavProf($favProf);
            
			return $profile;
		}

		public function getUserId() {
            return $this->userId;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }

		public function getMajor() {
            return $this->major;
        }

        public function setMajor($major) {
            $this->major = $major;
        }

        public function getStudentClass() {
            return $this->studentClass;
        }

        public function setStudentClass($studentClass) {
            $this->studentClass = $studentClass;
        }

        public function getGPA() {
            return $this->gpa;
        }

        public function setGPA($gpa) {
            $this->gpa = $gpa;
        }

        public function getFavClass() {
            return $this->favClass;
        }

        public function setFavClass($favClass) {
            $this->favClass = $favClass;
        }

        public function getFavProf() {
            return $this->favProf;
        }

        public function setFavProf($favProf) {
            $this->favProf = $favProf;
        }
	}
?>