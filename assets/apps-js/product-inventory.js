var table;
var action;

$(document).ready(function() {

	var start = moment().startOf('month');
    var end   = moment().endOf('month');

    function cb(start, end) {
		$('[name="range"]').val(start.format('YYYY-MM-DD') + ';' + end.format('YYYY-MM-DD'))
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

	cb(start, end);

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
        dom: 'Blrftip',
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'product_inventory/view_data',
            type: "POST",
            dataSrc : 'data',
            data: function ( d ) {
               	var date = $('[name="range"]').val().split(";");
				d.csrf_token = $("[name='csrf_token']").val();
				d.start_date = date[0];
				d.end_date = date[1];
            }
        },
        columns: columns,
        columnDefs: [ 
			{ className: "dt-body-right", targets: right_align },
			{ className: "dt-body-center", targets: center_align },
			{ className: "dt-body-left", targets: left_align },
			{ "orderable": false, targets : [0,3,4,5,6,7,-1]  },
			{ "visible": false, targets : [0]  } 
        ],
		buttons: [
            {
                text: '<i class="fa fa-md fa-print" data-toggle="tooltip" title="Print"></i>',
                extend: 'print',
                title: 'print',
                extension: '.print'
            },
            {
                text: '<i class="fa fa-md fa-file-excel-o" data-toggle="tooltip" title="Export as Excel"></i>',
                extend: 'excel',
                title: 'excel',
                extension: '.xls'
            }
        ]
	});

	$('#searchBtn').click(function (e){
        table.ajax.reload();
    });
	
	$('#form-panel').hide();
	$('#adjustment-panel').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$('#form-title').text('Add Form');
		$("#form").validator();
		show_hide_form1(true);
	});

	$('#cancelBtn').click(function(){
		$("#form").validator('destroy');
		show_hide_form(false);
		$('#form')[0].reset();
	});

	$('#cancelBtn1').click(function(){
		$("#form").validator('destroy');
		show_hide_form1(false);
		$('#form')[0].reset();
	});

    $("#saveBtn").click(function (e) {
         var validator = $("#form").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            save_data();
         }
	 });
	
});

function form_jsgrid(id){
    $("#jsGrid").jsGrid({ 
    	width: "100%", 
    	height: "400px", 

    	// inserting: false, 
    	// editing: false, 
    	sorting: true, 
    	paging: true, 

        // data: lists, 
        controller: {
        	loadData: function(filter) {
        		return $.ajax({
        			type: "GET",
        			url: "product_inventory/jsgrid_functions/"+id,
        			data: filter,
        			dataType:"JSON"
        		});
        	}
        },

        fields: [ 
        { name: "id", visible:false }, 
        { name: "name", title:"Name", type: "text", width: 200 }, 
        { name: "date", title:"Date", type: "text", width: 200 }, 
        { name: "qty", title:"Qty", type: "text", width: 200 }, 
        { name: "type", title:"Type", type: "text", width: 200 },  
        { name: "status", title:"Status", type: "text", width: 200 },  
        { name: "code", title:"Code", type: "text", width: 200 },  
        { type: "control", deleteButton: false, editButton: false } 
        ] 
    }); 
}

function show_hide_form(bShow){
	if(bShow==true){
		$('#form-panel').show();
		$('#table-panel').hide();
		$('#adjustment-panel').hide();
	}else{
		$('#form-panel').hide();
		$('#table-panel').show();
		$('#adjustment-panel').hide();
	}
}

function show_hide_form1(bShow){
	if(bShow==true){
		$('#form-panel').hide();
		$('#table-panel').hide();
		$('#adjustment-panel').show();
	}else{
		$('#form-panel').show();
		$('#table-panel').hide();
		$('#adjustment-panel').hide();
	}
}

function reload_table(){
	table.ajax.reload(null,false); //reload datatable ajax 
} 

function save_data(){
	var url;
	if(action == "Add"){
		url = site_url+"product_inventory/add";
	}else{
		url = site_url+"product_inventory/update";
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
	$.ajax({
			url : site_url+"product_inventory/get_product_inventory/"+id,
			type: "GET",
			dataType: "JSON",
			success: function(data)
			{
				form_jsgrid(id);
				$('#jsGrid').jsGrid('loadData');
				$('#products').val(data.name);
				$('#products_id').val(data.id);
				$('#initial_qty').val(data.initial_qty);
				$("#form").validator();
				$('#form-title').text('Edit Form');
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
				url : site_url+"product_inventory/delete/"+id,
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
