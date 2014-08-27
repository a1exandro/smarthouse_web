<?php
define('VAL_ON',0);
define('VAL_OFF',1);
	class switches
	{
		public static function module_onStatus($recv_stat,$statSender,$m_name,$g_stat,$cfg,$board_id)
		{
			if ($statSender != 'GPIO') return NULL;
			$g_stat->data = (array)$g_stat->data;
			$g_stat->data[ 'p'.$recv_stat->port ]->{$recv_stat->type} = $recv_stat->data;

			$res->stat = $g_stat;
			return $res;
		}

		public static function module_getContent($cfg,$stat,$m_name)
		{
			$stat->data = (array)$stat->data;
			foreach ($cfg->switches as $switch)
			{
				if ($stat->data['p'.$switch->port]->port_val == $switch->val)
					$d_class='val_ok';
				else
					$d_class='val_err';

			?>
				<div class='<?=$d_class;?>'>
					<?=$switch->name;?>
					<input name='p<?=$switch->port;?>' type='checkbox' value='ON' onClick="javascript:onModuleAction('<?=$m_name;?>','p<?=$switch->port;?>');" <?if ($switch->val == VAL_ON) echo 'checked';?>><br>
				</div>
			<? }
		}

		public static function module_onAction($cfg,$m_name)
	  	{
	  		$p = $_POST['p'];
			if ($_POST[$p])
				$val = VAL_ON;
			else
				$val = VAL_OFF;

			foreach ($cfg->switches as $sw)
			{
				$port = (int)mb_substr($p,1);
				if ($sw->port == $port)
					$sw->val = $val;
			}
			$res->cfg = $cfg;
			$res->cmd ="gpio set $p = $val";
	  		return $res;
	  	}

		public static function module_onCommand($cmd,$msg,$stat,$cfg,$m_name)
	  	{
			switch($cmd)
			{
				case 'register':
					return $m_name::module_onBoardRegister($msg,$stat,$cfg,$m_name);
				break;
			}
		}
	  	public static function module_onBoardRegister($msg,$stat,$cfg,$m_name)
	  	{
	    	foreach ($cfg->switches as $sw)
			{
				$cmd .= "gpio set p{$sw->port} out;";
				$cmd .= "gpio set p{$sw->port} = $sw->val;";
			}
	        $res->cmd = $cmd;
	    	return $res;
	  	}

	  	public static function module_editMode($cfg,$m_name)
		{
			$i = 0;
			echo "<div id = 'sw_blocks'>";
				echo "<script>";
					foreach ($cfg->switches as $switch)
					{
					?>
	            		addSwitch(<?=$i;?>,'<?=$switch->name;?>','<?=$switch->port;?>');
					<?
						$i++;
					}
				echo "</script>";
			echo "</div>";
			echo "<a href='javascript:addSwitch();'>добавить переключатель</a><br>";
		}

		public static function module_save($cfg,$m_name)
		{
			$names = $_POST['name'];
			$ports = $_POST['port'];
			$i = 0;
			$cfg->switches = array();
			foreach ($names as $name)
			{
				$port = (int)$ports[$i++];
				$sw = new stdClass;
				$sw->name = $name;
				$sw->port = $port;

				if ($name && $port)
					$cfg->switches[] = $sw;
			}
			return $cfg;
		}

		public static function module_onCron($cron_time,$stat,$cfg,$m_name)
		{

		}
	}
?>
