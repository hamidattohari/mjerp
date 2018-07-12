var table;
var table1;
var id_projects = -1;
var action;

$(document).ready(function() {

	$('#shipping_date').datepicker({
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

    var columns1 = [];
    var right_align1 = [];
    var center_align1 = [];
    var left_align1 = [];
    $("#datatable1").find('th').each(function(i, th){
        var field = $(th).attr('data-field');
        var align = $(th).attr('data-align');
        columns1.push({data: field, name: field});
        if(align == "right"){
			right_align1.push(i);
		}else if(align == "left"){
			left_align1.push(i);
		}else{
			center_align1.push(i);
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
			url: site_url+'projects/view_data',
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
			{ visible: false, targets: [0,-1] } 
		]
	});

	table1 = $('#datatable1').DataTable({
        dom: 'lrftip',
        responsive: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'shipping/view_data/'+id_projects,
            type: "POST",
            dataSrc : 'data',
            data: function ( d ) {
                d.csrf_token = $("[name='csrf_token']").val();
            }
        },
        columns: columns1,
        columnDefs: [ 
			{ className: "dt-body-right", targets: right_align1 },
			{ className: "dt-body-center", targets: center_align1 },
			{ className: "dt-body-left", targets: left_align1 },
            { "orderable": false, targets : [-1]  },
			{ "visible": false, targets : [0]  } 
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
        id_projects = id;
        table1.ajax.url(site_url+'shipping/view_data/'+id_projects).load();
        $('#add-btn').show();
        $('#nopo').text(table.row( this ).data().code);
    } );
	
	$('#form-panel').hide();
	$('#add-btn').hide();

	function generateID(){
		$.ajax({
			url : site_url+"shipping/generate_id",
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
		$("[name='asd']").val(-1);
		form_datagrid(-1);
		$('#jsGrid').jsGrid('loadData');
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
		$("#saveBtn").text("Save");
		$("#saveBtn").prop('disabled', false);
		table1.ajax.url(site_url+'shipping/view_data/'+id_projects).load();
	});

	$("#saveBtn").click(function (e) {
		var validator = $("#form").data("bs.validator");
		validator.validate();
		if (!validator.hasErrors()){
			save_data();
		}
	});

    // JsGrid
    // var lists = [ 
    //     { "Item Name": 1, "Qty": 25, "Amount": 10000}, 
    //     { "Item Name": 2, "Qty": 45, "Amount": 200000}, 
    //     { "Item Name": 2, "Qty": 29, "Amount": 300000}, 
    //     { "Item Name": 1, "Qty": 56, "Amount": 100000}, 
    //     { "Item Name": 1, "Qty": 32, "Amount": 300000} 
    // ]; 

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
    	url: site_url+'projects/populate_product_select/'+id_projects,
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
        			url: "shipping/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	insertItem: function(item) {
        		item["csrf_token"] = $("[name='csrf_token']").val();
        		console.log(item)
        		return $.ajax({
        			type: "POST",
        			url: "shipping/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
        			dataType:"JSON"
        		});
        	},
        	updateItem: function(item) {
        		return $.ajax({
        			type: "PUT",
        			url: "shipping/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
        			dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "shipping/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
		{ name: "id", title:"ID", visible: false}, 
		{ name: "products_id", title:"Product", type: "select", items: products, valueField: "Id", textField: "Name", width: 150, validate: "required" }, 
        { name: "qty", title:"Qty", type: "number", width: 50 }, 
        { name: "unit_price", title:"Unit Price", type: "number", width: 50 }, 
        { name: "total_price", title:"Total Price", type: "number", width: 50, readOnly: true }, 
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
		url = site_url+"shipping/add/"+id_projects;
	}else{
		url = site_url+"shipping/update";
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
				$('[name="asd"]').val(data.id);
				form_datagrid(data.id);
				reload_table();
				$("#saveBtn").text("Saved");
				$("#saveBtn").prop('disabled', true);
				$('div.block-div').unblock();
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
		url : site_url+"shipping/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			form_datagrid(id);
			$('#code').val(data.code);
			$('#shipping_date').val(data.shipping_date);
			$('#note').val(data.note);
			$('#projects_id').val(data.projects_id);
			$("#form").validator();
			$('#form-title').text('Edit Form');
			$('[name="asd"]').val(id);
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
			url : site_url+"shipping/delete/"+id,
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
