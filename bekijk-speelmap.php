<?php
	include 'includes/page_top.php';
	include 'includes/permanent_data.php';

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
	}
	$memberid = $_SESSION['memberid'];

	//aanpassen voor bekijken

	$check_if_participant = $connection->query("SELECT * FROM gamememberids WHERE memberid='$memberid' AND finished='0';");
	$temp = mysqli_fetch_array($check_if_participant, MYSQLI_ASSOC);
	$gameid = $temp['gameid'];
	$personal_game_id = $temp['personal_game_id'];

	$games_query = $connection->query("SELECT * FROM games WHERE gameid='$gameid';");
	$temp = mysqli_fetch_array($games_query, MYSQLI_ASSOC);
	$beurt = $temp['beurt'];
	$amount_of_players = $temp['amount_of_players'];

	$i = 0;
	$players = array();
	while($i < $amount_of_players){
		$players[] = $i;
		$i++;
	}

	/* loop */
	$moves_check = $connection->query("SELECT * FROM moves WHERE gameid='$gameid' AND beurt='$beurt';");
	if ($moves_check->num_rows == $amount_of_players) {
		header("Location: engine.php");
	}

	$personal_check_moves_done = $connection->query("SELECT * FROM moves WHERE gameid='$gameid' AND beurt='$beurt' AND memberid='$memberid';");
	if ($personal_check_moves_done->num_rows == 0){
		header("Location: index.php");
	}

	if (isset($_GET['beurt'])) {
		$beurt = $_GET['beurt'];
	}
?>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white;">
		<div class="gamecont_view" id="gamecont_view">
			<canvas class="gamecanvas" id="gamecanvas" width="2000" height="970"></canvas>
		</div>
		<div style="width: 210px; height: 92%; float: right;">
			<div style="width: 210px; background-color: white;">
				<div id="bekijk_speelmap_text" style="height: 100%; overflow-x: scroll; overflow-y: none; overflow: auto; border: 1px solid gray;">
					<?php
						echo "zou hier een output voor beurt_text moeten zitten";
					?>
				</div>
			</div>
		</div>
		<div name="information">
			<?php
				$posities_query = $connection->query("SELECT * FROM posities WHERE gameid='$gameid' AND beurt='$beurt';");
				$temp = mysqli_fetch_array($posities_query, MYSQLI_ASSOC);
				$versterking = $temp['versterking'];
				$versterking = explode(";", $versterking);
				$versterking = $versterking[$personal_game_id];
				$eigenaarString = $temp['eigenaar'];
				$hoeveelheidmannenString = $temp['hoeveelheidmannen'];

				echo ('<div name="zet">
					<form method="POST" id="zet_form" action="insert_zet.php">
						<input type="hidden" value="" name="sourceLandAMHoevMann" id="sourceLandAMHoevMann">
						<input type="hidden" value="" name="sourceLand" id="sourceLand">
						<input type="hidden" value="" name="targetLand" id="targetLand">
						<div id="addMen">

						</div>
					</form>
				</div>
				<div name="randomValues">
					<input type="hidden" value="'.$versterking.'" id="versterking">
					<input type="hidden" value="'.$hoeveelheidmannenString.'" id="hoeveelheidmannenString">
					<input type="hidden" value="'.$eigenaarString.'" id="eigenaarString">
					<input type="hidden" value="'.$personal_game_id.'" id="personal_game_id">
				</div>
				<div name="country_data">');
				$i = 0;
				while(42 > $i){
					echo ('
					<input id="'.$i.'X" type="hidden" value="'.$PositiesX[$i].'">
					<input id="'.$i.'Y" type="hidden" value="'.$PositiesY[$i].'">
					<input id="'.$i.'pAT" type="hidden" value="'.$possAttTargets[$i].'">
					<input id="'.$i.'" type="hidden" value="'.$landenlijstById[$i].'">
					<div id="target_div_'.$i.'" style="display: none;"></div>
					');
					$i++;
				}
				echo ('</div>');
			?>
		</div>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>