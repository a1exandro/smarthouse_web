function addSwitch(id,name,port)
{
	var templ = 'sw_block_';	if (!name) name = '';
	if (!port) port = '';
	if (!id) id = getFreeId(templ);
    var fields = ''; 

	fields += "<div id='"+templ+id+"'> ";
		fields += "Имя: <input name='name[]' type='text' value='"+name+"'>";
		fields += " порт: <input name='addr[]' type='text' value='"+port+"'>";
		fields += "[<a href=\"javascript:delBlock('"+templ+id+"');\">x</a>]";
	fields += "</div>";
	$("#sw_blocks").append(fields);
}