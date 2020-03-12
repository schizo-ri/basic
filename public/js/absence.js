$(function(){
	var d = new Date();
	var ova_godina = d.getFullYear();
	var prosla_godina = ova_godina - 1;
	var year = '';
	console.log(ova_godina);
	$('.info_abs .go.go_'+ova_godina).show();
	$('.info_abs .bol.bol_'+ova_godina).show();
	$('tbody tr.tr_'+ova_godina).show();

	$('#year_vacation').change(function(){
		year = $(this).val();
		$('tbody tr').hide();
		$('.tr_'+year).show();
		$('.info_abs>p>.go').hide();
		$('.info_abs>p>.go.go_'+year).show();
		$('#mySearchTbl').val("");
	
	});
	$('#year_sick').change(function(){
		console.log($(this).val());
		year = $(this).val();
		$('.info_abs>p>.bol').hide();
		$('.info_abs>p>.bol.bol_'+year).show();

		$('tbody tr').hide();
		$('.tr_'+year+'.bol').show();

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
});
	
