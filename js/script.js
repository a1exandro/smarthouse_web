$(document).ready(function() {
	document.getElementById('log_bottom').scrollIntoView();
});

function sendCmd()
{
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

