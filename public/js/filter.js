$(document).ready(function(){
	$("#mySearch").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$(".panel").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
});