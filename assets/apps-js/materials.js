var table;
var action;
var method;

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
            url: site_url+'materials/view_data',
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

	var selectedRow;
	$("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	inserting: false, 
    	editing: false, 
    	sorting: true, 
    	paging: true, 

		rowClick: function(args) {
			method = "Edit";
			clear_highlight(selectedRow);
			var $row = this.rowByItem(args.item);
			$row.children('.jsgrid-cell').css('background-color','#F7B64B');
			selectedRow = $row;
			get_material_vendor(args.item);
        },
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "materials/jsgrid_functions/"+$('[name="change_id"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "materials/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
        { name: "id", visible: false }, 
        { name: "vendors_id", type: "text", visible:false }, 
        { name: "name", title:"Vendor", type: "text", width: 150 }, 
        { name: "address", title:"Address", type: "text", width: 150 }, 
		{ name: "telp", title:"Telp", type: "text", width: 50 },
		{ 
			type: "control",
			modeSwitchButton: false,
			editButton: false 
		} 
        ] 
	}); 
	
	$( "#vendor_name" ).autocomplete({
		maxShowItems: 5,
		source: site_url+"vendors/populate_autocomplete",
		minLength: 1,
		select: function( event, ui ) {
		  $('[name="vendors_id"]').val(ui.item.id);
		}
	});

	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		method = "";
		clear_highlight(selectedRow);
		$('#form-title').text('Add Form');
		$("#form").validator();
		$("[name='change_id']").val("");
		$('#jsGrid').jsGrid('loadData');
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		clear_highlight(selectedRow);
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
	});

    $("#saveBtn").click(function (e) {
         var validator = $("#form").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            save_data();
         }
	 });

	 var save_material_vendor = function(){
		var data = $("#form2").serializeArray();
		data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});
		data.push({name: 'materials_id',value: $("[name='change_id']").val()});
		var url = site_url+"materials/edit_material_vendor";
		if(method != "Edit"){
			url = site_url+"materials/add_material_vendor";
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

	 var get_material_vendor = function(data){
		$('[name="vendor_name"]').val(data.name);
		$('[name="vendors_id"]').val(data.vendors_id);
		$('[name="details_id"]').val(data.id);
	 }

	 var clear_highlight = function(selected){
		if ( selected ) { selected.children('.jsgrid-cell').css('background-color', ''); }
	 }

	 $("#saveBtn2").click(function (e) {
		var validator = $("#form2").data("bs.validator");
		validator.validate();
		if (!validator.hasErrors()){
		   save_material_vendor();
		}
	});

	$('#cancelBtn2').click(function(){
		method = "";
		clear_highlight(selectedRow);
		$('#form2')[0].reset();
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
		url = site_url+"materials/add";
	}else{
		url = site_url+"materials/update";
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
			   if(data.status){
				   reload_table();
				   $("#saveBtn").text("Save");
				   $("#saveBtn").prop('disabled', false);
				   $('div.block-div').unblock();
				   $('[name="change_id"]').val(data.status);
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
			url : site_url+"materials/get_by_id/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$('#name').val(data.name);
				$('#material_categories_id').val(data.material_categories_id);				
				$('#uom_id').val(data.uom_id);				
				$('#initial_qty').val(data.initial_qty);				
				$('#min_stock').val(data.min_stock);				
				$("#form").validator();
				$('#form-title').text('Edit Form');
				$('#jsGrid').jsGrid('loadData');
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
				url : site_url+"materials/delete/"+id,
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
