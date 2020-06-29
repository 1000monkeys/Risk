<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js" ></script>
		<script type="text/javascript" src="includes/js.js"></script>		
		<link rel="stylesheet" type="text/css" href="includes/css.css">
		<?php
			session_start();
			$host = 'localhost';
			$username = 'risk_dev';
			$password = 'WeeQHcHux68jCpUm';
			$database = 'riskgame';

			$connection = new mysqli($host, $username, $password, $database);
            // Check connection
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }
		?>
	</head>
	<body>
		<div style="background-color: gray; text-decoration: none; border-bottom: 2px solid white;">
			<style type="text/css">
				/*Initialize*/
				ul#menu, ul#menu ul.sub-menu {
					padding:0;
					margin: 0;
				}
				ul#menu li, ul#menu ul.sub-menu li {
					list-style-type: none;
					display: inline-block;
				}
				/*Link Appearance*/
				ul#menu li a, ul#menu li ul.sub-menu li a {
					text-decoration: none;
					color: #fff;
					background: #666;
					padding: 5px;
					display:inline-block;
					width: 100px;
					text-align: center;
				}
				/*Make the parent of sub-menu relative*/
				ul#menu li {
					position: relative;
				}
				/*sub menu*/
				ul#menu li ul.sub-menu {
					display:none;
					position: absolute;
					top: 30px;
					left: 0;
					width: 100px;
				}
				ul#menu li:hover ul.sub-menu {
					display:block;
				}
			</style>
			<div style="margin-left: auto; margin-right: auto; width: 340px; display: block;">
				<ul id="menu">
				    <li>
				    	<?php
				    	if (!isset($_SESSION['username'])) {
				    		echo "<a href=\"login.php\">Login</a>";
				    		echo "<ul class=\"sub-menu\"><li><a href=\"register.php\">Register</a></li></ul>";
				    	}else{
				    		echo "<li><a href=\"logout.php\">Logout</a><ul class=\"sub-menu\"></ul></li>";
				    	}
				    	?>
				    </li>
				    <li>
				    	<a href="lobby.php">Lobby</a>
				    	<ul class="sub-menu">
				            <li>
				                <a href="creategamemenu.php">Create Game</a>
				            </li>
				        </ul>
					</li>
				    <li>
				    	<a href="index.php">Game</a>
				    </li>
				</ul>
			</div>
		</div>
