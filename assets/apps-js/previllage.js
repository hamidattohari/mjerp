var table;
var action;
var selectedNode;
var role_id;
$(document).ready(function() {
	$("#saveBtn").prop("disabled", true);
	var columns = [];
    var right_align = [];
    $("#datatable").find('th').each(function(i, th){
        var field = $(th).attr('data-field');
        var align = $(th).attr('data-align');
        columns.push({data: field, name: field});
        if(align == "right")
            right_align.push(i);
    });

	table = $('#datatable').DataTable({
        dom: 't',
        columns: columns,
        columnDefs: [ 
			{ className: "dt-body-right", targets: right_align },
			{ "orderable": false, targets : [-1]  }, 
			{ "visible": false, targets : [0]  }, 
        ]
	});

	$('#datatable tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('active') ) {
		$(this).removeClass('active');
		}
		else {
			table.$('tr.active').removeClass('active');
			$(this).addClass('active');
		}
		var id = table.row( this ).data().id;
		role_id = id;
		$("#saveBtn").prop("disabled", false);
		generate_tree(id);
	} );
	
	$('#saveBtn').click(function(){
		$.ajax({
            url : site_url+"previllage/save_previllage",
            type: "POST",
            data:{
				id:role_id,
				menu:selectedNode,
				csrf_token:$("[name='csrf_token']").val()
			},
			dataType: "JSON",
			beforeSend: function() { 
				$('div.block-div').block({
					 message: '<h4><img src="'+base_url+'assets/plugins/images/busy.gif" /> Just a moment...</h4>',
					 css: {
						 border: '1px solid #fff'
					 }
				 });
			},
            success: function(data)
            {
                if(data.status){
                    if(confirm("The Page Will Reresh To Make A Change.")){
                        location.replace(site_url+'previllage');
                    }
                }
            }
        });
	});
	
});

function generate_tree(id){
    $("#tree").jstree();
    $("#tree").jstree(true).destroy(true);
    $("#tree").empty();
      $.ajax({
            url : site_url+"previllage/get_all_menu/"+id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {  
                var g = formTree(data, function (x){
                    $("#tree").html(x);
                });
                var j = modifyTree(function (jt){
                    jt.jstree(true).open_all();
                    $('li[data-checkstate="checked"]').each(function() {
                      jt.jstree('check_node', $(this));
                    });
                     $('#tree').on("changed.jstree", function (e, data) {
						selectedNode = data.selected; 
						console.log(selectedNode);
                      });
                });
   
            }
        });

}

function formTree(data, result){
    
    var prev = 0;
    var curr = 0;
    var next = 0;
	var treeview = "";
	var size = data.length;
    treeview += "<ul>";
    for(let i = 0; i < size; i++){
		curr = data[i];
		//console.log(curr);
		if(i==0){
			prev = null;
			next = data[i+1];
		}else if(i == size - 1){
			prev = data[i-1];
			next = null;
		}else{
			prev = data[i-1];
			next = data[i+1];
		}
		var pm = "";
        if(curr.view === '1'){
            pm = "data-checkstate='checked'";
		}
		
		if(curr.parent_id == 0 && curr.link != "#"){
			treeview += '<li id="'+curr.id+'" '+pm+'>'+curr.menu+'</li>';
		}else if(curr.parent_id == 0 && curr.link == "#"){
			treeview += '<li id="'+curr.id+'" '+pm+'>'+curr.menu;
		}else if(curr.parent_id != 0){
			if(prev == null || curr.parent_id != prev.parent_id){
				treeview += '<ul><li id="'+curr.id+'" '+pm+'>'+curr.menu+'</li>';
			}else if(next == null || curr.parent_id != next.parent_id){
				treeview += '<li id="'+curr.id+'" '+pm+'>'+curr.menu+'</li></ul></li>';
			}else{
				treeview += '<li id="'+curr.id+'" '+pm+'>'+curr.menu+'</li>';
			}
		}

	}
	treeview += "</ul>";
	//console.log(treeview);
    result(treeview);
}

function modifyTree(treeVar){
    var jt = $("#tree").jstree({
       "types" : {
           "default" : {
             "icon" : "fa fa-folder-open"
           },
           "demo" : {
               "icon" : "glyphicon glyphicon-ok"
             }
       },
       "checkbox": {
		   "keep_selected_style": false,
		   "three_state" : false
       },
       "plugins": ["checkbox","types"]
   });
   treeVar(jt);
}
