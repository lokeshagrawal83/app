<?php

	require_once('html/CreateHTML.php');							// Paths are relative to PHP (calling) files.

	class CreateIndexHTML extends CreateHTML
	{
		private $error_msg;											// Error messages specific to page

		public function __construct($error_msg)
		{
			parent::__construct("Login");							// Setting the page title.
			$this->error_msg = $error_msg;
		}
		
		protected function createScripts()
		{
			?>
				<script type="text/javascript">
					function inProgress()							// About, Contact Us link function call.
					{
						alert("Working on it.");
					}

					$(document).ready(function(){					

						$('#username').focus();
					});
				</script>
			<?php
		}

		protected function createUserInfo()
		{
			?>
				<div id="userinfo">
					<div id="links">
						<ul>
							<!-- li><a target="_blank" href="about.html">About</a></li -->
							<li class="active"><a href="" onclick="inProgress()">About</a></li>
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
						<?php  $this->showErrors($this->error_msg);  ?>								<!-- Parent function call to show errors -->
						<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >						
							<table>
								<tr>
									<td align="right">Username:</td>
									<td><input id="username" type="text" name="username"/></td>
								</tr>
								<tr>
									<td align="right">Password:</td>
									<td><input type="password" name="password"/></td>
								</tr>
								<tr><td></td>
									<td><input type="submit" name="submit" value="Login"/>
									<input type="submit" name="signup" value="Sign Up"/></td>
								</tr>
								<tr>								
									<td colspan="2">
										<!-- input type="image" src="../img/fbLogin.png" name="submit" alt="Submit" value="fbLogin" -->
										<!-- button name="submit" type="submit" value="fbLogin"><img src="../img/fbLogin.png"></button -->
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<!-- input type="image" src="../img/uaLogin.png" name="submit" alt="Submit" value="uaLogin" -->
										<!-- button name="submit" type="submit" value="uaLogin"><img src="../img/uaLogin.png"></button -->
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>				<!-- End of page body div-->
			<?php			
		}
	}
?>