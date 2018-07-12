var table;
var action;
var method;

$(document).ready(function() {

	$( "#dialog" ).dialog({ 
		autoOpen: false,
		modal: true,
		height: 500, 
		width: 500,
		buttons: [
			{
			  text: "Save",
			  icon: "ui-icon-save",
			  click: function() {
				var data = $("#form3").serializeArray();
				data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});  
				data.push({name: 'material_usages_id',value: $("[name='asd']").val()});  
				data.push({name: 'products_id',value: $("[name='products_id']").val()});  
				$.ajax({
					type: "POST",
        			url: "return_material/add_new_material",
        			data: data,
					dataType:"JSON",
					success:function(resp){
						if(resp){
							$('#jsGrid').jsGrid('loadData');
							populate_product_materials("");
							$( "#dialog" ).dialog("close");
						}
					}
				});
			  }
			},
			{
				text: "Cancel",
				icon: "ui-icon-close",
				click: function() {
				  $( this ).dialog( "close" );
				}	
			}
		  ] 
	})

	$("#newItemBtn").click(function(){
		$( "#dialog" ).dialog("open");
	});

	$('#date').datepicker({
		format: 'yyyy-mm-dd' 
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
			editedRow = args.item;
			get_pick_detail(args.item);
        },
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: site_url+"return_material/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: site_url+"return_material/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
			{ name: "id", visible: false }, 
			{ name: "materials_id", type: "text", visible:false }, 
			{ name: "name", title:"Item Name", type: "text", width: 150 }, 
			{ name: "qty", title:"Qty", type: "number", width: 50 }, 
			{ name: "symbol", title:"Unit", type: "text" },  
			{ name: "note", title:"Note", type: "text", width: 200 },  
        ] 
	}); 
	
	$( "#work_orders_code" ).autocomplete({
		maxShowItems: 5,
		source: site_url+"work_orders/populate_autocomplete",
		minLength: 2,
		select: function( event, ui ) {
		  $('[name="work_orders_id"]').val(ui.item.id);
		  $('#jsGrid').jsGrid('loadData');
		  populate_product_select("");
		  generateID();	
		}
	});

	function generateID(){
		$.ajax({
			url : site_url+"pickup_material/generate_id",
			type: "GET",
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

	table = $('#datatable').DataTable({
        dom: 'lrftip',
        responsive: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'return_material/view_data/'+$('[name="woid"]').val()+"/"+$('[name="pid"]').val(),
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
			{ "orderable": false, targets : [-1]  } 
        ]
	});
	
	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$('#form-title').text('Add Form');
		$("#form").validator();
		$("#saveBtn").prop('disabled', false);
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
	});

	$('#cancelBtn2').click(function(){
		method = "";
		$('#form2')[0].reset();
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
			save_pick_detail()
		}
	});

	var save_pick_detail = function(){
		$('[name="materials_id"]').prop('disabled', false);
		var data = $("#form2").serializeArray();
		data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});
		data.push({name: 'material_usages_id',value: $("[name='asd']").val()});
		if(method == "Edit"){
			var url = site_url+"return_material/update_detail/"+$("#usage_categories_id").val();
			$.ajax({
				url : url,
				type: "POST",
				data: data,
				dataType: "JSON",
				success: function(data)
				{
					if(data.status){
						method = "";
						$('#form2')[0].reset();
						$('#jsGrid').jsGrid('loadData');
						reload_table();
					}
				}
			}); 
		}
		$('[name="materials_id"]').prop('disabled', true);
	 }

	 var get_pick_detail = function(data){
		$('[name="details_id"]').val(data.id);
		$('[name="materials_id"]').val(data.materials_id);
		$('[name="materials_name"]').val(data.name);
		$('[name="qty"]').val(data.qty);
		$('[name="note"]').val(data.note);
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
		url = site_url+"pickup_material/add";
	}else{
		url = site_url+"pickup_material/update";
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

function add(id){
	action = "Add";
	$('[name="change_id"]').val(id);
	$.ajax({
			url : site_url+"pickup_material/get_by_id/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				// $('#date').val(data.usage_date);				
				$("#form").validator();
				$('#form-title').text('Edit Form');
				$('[name="asd"]').val(id);
				// $('#jsGrid').jsGrid('loadData');
				show_hide_form(true);
			}
		});
}

function edit(id){
	action = "Edit";
	$('[name="change_id"]').val(id);
	$.ajax({
			url : site_url+"pickup_material/get_by_id/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				var temp = data.date.split(" ");
				$('[name="date"]').val(temp[0]);
				$('#code').val(data.code_return);			
				$('#work_orders_code').val(data.work_orders_code);			
				$('#work_orders_id').val(data.work_orders_id);			
				$('#machine_id').val(data.machine_id);			
				$('#usage_categories_id').val(data.usage_categories_id);	
				populate_product_select(data.products_id);	
				$("#form").validator();
				$('#form-title').text('Edit Form');
				$('[name="asd"]').val(id);
				$('#jsGrid').jsGrid('loadData');
				$("#saveBtn").prop('disabled', true);
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
				url : site_url+"pickup_material/delete/"+id,
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


function populate_product_select(selected){
	$.ajax({
		url : site_url+"work_orders/populate_product_select/"+$('[name="work_orders_id"]').val(),
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			var option = "<option value=''>Choose Product</option>";
			for(let i=0; i<data.length; i++){
				option += "<option value='"+data[i].id+"'>"+data[i].code+" "+data[i].name+"</option>";
			}
			$('[name="products_id"]').html(option);
			$('[name="products_id"]').val(selected);
			populate_product_materials("");
		}
	});	
}

function printEvidence(id){
	window.open(site_url+"invoice/print_return/"+id);
}

function populate_product_materials(selected){
	$.ajax({
		url : site_url+"products/get_product_materials/"+$('[name="products_id"]').val(),
		type: "GET",
		data: {
			products_id:$('[name="products_id"]').val(),
			usage_categories_id: $('[name="usage_categories_id"]').val()
		},
		dataType: "JSON",
		success: function(data)
		{
			var option = "<option value=''>Choose Material in Table</option>";
			for(let i=0; i<data.length; i++){
				option += "<option value='"+data[i].id+"'>"+data[i].code+" - "+data[i].name+"</option>";
			}
			$('[name="materials_id"]').html(option);
			$('[name="materials_id"]').val(selected);
		}
	});	
}

