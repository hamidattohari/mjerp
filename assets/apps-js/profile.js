$(document).ready(function() {
	$.ajax({
		url : site_url+"profile/get_profile_details",
		type: "GET",
		dataType: "JSON",
		success: function(data)
		{
			$('[name="name"]').val(data.name);
			$('[name="email"]').val(data.email);
			$('#h-email').text(data.email);
			$('[name="password"]').val(data.password);
			$('[name="telp"]').val(data.telp);
			$('[name="address"]').val(data.address);
		}
	});	

	$('#saveBtn').click(function(){
		var data = $("#form").serializeArray();
		data.push({name: 'csrf_token',value: $("[name='csrf_token']").val()});	
		$.ajax({
			url : site_url+"profile/update",
			type: "POST",
			data: data,
			dataType: "JSON",
			success: function(response)
			{
				if(response.status){
					$('#h-email').text($('[name="email"]').val());
					swal("Profile Updated!");
				}
			}
		});	
	});

});
