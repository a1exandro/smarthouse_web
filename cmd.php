<?php
	require('config.php');
	require("engine/engine.php");
	
	set_time_limit(SCRIPT_TIME_LIMIT);

	$recv_cmd = getMessage();
	sendCommands( $recv_cmd=='ping' );
	

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

	function getMessage()
	{
		$cmd = $_REQUEST['cmd'];
    	$msg = $_REQUEST['msg'];
    	$board_id = (int)$_REQUEST['board_id'];
	//////// 	
    //	$cmd = "message";//$_REQUEST['cmd'];
    //	$msg = 'SENSORS: [{"data": 11.11, "type": "T", "addr": "28-0000045f3ba4"}, {"data": 0, "type": "D", "addr": "22"}] ';//$_REQUEST['msg'];
    //	$board_id = 1;//(int)$_REQUEST['board_id'];
	////////
    	switch ($cmd)
    	{
			case 'register':
    		case 'ping':
				modules::onCommand($cmd,$msg);
    		break;
    		case 'message':
    		{
				mysql_query("LOCK TABLES messages WRITE");
    			$time = time();
				$msg = mysql_real_escape_string($msg);
    			$q = mysql_query("INSERT INTO `messages` (board_id,message,add_time) VALUES ($board_id, '$msg',$time);");
				mysql_query("UNLOCK TABLES");
				modules::onMsg($msg);
    		}
    		break;
    	}
		return $cmd;
	}

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

	function sendCommands($sleep)
	{
		$timeout = $sleep?WAIT_CMD_TIMEOUT:0;
		$board_id = (int)$_REQUEST['board_id'];
		do
		{
			$sent = false;
			$q = mysql_query("SELECT * FROM `commands` WHERE get_time = 0 and board_id = $board_id");
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