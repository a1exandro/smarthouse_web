<?php
	require('config.php');
	set_time_limit(SCRIPT_TIME_LIMIT);

	$recv_cmd = getMessage();
	sendCommands( $recv_cmd=='ping' );

/////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////

	function getMessage()
	{    	$cmd = $_POST['cmd'];
    	$msg = mysql_escape_string($_POST['msg']);
    	switch ($cmd)
    	{
		case 'register':
		break;    		case 'ping':
    		break;
    		case 'message':
    		{
    			$time = time();
    			$q = mysql_query("INSERT INTO `messages` (message,time) VALUES ('$msg',$time);");
    		}
    		break;
    	}
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
	        if (!$sent) sleep(WAIT_CMD_SLEEP_TIME);
	        $timeout -= WAIT_CMD_SLEEP_TIME;
		}
		while ((!$sent) && ($timeout >0));
	}
?>