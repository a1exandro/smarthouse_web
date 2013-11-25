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
    						updateHistory();
    						setTimeout(updateHistory, 3000)
 					 }
 		});
}
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
    						$("#history").html(response);
    						document.getElementById('log_bottom').scrollIntoView();
 					 }
 		});
}

function switchModuleToEditMode(m_name)
{
	httpParams = "act=setModuleEditMode&m_name="+m_name;
	ajaxUpdate(httpParams,"#module_"+m_name);
}

function refreshModule(m_name)
{
	httpParams = "act=refreshModule&m_name="+m_name;
	ajaxUpdate(httpParams,"#module_"+m_name);
}

function saveModuleForm(m_name)
{
	httpParams = "act=saveModuleForm&m_name="+m_name;
	httpParams += "&" + $('#saveModuleForm_'+m_name).serialize();
	ajaxUpdate(httpParams,"#module_"+m_name);
}

function onModuleAction(m_name, p)
{
	httpParams = "act=moduleAction&m_name="+m_name+"&p="+p;
	httpParams += "&" + $('#module_form_'+m_name).serialize();
	ajaxUpdate(httpParams,"#module_"+m_name);
}

function ajaxUpdate(httpParams,block,url)
{
	if (!url) url = "ajax_idx.php";
	$.ajax(
 		{
 			type:	"POST",
 			url:	url,
 			data:	httpParams,
 			success: function(response)
 					 {
    						$(block).html(response);
 					 }
 		});
}

function getFreeId(templ)
{
	var id = 0 ;
	while ( $("#"+templ+id).html() ) id ++;
	return id;
}

function delBlock(id)
{
	if (confirm('¬ы действительно хотите удалить выбранное поле?'))
		$("#"+id).remove();
}