<?php
	include 'includes/page_top.php';
	if (!isset($_SESSION['username'])) {
		header('Location: http://kjevo.nl/risk/login.php');
	}
?>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white; width: 800px;">
		<h1 style="width: 800px; text-align: center;">
			Lobby.
		</h1>
		<?php
			$games_query = $connection->query("SELECT * FROM games WHERE finished='0' AND beurt='0';");
			while ($array = mysqli_fetch_array($games_query, MYSQLI_ASSOC)) {
				$gameid = $array['gameid'];
				$join_game_part = '<form method="POST" action="creategameadd.php"><input type="hidden" value="'.$array['gameid'].'" name="gameid"><input type="submit" value="Join game."></form>';
				echo "<div style=\"width: 778px; border-left: 1px solid gray; border-right: 1px solid gray; padding: 10px;\">";
				echo "<table style=\"margin-left: auto; margin-right: auto;\">";
				echo "<tr><td style=\"width: 200px;\">Game ID:</td><td>".$array['gameid']."</td></tr>";
				echo "<tr><td>Aantal spelers:</td><td>".$array['player_count']."/".$array['amount_of_players']."</td></tr>";
				echo "<tr><td colspan=\"2\"><div style=\"margin-left: auto; margin-right: auto; width: 75px;\">".$join_game_part."</div></td></tr>";
				echo "</table>";
				echo "</div>";	
			}

			echo "<div style=\"text-align: center; width: 778px; border: 2px solid gray; padding: 10px;\">";
			echo "Niet meer games of geen games aanwezig in de lobby.";
			echo "</div>";
		?>
	</div>
</div>
<?php
	/*
				$gamememberids_query = mysql_query("SELECT * FROM gamememberids WHERE gameid='$gameid';");
				while ($abcd = mysql_fetch_array($gamememberids_query)) {
					$gamememberids_query = mysql_query("SELECT * FROM gamememberids WHERE gameid='$gameid';");
					$memberid = $abcd['memberid'];

					$accounts_query = mysql_query("SELECT * FROM accounts WHERE memberid='$memberid';");
					while ($defg = mysql_fetch_array($accounts_query)) {
						$username = $defg['username'];
						$lastlogin = $defg['lastlogin'];
						echo "<div style=\"text-align: center;\"><h4>Players</h4>".$username." - ".$lastlogin."</div>";
					}
				}
	*/

	include 'includes/page_bottom.php';
?>