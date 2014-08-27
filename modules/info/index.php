<?php
	class info
	{		
		public static function module_onStatus($recv_stat,$statSender,$m_name,$g_stat,$cfg,$board_id)
		{

			$res->stat = $g_stat; 
			return $res;
		}
		
		public static function module_getContent($cfg,$stat,$m_name)
		{
			$diff = time() - $stat->alive;
			if ($diff > 30)
				echo "<font color=red>Последняя активность: ".date("H:i:s - d.m.Y",$stat->alive)." ({$diff}с назад)</font>";
			else
				echo "<font color=green>Последняя активность: ".date("H:i:s - d.m.Y",$stat->alive)." ({$diff}с назад)</font>";
			echo "<br>Версия ПО: {$stat->version}<br>";
			echo "Время загрузки: ".date("H:i:s - d.m.Y",$stat->reg)."<br>";
			echo "IP: ".$stat->last_ip."<br>";
		}

		public static function module_onAction($cfg,$m_name)
	  	{
	  		
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
			$stat->alive= time();
			$stat->last_ip = $_SERVER['REMOTE_ADDR'];
			$res->stat = $stat;
			return $res;
		}
	  	public static function module_onBoardRegister($msg,$stat,$cfg,$m_name)
	  	{
			$data = json_decode($msg);
			$stat->version = $data->version;
			$stat->reg = time();
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
		
		public static function module_onCron($cron_time,$stat,$cfg,$m_name)
		{

		}

    }
?>
