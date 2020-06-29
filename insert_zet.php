<?php
	//Verbeteren check voor of geldige zet voor invoeren

	include 'includes/page_top.php';
	if (isset($_POST['sourceLand']) && isset($_POST['targetLand']) && isset($_POST['sourceLandAMHoevMann'])) {
		$sourceLand = $_POST['sourceLand'];
		$targetLand = $_POST['targetLand'];
		$sourceLandAMHoevMann = $_POST['sourceLandAMHoevMann'];
		$memberid = $_SESSION['memberid'];

		$check_if_participant = $connection->query("SELECT * FROM gamememberids WHERE memberid='$memberid' AND finished='0';");
		$temp = mysqli_fetch_array($check_if_participant, MYSQLI_ASSOC);
		$gameid = $temp['gameid'];
		$personal_game_id = $temp['personal_game_id'];

		$games_query = $connection->query("SELECT * FROM games WHERE gameid='$gameid';");
		$temp = mysqli_fetch_array($games_query, MYSQLI_ASSOC);
		$beurt = $temp['beurt'];

		$posities_query = $connection->query("SELECT * FROM posities WHERE gameid='$gameid' AND beurt='$beurt';");
		$temp = mysqli_fetch_array($posities_query, MYSQLI_ASSOC);

		$versterking = $temp['versterking'];
		$versterking = explode(";", $versterking);
		$versterking = $versterking[$personal_game_id];

		$eigenaarString = $temp['eigenaar'];
		$eigenaarExploded = explode(";", $eigenaarString);

		$hoeveelheidmannenString = $temp['hoeveelheidmannen'];
		$hoeveelheidmannenExploded = explode(";", $hoeveelheidmannenExploded);

		/*
		echo "<table border=\"1\">";
		echo "<tr><td>Memberid:		</td><td>".$memberid."						</td></tr>";
		echo "<tr><td>Gameid:		</td><td>".$gameid."						</td></tr>";
		echo "<tr><td>Beurt:		</td><td>".$beurt."							</td></tr>";
		echo "<tr><td>AMHoevMann:	</td><td>".$sourceLandAMHoevMann."			</td></tr>";
		echo "<tr><td>Versterking:	</td><td>".$versterking."					</td></tr>";
		echo "<tr><td>Source land:	</td><td>".$sourceLand."					</td></tr>";
		echo "<tr><td>Eigenaar: 	</td><td>".$eigenaarExploded[$sourceLand]."	</td></tr>";
		echo "<tr><td>Target land:	</td><td>".$targetLand."					</td></tr>";
		echo "<tr><td>Eigenaar: 	</td><td>".$eigenaarExploded[$targetLand]."	</td></tr>";
		echo "</table>";
		*/

		echo "<div style=\"margin-left: auto; margin-right: auto; text-align: center; margin-top: 25px;\">Zet ingevoerd in de database, Wacht totdat iedereen dit gedaan heeft.</div>";

		$check_if_moves_query = $connection->query("SELECT * FROM moves WHERE gameid='$gameid' AND memberid='$memberid' AND beurt='$beurt';");
		if ($check_if_moves_query->num_rows == 0) {
			$i = 0;
			$versterkingString = '';
			while ($versterking > $i){
				if ($personal_game_id == $eigenaarExploded[$_POST['addMen'.$i]]) {
					$versterkingString .= $_POST['addMen'.$i];	//echo "You own the country you tried placing your men.<br />";
				}
				$i++;
				if ($versterking != $i) {
					$versterkingString .= ';';
				}
			}
			$insert_query = $connection->query("INSERT INTO moves SET gameid='$gameid', memberid='$memberid', beurt='$beurt', versterking='$versterkingString', selecthm='$sourceLandAMHoevMann', sourceland='$sourceLand', targetland='$targetLand';");
		}
		header("Location: bekijk-speelmap.php");
	}else{
		header("Location: error.php?errortext=Er is iets mis met jouw zet!<Br />Probeer opnieuw.&errorheader=Zet error.");
	}
	include 'includes/page_bottom.php';
?>