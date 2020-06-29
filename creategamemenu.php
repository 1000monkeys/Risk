<?php
	include 'includes/page_top.php';
	if (!isset($_SESSION['username'])) {
		header('Location: http://kjevo.nl/risk/login.php');
	}
?>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white; width: 800px;">
		<h1 style="width: 800px; text-align: center;">
			Game aanmaken.
		</h1>
		<div style="width: 800px;">
			<form method="POST" action="creategame.php">
				<div style="text-align: center;">
					Amount of players: <input type="text" value="2" name="amount_of_players" id="amount_of_players" style="width: 25px; text-align: center;" maxlength="1" min="2" max="8">
				</div>
				<div style="width: 800px; text-align: center;">
					<input type="submit" value="Create game." name="" id="" style="align: center;">
				</div>
			</form>
		</div>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>