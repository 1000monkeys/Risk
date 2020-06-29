<?php
	include 'includes/page_top.php';
	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
	}
?>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white;">
		<?php
			/* CHECK PART IF THIS PLAYER IN OTHER BUSY GAME */
			$member_id = $_SESSION['memberid'];
			$check_query = $connection->query("SELECT * FROM gamememberids WHERE memberid='$member_id' AND finished='0';");
			if ($check_query->num_rows == 1) {
				header("Location: error.php?errortext=Jij hebt al een game die nog loopt.&errorheader=Loopt al een game van jou.");
			}else if (isset($_POST['amount_of_players'])){
				$amount_of_players = $_POST['amount_of_players'];
				if ($amount_of_players > 1 && $amount_of_players < 9) {
					/* CHECK PART IF THIS PLAYER IN OTHER BUSY GAME */
					$modulo_aantal_spelers = 42%($amount_of_players);
					$aantal_landen_per_speler = (42-$modulo_aantal_spelers)/($amount_of_players);
					$aantal_landen_per_speler++;
					$aantal_landen_per_speler = floor($aantal_landen_per_speler);

					/* OWNER STRING */
					$i = 1;
					$ownerstring = '';
					while ($i < $amount_of_players) {
						$temp_i = 1;
						while ($temp_i < $aantal_landen_per_speler) {
							$ownerstring .= $i.';';
							$temp_i++;
							if ($temp_i == $aantal_landen_per_speler) {
								$ownerstring .= $i.';';
							}
						}
						$i++;
					}

					$number_of_countries_left = (42 - ($aantal_landen_per_speler*$amount_of_players) + $aantal_landen_per_speler);
					while ($number_of_countries_left > 0) {
						$ownerstring .= '0';
						$number_of_countries_left--;
						if ($number_of_countries_left > 0) {
							$ownerstring .= ';';
						}
					}
					echo $ownerstring."<br />";			
				/*	$ownerstring = explode(";", $ownerstring);
					shuffle($ownerstring);
					echo "<table border=\"1\"><tr>";
					$i = 0;
					while ($i < 42) {
						echo "<td>".$ownerstring[$i]."</td>";
						$i++;
					}
					echo "</tr><tr>";
					$i = 0;
					while ($i < 42) {
						echo "<td>".$i."</td>";
						$i++;
					}
					echo "</tr></table>";	*/
					/* OWNER STRING */


					/* POSITIE STRING */
					$positiesstring = '3;';
					$i = 0;
					while ($i < 41) {
						$positiesstring .= '3';
						$i++;
						if ($i < 41) {
							$positiesstring .= ';';
						}
					}
					echo $positiesstring."<br />";
				/*	$positiesstring = explode(";", $positiesstring);
					shuffle($positiesstring);
					echo "<table border=\"1\"><tr>";
					$i = 0;
					while ($i < 42) {
						echo "<td>".$positiesstring[$i]."</td>";
						$i++;
					}
					echo "</tr><tr>";
					$i = 0;
					while ($i < 42) {
						echo "<td>".$i."</td>";
						$i++;
					}
					echo "</tr></table>";	*/
					/* POSITIE STRING */

					/* VERSTERKING STRING */
					$i = 0;
					$versterkingstring = '';
					$amount_men = $amount_of_players*15;
					$versterkinghoeveelheid = ceil(($amount_men/($amount_of_players+1)));
					while ($amount_of_players > $i) {
						$versterkingstring .= $versterkinghoeveelheid;
						$i++;
						if ($amount_of_players > $i) {
							$versterkingstring .= ';';
						}
					}
					echo $versterkingstring."<br />";
				/*	$versterkingstring = explode(";", $versterkingstring);
					echo "<table border=\"1\"><tr>";
					$i = 0;
					while ($i < $amount_of_players) {
						echo "<td>".$versterkingstring[$i]."</td>";
						$i++;
					}
					echo "</tr><tr>";
					$i = 0;
					while ($i < $amount_of_players) {
						echo "<td>".$i."</td>";
						$i++;
					}
					echo "</tr></table>";
					/* VERSTERKING STRING */

					/* SHUFFLE INFORMATION PART */
					$ownerstring_s = explode(";", $ownerstring);
					$ownerstring = '';
					shuffle($ownerstring_s);
					$i = 0;
					while ($i < count($ownerstring_s)) {
						$ownerstring .= $ownerstring_s[$i];
						$i++;
						if ($i < count($ownerstring_s)) {
							$ownerstring .= ';';
						}
					}
					echo $ownerstring."<br />";
					/* SHUFFLE INFORMATION PART */

					/* al in game check etc */
					//games INSERTEN en gameid terugkrijgen aanmaken, finished = 0 en beurt = 0 aan begin
					$games_query = $connection->query("INSERT INTO games SET beurt='0', finished='0', amount_of_players='$amount_of_players', player_count='1';");
					$gameid = $connection->insert_id;

					$posities_query = $connection->query("INSERT INTO posities SET gameid='$gameid', beurt='1', versterking='$versterkingstring', eigenaar='$ownerstring', hoeveelheidmannen='$positiesstring';");
					$member_id = $_SESSION['memberid'];

					$gamememberids = $connection->query("INSERT INTO gamememberids SET finished='0', gameid='$gameid', memberid='$member_id', personal_game_id='0';");
					header("Location: lobby.php");
				}else{
					header("Location: error.php?errortext=Er moet meer dan een speler zijn en kan niet meer dan 8 spelers zijn.<br /><a href=\"bekijk-speelmap.php\">Bekijk speelmap.</a>&errorheader=Raar aantal spelers!");
				}
			}
		?>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>