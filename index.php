<?php
	require('config.php');
	require('engine/engine.php');
?>
<html>
	<head>
    	<link rel="stylesheet" href="css/style.css" type="text/css" />
    	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    	<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<table border='1' width='100%'>
			<tr>
				<td class='capt' colspan='2'>
			     	Информация
				</td>
			</tr>
			<tr>
				<td colspan='2'>
			     	<? modules::load('info'); ?>
				</td>
			</tr>
			<tr>
				<td class='capt' width='50%'>
			     	Переключатели
				</td>
				<td class='capt'>
			     	Датчики 
				</td>
			</tr>
			<tr>
		    	<td>
		      		<? modules::load('switches'); ?>
		      	</td>
		      	<td>
		      		<? modules::load('sensors'); ?>
		  		</td>
        	</tr>
        	<tr>
				<td class='capt'>
			     	История комманд\ответов
				</td>
				<td class='capt'>
			     	Камеры
				</td>
			</tr>
        	<tr>
        		<td>
					<? modules::load('commands'); ?>
		      	</td>
		      	<td>
		      		<? modules::load('camera'); ?>
		  		</td>
        	</tr>
		</table>
	</body>
</html>