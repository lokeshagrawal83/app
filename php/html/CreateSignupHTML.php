<?php

	require_once('CreateHTML.php');										// / Paths are relative to PHP (calling) files.

	class CreateSignupHTML extends CreateHTML
	{
		private $error_msg;

		public function __construct($error_msg)
		{
			parent::__construct("Sign Up");
			$this->error_msg = $error_msg;
		}
		
		protected function createScripts()
		{

		}

		protected function createUserInfo()
		{
			?>
				<div id="userinfo">
					<div id="links">
						<ul>
							<li><a href="index.php">Home</a></li>
							<li><a href="" onclick="inProgress()">About</a></li>
							<li><a href="" onclick="inProgress()">Contact Us</a></li>
							<li><a href="" onclick="inProgress()">Help</a></li>
						</ul>
					</div>
				</div>				<!-- End of userinfo div  -->
			<?php
		}

		protected function createPageBody()
		{
			?>	
				<div id="pageBody">
					<div id="bodyImg">
						<img src="../img/teamwork_no_bg.png" alt="Team Work" />	
						<blockquote>
							If You Want To Go Fast, Go Alone.<br> 
							If You Want To Go Far, Go Together
						</blockquote>				
					</div>
					<div id="login">
						<?php  

							$this->showErrors($this->error_msg);  

							if(!empty($_POST))
								extract($_POST);
						?>
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >						
							<table>
								<tr>
									<td align="right">Username*:</td>
									<td align="left"><input type="text" name="username" value="<?php echo (!empty($username)? $username: ''); ?>"/></td>
								</tr>
								<tr>
									<td align="right">Password*:</td>
									<td align="left"><input type="password" name="password1"/></td>
								</tr>
								<tr>
									<td align="right">Confirm Password:</td>
									<td align="left"><input type="password" name="password2"/></td>
								</tr>
								<tr>
									<td colspan="2" width="100%">
										<input type="submit" name="signup" value="Create Login">
									</td>
								</tr>
							</table>
						</form>
					</div>			<!-- End of login div-->
				</div>				<!-- End of page body div-->
			<?php
		}
	}
?>