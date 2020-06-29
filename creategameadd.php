<?php
	include 'includes/page_top.php';
?>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white;">
		<?php
			$gameid = $_POST['gameid'];
			$memberid = $_SESSION['memberid'];
			$gamesquery = $connection->query("SELECT * FROM games WHERE gameid='$gameid';");
			$gamesquery = mysqli_fetch_array($gamesquery, MYSQLI_ASSOC);

			if ($gamesquery['finished'] == 0 && $gamesquery['beurt'] == 0) {
				if ($gamesquery['amount_of_players'] > $gamesquery['player_count']) {
					$already_in_game_query = $connection->query("SELECT * FROM gamememberids WHERE memberid='$memberid' AND gameid='$gameid' AND finished='0';");
					if ($already_in_game_query->num_rows == 0) {
						$get_highest_p_g_id = $connection->query("SELECT MAX(personal_game_id) AS personal_game_id FROM gamememberids WHERE gameid='$gameid';");
						$temp = mysqli_fetch_array($get_highest_p_g_id, MYSQLI_ASSOC);
						$personal_game_id = $temp['personal_game_id'];
						$personal_game_id++;
						$join_game_query = $connection->query("INSERT INTO gamememberids SET gameid='$gameid', personal_game_id='$personal_game_id', memberid='$memberid';");
						
						$get_player_count = $connection->query("SELECT * FROM games WHERE gameid='$gameid';");
						$player_count = mysqli_fetch_array($get_player_count, MYSQLI_ASSOC);
						$amount_of_players = $player_count['amount_of_players'];
						$player_count = $player_count['player_count'];
						$player_count++;

						$update_player_count = $connection->query("UPDATE games SET player_count='$player_count' WHERE gameid='$gameid';");
						if ($player_count == $amount_of_players) {
							$update_beurt = $connection->query("UPDATE games SET beurt='1' WHERE gameid='$gameid';");
						}
					}
					header("Location: lobby.php");
				}
			}
		?>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>