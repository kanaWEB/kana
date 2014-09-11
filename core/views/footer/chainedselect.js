//Managing Actions
del_inprogress = false;
add_inprogress = false;
id_actions = parseInt($("#nb_actions").val());

//Jquery Chained

/*
Chaining fields
*/

//We need to copy all options of selected that are chained
//So we can duplicate them entirely
type_html = $("#plugin_actions0").html();
objects_html = $("#objects_actions0").html();

//Actions
for(i = 0;i <= id_actions;i++ ){
	$("#objects_actions"+i).chained("#plugin_actions"+i);
}



refresh_actions();

/*
Functions
*/

function refresh_actions() {
	//Get number of actions
	nb_actions = $("#nb_actions").val();
	for(i=0;i <= nb_actions;i++){
		value_action = $("#actions"+i).val();	
	}
}

function add_actions(){
	console.log("Cloning actions");
	id_lastaction = id_actions;
	id_actions = id_actions + 1;

	var clone = $("#actions_list0").clone();
	
	modify_allid(clone,id_actions);
	clone.appendTo($("#new_actions"));
	clone.find('.bootstrap-select').remove();
	$('.selectpicker').selectpicker({
		width: '270px',
		style: 'label-info label'
	});

	$("#nb_actions").val(id_actions);
	rechained(id_actions);
}

$(document).on("click",".del_action",function() {
	console.log("Delete Action");
	action_to_delete = $(this).parent().parent();
	console.log(action_to_delete);
	action_to_delete_id = action_to_delete.attr("id");
	if (action_to_delete_id != "actions_list0"){
		action_to_delete.fadeOut("fast", function() {
			action_to_delete.remove();
			refresh_id(action_to_delete_id);
			id_actions--;
			nb_actions--;
			$("#nb_actions").val(nb_actions);
		});
	}
});


function modify_allid($clone,id){
	    // Get the number at the end of the ID, increment it, and replace the old id
	    $clone.attr('id',$clone.attr('id').replace(/\d+$/, id)); 

     // Find all elements in $clone that have an ID, and iterate using each()
     $clone.find('[id]').each(function() { 

     //Perform the same replace as above
     var $th = $(this);
     
     switch($th.attr("id")){
     	case "legend_nb0":
     	$th.html((id+1));
     	break;
     	case "plugin_actions0":
     	$th.html(type_html);
     	break;
     	case "objects_actions0":
     	$th.html(objects_html);
     	break;
     }

     

     var newID = $th.attr('id').replace(/\d+$/, id);
     $th.attr('id', newID);
     $th.attr('name', newID);
 });
 }

 function rechained(new_id){
 	console.log("rechained");
 	$("#objects_actions"+i).chained("#plugin_actions"+i);
 	refresh_actions();
 }

 function refresh_id(id_delete){
 	nb_actions = $("#nb_actions").val();
 	id_delete = id_delete.replace("actions_list","")
 	id_delete = parseInt(id_delete);
 	console.log("Refreshing id");
 	console.log("nb_actions: "+nb_actions);
 	console.log("id_delete: "+id_delete);
 	for(i=id_delete;i <= nb_actions;i++){
 		console.log("modify:" + (i+1) + "-->" + (i));
 		refresh_input("actions_list",i,i-1);
 		refresh_input("plugins_actions",i,i-1);
 		refresh_input("objects_actions",i,i-1);
 		$("#legend_nb"+i).html((i));
 		$("#legend_nb"+i).attr("id","legend_nb" + (i-1));
 	}
 	console.log("-------------");
 }

 function refresh_input(name,oldid,newid){
 	$("#"+name+oldid).attr("name",name+newid)
 	$("#"+name+oldid).attr("id",name+newid)
 }