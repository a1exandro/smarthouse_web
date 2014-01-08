function updateHistory()
{
	httpParams = "";
	$.ajax(
 		{
 			type:	"POST",
 			url:	"code/getHistory.php",
 			data:	httpParams,
 			success: function(response)
 					 {
						var el = $("#history");
						if (el)
						{
    						$("#history").html(response);
    						document.getElementById('log_bottom').scrollIntoView();
						}
 					 }
 		});
}

$(document).ready(function() {
	updateInterval = 5;
	//setInterval(updateHistory,updateInterval*1000)
});