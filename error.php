<?php
	include 'includes/page_top.php';
?>
<div>
	<div style="margin-left: auto; margin-right: auto; border-left: 2px solid white; border-right: 2px solid white; width: 800px;">
		<?php
			// error.php?errortext=TEXT&errorheader=HEADER
			if (isset($_GET['errortext']) && isset($_GET['errorheader'])) {
				$errortext = $_GET['errortext'];
				$errorheader = $_GET['errorheader'];
			}else{
				$errorheader = 'Error page.';
				$errortext = 'Unknow error.';
			}
			echo "<h1 style=\"text-align: center;\">".$errorheader."</h1>";
			echo "<p style=\"text-align: center;\">".$errortext."</p>";
		?>
	</div>
</div>
<?php
	include 'includes/page_bottom.php';
?>