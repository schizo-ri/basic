$('.side_navbar a.link1').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');   
	$('.container').load(page + ' .container .row');
	
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