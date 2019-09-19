$(function(){
	var d = new Date();
	var ova_godina = d.getFullYear();
	var prosla_godina = ova_godina - 1;

	$('#year_vacation').change(function(){
		$('.go_og').toggle();
		$('.go_pg').toggle();
		if($('#year_vacation').val() == prosla_godina) {
			$('tbody tr').each(function(){
				
				if ($(this).hasClass('prosla_godina')) {
					$(this).show();
				}
				if ($(this).hasClass('ova_godina')) {
					$(this).hide();
				}
			});
		} 
		if ($('#year_vacation').val() == ova_godina) {
			$('tbody tr').each(function(){
				if ($(this).hasClass('prosla_godina')) {
					$(this).hide();
				}
				if ($(this).hasClass('ova_godina')) {
					$(this).show();
				}
			});
		}
	});
	$('#year_sick').change(function(){
		$('.bol_og').toggle();
		$('.bol_pg').toggle();
	});
	
	$( "#request_type" ).change(function() {
		if($(this).val() == 'IZL') {
			$('.form-group.time').show();
			$('.form-group.date2').hide();
			var start_date = $( "#start_date" ).val();
			var end_date = $( "#end_date" );
			end_date.val(start_date);
		} else {
			$('.form-group.time').hide();
			$('.form-group.date2').show();
		}
	});
	$( "#start_date" ).change(function() {
		var start_date = $( this ).val();
		var end_date = $( "#end_date" );
		end_date.val(start_date);
	
	});

	function mySearchTable() {
		$("#mySearchTbl").keyup(function() {
			var value = $(this).val().toLowerCase();
			
			$("#index_table tbody tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
	}
});
	
