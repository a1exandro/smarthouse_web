<?php
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{
		require('../config.php');
		getInfo();
		$board_id = (int)$_POST['board_id'];
	}

	function getInfo()
	{
		global $board_id;		$q = mysql_query("SELECT * from boards where id = $board_id");
		$board = mysql_fetch_object($q);

		$diff = time() - $board->alive;
		if ($diff > 30)
			echo "<font color=red>Last activity: ".date("H:i:s d.m.Y",$board->alive)." ($diff secs ago)</font>";
		else
			echo "<font color=green>Last activity: ".date("H:i:s d.m.Y",$board->alive)." ($diff secs ago)</font>";
	}
?>