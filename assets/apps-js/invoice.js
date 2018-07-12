$(document).ready(function() {
	$("#print-invoice").click(function() {
		var mode = 'iframe'; //popup
		var close = mode == "popup";
		var options = {
			mode: mode,
			popClose: close
		};
		$("div.printableArea").printArea(options);
	});


	$("#print-invoice2").click(function() {
		var mode = 'iframe'; //popup
		var close = mode == "popup";
		var options = {
			mode: mode,
			popClose: close
		};
		$("div.printableArea2").printArea(options);
	});

});
