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
			$port = (int)mb_substr($p,1);
				$sw->val = $val;
		}
		$res->cfg = $cfg;
		$res->cmd ="gpio set $p = $val";
  	}

  	function module_onBoardRegister($cfg,$m_name)
  	{
		{
			$cmd .= "gpio set p{$sw->port} out;";
			$cmd .= "gpio set p{$sw->port} = $sw->val;";
		}
        $res->cmd = $cmd;
    	return $res;
  	}

?>