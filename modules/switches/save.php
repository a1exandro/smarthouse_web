<?php
	function module_save($cfg,$m_name)
	{
		$names = $_POST['name'];
		$ports = $_POST['port'];
		$i = 0;
		$cfg->switches = array();
		foreach ($names as $name)
		{			$port = (int)$ports[$i++];
			$sw = new stdClass;
			$sw->name = $name;
			$sw->port = $port;
			$sw->val = 0;
			if ($name && $port)
				$cfg->switches[] = $sw;
		}
		return $cfg;
	}
?>
