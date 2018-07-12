var table;
var action;

$(document).ready(function() {

	var edit_data;	

	$("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	// inserting: true, 
    	// editing: true, 
    	sorting: true, 
    	paging: true, 
		rowClick: function(args) {
			fillForm2(args.item);
        },
        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "work_orders/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "work_order/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
		{ name: "no", title:"No", width: 10 }, 
		{ name: "id", title:"ID", visible:false }, 
		{ name: "name", title:"Product", width: 150 }, 
		{ name: "qty", title:"Qty", type: "number", width: 50 },
		{ name: "symbol", title:"Unit", type: "text", width: 50 },
		{ name: "note", title:"Note", type: "text", width: 50 },
		{ name: "action", title:"Action", type: "text", width: 50 },
        ] 
	}); 

	var fillForm2 = function(data){
		edit_data = data;
		$('[name="details_id"]').val(data.id);
		$('[name="product_name"]').val(data.name);
		$('[name="qty"]').val(data.qty);
		$('[name="note"]').val(data.note);
	}

	var saveDetail = function(data){
		var input = $("#form2").serializeArray();
		input.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});

		$.ajax({
			url : site_url+"work_orders/update_detail",
			type: "POST",
			data: input,
			dataType: "JSON",
			success: function(resp){
				if(resp.status){
					$.extend(data, {
						qty: $('[name="qty"]').val()
					});
					$('#form2')[0].reset();
					$("#jsGrid").jsGrid("updateItem", data);
				}
			}
		});
		
	}

	$( "#projects_code" ).autocomplete({
		maxShowItems: 5,
		source: site_url+"projects/populate_autocomplete",
		minLength: 2,
		select: function( event, ui ) {
		  $('[name="projects_id"]').val(ui.item.id);
		  generateID();	
		}
	  });

	$('#start_date').datepicker({
		format: 'yyyy-mm-dd' ,
		startDate: '-7d',
		endDate: '+1Y',
	});

	$('#end_date').datepicker({
		format: 'yyyy-mm-dd', 
		startDate: '-7d',
		endDate: '+1Y',
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
			url: site_url+'work_orders/view_data',
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
			{ visible: false, targets: [1]},
		]
	});
	
	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$('[name="projects_id"]').val("");
		$('[name="asd"]').val("");
		$('#jsGrid').jsGrid('loadData');
		$('#form-title').text('Add Form');
		$("#form").validator();
		$('[name="projects_code"]').attr("Disabled", false);
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
		$("#saveBtn").text("Save");
		$("#saveBtn").prop('disabled', false);
	});

	$('#cancelBtn2').click(function(){
		$("#form2").validator('destroy');
		$('#form2')[0].reset();
		$("#saveBtn2").text("Save");
		$("#saveBtn2").prop('disabled', false);
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
			saveDetail(edit_data);
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

function reload_table(){
	table.ajax.reload(null,false); //reload datatable ajax 
} 

function save_data(){
	var url;
	if(action == "Add"){
		url = site_url+"work_orders/add";
	}else{
		url = site_url+"work_orders/update";
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
				$('#jsGrid').jsGrid('loadData');
				// $('#form')[0].reset();
			}else if (data.fail){
				$('div.block-div').unblock();
				$.toast({
					heading: 'Sales order empty',
					text: 'Sales does not have any items',
					position: 'top-right',
					loaderBg:'#ff6849',
					icon: 'error',
					hideAfter: 10000, 
					stack: 6
				  });
			}else {

			}
		}
	});
}

function generateID(){
	$.ajax({
		url : site_url+"work_orders/generate_id",
		type: "GET",
		data: {projects_id: $('[name="projects_id"]').val()},
		dataType: "JSON",
		success: function(data)
		{
			$("#code").val(data.id);
		}
	});	
}

function edit(id){	
	$('[name="change_id"]').val(id);
	$('[name="asd"]').val(id);
	$.ajax({
		url : site_url+"work_orders/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			
			if (data.id) {
				$("#saveBtn").text("Saved");
				$("#saveBtn").prop('disabled', true);
				action = "Edit";
			} else {
				$("#saveBtn").text("Save");
				$("#saveBtn").prop('disabled', false);
				action = "Add";
			}
			$('[name="projects_code"]').val(data.projects_code);
			$('[name="projects_id"]').val(data.projects_id);
			$('[name="code"]').val(data.code);
			var temp = data.start_date.split(" ");
			$('[name="start_date"]').val(temp[0]);
			temp = data.end_date.split(" ");
			$('[name="end_date"]').val(temp[0]);
			$('[name="projects_code"]').attr("Disabled", true);
			$("#form").validator();
			$('#form-title').text('Edit Form');
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
			url : site_url+"work_orders/delete/"+id,
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

