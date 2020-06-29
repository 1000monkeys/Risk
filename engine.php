<?php
	include 'includes/page_top.php';
	include 'includes/permanent_data.php';

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
	}else{
		$memberid = $_SESSION['memberid'];
	}


	//$beurt_text = '';
	$if_game_get_id = $connection->query("SELECT * FROM gamememberids WHERE memberid='$memberid' AND finished='0';");
	$value = mysqli_fetch_array($if_game_get_id, MYSQLI_ASSOC);
	$game_id = $value['gameid'];

	$games_query = $connection->query("SELECT * FROM games WHERE gameid='$game_id';");
	$value = mysqli_fetch_array($games_query, MYSQLI_ASSOC);

	$beurt = $value['beurt'];
	$amount_of_players = $value['amount_of_players'];

	$posities_query = $connection->query("SELECT * FROM posities WHERE gameid='$game_id' AND beurt='$beurt';");
	$value = mysqli_fetch_array($posities_query, MYSQLI_ASSOC);
	$temp = $value['versterking'];
	$versterking_outside = explode(";", $temp);
	$temp = $value['eigenaar'];
	$eigenaar = explode(";", $temp);
	$temp = $value['hoeveelheidmannen'];
	$hoeveelheidmannen = explode(";", $temp);

	/*$i = 0;
	echo "<table border=\"1\"><tr><td>CID</td><td>EID</td><td>HM</td></tr>";
	while (42 > $i){
		echo "<tr><td>".$i."</td><td>".$eigenaar[$i]."</td><td>".$hoeveelheidmannen[$i]."</td></tr>";
		$i++;
	}
	echo "</table><br />NIEUWE<br />";*/

	$i = 0;
	$players = array();
	while($i < $amount_of_players){
		$players[] = $i;
		$i++;
	}


	//$beurt_text .= 'Hoeveelheid spelers: '.$amount_of_players.', Beurt: '.$beurt.'<br />';
	$moves_check = $connection->query("SELECT * FROM moves WHERE gameid='$game_id' AND beurt='$beurt';");
	if (count($players) == $amount_of_players && $moves_check->num_rows == $amount_of_players) {
		shuffle($players);
		foreach ($players as $personal_game_id) {
			$gamememberids_query = $connection->query("SELECT * FROM gamememberids WHERE gameid='$game_id' AND personal_game_id='$personal_game_id';");
			$value = mysqli_fetch_array($gamememberids_query, MYSQLI_ASSOC);
			$member_id = $value['memberid'];

			$moves_query = $connection->query("SELECT * FROM moves WHERE memberid='$member_id' AND gameid='$game_id' AND beurt='$beurt';");
			$value = mysqli_fetch_array($moves_query, MYSQLI_ASSOC);
			$versterking = $value['versterking'];
			$versterking = explode(";", $versterking);

			$i = 0;
			while ($i < count($versterking)){
				//$beurt_text .= 'Speler: '.$personal_game_id;
				if ($eigenaar[$versterking[$i]] == $personal_game_id) {
					$hoeveelheidmannen[$versterking[$i]]++;
					//$beurt_text .= ' heeft op '.$landenlijstById[$versterking[$i]].' een mannetje geplaatst.';
				}
				//$beurt_text .= '<br />';
				$i++;
			}
		}


		foreach ($players as $personal_game_id) {
			$gamememberids_query = $connection->query("SELECT * FROM gamememberids WHERE gameid='$game_id' AND personal_game_id='$personal_game_id';");
			$value = mysqli_fetch_array($gamememberids_query, MYSQLI_ASSOC);
			$member_id = $value['memberid'];

			$moves_query = $connection->query("SELECT * FROM moves WHERE memberid='$member_id' AND gameid='$game_id' AND beurt='$beurt';");
			$value = mysqli_fetch_array($moves_query, MYSQLI_ASSOC);
			$source_land = $value['sourceland'];
			$target_land = $value['targetland'];
			$select_hm = $value['selecthm'];
			$versterking = $value['versterking'];
			$versterking = explode(";", $versterking);

			if (count($versterking) == $versterking_outside[$personal_game_id]) {
				if (2 >= ($hoeveelheidmannen[$source_land] - $select_hm)) {
					if ($eigenaar[$source_land] == $eigenaar[$target_land]) {
						$hoeveelheidmannen[$source_land] = ($hoeveelheidmannen[$source_land] - $select_hm);
						$hoeveelheidmannen[$target_land] = ($hoeveelheidmannen[$target_land] + $select_hm);
					}else if($eigenaar[$source_land] != $eigenaar[$target_land]){
						if ($hoeveelheidmannen[$target_land] < $select_hm) {
							//win
							$eigenaar[$target_land] = $personal_game_id;
							$random = rand(0, $select_hm);
							$hoeveelheidmannen[$target_land] = $random;
						}else if ($hoeveelheidmannen[$target_land] > $select_hm){
							//loss
							$random = rand(0, $select_hm);
							$hoeveelheidmannen[$target_land] = ($hoeveelheidmannen[$target_land] - $random);
						}
						$hoeveelheidmannen[$source_land] = ($hoeveelheidmannen[$source_land] - $select_hm);
						if ($hoeveelheidmannen[$target_land] < 1) {
							$hoeveelheidmannen[$target_land] = 1;
						}
					}
				}
			}
		}

		$aantalversterking = array();
		$eigenaarCounted = array_count_values($eigenaarExploded);
		foreach($players as $personal_game_id){
			$eigenaarCounted[$personal_game_id] = floor($eigenaarCounted[$personal_game_id]/($aantal/$aantalSpelers));	
			
			if (3 > $eigenaarCounted[$spelerid]){
				$eigenaarCounted[$personal_game_id] = 3;
			}

			$aantalversterking[$personal_game_id] = $eigenaarCounted[$personal_game_id];
			if ($eigenaar[1] == $personal_game_id && $eigenaar[1] == $eigenaar[2] && $eigenaar[2] == $eigenaar[3] && $eigenaar[3] == $eigenaar[4] && $eigenaar[4] == $eigenaar[5] && $eigenaar[5] == $eigenaar[6] && $eigenaar[6] == $eigenaar[7] && $eigenaar[7] == $eigenaar[8]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 2;
			}
			if ($eigenaar[9] == $personal_game_id && $eigenaar[9] == $eigenaar[10] && $eigenaar[10] == $eigenaar[11] && $eigenaar[11] == $eigenaar[12] && $eigenaar[12] == $eigenaar[13]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 2;
			}
			if ($eigenaar[14] == $personal_game_id && $eigenaar[14] == $eigenaar[15] && $eigenaar[15] == $eigenaar[16] && $eigenaar[16] == $eigenaar[17] && $eigenaar[17] == $eigenaar[18] && $eigenaar[18] == $eigenaar[19] && $eigenaar[19] == $eigenaar[20]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 4;
			}
			if ($eigenaar[21] == $personal_game_id && $eigenaar[21] == $eigenaar[22] && $eigenaar[22] == $eigenaar[23] && $eigenaar[23] == $eigenaar[24] && $eigenaar[24] == $eigenaar[25] && $eigenaar[25] == $eigenaar[26]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 3;
			}
			if ($eigenaar[27] == $personal_game_id && $eigenaar[27] == $eigenaar[28] && $eigenaar[28] == $eigenaar[29] && $eigenaar[29] == $eigenaar[30] && $eigenaar[30] == $eigenaar[31]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 3;
			}
			if ($eigenaar[32] == $personal_game_id && $eigenaar[32] == $eigenaar[33] && $eigenaar[33] == $eigenaar[34] && $eigenaar[34] == $eigenaar[35] && $eigenaar[35] == $eigenaar[36] && $eigenaar[36] == $eigenaar[37] && $eigenaar[37] == $eigenaar[38]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 3;
			}
			if ($eigenaar[39] == $personal_game_id && $eigenaar[39] == $eigenaar[40] && $eigenaar[40] == $eigenaar[41] && $eigenaar[41] == $eigenaar[42]) {
				$aantalversterking[$personal_game_id] = $aantalversterking[$personal_game_id] + 2;
			}
		}

		/*$i = 0;
		echo "<table border=\"1\"><tr><td>CID</td><td>EID</td><td>HM</td></tr>";
		while (42 > $i){
			echo "<tr><td>".$i."</td><td>".$eigenaar[$i]."</td><td>".$hoeveelheidmannen[$i]."</td></tr>";
			$i++;
		}
		echo "</table>";*/

		$i = 0;
		$eigenaarString = '';
		while (42 > $i) {
			$eigenaarString .= ''.$eigenaar[$i].';';				
			$i++;
		}

		$i = 0;
		$hoeveelheidmannenString = '';
		while (42 > $i) {
			$hoeveelheidmannenString .= ''.$hoeveelheidmannen[$i].';';				
			$i++;
		}

	    $versterkingString = '';
	    $i = 0;
	    while ($amount_of_players > $i) {
			$versterkingString .= $aantalversterking[$i];
			$i++;
			if ($i != $amount_of_players) {
				$versterkingString .= ';';
			}
	    }

	    $winner = 99;
	    $check_winner = array_count_values($eigenaar);
	    foreach($players as $personal_game_id){
	    	if ($check_winner[$personal_game_id] == 42) {
				$winner = $personal_game_id;
	    	}
	    }

	    //echo $eigenaarString."<br />".$hoeveelheidmannenString."<br />".$versterkingString;
		$beurt++;		
		if($winner == 99) {
			$insert_posities_query = $connection->query("INSERT INTO posities SET gameid='$game_id', beurt='$beurt', versterking='$versterkingString', eigenaar='$eigenaarString', hoeveelheidmannen='$hoeveelheidmannenString';");
			$update_games_query = $connection->query("UPDATE games SET beurt='$beurt' WHERE gameid='$game_id';");
		}else if ($winner != 99) {
			$insert_posities_query = $connection->query("INSERT INTO posities SET gameid='$game_id', beurt='$beurt', versterking='$versterkingString', eigenaar='$eigenaarString', hoeveelheidmannen='$hoeveelheidmannenString';");
			$update_games_query = $connection->query("UPDATE games SET beurt='$beurt', finished='1' WHERE gameid='$game_id';");
			$update_games_query = $connection->query("UPDATE gamememberids SET finished='1' WHERE gameid='$game_id';");
		}
	}

	header("Location: index.php");
?>