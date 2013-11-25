<?php
	$board_id = 1;
	require('config.php');
    require('code/getHistory.php');
    require('code/getInfo.php');
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
			     	<?=getInfo();?>
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
		      		51°
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
		      		<div id='logblock'>
		      			<div id='history'>
		      				<?getHistory();?>
		    			</div>
		      		   	<div id='log_bottom'><a href="javascript:updateHistory();">обновить</a></div>
		      		</div>
		      		<input name="cmd" class="cmd" id="cmd" type="text" value=""><input type="button" value="Отправить" onClick="sendCmd();">
		      	</td>
		      	<td>
		      		came 1
		  		</td>
        	</tr>
		</table>
	</body>
</html>