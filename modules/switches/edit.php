<?php
	function module_editMode($cfg,$m_name)
	{
		$i = 0;
		echo "<div id = 'sw_blocks'>";
			echo "<script>";
				{
				?>
				<?
					$i++;
				}
			echo "</script>";
		echo "</div>";
		echo "<a href='javascript:addSwitch();'>добавить переключатель</a><br>";
	}

?>