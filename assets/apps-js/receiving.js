var table;
var table1;
var id_purchase = -1;
var action;

$(document).ready(function() {

	$('.dropify').dropify();

	$('#receive_date').datepicker({
		format: 'yyyy-mm-dd',
		startDate: '-7d',
        endDate: '0d',
	});

    function generateID(){
        $.ajax({
            url : site_url+"receiving/generate_id",
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
    $("#datatable").find('th').each(function(i, th){
        var field = $(th).attr('data-field');
        var align = $(th).attr('data-align');
        columns.push({data: field, name: field});
        if(align == "right")
            right_align.push(i);
    });

    var columns1 = [];
    var right_align1 = [];
    $("#datatable1").find('th').each(function(i, th){
        var field = $(th).attr('data-field');
        var align = $(th).attr('data-align');
        columns1.push({data: field, name: field});
        if(align == "right")
            right_align1.push(i);
    });

	table = $('#datatable').DataTable({
        dom: 'lrftip',
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        deferRender: true,
        select: true,
        "stripeClasses": [],
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
            { "orderable": false, targets : [-1]  } ,
            { "visible": false, targets : [0,-1]  } 
        ]
    });

    table1 = $('#datatable1').DataTable({
        dom: 'lrftip',
        responsive: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'receiving/view_data/'+id_purchase,
            type: "POST",
            dataSrc : 'data',
            data: function ( d ) {
                d.csrf_token = $("[name='csrf_token']").val();
            }
        },
        columns: columns1,
        columnDefs: [ 
			{ className: "dt-body-right", targets: right_align1 },
            { "orderable": false, targets : [-1]  } ,
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
        id_purchase = id;
        table1.ajax.url(site_url+'receiving/view_data/'+id_purchase).load();
        $('#add-btn').show();
        $('#nopo').text(table.row( this ).data().code);
    } );
	
	$('#form-panel').hide();
	$('#detail-panel').hide();
    $('#add-btn').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$('#form-title').text('Add Form');
		$("#form").validator();
        generateID();
		show_hide_form(true);
        receiving(id_purchase);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form_detail(false);
		$('#form')[0].reset();
        table1.ajax.url(site_url+'receiving/view_data/'+id_purchase).load();
	});

    $("#saveBtn").click(function (e) {
         var validator = $("#form").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            save_data();
         }
	 });

    var materials;
    $.ajax({
    	url: site_url+'purchasing/get_materials',
    	type: "GET",
    	async: false,
    	success : function(text)
    	{
    		materials = JSON.parse(text);
    	}
    });

    var isVisible = false;
    if (role_id == 1) {
    	isVisible = true;
    }

    $("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	inserting: false, 
    	editing: true, 
    	deleting: false, 
    	sorting: true, 
    	paging: true, 

        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "receiving/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	insertItem: function(item) {
        		item["csrf_token"] = $("[name='csrf_token']").val();
        		console.log(item)
        		return $.ajax({
        			type: "POST",
        			url: "receiving/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
        			dataType:"JSON"
        		});
        	},
        	updateItem: function(item) {
        		return $.ajax({
        			type: "PUT",
        			url: "receiving/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
                    dataType:"JSON",
                    success: function (item) {
                        table.ajax.reload();
                    }
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "receiving/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
        { name: "id", visible:false }, 
        { name: "name", title:"Item Name", type: "select", items: materials, valueField: "Id", textField: "Name", width: 150, validate: "required"}, 
        { name: "qty", title:"Qty", type: "number", width: 200 }, 
        { name: "price", title:"Price", type: "number", width: 200},  
        { name: "discount", title:"Discount", type: "number", width: 200},  
		{ name: "vat", title:"Vat", type: "number", width: 200}, 
        { name: "subtotal_price", title:"Subtotal", type: "number", width: 200, readOnly: true},  
		{ name: "total_price", title:"Total Price", type: "number", width: 200, readOnly: true},
        { type: "control", deleteButton: false, editButton: false } 
        ] 
    }); 

    $("#jsGrid1").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	inserting: false, 
    	editing: true, 
    	deleting: false, 
    	sorting: true, 
    	paging: true, 

        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "receiving/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	},
        	insertItem: function(item) {
        		item["csrf_token"] = $("[name='csrf_token']").val();
        		console.log(item)
        		return $.ajax({
        			type: "POST",
        			url: "receiving/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
        			dataType:"JSON"
        		});
        	},
        	updateItem: function(item) {
        		return $.ajax({
        			type: "PUT",
        			url: "receiving/jsgrid_functions/"+$('[name="asd"]').val(),
        			data: item,
                    dataType:"JSON"
        		});
        	},
        	deleteItem: function(item) {
        		return $.ajax({
        			type: "DELETE",
        			url: "receiving/jsgrid_functions",
        			data: item
        		});
        	}
        },

        fields: [ 
        { name: "id", visible:false }, 
        { name: "name", title:"Item Name", type: "select", items: materials, valueField: "Id", textField: "Name", width: 150, validate: "required", readOnly: true }, 
        { name: "qty", title:"Qty", type: "number", width: 50 }, 
        { name: "price", title:"Price", type: "number", width: 200},  
        { name: "discount", title:"Discount", type: "number", width: 200},  
        { name: "total_price", title:"Total Price", type: "number", width: 200, readOnly: true},
        { type: "control", deleteButton: false} 
        ] 
	}); 
	
	$("#uploadBtn").click(function(){
		var data = new FormData($("#form2")[0]);
		data.append('csrf_token',$("[name='csrf_token']").val());
		data.append('id_rec',$('[name="asd"]').val());
		$.ajax({
			url: site_url+"receiving/upload_doc", 
			type: "POST",             
			data: data,
			contentType: false,       
			cache: false,             
			processData:false,        
			success: function(data)   
			{	
				if(!data){
					alert("Fail!");
				}else{
					swal("File Uploaded!");
				}
			}
		});
	});

	$("#downloadBtn").click(function(){
		window.open(site_url+"receiving/download_doc/"+ $('[name="asd"]').val());
		// $.ajax({
		// 	url: site_url+"receiving/download_doc", 
		// 	type: "POST",             
		// 	data: {
		// 		'id_rec': $('[name="asd"]').val(),
		// 		'csrf_token': $("[name='csrf_token']").val()
		// 	}
		// });
	});
	
});

function show_hide_form(bShow){
	if(bShow==true){
		$('#form-panel').show();
		$('#table-panel').hide();
		$('#detail-panel').hide();
	}else{
		$('#form-panel').hide();
		$('#table-panel').show();
		$('#detail-panel').hide();
	}
}

function show_hide_form_detail(bShow){
	if(bShow==true){
		$('#table-panel').hide();
		$('#form-panel').hide();
		$('#detail-panel').show();
	}else{
		$('#detail-panel').hide();
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
		url = site_url+"receiving/add/"+$('[name="change_id"]').val();
	}else{
		url = site_url+"receiving/update/"+$('[name="asd"]').val();
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
				   $('#jsGrid').jsGrid('loadData');
				   show_hide_form(true);
			   }else{
				   alert('Fail');
			   }
		   }
	   });
   }

function receiving(id){
	action = "Add";
	$('[name="change_id"]').val(id);
	$.ajax({
			url : site_url+"purchasing/get_by_id/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
                $("#saveBtn").text("Save");
                $("#saveBtn").prop('disabled', false);
                var temp = data.delivery_date.split(" ");
                $('#delivery_date').val(temp[0]);
				$('#vendors_id').val(data.vendors_id);			
				$("#form").validator();
				$('#form-title').text('Edit Form');
				$('[name="asd"]').val(-1);
				$('#jsGrid').jsGrid('loadData');
				show_hide_form(true);
			}
		});
}

function details(id){
    action = "Edit";
    $('[name="change_id"]').val(id);
	$.ajax({
			url : site_url+"receiving/get_receiving/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
                $("#saveBtn").text("Save");
                $("#saveBtn").prop('disabled', false);
                $('#code').val(data.code);
				$('#no_sj').val(data.no_sj);
                var temp = data.delivery_date.split(" ");
                $('#delivery_date').val(temp[0]);
                var temp1 = data.receive_date.split(" ");
                $('#receive_date').val(temp1[0]);
                $('#vendors_id').val(data.vendors_id);          
                $("#form").validator();
                $('#form-title').text('Edit Form');
                $('[name="asd"]').val(data.id);
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
				url : site_url+"receiving/delete/"+id,
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
