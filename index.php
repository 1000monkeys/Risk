<?php
	include 'includes/page_top.php';
	include 'includes/permanent_data.php';

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
	}else{
		$memberid = $_SESSION['memberid'];
	}

	$find_running_game_query = $connection->query("SELECT * FROM gamememberids WHERE memberid='$memberid' AND finished='0';");
	if ($find_running_game_query->num_rows == 1) {
		$value = mysqli_fetch_array($find_running_game_query, MYSQLI_ASSOC);
		$gameid = $value['gameid'];
		$personal_game_id = $value['personal_game_id'];

		$games_query = $connection->query("SELECT * FROM games WHERE gameid='$gameid';");
		$value = mysqli_fetch_array($games_query, MYSQLI_ASSOC);
		$beurt = $value['beurt'];
		if ($beurt == 0) {
			header("Location: lobby.php");
		}
		$amount_of_players = $value['amount_of_players'];

		$check_moves_done = $connection->query("SELECT * FROM moves WHERE gameid='$gameid' AND beurt='$beurt';");
		$personal_check_moves_done = $connection->query("SELECT * FROM moves WHERE gameid='$gameid' AND beurt='$beurt' AND memberid='$memberid';");
		if ($check_moves_done->num_rows == $amount_of_players) {
			//header("Location: engine.php");
		}else if ($personal_check_moves_done->num_rows == 1){
			header("Location: bekijk-speelmap.php");
		}
	}else{
		header("Location: lobby.php");
	}
?>
<div style="background-color: gray; text-decoration: none; height: 115px;">
	<div style="overflow: scroll; overflow-y: hidden;">
		<div style="height: 50px; width: auto; white-space: nowrap;">
			<div id="countries_div" style="display: table-row;">
				<?php
					//js output
					//output owned countries
					//switch naar output target countries
					//output possible targets
				?>
			</div>
		</div>
	</div>
	<div style="overflow: scroll; overflow-y: hidden;">
		<div style="height: 50px; width: auto; white-space: nowrap;">
			<div style="display: table-row;">
				<button onclick="reset()" style="border: 0; outline: 0; background: none; padding: 0; margin: 0;">
					<div id="reset" style="background-color: white; color: black; height: 50px; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 2px solid black; border-right: 2px solid black; display: table-cell; padding-left: 15px; padding-right: 15px; font-size: 25px; line-height: 46px;">
						Reset.
					</div>
				</button>
				<div id="target_div_ui" style="background-color: white; color: black; height: 48px; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 2px solid black; border-right: 2px solid black; display: table-cell; padding-left: 15px; padding-right: 15px; font-size: 25px; line-height: 46px;">
					Target: target
				</div>
				<div id="source_div_ui" style="background-color: white; color: black; height: 48px; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 2px solid black; border-right: 2px solid black; display: table-cell; padding-left: 15px; padding-right: 15px; font-size: 25px; line-height: 46px;">	
					Source: source
				</div>
				<div id="hm_div_ui" style="background-color: white; color: black; height: 48px; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 2px solid black; border-right: 2px solid black; display: table-cell; padding-left: 15px; padding-right: 15px; font-size: 25px; line-height: 46px;">	
					Hoeveelheidmannen
				</div>
				<button onclick="submit()" style="border: 0; outline: 0; background: none; padding: 0; margin: 0;">
					<div id="submit" style="background-color: white; color: black; height: 50px; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 2px solid black; border-right: 2px solid black; display: table-cell; padding-left: 15px; padding-right: 15px; font-size: 25px; line-height: 46px;">
						Submit.
					</div>
				</button>
			</div>
		</div>
	</div>
</div>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white;">
		<div class="gamecont" id="gamecont">
			<canvas class="gamecanvas" id="gamecanvas" width="2000" height="970"></canvas>
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
						<input type="hidden" value="99" name="sourceLandAMHoevMann" id="sourceLandAMHoevMann">
						<input type="hidden" value="99" name="sourceLand" id="sourceLand">
						<input type="hidden" value="99" name="targetLand" id="targetLand">
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
	include 'include/page_bottom.php';
?>