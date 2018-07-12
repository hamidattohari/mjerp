var table;
var action;

$(document).ready(function() {

	$('#purchase_return_date').datepicker({
		format: 'yyyy-mm-dd' 
	});


	var columns = [];
    var right_align = [];
    var center_align = [];
    var left_align = [];
    $("#datatable").find('th').each(function(i, th){
        var field = $(th).attr('data-field');
        var align = $(th).attr('data-align');
        columns.push({data: field, name: field});
        if(align == "right"){
			right_align.push(i);
		}else if(align == "left"){
			left_align.push(i);
		}else{
			center_align.push(i);
		}	
    });

	table = $('#datatable').DataTable({
		dom: 'lrftip',
		processing: true,
		serverSide: true,
		responsive: true,
		pageLength: 10,
		deferRender: true,
		lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
		ajax: {
			url: site_url+'purchase_return/view_data',
			type: "POST",
			dataSrc : 'data',
			data: function ( d ) {
				d.csrf_token = $("[name='csrf_token']").val();
			}
		},
		columns: columns,
		columnDefs: [ 
			{ className: "dt-body-right", targets: right_align },
			{ className: "dt-body-center", targets: center_align },
			{ className: "dt-body-left", targets: left_align }, 
			{ "visible": false, targets : [0]  } 
		]
	});
	
	$('#form-panel').hide();

	function generateID(){
		$.ajax({
			url : site_url+"purchase_return/generate_id",
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$("#code").val(data.id);
			}
		});	
	}

	$('#add-btn').click(function(){
		action = "Add";
		generateID();
		$('#form-title').text('Add Form');
		$("#form").validator();
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
		$("#saveBtn").text("Save");
		$("#saveBtn").prop('disabled', false);
	});

	$("#saveBtn").click(function (e) {
		var validator = $("#form").data("bs.validator");
		validator.validate();
		if (!validator.hasErrors()){
			save_data();
		}
	});

});

function show_hide_form(bShow){
	if(bShow==true){
		$('#form-panel').show();
		$('#table-panel').hide();
	}else{
		$('#form-panel').hide();
		$('#table-panel').show();
	}
}

function form_datagrid(id){
	var products;
    $.ajax({
    	url: site_url+'receiving/populate_receiving_details/'+$('[name="receiving_id"]').val(),
    	type: "GET",
    	async: false,
    	success : function(text)
    	{
    		products = JSON.parse(text);
    	}
    });

    $("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	inserting: true, 
    	editing: true, 
    	sorting: true, 
    	paging: true, 

        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "purchase_return/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	insertItem: function(item) {
        		item["csrf_token"] = $("[name='csrf_token']").val();
        		console.log(item)
        		return $.ajax({
        			type: "POST",
        			url: "purchase_return/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
        			dataType:"JSON"
        		});
        	},
        	updateItem: function(item) {
        		return $.ajax({
        			type: "PUT",
        			url: "purchase_return/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
                    dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "purchase_return/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [  
        { name: "id", visible:false }, 
		{ name: "materials_id", title:"Material", type: "select", items: products, valueField: "Id", textField: "Name", width: 150, validate: "required" }, 
        { name: "qty", title:"Qty", type: "number", width: 50 }, 
        { name: "note", title:"Note", type: "text", width: 50 }, 
        { type: "control" } 
        ] 
	}); 
}

function reload_table(){
	table.ajax.reload(null,false); //reload datatable ajax 
} 

function save_data(){
	var url;
	if(action == "Add"){
		url = site_url+"purchase_return/add";
	}else{
		url = site_url+"purchase_return/update";
	}

	var data = $("#form").serializeArray();
	data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});

	$.ajax({
		url : url,
		type: "POST",
		data: $.param(data),
		dataType: "JSON",
		beforeSend: function() { 
			$("#saveBtn").text("Saving...");
			   $("#saveBtn").prop('disabled', true); // disable button
			   $('div.block-div').block({
			   	message: '<h4><img src="'+base_url+'assets/plugins/images/busy.gif" /> Just a moment...</h4>',
			   	css: {
			   		border: '1px solid #fff'
			   	}
			   });
			},
		success: function(data)
		{
			if(data.id){
				$('[name="asd"]').val(data.id)
				form_datagrid(data.id);
				reload_table();
				$("#saveBtn").text("Saved");
				$("#saveBtn").prop('disabled', true);
				$('div.block-div').unblock();
				$('[name="asd"]').val(data.id);
				$('#jsGrid').jsGrid('loadData');
				show_hide_form(true);
				// $('#form')[0].reset();
			}else{
				alert('Fail');
			}
		}
	});
}

function edit(id){
	action = "Edit";
	$('[name="change_id"]').val(id);
	$.ajax({
		url : site_url+"purchase_return/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			$('#code').val(data.code);
			$('#purchase_return_date').val(data.date);
			$('#receiving_id').val(data.receiving_id);
			$("#form").validator();
			$('#form-title').text('Edit Form');
			$('[name="asd"]').val(id);
			form_datagrid(id);
			$('#jsGrid').jsGrid('loadData');
			show_hide_form(true);
		}
	});
}

function remove(id){
	swal({
		title: "Are you sure?",
		text: "Your will not be able to recover this data!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Yes, delete it!",
		closeOnConfirm: false,
		showLoaderOnConfirm: true
	},
	function(){
		$.ajax({
			url : site_url+"purchase_return/delete/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				reload_table();
				if (data.status == false) {	
					swal("Cannot delete data!", "Your has been used in another transaction.", "error");
				} else {
					swal("Deleted!", "Your data has been deleted.", "success");
				}
			}
		});	
	});
}
