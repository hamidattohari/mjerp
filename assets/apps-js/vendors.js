var table;
var action;

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
            url: site_url+'vendors/view_data',
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
        ],
        "order": [[1, "asc"]]
	});
	
	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$('#form-title').text('Add Form');
		$("#form").validator();
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
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

	var category;
    $.ajax({
    	url: site_url+'material_categories/get_material_categories',
    	type: "GET",
    	async: false,
    	success : function(text)
    	{
    		category = JSON.parse(text);
    	}
    });

    var uom;
    $.ajax({
    	url: site_url+'uom/populate_select',
    	type: "GET",
    	async: false,
    	success : function(text)
    	{
    		uom = JSON.parse(text);
    	}
    });

    $("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	inserting: false, 
    	editing: false, 
    	sorting: true, 
    	paging: true, 

        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "vendors/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	}
        },

        fields: [ 
        { name: "id", title:"ID", visible:false }, 
        { name: "name", title:"Material Name", type: "text", width: 150, validate: "required" }, 
        { name: "category", title:"Category", type: "text", width: 150, validate: "required" }, 
		{ name: "min_stock", title:"Min Stock", type: "number", validate: "required" }, 
		{ name: "uom", title:"Uom", type: "text", width: 50, validate: "required" }
        ] 
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
		url = site_url+"vendors/add";
	}else{
		url = site_url+"vendors/update";
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
				   show_hide_form(false);
				   $('#form')[0].reset();
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
			url : site_url+"vendors/get_by_id/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				check_vat(data.ppn);
				$('#name').val(data.name);
				$('#description').val(data.description);
				$('#address').val(data.address);
				$('#telp').val(data.telp);
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
				url : site_url+"vendors/delete/"+id,
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

function check_vat(value){
	if(value == 1){
		$('#vat').iCheck('check');
	}else{
		$('#nonvat').iCheck('check');
	}
}

