var table;
var action;
var methode;

$(document).ready(function() {

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
			url: site_url+'products/view_data',
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
		]
	});
	
	$( "#code" ).autocomplete({
		maxShowItems: 5,
		source: function(request,response){
			$.ajax({
				url: site_url+"products/populate_autocomplete_code",
				type:"GET",
				data:{term:request.term, category_id:$("#product_categories_id").val()},
				success: response,
				dataType:"json"
			});
		},
		minLength: 1
	});

	$( "#material_name" ).autocomplete({
		maxShowItems: 5,
		source: site_url+"materials/populate_autocomplete",
		minLength: 1,
		select: function( event, ui ) {
		  $('[name="materials_id"]').val(ui.item.id);
		}
	});

	var processes;
    $.ajax({
    	url: site_url+'processes/populate_select',
    	type: "GET",
    	async: false,
    	success : function(text)
    	{
    		processes = JSON.parse(text);
    	}
    });

    $("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	inserting: false, 
    	editing: false, 
    	sorting: true, 
    	paging: true, 
		rowClick: function(args) {
			method = "Edit";
			get_product_material(args.item);
        },
        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "products/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "products/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
        { name: "id",  visible:false }, 
        { name: "materials_id", visible:false }, 
        { name: "name", title:"Material", type: "text", width: 150, validate: "required" }, 
        { name: "qty", title:"Qty", type: "number", width: 50 }, 
        { name: "unit", title:"Unit", type: "text", width: 50 }, 
        { 
			type: "control",
			modeSwitchButton: false,
			editButton: false 
		} 
        ] 
	}); 
	
	
	$("#jsGrid2").jsGrid({ 
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
        			url: "products/jsgrid_functions2/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	insertItem: function(item) {
        		item["csrf_token"] = $("[name='csrf_token']").val();
        		return $.ajax({
        			type: "POST",
        			url: "products/jsgrid_functions2/"+$('[name="asd"]').val(),
					data: item,
					dataType:"JSON"
        		});
        	},
        	updateItem: function(item) {
        		return $.ajax({
        			type: "PUT",
        			url: "products/jsgrid_functions2/"+$('[name="asd"]').val(),
        			data: item
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "products/jsgrid_functions2",
        			data: item
        		});
        	}
        },

        fields: [ 
        { name: "id", visible:false}, 
        { name: "processes_id", title:"Item Name", type: "select", items: processes, valueField: "Id", textField: "Name", width: 150, validate: "required" }, 
       	{ type: "control" } 
        ] 
    }); 

	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		method = "";
		$('#form-title').text('Add Form');
		$("#form").validator();
		$('[name="asd"]').val("");
		$('#jsGrid').jsGrid('loadData');
		$('#jsGrid2').jsGrid('loadData');
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

	$("#saveBtn2").click(function (e) {
		var validator = $("#form2").data("bs.validator");
		validator.validate();
		if (!validator.hasErrors()){
			save_product_material();
		}
	});

	$('#cancelBtn2').click(function(){
		method = "";
		$('#form2')[0].reset();
	});

	var save_product_material = function(){
		var data = $("#form2").serializeArray();
		data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});
		data.push({name: 'products_id',value: $("[name='asd']").val()});
		var url = site_url+"products/edit_product_material";
		if(method != "Edit"){
			url = site_url+"products/add_product_material";
		}
		$.ajax({
			url : url,
			type: "POST",
			data: data,
			dataType: "JSON",
			success: function(data)
			{
				if(data){
					method = "";
					$('#form2')[0].reset();
					$('#jsGrid').jsGrid('loadData');
				}
			}
		}); 
	}

	var get_product_material = function(data){
		$('[name="material_name"]').val(data.name);
		$('[name="materials_id"]').val(data.materials_id);
		$('[name="qty"]').val(data.qty);
		$('[name="details_id"]').val(data.id);
	 }


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

function reload_table(){
	table.ajax.reload(null,false); //reload datatable ajax 
} 

function save_data(){
	var url;
	if(action == "Add"){
		url = site_url+"products/add";
	}else{
		url = site_url+"products/update";
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
				reload_table();
				$("#saveBtn").text("Saved");
				$("#saveBtn").prop('disabled', true);
				$('div.block-div').unblock();
				$('[name="asd"]').val(data.id);
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
		url : site_url+"products/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			var code = data.code;
			if(code==null || code.length-5 == 0){
				$('[name="type"][value="tipping"]').prop("checked", true).trigger('change');
			}else{
				$("#color").val(code.substring(0, code.length-5).toString());
				$('[name="type"][value="foil"]').prop("checked", true).trigger('change');
			}
			$('#name').val(data.name);
			$('#code').val(data.code);
			$('#initial_qty').val(data.initial_qty);
			$('#initial_half_qty').val(data.initial_half_qty);
			$('#code').val(data.code);
			$('#uom_id').val(data.uom_id);
			$('#product_categories_id').val(data.product_categories_id);
			$("#form").validator();
			$('#form-title').text('Edit Form');
			$('[name="asd"]').val(id);
			$('#jsGrid').jsGrid('loadData');
			$('#jsGrid2').jsGrid('loadData');
			show_hide_form(true);
			method = "";
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
			url : site_url+"products/delete/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				reload_table();
				swal("Deleted!", "Your data has been deleted.", "success");
			}
		});	
	});
}
