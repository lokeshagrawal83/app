<?php

	class UserGroup 
	{
		private $groupId;
		private $groupName;
        private $genericIdentity;
        private $groupPic;
        private $showIdentity;
		private $showProfile;
		private $showPic;
        private $allowUsers;
		private $params;
        private $chatLogs;
        private $sendLogs;

		public function __construct($groupId)
		{
			$this->groupId = $groupId;
		}

		public function getGroupId() {
            return $this->groupId;
        }

        public function setGroupId($groupId) {
            $this->groupId = $groupId;
        }

        public function getGroupName() {
            return $this->groupName;
        }

        public function setGroupName($groupName) {
            $this->groupName = $groupName;
        }

        public function getGenericIdentity() {
            return $this->genericIdentity;
        }

        public function setGenericIdentity($genericIdentity) {
            $this->genericIdentity = $genericIdentity;
        }

        public function getGroupPic() {
            return $this->groupPic;
        }

        public function setGroupPic($groupPic) {
            $this->groupPic = $groupPic;
        }

        public function getShowIdentity() {
            return $this->showIdentity;
        }

        public function setShowIdentity($showIdentity) {
            $this->showIdentity = $showIdentity;
        }

        public function getShowProfile() {
            return $this->showProfile;
        }

        public function setShowProfile($showProfile) {
            $this->showProfile = $showProfile;
        }

        public function getShowPic() {
            return $this->showPic;
        }

        public function setShowPic($showPic) {
            $this->showPic = $showPic;
        }

        public function getAllowUsers() {
            return $this->allowUsers;
        }

        public function setAllowUsers($allowUsers) {
            $this->allowUsers = $allowUsers;
        }

        public function getParams() {
            return $this->params;
        }

        public function setParams($params) {
            $this->params = $params;
        }

        public function getChatLogs() {
            return $this->chatLogs;
        }

        public function setChatLogs($chatLogs) {
            $this->chatLogs = $chatLogs;
        }

        public function getSendLogs() {
            return $this->sendLogs;
        }

        public function setSendLogs($sendLogs) {
            $this->sendLogs = $sendLogs;
        }
	}
?>