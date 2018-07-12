
$(document).ready(function() {
	var editEnabled = false;

	$.ajax({
		url:site_url+"application/get_logo",
		type:"GET",
		async:false,
		dataType:"JSON",
		success: function(response){
			logo = 
			$("#logo_path").dropify({
				defaultFile:base_url+"assets/"+response.logo_path
			});
			$("#logo_title_path").dropify({
				defaultFile:base_url+"assets/"+response.logo_title_path
			});
		} 
	});

	$('#taxpayer_reg_number, #name, #address, #telp, #owner').editable({
		validate: function(value) {
			if ($.trim(value) == '') return 'This field is required';
		},
		mode: 'inline',
		url: site_url+"application/update",
		params: function ( d ) {
			var field = this.id;
			d.csrf_token = $("[name='csrf_token']").val();
			return d;
		},
		success: function(response, newValue) {
			if(response.status == false ) return response.msg; 
		},
		error: function(response, newValue) {
			return response.responseText;
		}
	});

	$('#currency_id').editable({
		prepend: "not selected",
		mode: 'inline',
		source: site_url+"currency/populate_select",
		url: site_url+"application/update",
		params: function ( d ) {
			var field = this.id;
			d.csrf_token = $("[name='csrf_token']").val();
			return d;
		},
		success: function(response, newValue) {
			if(response.status == false ) return response.msg; 
		},
		error: function(response, newValue) {
			return response.responseText;
		}
	});

	$('#user .editable').editable('toggleDisabled');

	$('#enable').click(function() {
		if(!editEnabled){
			$("#enable").removeClass("btn-success");
			$("#enable").addClass("btn-danger");
			$("#enable").text("Finish");
			editEnabled = true;
		}else{
			$("#enable").removeClass("btn-danger");
			$("#enable").addClass("btn-success");
			$("#enable").text("Edit");
			editEnabled = false;
		}
		$('#user .editable').editable('toggleDisabled');
	});    

	$("#upload1").click(function(){
		var data = new FormData($("#logo-form")[0]);
		data.append('csrf_token',$("[name='csrf_token']").val());
		$.ajax({
			url: site_url+"application/update_logo", 
			type: "POST",             
			data: data,
			contentType: false,       
			cache: false,             
			processData:false,        
			success: function(data)   
			{
				console.log(data);	
				if(!data){
					alert("Fail!");
				}else{
					swal("Logo Updated!");
				}
			}
		});
	});

	$("#upload2").click(function(){
		var data = new FormData($("#logo-title-form")[0]);
		data.append('csrf_token',$("[name='csrf_token']").val());
		$.ajax({
			url: site_url+"application/update_logo_title", 
			type: "POST",             
			data: data,
			contentType: false,       
			cache: false,             
			processData:false,        
			success: function(data)   
			{
				console.log(data);	
				if(!data){
					alert("Fail!");
				}else{
					swal("Logo Updated!");
				}
			}
		});
	});

});
