var table;
var action;
var vat = 1;
var method;


$(document).ready(function() {

	$('#delivery_date').datepicker({
		format: 'yyyy-mm-dd',
		startDate: '0d',
		endDate: '+1Y',
	});

	$('[name="vat"]').on('ifClicked', function (event) {
        console.log(this);
        vat = this.value;
        generateID(this.value);
        $("[name='vendor_name']").val();
	});
	
	$("[name='asd']").val(-1);

	$( "#vendor_name" ).autocomplete({
		maxShowItems: 5,
		source: function(request,response){
			$.ajax({
				url: site_url+"vendors/populate_autocomplete2",
				type:"GET",
				data:{term:request.term, vat:vat},
				success:response,
				dataType:"json"
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			$('[name="vendors_id"]').val(ui.item.id);
			generate_vendor_material();
		}
	});

	function generateID(vat){
		$.ajax({
			url : site_url+"purchasing/generate_id",
			type: "GET",
			data: {vat:vat},
			dataType: "JSON",
			success: function(data)
			{
				$("#code").val(data.id);
			}
		});	
	}


	$( "#material_name" ).autocomplete({
		maxShowItems: 5,
		source: function(request,response){
			$.ajax({
				url: site_url+"vendors/populate_autocomplete_material",
				type:"GET",
				data:{term:request.term, vendors_id:$('[name="vendors_id"]').val()},
				success:response,
				dataType:"json"
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			$('[name="materials_id"]').val(ui.item.id);
			generate_vendor_material();
		}
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
			url: site_url+'purchasing/view_data',
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
			{ visible: false, targets: [0] }, 
		]
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
			get_item_detail(args.item);
        },
        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "purchasing/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "purchasing/jsgrid_functions",
        			data: item,
        			success: function(data)
        			{
        				if(data.status){
        				}else{
        					alert('Fail');
        				}
        			}
        		});
        	}
        },

        fields: [ 
        { name: "id", visible:false }, 
        { name: "materials_id", visible:false }, 
        { name: "name", title:"Item Name", type: "text" }, 
        { name: "qty", title:"Qty", type: "number" }, 
		{ name: "uom", title:"Unit", type: "text", readOnly: true }, 
        { name: "price", title:"Price", type: "number" }, 
		{ name: "discount", title:"Discount", type: "number" }, 
		{ name: "sub_total", title:"Sub Total", type: "number" }, 	
		{ name: "note", title:"Note", type: "text" }, 			
		{ 
			type: "control",
			modeSwitchButton: false,
			editButton: false 
		} 
        ] 
    });

	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		method = "";
		generateID(1);
		$('[name="asd"]').val("");
		$('#form-title').text('Add Form');
		$("#form").validator();
		$('#jsGrid').jsGrid('loadData');
		$('[name="vendor_name"]').attr('disabled', false);
		$("#vat").attr('disabled', false);
		$("#nonvat").attr('disabled', false);
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

	var save_item = function(){
		var data = $("#form2").serializeArray();
		data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});
		data.push({name: 'currency_id',value: $("[name='currency']").val()});
		data.push({name: 'purchasing_id',value: $('[name="asd"]').val()});
		var url = site_url+"purchasing/edit_item";
		if(method != "Edit"){
			url = site_url+"purchasing/add_item";
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

	 var get_item_detail = function(data){
		$('[name="details_id"]').val(normalize_number_format(data.id));
		$('[name="materials_id"]').val(normalize_number_format(data.materials_id));
		$('[name="material_name"]').val(normalize_number_format(data.name));
		$('[name="qty"]').val(normalize_number_format(data.qty));
		$('[name="price"]').val(normalize_number_format(data.price));
		$('[name="discount"]').val(normalize_number_format(data.discount));
		$('[name="note2"]').val(normalize_number_format(data.note));
	 }

	 var clear_highlight = function(selected){
		if ( selected ) { selected.children('.jsgrid-cell').css('background-color', ''); }
	 }

	 $("#saveBtn2").click(function (e) {
		var validator = $("#form2").data("bs.validator");
		validator.validate();
		if (!validator.hasErrors()){
		   save_item();
		}
	});

	$('#cancelBtn2').click(function(){
		method = "";
		$('#form2')[0].reset();
	});

});

function generate_vendor_material(){
	$( "#material_name" ).autocomplete({
		maxShowItems: 5,
		source: function(request,response){
			$.ajax({
				url: site_url+"vendors/populate_autocomplete_material",
				type:"GET",
				data:{term:request.term, vendors_id:$("[name='vendors_id']").val()},
				success:response,
				dataType:"json"
			});
		},
		minLength: 1,
		select: function( event, ui ) {
		  $('[name="materials_id"]').val(ui.item.id);
		}
	});
}

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
		url = site_url+"purchasing/add";
	}else{
		url = site_url+"purchasing/update";
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
		url : site_url+"purchasing/get_by_id/"+id,
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			$('[name="code"]').val(data.code);
			check_vat(data.vat);
			var temp = data.delivery_date.split(" ");
			$('[name="delivery_date"]').val(temp[0]);
			$('[name="delivery_place"]').val(data.delivery_place);
			$('[name="note"]').val(data.note);
			$('[name="vendor_name"]').val(data.name);
			$('[name="vendors_id"]').val(data.vendors_id);
			$('[name="currency"]').val(data.currency_id);
			$('[name="vendor_name"]').attr('disabled', true);
			$("#vat").attr('disabled', true);
			$("#nonvat").attr('disabled', true);
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
			url : site_url+"purchasing/delete/"+id,
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

function check_vat(value){
	if(value == 1){
		$('#vat').iCheck('check');
	}else{
		$('#nonvat').iCheck('check');
	}
}

function normalize_number_format(number){
	return number.split(",")[0].replace(".", "");
}

function prints(id){
	window.open(site_url+"invoice/print_purchasing/"+id);
}
