$(document).ready(function() {
	document.getElementById('log_bottom').scrollIntoView();
});

function sendCmd()
{	var cmd = $("#cmd").val();
	httpParams = "cmd="+cmd;
	$.ajax(
 		{
 			type:	"POST",
 			url:	"code/sendCommand.php",
 			data:	httpParams,
 			success: function(response)
 					 {
    						$("#cmd").val('');
 					 }
 		});
}


