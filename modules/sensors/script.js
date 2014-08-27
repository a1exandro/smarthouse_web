function addSensor(id,name,addr,type,err_sign,err_val,err_warn)
{
	var templ = 'sens_block_';	if (!name) { name = ''; }
	if (!addr) addr = '';
	if (!type) type = 'T';
	if (!id) id = getFreeId(templ);
	if (!err_sign) err_sign = 1;
	if (!err_val) err_val = 50;
	if (err_warn == 1) 
		checked='checked="checked"'; 
	else 
		checked='';
		
    var fields = '';
	fields += "<div id='"+templ+id+"'> "; 
		fields += "Имя: <input name='name[]' type='text' value='"+name+"'>";
		fields += "<br>адрес: <input name='addr[]' type='text' value='"+addr+"'>";

		fields += "<br>тип: <select size='1' name='type[]'>  \
		  <option value='T' "+(type=='T'?'selected':'')+">Температурный</option>  \
		  <option value='H' "+(type=='H'?'selected':'')+">Влажность</option>      \
		  <option value='D' "+(type=='D'?'selected':'')+">Цифровой</option>   \
		</select>";
		fields += "<br>нет нормы, если значение ";
		
		fields += " <select size='1' name='err_sign[]'>  \
		  <option value='0' "+(err_sign=='0'?'selected':'')+"> меньше </option>  \
		  <option value='1' "+(err_sign=='1'?'selected':'')+"> равно </option>      \
		  <option value='2' "+(err_sign=='2'?'selected':'')+"> больше </option>   \
		</select>";
		
		fields += "<input name='err_val[]' type='text' value='"+err_val+"' size=3>";
		fields += "<br><input name='err_warn_"+id+"' type='checkbox' value='1' "+checked+" id='err_warn_"+id+"'><label for='err_warn_"+id+"'>оповестить при недопустимом значении</label><br>";
		
		fields += "[<a href=\"javascript:delBlock('"+templ+id+"');\">удалить датчик</a>] <hr>";
	fields += "</div>";
	$("#sens_blocks").append(fields);
}
