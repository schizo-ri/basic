$('.button_nav').click(function(e){   
    //window.history.replaceState({}, document.title, location['origin']+'/dashboard');
    window.history.replaceState({}, document.title, $(this).attr('href'));
  
    //console.log($(this).attr('href'));
    $.ajaxSetup({
        cache: true
    });
    
    jQuery.cachedScript = function( url, options ) {
        // Allow user to set any option except for dataType, cache, and url
        options = $.extend( options || {}, {
            dataType: "script",
            cache: true,
            url: url
        });

        // Return the jqXHR object so we can chain callbacks
        return jQuery.ajax( options );
    };
    var body_width;
    body_width = $('body').width();
    if($( this).hasClass('not_employee')){
        e.preventDefault();
    } else {
        $.getScript( "/../js/nav_button_color.js" );
        
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
                    $.getScript( '/../restfulizer.js');
                }
                if( $( '.button_nav.active' ).hasClass('doc_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/documents.js');
                    $.getScript( '/../js/filter_table.js');
                    $.getScript( '/../restfulizer.js');
                    
                }
                if( $( '.button_nav.active' ).hasClass('quest_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/questionnaire.js');
                    $.getScript( '/../js/filter_table.js');
                    $.getScript( '/../restfulizer.js');
                }
                if( $( '.button_nav.active' ).hasClass('post_button')) {
                    $.getScript( '/../js/posts.js');
                    $.getScript( '/../js/filter.js');
                }
                if( $( '.button_nav.active' ).hasClass('ads_button')) {
                    $('.placeholder').show();
                   
                    if(body_width > 450) {
                    var header_width = $('.index_main header.ad_header').width();
                        $('.index_main header.ad_header').css('max-height',header_width);
                        $.getScript( '/../js/filter.js');
                        $.getScript( '/../js/filter_dropdown.js');
                        $.getScript( '/../js/ads.js');
                    }
                }
            });
        }
        $( this).addClass('active');
    }

    if(body_width < 800) {
        $('#myTopnav').removeClass('responsive');
    }
});