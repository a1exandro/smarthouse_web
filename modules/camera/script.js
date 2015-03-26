function addCame(id,name,addr)
{
	var templ = 'came_block_';
	if (!name) name = ''; 
	if (!addr) addr = '';
	if (!id) id = getFreeId(templ);

    var fields = '';

	fields += "<div id='"+templ+id+"'> ";
		fields += "Имя: <input name='name[]' type='text' value='"+name+"'>";
		fields += " идентификатор: <input name='addr[]' type='text' value='"+addr+"'>";

		fields += "[<a href=\"javascript:delBlock('"+templ+id+"');\">x</a>]";
	fields += "</div>";
	$("#came_blocks").append(fields);
}
