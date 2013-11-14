<?php
	require('../config.php');
	$cmd = mysql_escape_string($_POST['cmd']);
	if (strlen($cmd) == 0) die();

	if (mb_substr($cmd,-1) != ';') $cmd .= ';';
	$time = time();
	$q = mysql_query("INSERT INTO commands (command,add_time) VALUES ('$cmd',$time);");
	echo mysql_error();
?>