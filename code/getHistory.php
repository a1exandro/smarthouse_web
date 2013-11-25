<?php
	$showCmd = $showMsg = 50;
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{
		require('../config.php');
		getHistory();

	}

	function getHistory()
	{
		global $showCmd,$showMsg;
		$q = mysql_query("select count(id) as count from commands");
		$stCmd = mysql_result($q,0)-$showCmd;
		if ($stCmd < 0) $stCmd = 0;
		$q = mysql_query("SELECT * FROM commands ORDER BY add_time LIMIT $stCmd,$showCmd");
		while ($cmd = mysql_fetch_object($q))
		{			$cmds[] = $cmd;
		}
        $q = mysql_query("select count(id) as count from messages");
		$stMsg = mysql_result($q,0)-$showMsg;
		if ($stMsg < 0) $stMsg = 0;
		$q = mysql_query("SELECT * FROM messages ORDER BY add_time LIMIT $stMsg,$showMsg");
		while ($msg = mysql_fetch_object($q))
		{
			$msgs[] = $msg;
		}
              echo mysql_error();
		$mNum = 0;
		if (sizeof($msgs))
		{			$msg = $msgs[$mNum];
			if (sizeof($cmds))  	// commands exists
			{
				$cmd = $cmds[0];
				while (($msg) && ($msg->add_time < $cmd->add_time))
				{
					printMsg($msg);
					$mNum++;
					$msg = $msgs[$mNum];
				}
			}
			else
			{
				foreach ($msgs as $msg)
					printMsg($msg);
			}

		}

		foreach ($cmds as $cmd)
		{
			while ( ($msg->add_time < $cmd->add_time) && ($mNum < sizeof($msgs)) )
			{
           		printMsg($msg);
           		$msg = $msgs[++$mNum];
			}
			printCmd($cmd);
		}
		while ( ($msg = $msgs[$mNum++]))
			printMsg($msg);
	}

	function printMsg($msg)
	{		echo "<- {$msg->message} <br>";
	}

	function printCmd($cmd)
	{
		echo "-> {$cmd->command} <br>";
	}
?>