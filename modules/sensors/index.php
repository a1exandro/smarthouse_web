<?php
	class sensors 
	{		
		public static function module_onStatus($recv_stat,$statSender,$m_name,$g_stat,$cfg,$board_id)
		{
			if ($statSender != 'SENSORS') return NULL;
			$g_stat->data = (array)$g_stat->data;
			$cmd = '';
			switch ($recv_stat->type)
			{
				case 'temp':
					$g_stat->data[ 'a'.$recv_stat->addr ]->{$recv_stat->type} = $recv_stat->data;
				break;
				case 'list':
					foreach ($recv_stat->data as $sens)
					{
						if (!sensors::sensorExists($sens,$cfg)) 
						{
							$obj = new stdClass;
							$obj->name = "Датчик #$sens";
							$obj->addr = $sens;
							$obj->type = 'T';
							array_push($cfg->sensors,$obj);
							$cmd .= "sensors get a$sens;";
						}
					}
				break;
			}

			$res->stat = $g_stat;
			$res->cfg = $cfg;
			$res->cmd = $cmd;
			return $res;
		}
		
		public static function module_getContent($cfg,$stat,$m_name)
		{
			$stat->data = (array)$stat->data;			foreach ($cfg->sensors as $sensor) 
			{
				$sensor->temp = 0;
				$sensor->humidity = 0;
			 	if ( $stat->data['a'.$sensor->addr] ) 
				{ 
					if ($stat->data['a'.$sensor->addr]->temp)
						$sensor->temp = $stat->data['a'.$sensor->addr]->temp;
					if ($stat->data['a'.$sensor->addr]->humidity)
						$sensor->humidity = $stat->data['a'.$sensor->addr]->humidity;
				}	
			?>
				<?=$sensor->name."  - ";?>

				<?
					switch ($sensor->type)
					{						case "T":
						{
							echo "темп: {$sensor->temp}°";
						} break;
						case "TH":
						{							echo "темп: {$sensor->temp}°; влажность: {$sensor->humidity}%";
						} break;
						case "H":
						{							echo "влажность: {$sensor->humidity}%";
						} break;
					}
				?>
				<input name='a<?=$sensor->addr;?>' type='button' value='Обновить' onClick="javascript:onModuleAction('<?=$m_name;?>','a<?=$sensor->addr;?>');"><br>
			<? }
		}

		public static function module_onAction($cfg,$m_name)
	  	{
	  		$p = $_POST['p'];
			if ($_POST[$p])
				$val = 1;
			else
				$val = 0;

			$res->cfg = $cfg;
			$res->cmd ="sensors get $p;";
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
			$cmd = 'sensors get list;';
	    	foreach ($cfg->sensors as $sens)
			{
				$cmd .= "sensors get a{$sens->addr};";
			}
	        $res->cmd = $cmd;
	    	return $res;
	  	}

	  	public static function module_editMode($cfg,$m_name)
		{
			$i = 0;
			echo "<div id = 'sens_blocks'>";
				echo "<script>";
					foreach ($cfg->sensors as $sensor)
					{
					?>
	            		addSensor(<?=$i;?>,'<?=$sensor->name;?>','<?=$sensor->addr;?>','<?=$sensor->type;?>');
					<?
						$i++;
					}
				echo "</script>";
			echo "</div>";
			echo "<a href='javascript:addSensor();'>добавить датчик</a><br>";
		}

		public static function module_save($cfg,$m_name)
		{
			$names = $_POST['name'];
			$addrs = $_POST['addr'];
			$types = $_POST['type'];
			$i = 0;
			$cfg->sensors = array();
			foreach ($names as $name)
			{
				$addr = $addrs[$i];
				$type = $types[$i++];
				$sens = new stdClass;
				$sens->name = $name;
				$sens->addr = $addr;
				$sens->type = $type;
				if ($name && $addr && $type)
					$cfg->sensors[] = $sens;
			}
			return $cfg;
		}
		
		public static function sensorExists($sensor,$cfg)
		{
			foreach ($cfg->sensors as $s)
				if ($s->addr == $sensor) return true;
			return false;
		}
    }
?>
