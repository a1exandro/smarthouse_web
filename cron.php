<?php	require('config.php');	define('cron_time',60*60);	// 1 hour
	require('engine/engine.php');	modules::onCrontabExec(cron_time);
?>