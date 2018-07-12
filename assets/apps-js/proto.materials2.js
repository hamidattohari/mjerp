$(function() {
	 
	$("#dataGrid").jsGrid({
        width: "100%",
        height: "400px",
 
		editing: true,
        autoload: true,
		paging: true,
		confirmDeleting: false,
        rowClick: function(args) {
            showDetailsDialog("Edit", args.item);
		},
		onItemDeleting: function (args) {
			if (!args.item.deleteConfirmed) { // custom property for confirmation
				// cancel deleting
				args.cancel = true; 
				if(confirm("Want to delete?")){
					args.item.deleteConfirmed = true;
					$("#dataGrid").jsGrid('deleteItem', args.item); 
				}
			}
		},
		controller: {
            loadData: function() {
                var d = $.Deferred();
 
                $.ajax({
					url: site_url+"materials/view_data/",
					type:'POST',
					data: { csrf_token : $("[name='csrf_token']").val() },
                    dataType: "json"
                }).done(function(response) {
                    d.resolve(response.value);
                });
 
                return d.promise();
			}
        },
 
        fields: [
            { name: "id", title: "ID", width: "20%",  },
			{ name: "name", title: "Name", type: "typeheadField", width: "70%", validate: "required" },
			{
                type: "control",
                modeSwitchButton: false,
                editButton: false,
                headerTemplate: function() {
                    return $("<button>").attr("type", "button").addClass("btn btn-sm btn-info").append('<i class="fa fa-plus"></i> Add')
                            .on("click", function () {
                                showDetailsDialog("Add", {});
                            });
                }
            }
        ]
    });
 
	$("#detailsDialog").dialog({
		autoOpen: false,
		width: 400,
		close: function() {
			$("#detailsForm").validate().resetForm();
			$("#detailsForm").find(".error").removeClass("error");
			$("#detailsForm").find(".help-block ul").remove();
			
		}
	});
 
	$("#detailsForm").validate({
		rules: {
			name: "required",
		},
		messages: {
			name: "Please enter name",
		},
		submitHandler: function() {
			formSubmitHandler();
		}
	});
 
	var formSubmitHandler = $.noop;
 
	var showDetailsDialog = function(dialogType, client) {
		var subjects = ['PHP', 'MySQL', 'SQL', 'PostgreSQL', 'HTML', 'CSS', 'HTML5', 'CSS3', 'JSON']; 
		$('#name').autocomplete({
			source: subjects
		});
		$("#name").val(client.name);
 
		formSubmitHandler = function() {
			saveClient(client, dialogType === "Add");
		};
 
		$("#detailsDialog").dialog("option", "title", dialogType + " Client")
				.dialog("open");
	};
 
	var saveClient = function(client, isNew) {
		$.extend(client, {
			Name: $("#name").val()
		});
 
		$("#dataGrid").jsGrid(isNew ? "insertItem" : "updateItem", client);
 
		$("#detailsDialog").dialog("close");
	};
 
});
