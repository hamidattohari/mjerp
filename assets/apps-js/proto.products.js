$(document).ready(function() {

	$( "#dialog" ).dialog();

	var clients = [
        { "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false },
        { "Name": "Connor Johnston", "Age": 45, "Country": 2, "Address": "Ap #370-4647 Dis Av.", "Married": true },
        { "Name": "Lacey Hess", "Age": 29, "Country": 3, "Address": "Ap #365-8835 Integer St.", "Married": false },
        { "Name": "Timothy Henson", "Age": 56, "Country": 1, "Address": "911-5143 Luctus Ave", "Married": true },
        { "Name": "Ramona Benton", "Age": 32, "Country": 3, "Address": "Ap #614-689 Vehicula Street", "Married": false }
    ];
 
    var countries = [
        { Name: "", Id: 0 },
        { Name: "United States", Id: 1 },
        { Name: "Canada", Id: 2 },
        { Name: "United Kingdom", Id: 3 }
	];
	
	$("#jsGrid").jsGrid({
        width: "100%",
        height: "400px",
 
        autoload : true,
		paging : true,
		pageSize : 10,
		pageButtonCount : 5,
		inserting : true,
		editing : true,
		filtering: true,
		sorting : true,
		confirmDeleting: false,
		onItemInserting: function(args) {
			console.log(args)
			// cancel update of the item with empty 'name' field
			if(args.item.id === undefined) {
				args.cancel = true;
				alert("Specify the name of the item!");
			}
		},
		onItemDeleting: function (args) {
			if (!args.item.deleteConfirmed) { // custom property for confirmation
				args.cancel = true; // cancel deleting
				var result = confirm("Want to delete?");
				if (result) {
					args.item.deleteConfirmed = true;
					$("#jsGrid").jsGrid('deleteItem', args.item); 
				}
			}
		},
		onItemUpdating: function(args) {
			console.log(args)
			// cancel update of the item with empty 'name' field
			if(args.item.name === "aaaa") {
				args.cancel = true;
				alert("Specify the name of the item!");
			}
		},
		onItemUpdated: function(args) {
			console.log(args)

			// cancel update of the item with empty 'name' field
			if(args.item.id === "") {
				args.cancel = true;
				alert("Specify the name of the item!");
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
            { name: "id", title: "ID", type: "number", width: "25%",  },
			{ name: "name", title: "Name", type: "text", width: "70%", validate: "required" },
			{ type: "control" }
        ]
    });
 
    /*$("#jsGrid").jsGrid({
        width: "100%",
        height: "400px",
 
        autoload : true,
		confirmDeleting : false,
		paging : true,
		pageSize : 10,
		pageButtonCount : 5,
		inserting : true,
		editing : true,
		sorting : true,
		deleteConfirm: function(item) {
            return "The client \"" + item.Name + "\" will be removed. Are you sure?";
        },
		editItem: function(item) {
			var $row = this.rowByItem(item);
			if ($row.length) {
				console.log('$row: '+JSON.stringify($row)); // I modify this
				this._editRow($row);
			}
		},
		invalidNotify: function(args) {
			var messages = $.map(args.errors, function(error) {
				return error.field + ": " + error.message;
			});
	 
			console.log(messages);
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
			},
			deleteItem: function(item) {
				alert("hallo");
				$('#jsGrid').jsGrid("loadData");
			}
        },
 
        fields: [
            { name: "id", title: "ID", type: "number", width: "25%",  },
			{ name: "name", title: "Name", type: "text", width: "70%", validate: "required" },
            { 
				type: "control",
                modeSwitchButton: false,
				editButton: false,
				width:"5%",
                headerTemplate: function() {
                    return $("<button>").attr("type", "button").text("Add")
                            .on("click", function () {
                                showDetailsDialog("Add", {});
                            });
                }	
			}
        ]
    });
	
	$(".jsgrid-insert-button").click(function(){
		alert("insert");
	})

	$(".jsgrid-delete-button").click(function(){
		alert("delete");
	})

	$("#detailsDialog").dialog({
		autoOpen: false,
		width: '500',
		height: 'auto',
		modal: true,
        close: function() {
            $("#detailsForm").validate().resetForm();
            $("#detailsForm").find(".error").removeClass("error");
        }
    });
 
    $("#detailsForm").validate({
        rules: {
            name: "required"
        },
        messages: {
            name: "Please enter name"
        },
        submitHandler: function() {
            formSubmitHandler();
        }
    });
 
    var formSubmitHandler = $.noop;
 
    var showDetailsDialog = function(dialogType, client) {
        $("#name").val(client.Name);
 
        formSubmitHandler = function() {
            saveClient(client, dialogType === "Add");
        };
 
        $("#detailsDialog").dialog("option", "title", dialogType + " Client")
                .dialog("open");
    };
 
    var saveClient = function(client, isNew) {
        $.extend(client, {
            name: $("#name").val()
        });
 
        $("#jsGrid").jsGrid(isNew ? "insertItem" : "updateItem", client);
 
        $("#detailsDialog").dialog("close");
    };


	$('#add-btn').click(function(){
		alert("haha");
		$('#jsGrid').jsGrid("loadData");
	})*/

});
