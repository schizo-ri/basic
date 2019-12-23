$(function() {
    
    var body_width = $('body').width();
    if(body_width > 450) {
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height -65);
    }

    $('.index_page table.dataTable.no-footer').css('height','fit-content');

    var div_width = $( '.preview_doc').width();
    var all_width = 217;

    $( ".preview_doc .thumb_container" ).each( (index, element) => {
        all_width += 217;
    });

    if(all_width > div_width ) {
        $('.preview_doc .scroll_right').show();
    }
        
    $(".clickable-row").click(function() {
     //   window.location = $(this).data("href");

    });

    $('#right-button').click(function() {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "+=217px"
        }, "slow");
        $('.preview_doc .scroll_left').show();
        
    });

    $('.sendEmail').click(function(){
		if (!confirm("Stvarno želiš poslati obavijest mailom?")) {
			return false;
		}
    });
    
    $('#left-button').click(function() {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "-=217px"
        }, "slow");
        if($('.preview_doc').scrollLeft() < 217 ) {
            $('.preview_doc .scroll_left').hide();
        } else {
            $('.preview_doc .scroll_left').show();
        }
    });

    $('.show').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').toggle();
        
        var body_width = $('body').width();
        if(body_width > 1200) {
            var index_height = $('.index_main.main_documents').height();
            var header_height = $('.page-header.header_questionnaire').height();
            var body_height = index_height - header_height;
            $('.all_documents').height(body_height -65 );
            var thumb_height = $('.preview_doc.preview_q .thumb_container').last().height();
            $('.thumb_container').first().height(thumb_height);
        }
    });

    $('.hide').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').toggle();
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height - 65);
    });

    $( ".change_view" ).click(function() {
        $( ".change_view" ).toggle();
        $( ".change_view2" ).toggle();
        $('.table-responsive.first_view').toggle();
        $('.table-responsive.second_view').toggle();        
    });
    $( ".change_view2" ).click(function() {
        $( ".change_view" ).toggle();
        $( ".change_view2" ).toggle();
        $('.table-responsive.first_view').toggle();
        $('.table-responsive.second_view').toggle();
    });
});

$( window ).resize(function() {
    var body_width = $('body').width();
    if(body_width > 1200) {
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height -65);
    }

});
