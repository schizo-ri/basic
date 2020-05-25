$(function(){
	var d = new Date();
	var ova_godina = d.getFullYear();
	var prosla_godina = ova_godina - 1;
	var year = '';

	$('#year_vacation').change(function(){
		year = $(this).val();
		
		$('.info_abs>p>.go').hide();
		$('.info_abs>p>.go.go_'+year).show();
		$('#mySearchTbl').val("");

		var url = location.href + '?year='+year;
		console.log(url);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});     
		$.ajax({
			url: url,
			type: "GET",
			success: function( response ) {
				console.log("prošlo");
				$('table').load(url + ' table',function(){
					$.getScript( '/../restfulizer.js');
				});
			}, 
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr);                            
			}
		});
	});
	$('#year_sick').change(function(){
		console.log($(this).val());
		year = $(this).val();
		$('.info_abs>p>.bol').hide();
		$('.info_abs>p>.bol.bol_'+year).show();

		var url = location.href + '?year='+year+'&type=BOL';
		console.log(url);
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});     
		$.ajax({
			url: url,
			type: "GET",
			success: function( response ) {
				console.log("prošlo");
				$('table').load(url + ' table',function(){
					$.getScript( '/../restfulizer.js');
				});
			}, 
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr);                            
			}
		});

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
	
