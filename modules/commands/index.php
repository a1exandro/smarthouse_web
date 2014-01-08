<?php
	class commands
	{		
		public static function module_onStatus($recv_stat,$statSender,$m_name,$g_stat,$cfg,$board_id)
		{
			$res->stat = $g_stat; 
			return $res;
		}
		
		public static function module_getContent($cfg,$stat,$m_name)
		{
			echo "
			<div id='logblock'>
				<div id='history'>
			";
					commands::getHistory();
			echo "
				</div>
				<div id='log_bottom'><a href=\"javascript:updateHistory();\">обновить</a></div>
			</div>
			<input name='cmd' class='cmd' id='cmd' type='text' value=''><input type='button' value='Отправить' onClick=\"javascript:onModuleAction('{$m_name}','send_cmd');\">";
			
			echo "<script>document.getElementById('log_bottom').scrollIntoView();</script>";
		}

		public static function module_onAction($cfg,$m_name)
	  	{
			
			$p = $_POST['p'];
			switch ($p)
			{
				case 'send_cmd':
					$cmd = $_POST['cmd'];
					$res->cmd = $cmd;
				break;
			}
			
			$res->cfg = $cfg;
	  		return $res;
	  	}

		public static function module_onCommand($cmd,$msg,$stat,$cfg,$m_name)
	  	{
			switch($cmd)
			{
				case 'register': 
					return $m_name::module_onBoardRegister($msg,$stat,$cfg,$m_name);
				break;	
				case 'ping':
					return $m_name::module_onBoardActivity($msg,$stat,$cfg,$m_name);
				break;
			}
		}
		
		public static function module_onBoardActivity($msg,$stat,$cfg,$m_name)
		{
			$res->stat = $stat;
			return $res;
		}
	  	public static function module_onBoardRegister($msg,$stat,$cfg,$m_name)
	  	{
			$data = json_decode($msg);

			$res->stat = $stat;
			return $res;
	  	}

	  	public static function module_editMode($cfg,$m_name)
		{

		}

		public static function module_save($cfg,$m_name)
		{
			
			return $cfg;
		}
		
		public static function printMsg($msg)
		{
			echo "<- {$msg->message} <br>";
		}

		public static function printCmd($cmd)
		{
			echo "-> {$cmd->command} <br>";
		}
		
		public static function getHistory()
		{
			$showCmd = $showMsg = 50;
			$q = mysql_query("select count(id) as count from commands");
			$stCmd = mysql_result($q,0)-$showCmd;
			if ($stCmd < 0) $stCmd = 0;
			$q = mysql_query("SELECT * FROM commands ORDER BY add_time LIMIT $stCmd,$showCmd");
			while ($cmd = mysql_fetch_object($q))
			{
				$cmds[] = $cmd;
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
			{
				$msg = $msgs[$mNum];
				if (sizeof($cmds))  	// commands exists
				{
					$cmd = $cmds[0];
					while (($msg) && ($msg->add_time < $cmd->add_time))
					{
						commands::printMsg($msg);
						$mNum++;
						$msg = $msgs[$mNum];
					}
				}
				else
				{
					foreach ($msgs as $msg)
						commands::printMsg($msg);
				}

			}

			foreach ($cmds as $cmd)
			{
				while ( ($msg->add_time < $cmd->add_time) && ($mNum < sizeof($msgs)) )
				{
					commands::printMsg($msg);
					$msg = $msgs[++$mNum];
				}
				commands::printCmd($cmd);
			}
			while ( ($msg = $msgs[$mNum++]))
				commands::printMsg($msg);
		}

    }
	
	
?>
