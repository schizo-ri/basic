$('.button_nav').click(function(e){
   
    if($( this).hasClass('not_employee')){
        e.preventDefault();
    } else {
        var body_width = $('body').width();
    
        if($( this).hasClass('load_button')) {
            e.preventDefault();
            var page = $(this).attr('href');  

            $('.button_nav').css({
                'background': '#051847',
                'color': '#ffffff'
            });
            $('.button_nav').removeClass('active');
            $( this ).addClass('active');

            $('.container').load( page + ' .container > div', function() {
                
                if( $( '.button_nav.active' ).hasClass('event_button')) {
                    $.getScript( '/../js/event.js');
                    $.getScript( '/../js/load_calendar2.js');
                }
                if( $( '.button_nav.active' ).hasClass('doc_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/documents.js');
                    $.getScript( '/../js/collaps.js');
                    $.getScript( '/../js/filter_table.js');
                }
                if( $( '.button_nav.active' ).hasClass('quest_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/questionnaire.js');
                    $.getScript( '/../js/collaps.js');
                    $.getScript( '/../js/filter_table.js');
                }
                if( $( '.button_nav.active' ).hasClass('post_button')) {
                    $.getScript( '/../js/posts.js');
                    $.getScript( '/../js/filter.js');
                }
                if( $( '.button_nav.active' ).hasClass('ads_button')) {
                    $('.placeholder').show();
                    var body_width = $('body').width();
                    if(body_width > 450) {
                    var header_width = $('.index_main header.ad_header').width();
                    $('.index_main header.ad_header').css('max-height',header_width);
                    $.getScript( '/../js/filter.js');
                    $.getScript( 'js/filter_dropdown.js');
                    $.getScript( '/../js/ads.js');
                    }
                }
            });
        }
        $( this).addClass('active');
        $.getScript( "/../js/nav_button_color.js" );

    }

    if(body_width < 800) {
        $('#myTopnav').removeClass('responsive');
    }
});


