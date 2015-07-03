function change_result_text(){
	id = $("#dev-text-input-id").val();
	name = $("#dev-text-input-name").val();
	type = $("#dev-text-input-type").val();
	placeholder = $("#dev-text-input-placeholder").val();
	
	input_label = $("#dev-text-label");
	input = $("#dev-text-input");
	translate_text = $("#dev-text-translate-text");
	translate_placeholder = $("#dev-text-translate-placeholder");
	//console.log(id);
	//console.log(name);
	//console.log(type);
	//console.log(placeholder);

	$(input_label).html(name);
	$(input).attr("placeholder",placeholder);
	$(translate_text).html(name);
	$(translate_placeholder).html(placeholder);

	key =["id","name","type","placeholder"];
	values = [id,name,type,placeholder];
	change_table("dev-text-markdown",key,values);
}


function change_table(id_table,key,values){
	id = $("#"+id_table);
	
	//for()

	//console.log($(id));
	//max_length = 0;
	
	//Check length of values/key
	//max_length = check_length(key,max_length);
	//max_length = check_length(values,max_length);
	html = generate_table(key,values);
	id.html(html);
	//html = "";
	//html = html + draw_line(key,max_length);
	//draw_emptyline();
	//html = html + draw_line(values,max_length);
	//console.log(html);

}

function generate_table(keys,values){
	html = [];
	html.key = "";
	html.values = "";
	html.separator = "";
	for(var i=0;i<keys.length;i++){
		value = values[i];
		key = keys[i];
		console.log(value);
		if(value.length > key.length){
			key = addspace(key,value.length);
			html.separator = adddash(html.separator,value.length);

		}
		else
		{
			value = addspace(value,key.length);
			html.separator = adddash(html.separator,key.length);
			console.log(value.length);
			console.log(key.length);
		}
		html.key = html.key + key;
		html.values = html.values + value;
		
		if(i != (keys.length - 1)){
		html.key = html.key + " |";
		html.values = html.values + " |";
		html.separator = html.separator + "|";
		}
	}
	html.key = html.key.trim();
	html.values = html.values.trim();
	console.log(html.key);
	console.log(html.separator);
	console.log(html.values);

	result = html.key + "\n" + html.separator + "\n" + html.values;
	return result;
}

function addspace(string,nb){
	for(var i=string.length; i<nb;i++){
		string = string + " ";
	}
	return string;
}

function adddash(string,nb){
	nb = nb +1;
	for(var i=0;i<nb;i++){
		string = string + "-";
	}
	return string;
}
