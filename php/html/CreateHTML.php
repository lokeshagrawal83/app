<?php

	abstract class CreateHTML 
	{
		private $title;
		private $class;

		function __construct($title, $class="")								// takes page title 
		{
			$this->title = $title;
			if(!empty($class))
				$this->class = $class;
		}

		public function createHTML()								// function to start creating HTML
		{
			?>
				<!DOCTYPE html>														<!-- Common HTML -->
				<html>
				<?php
					$this->createHeadHTML();
					$this->createBodyHTML();
				?>
				</html>
			<?php
		}

		private function createHeadHTML()							// HTML Head
		{
			?>
				<head>
					<title><?php echo $this->title; ?> </title>
					<link rel="stylesheet" type="text/css" href="../css/style.css">
					<link rel="stylesheet" href="../css/jquery-ui.css">
					<script src="../js/jquery-1.11.1.min.js"></script>
					<script src="../js/jquery-ui-1.11.2.js"></script>
					<script src="../js/mixpanel.js"></script>
				<?php

					$this->createScripts();								// Customized javascript section for each page.
				?>
				</head>
			<?php
		}

		private function createBodyHTML()							// HTML Body starts here
		{
			?>
				<body>
					<div id="wrapper">
						<div id="header">
							<img class="<?php echo $this->class; ?>" src="../img/azlogo.png" alt="Logo"/>

							<?php	$this->createUserInfo();	?>			
							<div id="divLine"></div>
						</div>												<!-- End of header div -->
						
						<?php	$this->createPageBody();	?>			
							
						<div id="footer">
							<center><p>Copyright &copy; University of Arizona, 2014 <br> 
								<font size="2px"><a href="../html/WhatsNew.html" target="_blank">version 1.0.0</a>: updated on 4th November, 2014</font>
							</p></center>
						</div>			<!-- End of footer div-->
					</div>				<!-- End of wrapper div-->
				</body>
			<?php
		}

		protected abstract function createScripts();						// Customized functions for child classes to override.
		protected abstract function createPageBody();
		protected abstract function createUserInfo();

		protected function showErrors($error_msg)							// Common show error logic for all pages.
		{																	// This is called from child classes.
			if (!empty($error_msg) && $error_msg != "") {
				$parts = explode("->", $error_msg);
				if ($parts[0] == "success") {
					echo "<div class=\"success\">";
					echo "<ul><li>".$parts[1]."</li></ul>";
				}
				else
				{
					?>
						<div class="error">
			            	<ul><li><?php echo $error_msg; ?></li></ul>
			            </div>
		            <?php
	        	}
			}
		}
	}
?>