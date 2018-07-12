var table;
var table2;
var action;
var action2;
var vat = 1;

$(document).ready(function() {

	action2 = "Add";

	$('[name="vat"]').on('ifClicked', function (event) {
        vat = this.value;
        generateID(this.value);
    });

	$("[name='asd']").val(-1);

    $( "#product_name" ).autocomplete({
	  maxShowItems: 5,
      source: site_url+"products/populate_autocomplete",
      minLength: 1,
      select: function( event, ui ) {
		$('#products_id').val(ui.item.id);
      }
	});
	
	$( "#customer_name" ).autocomplete({
		maxShowItems: 5,
		source: function(request,response){
			$.ajax({
				url: site_url+"customers/populate_autocomplete",
				type:"GET",
				data:{term:request.term, vat:vat},
				success:response,
				dataType:"json"
			});
		},
		minLength: 1,
		select: function( event, ui ) {
		  $('[name="customers_id"]').val(ui.item.id);
		}
	});

	function generateID(vat){
		$.ajax({
			url : site_url+"projects/generate_id",
			type: "GET",
			data:{vat:vat},
			dataType: "JSON",
			success: function(data)
			{
				$("#code").val(data.id);
			}
		});	
	}

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

	var columns2 = [];
	var right_align2 = [];
    var center_align2 = [];
    var left_align2 = [];
	$("#datatable2").find('th').each(function(i, th){
		var field = $(th).attr('data-field');
		var align = $(th).attr('data-align');
		columns2.push({data: field, name: field});
		if(align == "right"){
			right_align2.push(i);
		}else if(align == "left"){
			left_align2.push(i);
		}else{
			center_align2.push(i);
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
			{ "orderable": false, targets : [0, -1]  } 
		]
	});

	table2 = $('#datatable2').DataTable({
		dom: 'rtip',
		processing: true,
		serverSide: true,
		responsive: true,
		ordering: false,
		pagging:false,
		deferRender: true,
		ajax: {
			url: site_url+'project_details/view_data',
			type: "POST",
			dataSrc : 'data',
			data: function ( d ) {
				d.projects_id = $("[name='asd']").val();
				d.csrf_token = $("[name='csrf_token']").val();
			}
		},
		columns: columns2,
		columnDefs: [ 
			{ className: "dt-body-right", targets: right_align2 },
			{ className: "dt-body-center", targets: center_align2 },
			{ className: "dt-body-left", targets: left_align2 },
			{ "visible": false, targets : [0]  } 
		]
	});
	
	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$("[name='asd']").val(-1);
		$('#form2')[0].reset();
		table2.ajax.reload();
		generateID($("[name='vat']").val());
		$('#form-title').text('Add Form');
		$("#form").validator();
		$("#customer_name").attr('disabled', false);
		$("#vat").attr('disabled', false);
		$("#nonvat").attr('disabled', false);
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		$("[name='asd']").val(-1);
		table2.ajax.reload();
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
			save_data2();
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
		url = site_url+"projects/add";
	}else{
		url = site_url+"projects/update";
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
				table2.ajax.reload();
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
		url : site_url+"projects/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			$('#code').val(data.code);
			$('#name').val(data.name);
			check_vat(data.vat);
			vat = data.vat;
			$('#description').val(data.description);
			$('#customers_id').val(data.customers_id);
			$('#customer_name').val(data.customer_name);
			$('#po_cust').val(data.po_cust);
			$("#customer_name").attr('disabled', true);
			$("#vat").attr('readonly', true);
			$("#nonvat").attr('readonly', true);
			$("#form").validator();
			$('#form-title').text('Edit Form');
			$('[name="asd"]').val(id);
			table2.ajax.reload();
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
			url : site_url+"projects/delete/"+id,
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


function save_data2(){
	var url;
	if(action2 == "Add"){
		url = site_url+"project_details/add";
	}else{
		url = site_url+"project_details/update";
	}

	var data = $("#form2").serializeArray();
	data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});
	data.push({name: 'asd',value: $("[name='asd']").val()});

	$.ajax({
		url : url,
		type: "POST",
		data: $.param(data),
		dataType: "JSON",
		beforeSend: function() { 
			$("#saveBtn2").text("Saving...");
			   $("#saveBtn2").prop('disabled', true); // disable button
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
				$("#saveBtn2").text("Saved");
				$("#saveBtn2").prop('disabled', false);
				$('div.block-div').unblock();
				action2 = "Add";
				table2.ajax.reload();
				$('#form2')[0].reset();
			}else{
				alert('Fail');
				$('div.block-div').unblock();
			}
		}
	});
}

function edit2(id){
	action2 = "Edit";
	$('[name="details_id"]').val(id);
	$.ajax({
		url : site_url+"project_details/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			$('[name="products_id"]').val(data.products_id);
			$('[name="product_name"]').val(data.code+" - "+data.name);
			$('[name="qty"]').val(data.qty);
			$('[name="price"]').val(data.price);
			$('[name="note"]').val(data.note);
			$("#form2").validator();
		}
	});
}

function remove2(id){
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
			url : site_url+"project_details/delete/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				table2.ajax.reload();
				swal("Deleted!", "Your data has been deleted.", "success");
			}
		});	
	});
}

function prints(id){
	window.open(site_url+"invoice/print_sales_order/"+id);
}

function check_vat(value){
	if(value == 1){
		$('#vat').iCheck('check');
	}else{
		$('#nonvat').iCheck('check');
	}
}
