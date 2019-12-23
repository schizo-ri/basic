//load wifhout refresh pages
$('.load_page').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');  
	$('.container').load(page + ' .container > .row > div', function()
	{$.getScript("js/upload_page.js");}
	);
});


