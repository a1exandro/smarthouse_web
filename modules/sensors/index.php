<?php
	class sensors
	{
		public static function module_onStatus($recv_stat,$statSender,$m_name,$g_stat,$cfg,$board_id)
		{
			if ($statSender != 'SENSORS') return NULL;
			$g_stat->data = (array)$g_stat->data;
			$cmd = '';
			$sensor_addr = '';

			switch ($recv_stat->type)
			{
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
							$cmd .= "sensors get T$sens;";
							$sensor_addr = $obj->addr;
							$sensor_type = $obj->type;
						}
					}
				break;
                case 'cfg':
					$updateInterval = $cfg->updateInterval;
                    $cfg = json_decode($recv_stat->data);
					$cfg->updateInterval = $updateInterval;
                    foreach ($cfg->sensors as $sensor)
                        $cmd .= "sensors get {$sensor->type}{$sensor->addr};";
                break;
				default:
					$g_stat->data[ $recv_stat->type.$recv_stat->addr ]->{$recv_stat->type} = $recv_stat->data;
					$sensor_addr = $recv_stat->addr;
					$sensor_type = $recv_stat->type;
				break;
			}

			$res->stat = self::module_checkSensor($cfg, $g_stat, $sensor_addr, $sensor_type);

			$res->cfg = $cfg;
			$res->cmd = $cmd;
			return $res;
		}

		public static function module_checkSensor($cfg, $stat, $addr, $type)
		{
			foreach ($cfg->sensors as $sensor)
			{
				if ($sensor->type == $type && $sensor->addr == $addr) // found sensor
				{
					$sensor->data = 0;
					if ( $stat->data[$sensor->type.$sensor->addr] )
					{
						$type = $sensor->type;
						if (isset($stat->data[$sensor->type.$sensor->addr]->$type))
						{
							$sensor->data = $stat->data[$sensor->type.$sensor->addr]->$type;
						}
					}
					$rangeErr = self::module_checkRange($sensor);
					if ($stat->data[$sensor->type.$sensor->addr]->warn != $rangeErr) // warning status changed
					{
						if ($rangeErr && $sensor->err_warn)	// if error & errors warning enabled - send msg
						{
							//print("\nOUT OF RANGE ON SENSOR ".$sensor->type.$sensor->addr);
							require('engine/alarm.php');
							$sensorName = (mb_substr($sensor->name,0,25));											// max 25 symbols
							sendAlarm("SmartHouse:\nКритическое значение: '{$sensorName}' - {$sensor->data}");	// len 39 symbols + val(max 6) = 45 symbols
						}

						$stat->data[$sensor->type.$sensor->addr]->warn = $rangeErr;
					}

					break;
				}
			}
			return $stat;
		}
		public static function module_getContent($cfg,$stat,$m_name)
		{
			$stat->data = (array)$stat->data;
			echo "<table>";
			foreach ($cfg->sensors as $sensor)
			{
				$sensor->data = 0;
				echo "<tr>";
			 	if ( $stat->data[$sensor->type.$sensor->addr] )
				{
					$type = $sensor->type;
					if (isset($stat->data[$sensor->type.$sensor->addr]->$type))
					{
						$sensor->data = $stat->data[$sensor->type.$sensor->addr]->$type;
					}

				}
				$rangeErr = self::module_checkRange($sensor);

				if (!$rangeErr)
					$d_class='val_ok';
				else
				{
					$d_class='val_err';
				}

				echo "<td class='$d_class'>";
				echo "$sensor->name  - ";


				switch ($sensor->type)
				{
					case "T":
					{
						echo "{$sensor->data}°";
					} break;
					case "H":
					{
						echo "{$sensor->data}%";
					} break;
					default:
					{
						echo "{$sensor->data}";
					}; break;
				}
				echo "</td>";
				echo "<td><input name='{$sensor->type}{$sensor->addr}' type='button' value='Обновить' onClick=\"javascript:onModuleAction('{$m_name}','{$sensor->type}{$sensor->addr}');\"></td>";
				echo "</tr>";
			}
			echo "</table>";
		}

		public static function module_checkRange($sensor)
		{
			switch ($sensor->err_sign)
			{
				case 0:	// <
					return ($sensor->data < $sensor->err_val);
				break;
				case 1:	// =
					return ($sensor->data == $sensor->err_val);
				break;
				case 2:	// >
					return ($sensor->data > $sensor->err_val);
				break;
				default:

				break;
			}

			return true;
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
			$cmd = 'sensors get cfg;';

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
	            		addSensor(<?=$i;?>,'<?=$sensor->name;?>','<?=$sensor->addr;?>','<?=$sensor->type;?>','<?=$sensor->err_sign;?>','<?=$sensor->err_val;?>','<?=$sensor->err_warn;?>');
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
			$err_signs = $_POST['err_sign'];
			$err_vals = $_POST['err_val'];

			$i = 0;
			$cfg->sensors = array();
			foreach ($names as $name)
			{
				$addr = $addrs[$i];
				$type = $types[$i];
				$sens = new stdClass;
				$sens->name = $name;
				$sens->addr = $addr;
				$sens->type = $type;
				$sens->err_sign = $err_signs[$i];
				$sens->err_val = $err_vals[$i];
				$sens->err_warn = isset($_POST["err_warn_$i"])?1:0;
				if ($name && $addr && $type)
					$cfg->sensors[] = $sens;
				$i++;
			}
			return $cfg;
		}

		public static function sensorExists($sensor,$cfg)
		{
			foreach ($cfg->sensors as $s)
				if ($s->addr == $sensor) return true;
			return false;
		}

		public static function module_onCron($cron_time,$stat,$cfg,$m_name)
		{

		}
    }
?>
