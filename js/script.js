var modules = []

$(document).ready(function() {
	document.getElementById('log_bottom').scrollIntoView();
});


function onModuleEditMode(m_name)
{
	for (i=0; i<modules.length; i++)
	{
		if (modules[i].m_name == m_name)
		{
			if (modules[i].timerId != 0)
			{
				clearInterval(modules[i].timerId);
			}
		}
	}
}

function onModuleUpdate(m_name,updateInterval)
{
	for (i=0; i<modules.length; i++)
	{
		if (modules[i].m_name == m_name)
		{
			if (modules[i].timerId != 0)
			{
				clearInterval(modules[i].timerId);
				if (updateInterval > 0)
					modules[i].timerId = setInterval(refreshModule,updateInterval*1000,m_name);
			}
		}
	}
}


function onModuleLoad(m_name,updateInterval)
{
	var timerId = 0;
	if (updateInterval > 0)
		timerId = setInterval(refreshModule,updateInterval*1000,m_name);
	var m =  {
		m_name:m_name,
		timerId: timerId
	}
	modules.push(m);
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
	if (confirm('Вы действительно хотите удалить выбранное поле?'))
		$("#"+id).remove();
}