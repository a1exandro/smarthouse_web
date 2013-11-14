<?php
	require('config.php');
    require('code/getHistory.php');
?>
<html>
	<head>
    	<link rel="stylesheet" href="css/style.css" type="text/css" />
    	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    	<script type="text/javascript" src="js/script.js"></script>
	</head>
	<body>
		<table border='1' width='100%'>
			<tr>
				<td class='capt'>
			     	Переключатели
				</td>
			</tr>
			<tr>
		    	<td>
		      		Полы <input name="Name" type="checkbox" value="ON"><br>
		      		Свет <input name="Name" type="checkbox" value="ON"><br>
		      	</td>
        	</tr>
        	<tr>
				<td class='capt'>
			     	История комманд\ответов
				</td>
			</tr>
        	<tr>
        		<td>
		      		<div id='logblock'>
		      			<?=getHistory();?>
		      		   	<div id='log_bottom'><a href="javascript::getHistory();">еще<a></div>
		      		</div>
		      		<input name="cmd" class="cmd" id="cmd" type="text" value=""><input type="button" value="Отправить" onClick="sendCmd();">
		      	</td>
        	</tr>
		</table>
	</body>
</html>