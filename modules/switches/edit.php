<?php
	function module_editMode($cfg,$m_name)
	{
		$i = 0;
		echo "<div id = 'sw_blocks'>";
			echo "<script>";				foreach ($cfg->switches as $switch)
				{
				?>            		addSwitch('<?=$switch->name;?>','<?=$switch->port;?>',<?=$i;?>);
				<?
					$i++;
				}
			echo "</script>";
		echo "</div>";
		echo "<a href='javascript:addSwitch();'>добавить переключатель</a><br>";
	}

?>
