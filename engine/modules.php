<?php
	class modules
	{
		public static function getCfg($m_name)
		{
			$board_id = (int)$_SESSION['board_id'];
        	$q = mysql_query("SELECT config FROM boards WHERE id = $board_id");
			$cfg = json_decode(mysql_result($q,0))->$m_name;
			return $cfg;
		}

		public static function updateCfg($cfg,$m_name)
		{
			$board_id = (int)$_SESSION['board_id'];
			$q = mysql_query("SELECT config FROM boards WHERE id = $board_id");
			$g_cfg = json_decode(mysql_result($q,0));
			$g_cfg->$m_name = $cfg;

			$j_conf = addslashes(json_encode($g_cfg));
			$q = mysql_query("UPDATE boards SET config = '$j_conf' WHERE id = $board_id");
			echo mysql_error();
		}

		public static function getStat($m_name)
		{
			$board_id = (int)$_SESSION['board_id'];
        	$q = mysql_query("SELECT stat FROM boards WHERE id = $board_id");
			$stat = (json_decode(mysql_result($q,0))->$m_name);
			return $stat;
		}

		public static function updateStat($stat,$m_name)
		{
			$board_id = (int)$_SESSION['board_id'];
			$q = mysql_query("SELECT stat FROM boards WHERE id = $board_id");
			$g_stat = json_decode(mysql_result($q,0));
			$g_stat->$m_name = $stat;
			$j_stat = addslashes(json_encode($g_stat));
			$q = mysql_query("UPDATE boards SET stat = '$j_stat' WHERE id = $board_id");
		}

		public static function load($m_name)
		{
			echo "<script type='text/javascript' charset='utf-8' src='modules/$m_name/script.js'></script>";
			echo "<div id='module_$m_name'>";
				modules::getContent($m_name);
				$cfg = modules::getCfg($m_name);
			echo "</div>";
			echo "<script>onModuleLoad('$m_name',{$cfg->updateInterval});</script>";
		}

		public static function getContent($m_name)
		{
			echo "<form id='module_form_$m_name'>";
				if (!class_exists($m_name))
					require ("modules/$m_name/index.php");
				$cfg = modules::getCfg($m_name);
				$stat = modules::getStat($m_name);
                $m_name::module_getContent($cfg,$stat,$m_name);
			echo "</form>";
			echo "<a href=\"javascript:switchModuleToEditMode('$m_name');\">редактировать</a>";
		}

		public static function switchToEditMode($m_name)
		{
			echo "<form meth='POST' name='saveModuleForm_$m_name' id='saveModuleForm_$m_name' action='modules/$m_name/save.php'>";
				require ("modules/$m_name/index.php");
				$cfg = modules::getCfg($m_name);
                $m_name::module_editMode($cfg,$m_name);
				echo "<br>Интервал обновления (сек): <input type='text' name='updateInterval' value='{$cfg->updateInterval}'>";
			echo "<br><input type='button' value='Сохранить' onClick=\"javascript:saveModuleForm('$m_name')\">
			      <input type='button' value='Отменить' onClick=\"javascript:onModuleUpdate('$m_name',{$cfg->updateInterval});refreshModule('$m_name')\">
			</form>";
			echo "<script>onModuleEditMode('$m_name');</script>";
		}

		public static function saveModuleForm($m_name)
		{
			require ("modules/$m_name/index.php");
			$cfg = modules::getCfg($m_name);
			$cfg = $m_name::module_save($cfg,$m_name);
		////////////////////////////////////////////////////
			$updateInterval = (int)$_POST['updateInterval'];
			$cfg->updateInterval = $updateInterval;
		////////////////////////////////////////////////////
			modules::updateCfg($cfg,$m_name);
			modules::getContent($m_name);
			echo "<script>onModuleUpdate('$m_name',{$cfg->updateInterval});</script>";
		}

        public static function onAction($m_name)
		{
			require ("modules/$m_name/index.php");
			$cfg = modules::getCfg($m_name);
			$result = $m_name::module_onAction($cfg,$m_name);
			if ($result->cfg) modules::updateCfg($result->cfg,$m_name);
			if ($result->cmd) engine::execCommand($result->cmd);
			modules::getContent($m_name);

			$updTime = (WAIT_CMD_SLEEP_TIME+1)*1000;
			echo "<script>setTimeout(refreshModule, {$updTime},'$m_name');</script>";
		}

		public static function onCommand($cmd,$msg)
		{
			$m_dir = "modules";
			$excl_list = array('..','.');
			if ($dh = opendir($m_dir))
			{
				while ($m_name = readdir($dh))
				{
					if (is_dir($m_dir. DIRECTORY_SEPARATOR .$m_name) && (!in_array($m_name,$excl_list)))
			        {
			        	require($m_dir.DIRECTORY_SEPARATOR.$m_name.DIRECTORY_SEPARATOR."index.php");
						$result = $m_name::module_onCommand($cmd,$msg,modules::getStat($m_name), modules::getCfg($m_name),$m_name);
						if ($result->cmd) engine::execCommand($result->cmd);
						if ($result->cfg) modules::updateCfg($result->cfg,$m_name);
						if ($result->stat) modules::updateStat($result->stat,$m_name);
					}
		        }
		        closedir($dh);
			}
		}
		public static function onMsg($msg)
		{
			$reg = '/([a-zA-Z]+): *(.+)/';

			if (!preg_match($reg, $msg, $matches)) return;

			$statSender = $matches[1];
			$rcv_data = json_decode(stripslashes($matches[2]));

			if ((!$rcv_data) || (!$statSender)) return;

			if (is_array($rcv_data))
			   	foreach ($rcv_data as $msg)
			   		self::parseMsg($msg,$statSender);
			else
				self::parseMsg($rcv_data,$statSender);


		}
		public static function parseMsg($data,$statSender)
		{
			$excl_list = array('..','.');
			$board_id = (int)$_SESSION['board_id'];
			$m_dir = "modules";
			if ($dh = opendir($m_dir))
			{
				while ($m_name = readdir($dh))
				{
					if (is_dir($m_dir. DIRECTORY_SEPARATOR .$m_name) && (!in_array($m_name,$excl_list)))
			        {
			        	require_once($m_dir.DIRECTORY_SEPARATOR.$m_name.DIRECTORY_SEPARATOR."index.php");
						$result = $m_name::module_onStatus($data,$statSender,$m_name,modules::getStat($m_name),modules::getCfg($m_name),$board_id);
						if ($result->stat) modules::updateStat($result->stat,$m_name);
						if ($result->cfg) modules::updateCfg($result->cfg,$m_name);
						if ($result->cmd) engine::execCommand($result->cmd);
					}
		        }
		        closedir($dh);
			}
		}

		public static function onCrontabExec($cron_time)
		{
			$m_dir = "modules";
			$excl_list = array('..','.');
			if ($dh = opendir($m_dir))
			{
				while ($m_name = readdir($dh))
				{
					if (is_dir($m_dir. DIRECTORY_SEPARATOR .$m_name) && (!in_array($m_name,$excl_list)))
			        {
			        	require($m_dir.DIRECTORY_SEPARATOR.$m_name.DIRECTORY_SEPARATOR."index.php");
						$result = $m_name::module_onCron($cron_time,modules::getStat($m_name), modules::getCfg($m_name),$m_name);
						if ($result->cmd) engine::execCommand($result->cmd);
						if ($result->cfg) modules::updateCfg($result->cfg,$m_name);
						if ($result->stat) modules::updateStat($result->stat,$m_name);
					}
		        }
		        closedir($dh);
			}
		}
	}

?>