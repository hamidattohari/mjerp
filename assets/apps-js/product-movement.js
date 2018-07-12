var table;
var table1;
var id_wo = -1;
var action;

$(document).ready(function() {

	$('#receive_date').datepicker({
		format: 'yyyy-mm-dd' 
	});

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
			{ "orderable": false, targets : [-1]  },
			{ "visible": false, targets : [0, -1]  } 
        ]
	});

	table1 = $('#datatable1').DataTable({
        dom: 'lrftip',
        responsive: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'product_movement/view_data/'+id_wo,
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
        id_wo = id;
        table1.ajax.url(site_url+'product_movement/view_data/'+id_wo).load();
        $('#add-btn').show();
        $('#nopo').text(table.row( this ).data().code);
    } );
	
	$('#form-panel').hide();

	$('#add-btn').click(function(){
		action = "generate";
		$('#form-title').text('Add Form');
		$("#form").validator();
		show_hide_form(true);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
	});

    $("#generateBtn").click(function (e) {
         var validator = $("#form").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            generate();
         }
     });

    $("#printBtn").click(function (e) {
         var validator = $("#form").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            window.location = site_url+"invoice/print_production/"+$('[name="receive_date"]').val()+"/"+$('[name="processes_id"]').val();
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
        			url: "product_movement/jsgrid_functions/"+$('[name="receive_date"]').val()+"/"+$('[name="processes_id"]').val(),
        			data: filter,
        			dataType:"JSON"
        		});
        	}
        },

        fields: [ 
        { name: "id", visible:false }, 
        { name: "product", title:"Product", type: "text", width: 100 }, 
        { name: "qty", title:"Qty", type: "text", width: 50 }, 
        { name: "uom", title:"Uom", type: "text", width: 50 }, 
        { type: "control", editButton: false, deleteButton: false } 
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

function generate() {
    url = site_url+"product_movement/jsgrid_functions/"+$('[name="receive_date"]').val()+"/"+$('[name="processes_id"]').val();

    var data = $("#form").serializeArray();
    data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});

    $.ajax({
        url : url,
        type: "GET",
        data: $.param(data),
        dataType: "JSON",
        beforeSend: function() { 
            $('div.block-div').block({
                message: '<h4><img src="'+base_url+'assets/plugins/images/busy.gif" /> Just a moment...</h4>',
                css: {
                    border: '1px solid #fff'
                }
            });
        },
        success: function(data){
            reload_table();
            $('#jsGrid').jsGrid('loadData');
            $('div.block-div').unblock();
            show_hide_form(true);
            // $('#form')[0].reset();
        }
    });
}

function save_data(){
	var url;
	if(action == "Add"){
		url = site_url+"product_movement/add";
	}else{
		url = site_url+"product_movement/update";
	}
   
	var data = $("#form").serializeArray();
	data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});

	$.ajax({
		   url : url,
		   type: "POST",
		   data: $.param(data),
		   dataType: "JSON",
		   beforeSend: function() { 
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
                    $("#printBtn").text("Print");
                    $("#printBtn").prop('disabled', false);
                    $('div.block-div').unblock();
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
			url : site_url+"product_movement/get_by_id/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				$('#receive_date').val(data.receive_date);
				$('#processes_id').val(data.processes_id);
				$('#processes_id1').val(data.processes_id1);				
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
				url : site_url+"product_movement/delete/"+id,
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
