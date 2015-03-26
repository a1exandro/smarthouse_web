<?php
	$dblocation="localhost";
	$dbuser="smhz";
	$dbpassw="LuhdA8FNz";
	$dbname="db_smhz";

	$dbcnx=@mysql_connect($dblocation, $dbuser, $dbpassw);
    mysql_select_db($dbname);

    date_default_timezone_set('Europe/Moscow');

	if(!$dbcnx)
	{
		die ("В настоящий момент соединение с базой данных невозможно.");
	}

	$sel=@mysql_select_db($dbname, $dbcnx);

	mysql_query("SET collation_connection = 'utf8_general_ci'");
	mysql_query("SET collation_server = 'utf8_general_ci'");
	mysql_query("SET character_set_client = 'utf8'");
	mysql_query("SET character_set_connection = 'utf8'");
	mysql_query("SET character_set_results = 'utf8'");
	mysql_query("SET character_set_server = 'utf8'");
	mysql_query("SET NAMES ‘utf8′ COLLATE ‘utf8_unicode_ci’;");
/////////////////////////////////////////////////////////////////

    define("SCRIPT_TIME_LIMIT",60);
	define("WAIT_CMD_TIMEOUT",30);
    define("WAIT_CMD_SLEEP_TIME",3);

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
 	ini_set("display_errors", 1);

?>