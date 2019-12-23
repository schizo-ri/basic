$('.side_navbar a.link1').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');   
	$('.container').load(page + ' .container .row');
	/*$.getScript( 'dataTables/JSZip-2.5.0/jszip.min.js');
	$.getScript( 'dataTables/pdfmake-0.1.36/pdfmake.min.js');
	$.getScript( 'dataTables/pdfmake-0.1.36/vfs_fonts.js');
	$.getScript( 'dataTables/Buttons-1.5.6/js/buttons.print.min.js');
	$.getScript('dataTables/datatables.min.js');
	$.getScript( 'js/datatables.js');*/
	$('.side_navbar a').removeAttr("style");
	$('.nav li').removeAttr("style");
	$('.nav .link_ads').removeAttr("style");
	$('.link_admin').removeAttr("style");
	$(this).css('color','orange');
});
$('nav.navbar a').on("click",function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');  
	$('.container').load(page + ' .container .row');
	/*$.getScript( 'dataTables/JSZip-2.5.0/jszip.min.js');
	$.getScript( 'dataTables/pdfmake-0.1.36/pdfmake.min.js');
	$.getScript( 'dataTables/pdfmake-0.1.36/vfs_fonts.js');
	$.getScript( 'dataTables/Buttons-1.5.6/js/buttons.print.min.js');
	$.getScript('dataTables/datatables.min.js');
	$.getScript( 'js/datatables.js');*/
	$('nav a').removeAttr("style");
	$('.side_navbar a').removeAttr("style");
	$(this).css('color','orange');
});

$('.side_navbar a.link3').on("click",function(e){ 

//	e.preventDefault(); // cancel click
	var page = $(this).attr('href'); 

//	$('.container').load(page + ' .container .row .calender_view', function()
//	{ $.getScript("node_modules/moment/moment.js");
//	$.getScript("node_modules/pg-calendar/dist/js/pignose.calendar.min.js");
//	
//	}
//	);
	
	$('nav li').removeAttr("style");
	$('.side_navbar a').removeAttr("style");
	$(this).css('color','orange');
});