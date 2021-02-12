$(function() {
	$("#mySearch").on( 'keyup', function() {
		var value = $(this).val().toLowerCase();
		$(".panel").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#mySearch1").on( 'keyup',function() {
		var value = $(this).val().toLowerCase();
		$(".panel1").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#mySearch_noticeboard").on( 'keyup',function() {
		var value = $(this).val().toLowerCase();
		$(".panel").parent().filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
});