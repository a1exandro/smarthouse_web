<?php
	function module_getContent($cfg,$m_name)
	{
		{ ?>
			<?=$switch->name;?>
			<input name='p<?=$switch->port;?>' type='checkbox' value='ON' onClick="javascript:onModuleAction('<?=$m_name;?>','p<?=$switch->port;?>');" <?if ($switch->val == 1) echo 'checked';?>><br>
		<? }
	}

?>