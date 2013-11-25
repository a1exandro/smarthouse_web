<?php
	class modules
	{
		public static function getCfg($m_name)
		{
			$board_id = $_SESSION['board_id'];        	$q = mysql_query("SELECT config FROM boards WHERE id = $board_id");
			$cfg = json_decode(mysql_result($q,0))->$m_name;
			return $cfg;
		}

		public static function updateCfg($cfg,$m_name)
		{
			$board_id = $_SESSION['board_id'];			$q = mysql_query("SELECT config FROM boards WHERE id = $board_id");
			$g_cfg = json_decode(mysql_result($q));
			$g_cfg->$m_name = $cfg;
			$j_conf = addslashes(json_encode($g_cfg));
			$q = mysql_query("UPDATE boards SET config = '$j_conf' WHERE id = $board_id");
			echo mysql_error();
		}
		public static function load($m_name)
		{
			echo "<script type='text/javascript' charset='utf-8' src='modules/$m_name/script.js'></script>";
			echo "<div id='module_$m_name'>";
				modules::getContent($m_name);
			echo "</div>";

		}

		public static function getContent($m_name)
		{
			echo "<form id='module_form_$m_name'>";
				require ("modules/$m_name/index.php");
				$cfg = modules::getCfg($m_name);
                module_getContent($cfg,$m_name);
			echo "</form>";
			echo "<br><a href=\"javascript:switchModuleToEditMode('$m_name');\">редактировать</a>";
		}

		public static function switchToEditMode($m_name)
		{
			echo "<form meth='POST' name='saveModuleForm_$m_name' id='saveModuleForm_$m_name' action='modules/$m_name/save.php'>";
				require ("modules/$m_name/edit.php");
				$cfg = modules::getCfg($m_name);
                module_editMode($cfg,$m_name);
			echo "<br><input type='button' value='Сохранить' onClick=\"javascript:saveModuleForm('$m_name')\">
			      <input type='button' value='Отменить' onClick=\"javascript:refreshModule('$m_name')\">
			</form>";

		}

		public static function saveModuleForm($m_name)
		{
			require ("modules/$m_name/save.php");
			$cfg = modules::getCfg($m_name);
			$cfg = module_save($cfg,$m_name);
			modules::updateCfg($cfg,$m_name);
			modules::getContent($m_name);
		}

        public static function onAction($m_name)
		{
			require ("modules/$m_name/action.php");
			$cfg = modules::getCfg($m_name);
			$result = module_onAction($cfg,$m_name);
			modules::updateCfg($result->cfg,$m_name);
			if ($result->cmd) engine::execCommand($result->cmd);
			modules::getContent($m_name);
		}

		public static function onBoardRegister()
		{			$m_dir = "modules";
			$excl_list = array('..','.');
			if ($dh = opendir($m_dir))
			{				while ($m_name = readdir($dh))
				{
					if (is_dir($m_dir. DIRECTORY_SEPARATOR .$m_name) && (!in_array($m_name,$excl_list)))
			        {			        	require($m_dir.DIRECTORY_SEPARATOR.$m_name.DIRECTORY_SEPARATOR."action.php");
						$result = module_onBoardRegister(modules::getCfg($m_name),$m_name);
						if ($result->cmd) engine::execCommand($result->cmd);
					}
		        }
		        closedir($dh);
			}

		}
	}

?>