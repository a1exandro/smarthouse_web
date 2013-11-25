<?php
	set_include_path(get_include_path() . PATH_SEPARATOR . 'engine');
	require_once('modules.php');
    session_start();
    if ($BOARD_REQ)
    	$_SESSION['board_id'] = (int)$_POST['board_id'];
    else
    	$_SESSION['board_id'] = 1;
    class engine
    {
		public static function execCommand($cmd)
		{
			$cmd = trim($cmd);
			if (strlen($cmd) == 0) return;

			if (mb_substr($cmd,-1) != ';') $cmd .= ';';
			$time = time();
			$q = mysql_query("INSERT INTO commands (command,add_time) VALUES ('$cmd',$time);");
			echo mysql_error();
		}
	}
?>