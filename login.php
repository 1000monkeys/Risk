<?php
	include 'includes/page_top.php';
?>
<?php
        /* LOGIN SCRIPT */
		$error = array();
		if ($_POST != null && $_POST['form_submitted'] == '1') {
		    if (empty($_POST['username']) OR empty($_POST['password'])){
		    	if (empty($_POST['username'])) {
		    		$error[] = "Please enter your username. <br />";
		    	}
		    	if (empty($_POST['password'])) {
		    		$error[] = "Please enter your password. <br />";
		    	}
		    }
        }

        if (empty($error) && $_POST != null && $_POST['form_submitted'] == '1') {
        	$password = $_POST['password'];
        	$password = md5($password);
            $username = $_POST['username'];

        	$query_check_credentials = "SELECT * FROM `accounts` WHERE (username='$username' AND password='$password');";
        	$result_check_credentials = $connection->query($query_check_credentials);

        	if(!$result_check_credentials){
	            $error[] = 'Login Failed[Q1] <br />';
	        }

	        if ($result_check_credentials->num_rows == 1) {
	        	$date = date('Y-m-d H:i:s');
            	$updatelastlogin = $connection->query("UPDATE `accounts` SET lastlogin='$date' WHERE (username='$username' AND password='$password');");

	        	$_SESSION = mysqli_fetch_array($result_check_credentials, MYSQLI_ASSOC);

	        	if (isset($_SESSION['username'])) {
					header('Location: lobby.php');
				}
	        }else{
	        	$error[] = 'Either your account is inactive or the entered E-Mail address/Password is invalid. <br />';
	        }
        }
        mysqli_close($connection);
?>
<div>
	<div style="margin-left: auto; margin-right: auto; width: 800px;">
		<table style="margin-left: auto; margin-right: auto;">
			<h1 style="width: 800px; text-align: center;">
				Inloggen.
			</h1>
			<form method="POST" id="login_form" class="login_form" action="login.php" novalidate="novalidate">
				<fieldset>
					<p style="text-align: center;">
						<label for="username">
							Username:
						</label>
						<input id="username" type="username" name="username">
					</p>
					<p style="text-align: center;">
						<label for="password">
							Password:
						</label>
						<input id="password" type="password" name="password">
					</p>
					<input id="form_submitted" type="hidden" name="form_submitted" value="1">
					<p style="text-align: center;">
						<input type="submit" onclick="submit_login_function();" value="Submit" style="width: 75px; text-align: center;">
					</p>
				</fieldset>
			</form>
		</table>
		<div style="width: 800px; margin-top: 10px; text-align: center; margin-left: auto; margin-right: auto;">
			<?php
				$i = 0;
				while (count($error) > $i){
					echo $error[$i];
					$i++;
				}
			?>
		</div>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>