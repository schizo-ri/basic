$(function() {
	$("#mySearch").on( 'keyup', function() { //ima funkcija u filter_table
		console.log("mySearch1");
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
	$('.check_all').on('click',function(){
		console.log('check_all');
		if( $( this ).attr('data-value') == 1 ) {
			$('input[type=checkbox]').prop('checked',true);
			$( this ).attr('data-value',0);
		} else {
			$('input[type=checkbox]').prop('checked',false);
			$( this ).attr('data-value',1);
		}
	});
});