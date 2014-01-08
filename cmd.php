<?php
	require('config.php');
	require("engine/engine.php");
	
	set_time_limit(SCRIPT_TIME_LIMIT);

	$recv_cmd = getMessage();
	sendCommands( $recv_cmd=='ping' );
	

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

	function getMessage()
	{    	$cmd = $_REQUEST['cmd'];
    	$msg = $_REQUEST['msg'];
    	$board_id = (int)$_REQUEST['board_id'];
    	switch ($cmd)
    	{
			case 'register':    		case 'ping':
				modules::onCommand($cmd,$msg);
    		break;
    		case 'message':
    		{
    			$time = time();
				$msg = mysql_escape_string($msg);
    			$q = mysql_query("INSERT INTO `messages` (message,add_time) VALUES ('$msg',$time);");
				modules::onMsg($msg);
    		}
    		break;
    	}
    	/*$time = time();
    	$q = mysql_query("UPDATE boards SET alive = $time WHERE id = $board_id");*/
		return $cmd;
	}

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

	function sendCommands($sleep)
	{		$timeout = $sleep?WAIT_CMD_TIMEOUT:0;
		do
		{
			$sent = false;
			$q = mysql_query("SELECT * FROM `commands` WHERE get_time = 0");
			if (mysql_num_rows($q))
			{
				while ($obj = mysql_fetch_object($q))
				{
					$cmd .= $obj->command;
					$time = time();
					$upd_q = mysql_query("UPDATE `commands` SET get_time = $time WHERE id = {$obj->id}");
				}
	            echo $cmd;
	            $sent = true;
			}
	        if (!$sent && $sleep) sleep(WAIT_CMD_SLEEP_TIME);
	        $timeout -= WAIT_CMD_SLEEP_TIME;
		}
		while ((!$sent) && ($timeout >0));
	}
?>