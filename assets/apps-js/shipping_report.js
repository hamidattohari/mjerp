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
		dom: 'Blrtip',
		ordering: false,
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 10,
        deferRender: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
            url: site_url+'report/view_shipping_report',
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
			{ "orderable": false, targets : [-1]  } 
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

});

