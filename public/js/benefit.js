$('.benefit_body').first().show();

$('.benefit_title').click(function(){
	$('.benefit_title').removeClass('active');
	var id = $(this).attr('id');
	console.log(id);
	$('.benefit_body').hide();
	$('.benefit_body#_'+id).show();
	$(this).addClass('active');
});
var main_benefits_height = $('.main_benefits').height();
var main_benefits_head_height = $('.main_benefits_head').height()+40;
var body_width = $('body').width();
var div_width = $( '.main_benefits_head').width();
var all_width = 0;

$('.benefit_title').first().addClass('active');

if(body_width > 450) {
	$('.main_benefits_body').height(main_benefits_height-main_benefits_head_height-97);
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 203;
	});
	if((all_width - 30) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	}
} else {
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 110;
	});
	if((all_width - 10) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	}
}
	
$('#right-button').click(function() {
	event.preventDefault();
	$('.main_benefits_head').animate({
		scrollLeft: "+=203px"
	}, "slow");
	$('.main_benefits .scroll_left').show();
	
});

$('#left-button').click(function() {
	event.preventDefault();
	$('.main_benefits_head').animate({
		scrollLeft: "-=203px"
	}, "slow");
	if($('.main_benefits_head').scrollLeft() < 203 ) {
		$('.main_benefits .scroll_left').hide();
	} else {
		$('.main_benefits .scroll_left').show();
	}
});

$( window ).resize(function() {
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 203;
	});
	
	if((all_width - 30) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	} else {
		$('.main_benefits .scroll_right').hide();
	}
});