<?php
	include 'includes/page_top.php';
?>
<?php
	/* REGISTREREN SCRIPT */
    $error = array();
	if (empty($_POST['username']) OR empty($_POST['password'])) {
		if (empty($_POST['username'])) {
			$error[] = 'Please enter your username.<br />';
		}
		if (empty($_POST['password'])) {
			$error[] = 'Please enter your password.<br />';
		}
	}else{
		$password = $_POST['password'];
		$password = md5($password);
		$username = $_POST['username'];

		if (isset($_POST['username'])) {
			$query_verify_username = "SELECT * FROM accounts WHERE username='$username';";
			$result_verify_username = $connection->query($query_verify_username);

			if ($result_verify_username) {
                if (!($result_verify_username->num_rows == 0)) {
                    $error[] = 'Username already used! Try another username.<br />';
                }
            }
		}

		if (empty($error)) {
			$query_insert_user = "INSERT INTO accounts (`username`, `password`) VALUES ('$username', '$password')";
			$result_insert_user = mysqli_query($connection, $query_insert_user);

			if (!$result_insert_user) {
				$error[] = 'Something unknown went wrong, Try again!<br />';
			}
			mysqli_close($connection);
		}
	}
?>
<div>
	<div style="margin-left: auto; margin-right: auto; width: 800px;">
		<table style="margin-left: auto; margin-right: auto;">
			<h1 style="width: 800px; text-align: center;">
				Registreren.
			</h1>
			<form method="POST" id="register_form" class="register_form" action="register.php" novalidate="novalidate">
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
					<p style="text-align: center;">
						<input type="submit" onclick="submit_login_function();" value="Submit" style="width: 75px; text-align: center;">
					</p>
					<?php
						$i = 0;
						while (count($error) > $i) {
							echo "<p>".$error[$i]."</p>";
							$i++;
						}
					?>
				</fieldset>
			</form>
		</table>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>