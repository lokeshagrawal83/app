<?php

	require_once('CreateAdminHTML.php');									//  Paths are relative to PHP (calling) files.
	require_once('db/DBHelper.php');

	class ViewGroupChatHTML extends CreateAdminHTML
	{

		public function __construct()
		{
			parent::__construct();
		}
		
		protected function createScripts()
		{
			?>
				<link rel="stylesheet" type="text/css" href="html/css/chat.css">
			<?php
		}

		protected function createPageBody()
		{
			$logs = DBHelper::getChatLogDAO()->getGroupLogs($_POST['groupId']);
			$users = DBHelper::getUserGroupDAO()->getAllUsersInGroup($_POST['groupId']);
			?>
				<div id="homeBody">
					<div id="team">
						<h1>Team Info</h1>
						<?php
							while ($row = mysqli_fetch_array($users)) {
								$this->userProfile($row);
							}
						?>
					</div>
					<div id="chat">
						<h1>Group Chat Area</h1>
						<div id="area" class="admin">
							<?php
								while ( $row = mysqli_fetch_array($logs) ) 
								{
									if ($row['logtype'] == 1) {
										echo "<span class='system'>".$row['username']." ".$row['msg']."</span><span class='logtime'>".substr($row['logtime'], 14)."</span><br>";
									}
									else
									{
										echo "<span class='uName'>".$row['username']."</span>: <span class='msg'>".$row['msg']."</span><span class='logtime'>".substr($row['logtime'], 14)."</span><br>";
									}
								}
							?>
						</div>
					</div>
				</div>
			<?php
		}

		private function userProfile($row) 
		{
			?>
				<div class="member">
					<img src="<?=$row['profilepath']?>" />
					<div id="profile">
						<a href="profile.php?userId=<?=$row['userid']?>" class="profileName"><?= $row['username']?></a>
						<table>
							<tr><td class="title">Gender:</td><td class="value"><?=$row['gender'] == 'Male' ? 'Male' : 'Female'?></td></tr>
							<tr><td class="title">Age:</td><td class="value"><?=$row['age']?></td></tr>
							<tr><td class="title">Major:</td><td class="value"><?=$row['major']?></td></tr>
						</table>	
					</div>
				</div>
			<?php
		}
	}
?>