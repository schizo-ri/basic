$( function () {
    var body_width = $('body').width();
    var div_width = $( '.preview_doc').width();
    var all_width = 115;
    
    $('.notice_show').click(function(){
        $.getScript( '/../js/open_modal.js');
	});
    $( ".preview_doc > .thumbnail" ).each( (index, element) => {
        all_width += 115;
    });

    if(all_width > div_width ) {
        $('.preview_doc .scroll_right').show();
    }

    $('#right-button').click(function() {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "+=115px"
        }, "slow");
        $('.preview_doc .scroll_left').show();
        
    });

    $('.thumbnail').each(function(){
        var src = $(this).attr('title');
    //	$( this ).find('.ajax-content').load(src);
    });

    $('#left-button').click(function() {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "-=115px"
        }, "slow");
        if($('.preview_doc').scrollLeft() < 115 ) {
            $('.preview_doc .scroll_left').hide();
        } else {
            $('.preview_doc .scroll_left').show();
        }
    });
    
    var documents_height = $('.all_documents').height();
    var filter_height = $('.dataTables_filter').height();
    var table_height = documents_height - filter_height;
//    $('.display.table.dataTable').height(table_height);

    var index_height = $('.index_main.main_documents').height();
	var header_height = $('.page-header.header_document').height();
    
    if(body_width<768) {
	 //   $('.all_documents').css('height','auto');
    } 

	$('.show').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').show();
        
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_document').height();
        var body_height = index_height - header_height - 60;
        $('.all_documents').height(body_height);
    });
    
    $('.hide').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').hide();
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_document').height();
        var body_height = index_height - header_height - 60;
        $('.all_documents').height(body_height);
    });
    
    $('.button_nav').css({
        'background': '#051847',
        'color': '#ffffff'
    });
    
    $( '.doc_button' ).css({
        'background': '#0A2A79',
        'color': '#ccc'
    });
    
    $(function() {
		 $('#index_table').css('height','fit-content');
    });
});