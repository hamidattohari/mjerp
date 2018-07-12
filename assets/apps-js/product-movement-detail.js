var table;
var table1;
var woid = -1;
var pid = -1;
var prid = -1;
var action;
var method;

$(document).ready(function() {

	$('#receive_date').datepicker({
		format: 'yyyy-mm-dd' 
	});

    $('#datetime').datepicker({
        format: 'yyyy-mm-dd',
    });

    $('#time_start').clockpicker();

    $('#time_end').clockpicker();

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
        responsive: true,
        select: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'product_movement_detail/view_data/'+$('[name="pid"]').val(),
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
            { "visible": false, targets : [0]  } 
        ]
    });

    table1 = $('#datatable1').DataTable({
        dom: 'lrftip',
        responsive: true,
        select: {
            style: 'multi'
        },
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'product_movement_detail/view_data1/'+woid+'/'+pid+'/'+prid,
            type: "POST",
            dataSrc : 'data',
            data: function ( d ) {
                d.csrf_token = $("[name='csrf_token']").val();
            }
        },
        columns: columns1,
        columnDefs: [ 
            { className: "dt-body-center", targets: [0,1,2,3,4,5,6,7] },
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
        woid = $("[name='woid']").val();
        pid = $("[name='pid']").val();
        prid = id;
        table1.ajax.url(site_url+'product_movement_detail/view_data1/'+woid+'/'+pid+'/'+prid).load();
        $('#nopo').text(table.row( this ).data().code);
    } );

    $('#process_buttons').on( 'click', 'button', function () {

        var id = $(this).val();
        woid = $("[name='woid']").val();
        pid = $("[name='pid']").val();
        prid = id;
        $('#time-btn').show();
        if ($(this).val() == 1) {
            $('#add-btn').show(); 
            var url = site_url+'product_movement_detail/view_data/'+woid+'/'+pid+'/'+prid;
        } else {
            $('#add-btn').hide();
            var url = site_url+'product_movement_detail/view_data1/'+woid+'/'+pid+'/'+prid;
        }
        table1.ajax.url(url).load();
        $('#nopo').text($(this).text());

    } );
	
    $('#form-panel').hide();
    $('#form-panel1').hide();
	$('#form-panel2').hide();
    $('#add-btn').hide();
    $('#time-btn').hide();

	$('#add-btn').click(function(){
		action = "Add";
		$('#form-title').text('Add Form');
		$("#form").validator();
		show_hide_form(true);
        $.ajax({
            url: site_url+'machine/populate_select',
            type: "GET",
            data: {
                processes_id: 1
            },
            dataType:"JSON",
            success : function(resp)
            {
                var option = "<option value='' selected>Choose..</option>";
                $.each(resp, function(k, v){
                    option += "<option value='"+v.code+"'>"+v.code+"</option>";
                });
                $("#machine_id").html(option);
                // $("#machine_id").val();
            }
        });
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

    $('#cancelBtn1').click(function(){
        $("#form1").validator('destroy');
        show_hide_form(false);
        $('#form1')[0].reset();
        $('#number').show();
        $('#length1').show();
    });

    $("#saveBtn1").click(function (e) {
         var validator = $("#form1").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            //saveSelectedItems();
            save_data();
         }
     });

    $('#cancelBtn2').click(function(){
        $("#form2").validator('destroy');
        show_hide_form(false);
        $('#form2')[0].reset();
    });

    $('#time-btn').click(function(){
        action = "Time";
        $('#form-title').text('Time Table');
        $("#form2").validator();
        $("[name='wo_id']").val(woid);
        $("[name='processes_id']").val(prid);
        $("[name='products_id']").val(pid);
        $('#jsGrid').jsGrid('loadData');
        show_hide_form2(true);
    });

    $('#cancelTimeBtn').click(function(){
        $("#form2").validator('destroy');
        show_hide_form2(false);
        $('#form2')[0].reset();
    });

    $("#saveTimeBtn").click(function (e) {
         var validator = $("#form2").data("bs.validator");
         validator.validate();
         if (!validator.hasErrors()){
            //saveSelectedItems();
            save_item();
         }
     });

    $("#processes_id").change(function (e) {
        $.ajax({
            url: site_url+'machine/populate_select',
            type: "GET",
            data: {
                processes_id: this.value
            },
            dataType:"JSON",
            success : function(resp)
            {
                var option = "<option value='' selected>Choose..</option>";
                $.each(resp, function(k, v){
                    option += "<option value='"+v.code+"'>"+v.code+"</option>";
                });
                $("#machine_id1").html(option);
                // $("#machine_id").val();
            }
        });
    })


    var processes;
    $.ajax({
    	url: site_url+'processes/populate_select1/'+$('[name="pid"]').val(),
    	type: "GET",
    	async: false,
    	success : function(text)
    	{
    		processes = JSON.parse(text);
    	}
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
        			url: site_url+"product_movement_detail/jsgrid_functions/"+woid+"/"+pid+"/"+prid,
        			data: filter,
        			dataType:"JSON"
        		});
        	},
            deleteItem: function(item) {
                return $.ajax({
                    type: "DELETE",
                    url: site_url+"product_movement_detail/jsgrid_functions",
                    data: item,
                    success: function(data)
                    {
                        
                    }
                });
            }
        },
 
        fields: [ 
        { name: "id", visible:false, width: 10 }, 
        { name: "code", title:"Code", type: "text", width: 50 }, 
        { name: "length", title:"Length", type: "text", width: 50 }, 
        { 
            type: "control",
            modeSwitchButton: false,
            editButton: false 
        } 
        ] 
    });

    var save_item = function(){
        var data = $("#form2").serializeArray();
        data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});
        data.push({name: 'wo_id',value: $("[name='wo_id']").val()});
        data.push({name: 'processes_id',value: $("[name='processes_id']").val()});
        data.push({name: 'products_id',value: $("[name='products_id']").val()});
        var url = site_url+"product_movement_detail/edit_time";
        if(method != "Edit"){
            url = site_url+"product_movement_detail/add_time";
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
                    table.ajax.reload();
                }
            }
        }); 
    }

    var get_item_detail = function(data){
        $('[name="details_id"]').val(data.id);
        $('[name="datetime"]').val(data.date);
        $('[name="time_start"]').val(data.time_start);
        $('[name="time_end"]').val(data.time_end);
        $('[name="note2"]').val(data.note);
    }

    var clear_highlight = function(selected){
        if ( selected ) { selected.children('.jsgrid-cell').css('background-color', ''); }
    }

    var selectedItems = table.rows('.selected').data().toArray();
	
});

function show_hide_form(bShow){
    if(bShow==true){
        $('#form-panel').show();
        $('#form-panel1').hide();
        $('#form-panel2').hide();
        $('#table-panel').hide();
    }else{
        $('#form-panel').hide();
        $('#form-panel1').hide();
        $('#form-panel2').hide();
        $('#table-panel').show();
    }
}

function show_hide_form1(bShow){
    if(bShow==true){
        $('#form-panel').hide();
        $('#form-panel1').show();
        $('#table-panel').hide();
    }else{
        $('#form-panel').hide();
        $('#form-panel1').hide();
        $('#table-panel').show();
    }
}

function show_hide_form2(bShow){
	if(bShow==true){
        $('#form-panel').hide();
		$('#form-panel2').show();
		$('#table-panel').hide();
	}else{
        $('#form-panel').hide();
		$('#form-panel2').hide();
		$('#table-panel').show();
	}
}

function reload_table(){
	table.ajax.reload(null,false); //reload datatable ajax 
} 

function save_data(){
	var url;
	if(action == "Add"){
		url = site_url+"product_movement_detail/generate_code/"+$('[name="woid"]').val()+"/"+$('[name="pid"]').val();
	}else if(action == "MovePrint"){
        url = site_url+"product_movement_detail/add_to_process";
    }else if(action == "Move"){
        url = site_url+"product_movement_detail/update_process";
    }else{
		url = site_url+"product_movement_detail/update/"+$('[name="id"]').val();
	}
   
	var data = $("#form").serializeArray();
	data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});

    var selectedItems = table1.rows('.selected').data().toArray();
	$.ajax({
		   url : url,
		   type: "POST",
		   data: {
                csrf_token: $("[name='csrf_token']").val(),
                process_id: $("[name='processes_id']").val(),
                item: selectedItems,
                length: $("#length").val(),
                length1: $("#length1").val(),
                machine_id: $("#machine_id").val(),
                machine_id1: $("#machine_id1").val(),
                note: $("[name='note']").val(),
                pm_id: $("[name='pm_id']").val(),
                pmd_id: $("[name='pmd_id']").val(),
                no: $("[name='no']").val()
            },
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
                   table1.ajax.reload();
				   $("#saveBtn").text("Save");
				   $("#saveBtn").prop('disabled', false);
				   $('div.block-div').unblock();
				   $('[name="asd"]').val(data.id);
				   show_hide_form(false);
                   $('#form')[0].reset();
				   $('#form1')[0].reset();
                   selectedItems = [];
			   }else{
				   alert('Fail');
			   }
		   }
	   });
   }

function edit(woid, pid, prid, pmid, pqty, plength, id){
    action = "Edit";
    $('[name="length"]').val(plength);
    $('[name="pm_id"]').val(pmid);
    $('[name="id"]').val(id);
    $("#form1").validator();
    $("#machine_id").attr("disabled",true);
    $('#form-title').text('Edit Form');
    $('#jsGrid').jsGrid('loadData');
    $.ajax({
        url: site_url+'machine/populate_select',
        type: "GET",
        data: {
            processes_id: 1
        },
        dataType:"JSON",
        success : function(resp)
        {
            var option = "<option value='' selected>Choose..</option>";
            $.each(resp, function(k, v){
                option += "<option value='"+v.code+"'>"+v.code+"</option>";
            });
            $("#machine_id").html(option);
                // $("#machine_id").val();
            }
        });
    show_hide_form(true);
}

function movePrint(woid, pid, prid, pmid, pmdid, pqty, plength){
    action = "MovePrint";
    $('#processes_id').prop('disabled', false);
    $('#machine_id').prop('disabled', false);
    $('#saveBtn1').prop('disabled', false);
    $('[name="prid"]').val(prid);
    $('[name="pm_id"]').val(pmid);
    $('[name="pmd_id"]').val(pmdid);
    $("#form1").validator();
    $('#form-title').text('Edit Form');
    show_hide_form1(true);
    if (prid == 0 || prid == -1 || pqty == 0 || plength == 0) {
        $('[name="processes_id"]').val(prid);
        $('#processes_id').prop('disabled', 'disabled');
        $('#machine_id').prop('disabled', 'disabled');
        $('#saveBtn1').prop('disabled', 'disabled');
    }
}

function move(){
    action = "Move";
    $('#processes_id').prop('disabled', false);
    $('#machine_id').prop('disabled', false);
    $('#saveBtn1').prop('disabled', false);
    $('#number').hide();
    $('#length1').hide();
    $("#form1").validator();
    $('#form-title').text('Edit Form');
    show_hide_form1(true);
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
				url : site_url+"product_movement_detail/delete/"+id,
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
