<?php
	set_include_path(get_include_path() . PATH_SEPARATOR . 'engine');
	require_once('modules.php');
    session_start();
if ($_REQUEST['board_id'])
    	$_SESSION['board_id'] = (int)$_REQUEST['board_id'];
    elseif (!isset($_SESSION['board_id']))
    	$_SESSION['board_id'] = 1;

    class engine
    {
		public static function execCommand($cmd, $board_id = 0)
		{
			if ($board_id == 0)
				$board_id = (int)$_SESSION['board_id'];
			$cmd = mysql_real_escape_string(trim($cmd));
			if (strlen($cmd) == 0) return;

			if (mb_substr($cmd,-1) != ';') $cmd .= ';';
			$time = time();
			$q = mysql_query("INSERT INTO commands (board_id,command,add_time) VALUES ($board_id, '$cmd',$time);");
			echo mysql_error();
		}
	}
?>
