<?php
	class camera 
	{		
		public static function module_onStatus($recv_stat,$statSender,$m_name,$g_stat,$cfg,$board_id)
		{
			if ($statSender != 'CAMERA') return NULL;
			$g_stat->data = (array)$g_stat->data;
			$cmd = '';
			switch ($recv_stat->type)
			{
                case 'cfg':
                    $updateInterval = $cfg->updateInterval;
                    $cfg = json_decode($recv_stat->data);
					$cfg->updateInterval = $updateInterval;
                    foreach ($cfg->camera as $came)
                    {
                        $cmd .= "camera get c{$came->addr};";
                    }
                    break;
				case 'picture':
				{
					foreach ($_FILES as  $file)
					{	
						$name = $file['name'];
						$dir_name = "modules/{$m_name}/img/{$board_id}";
						if (!file_exists($dir_name))
							mkdir($dir_name);
						$nName = "$dir_name/$name";
						if (!move_uploaded_file($file["tmp_name"],$nName)) echo "ERROR UPLOAD FILE";
						$g_stat->data[ 'c'.$recv_stat->addr ]->{$recv_stat->type} = $nName;
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
			$stat->data = (array)$stat->data;
			foreach ($cfg->camera as $came) 
			{
				$iUrl = $stat->data['c'.$came->addr]->picture;
			?>
				<?=$came->name."  - ";?>
				<a href='<?=$iUrl;?>'>
					<img src='<?=$iUrl;?>' width='200'/> 
				</a>
				
				<input name='c<?=$came->addr;?>' type='button' value='Обновить' onClick="javascript:onModuleAction('<?=$m_name;?>','c<?=$came->addr;?>');"><br>
			<? }
		}

		public static function module_onAction($cfg,$m_name)
	  	{
	  		$p = $_POST['p'];
			
			$res->cfg = $cfg;
			$res->cmd ="camera get $p";
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
	    	$cmd = "camera get cfg;";
	        $res->cmd = $cmd;
	    	return $res;
	  	}

	  	public static function module_editMode($cfg,$m_name)
		{
			$i = 0;
			echo "<div id = 'came_blocks'>";
				echo "<script>";
					foreach ($cfg->camera as $came)
					{
					?>
	            		addCame(<?=$i;?>,'<?=$came->name;?>','<?=$came->addr;?>');
					<?
						$i++;
					}
				echo "</script>";
			echo "</div>";
			echo "<a href='javascript:addCame();'>добавить камеру</a><br>";
		}

		public static function module_save($cfg,$m_name)
		{
			$names = $_POST['name'];
			$addrs = $_POST['addr'];
			
			$i = 0;
			$cfg->camera = array();
			foreach ($names as $name)
			{
				$addr = $addrs[$i];
				
				$came = new stdClass;
				$came->name = $name;
				$came->addr = $addr;
				
				if ($name)
					$cfg->camera[] = $came;
				$i++;
			}
			return $cfg;
		}
		
		public static function module_onCron($cron_time,$stat,$cfg,$m_name)
		{
			
		}
    }
?>
