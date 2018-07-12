$(document).ready(function() {
	
	var MyDateField = function(config) {
        jsGrid.Field.call(this, config);
    };

    MyDateField.prototype = new jsGrid.Field({
        sorter: function(date1, date2) {
            return new Date(date1) - new Date(date2);
        },
 
        itemTemplate: function(value) {
            return new Date(value).toDateString();
        },
 
        insertTemplate: function(value) {
            return this._insertPicker = $("<input>").datepicker({ defaultDate: new Date() });
        },
 
        editTemplate: function(value) {
            return this._editPicker = $("<input>").datepicker().datepicker("setDate", new Date(value));
        },
 
        insertValue: function() {
            return this._insertPicker.datepicker("getDate").toISOString();
        },
 
        editValue: function() {
            return this._editPicker.datepicker("getDate").toISOString();
        }
	});
	

	var subjects = ['PHP', 'MySQL', 'SQL', 'PostgreSQL', 'HTML', 'CSS', 'HTML5', 'CSS3', 'JSON']; 
	

	var substringMatcher = function(strs) {
		return function findMatches(q, cb) {
		  var matches, substringRegex;
	  
		  // an array that will be populated with substring matches
		  matches = [];
	  
		  // regex used to determine if a string contains the substring `q`
		  substrRegex = new RegExp(q, 'i');
	  
		  // iterate through the pool of strings and for any string that
		  // contains the substring `q`, add it to the `matches` array
		  $.each(strs, function(i, str) {
			if (substrRegex.test(str)) {
			  matches.push(str);
			}
		  });
	  
		  cb(matches);
		};
	  };

	  

	var TypeheadField = function(config){
		jsGrid.Field.call(this, config);
	}

	TypeheadField.prototype = new jsGrid.Field({
        sorter: function(value) {
            return false;
        },
 
        itemTemplate: function(value) {
            return value;
        },
 
        insertTemplate: function(value) {
			return this._insertPicker = $("<input>").autocomplete({
				source: subjects
			});
        },
 
        editTemplate: function(value) {
            return this._editPicker = $("<input>").datepicker().datepicker("setDate", new Date(value));
        },
 
        insertValue: function() {
			console.log(this._insertPicker.on( "autocompleteselect", function( event, ui ) { } ));
			return this._insertPicker.on( "autocompleteselect", function( event, ui ) { } )[0].value;
        },
 
        editValue: function() {
            return this._editPicker.datepicker("getDate").toISOString();
        }
	});
 
    jsGrid.fields.myDateField = MyDateField;
    jsGrid.fields.typeheadField = TypeheadField;


	$("#dataGrid").jsGrid({
        width: "100%",
        height: "400px",
 
        autoload : true,
		paging : true,
		pageSize : 10,
		pageButtonCount : 5,
		inserting : true,
		editing : true,
		filtering: true,
		sorting : false,
		confirmDeleting: false,
		onItemInserting: function(args) {
			console.log(args)
			// cancel update of the item with empty 'name' field
			if(args.item.id === undefined) {
				args.cancel = true;
				alert("Specify the name of the item!");
			}
		},
		onItemInserted: function(args) {
			console.log(args);
			swal(args.item+" Item Added!");
		},
		onItemUpdating: function(args) {
			console.log(args)
			// cancel update of the item with empty 'name' field
			if(args.item.name === "aaaa") {
				args.cancel = true;
				alert("Specify the name of the item!");
			}
		},
		onItemDeleting: function (args) {
			if (!args.item.deleteConfirmed) { // custom property for confirmation
				// cancel deleting
				if (!confirm("Want to delete?")) {
					args.cancel = true; 
				}else{
					args.item.deleteConfirmed = true;
					$("#jsGrid").jsGrid('deleteItem', args.item); 
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
            { name: "id", title: "ID <a href='javascript:void(0);'><i class='fa fa-plus'></i></a>", width: "25%",  },
			{ name: "name", title: "Name", type: "typeheadField", width: "70%", validate: "required" },
			{ type: "control" }
        ]
    });
 
	$("#add-btn").click(function(){
		var data = $('#dataGrid').jsGrid('option', 'data');
		console.log(data);
	});
});
