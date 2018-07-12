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
});
