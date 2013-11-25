<?php
  	function module_onAction($cfg,$m_name)
  	{
  		$p = $_POST['p'];
		if ($_POST[$p])
			$val = 1;
		else
			$val = 0;

		foreach ($cfg->switches as $sw)
		{
			$port = (int)mb_substr($p,1);			if ($sw->port == $port)
				$sw->val = $val;
		}
		$res->cfg = $cfg;
		$res->cmd ="gpio set $p = $val";  		return $res;
  	}

  	function module_onBoardRegister($cfg,$m_name)
  	{    	foreach ($cfg->switches as $sw)
		{
			$cmd .= "gpio set p{$sw->port} out;";
			$cmd .= "gpio set p{$sw->port} = $sw->val;";
		}
        $res->cmd = $cmd;
    	return $res;
  	}

?>