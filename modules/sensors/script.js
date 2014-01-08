function addSensor(id,name,addr,type)
{
	var templ = 'sens_block_';
	var noTypeField = true;	if (!name) { name = ''; noTypeField = false; }
	if (!addr) addr = '';
	if (!type) type = 'T';
	if (!id) id = getFreeId(templ);

    var fields = '';

	fields += "<div id='"+templ+id+"'> ";
		fields += "Имя: <input name='name[]' type='text' value='"+name+"'>";
		fields += " адрес: <input name='addr[]' type='text' value='"+addr+"'>";

		fields += " <select size='1' name='type[]'>  \
		  <option value='T' "+(type=='T'?'selected':'')+">Температурный</option>  \
		  <option value='H' "+(type=='H'?'selected':'')+">Влажность</option>      \
		  <option value='TH' "+(type=='TH'?'selected':'')+">Температура и влажность</option>   \
		</select>";
		fields += "[<a href=\"javascript:delBlock('"+templ+id+"');\">x</a>]";
	fields += "</div>";
	$("#sens_blocks").append(fields);
}
