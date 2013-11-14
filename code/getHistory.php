<?php
	function getHistory()
	{
		$q = mysql_query("SELECT * FROM commands ORDER BY id DESC LIMIT 10");
		while ($cmd = mysql_fetch_object($q))
		{			$cmds[] = $cmd->command;
		}

		$q = mysql_query("SELECT * FROM messages ORDER BY id DESC LIMIT 10");
		while ($msg = mysql_fetch_object($q))
		{
			$msgs[] = $msg;
		}
		return implode("<br>",$cmds);
	}
?>