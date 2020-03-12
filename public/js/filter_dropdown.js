$( document ).ready(function() {  // filter knowledge base
	$('#filter').change(function() {
		var trazi = $('#filter').val().toLowerCase();
		if(trazi == "all"){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
			});
		}
	});	
});