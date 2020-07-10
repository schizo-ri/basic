
var prev_url = location.href;
var url_modul;
var body_width = $('body').width();
$('.button_nav').click(function(e){
    //window.history.replaceState({}, document.title, location['origin']+'/dashboard');
    // window.history.replaceState({}, document.title, $(this).attr('href') ); 
    url_modul = window.location.pathname;
    url_modul = url_modul.replace("/","");
    url_modul = url_modul.split('/')[0];

    window.history.pushState( prev_url, 'Title',  $(this).attr('href'));

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
    
    if($( this).hasClass('not_employee')){
        e.preventDefault();
    } else {
        $.getScript( "/../js/nav_button_color.js" );
        
        if($( this).hasClass('load_button')) {
            e.preventDefault();
            var page = $(this).attr('href');

            $('.button_nav').removeClass('active');
            $( this ).addClass('active');

            $('.container').load( page + ' .container > div', function() {
                $.getScript( "/../js/open_modal.js" );
                if( $( '.button_nav.active' ).hasClass('events_button')) {
                    $.getScript( '/../js/event.js');
                    $.getScript( '/../js/load_calendar2.js');
                    $.getScript( '/../restfulizer.js');
                }
                if( $( '.button_nav.active' ).hasClass('documents_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/documents.js');
                    $.getScript( '/../js/filter_table.js');
                    $.getScript( '/../restfulizer.js');
                }
                if( $( '.button_nav.active' ).hasClass('questionnaires_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/questionnaire.js');
                    $.getScript( '/../js/filter_table.js');
                    $.getScript( '/../restfulizer.js');
                }
                if( $( '.button_nav.active' ).hasClass('posts_button')) {
                    $.getScript( '/../js/posts.js');
                    $.getScript( '/../js/filter.js');
                }
                if( $( '.button_nav.active' ).hasClass('campaigns_button')) {                    
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/filter_table.js');                    
                    $.getScript( '/../restfulizer.js');
                    $.getScript( '/../js/event.js');
                    $.getScript( '/../js/campaign.js');
                } 
                if( $( '.button_nav.active' ).hasClass('benefits_button')) {
                    $.getScript( '/../js/benefit.js');
                    $.getScript( '/../js/filter.js');
                }            
                if( $( '.button_nav.active' ).hasClass('oglasnik_button')) {
                    $('.placeholder').show();
                    $.getScript( '/../restfulizer.js');
                   
                    if(body_width > 768) {
                        var header_width = $('.index_main header.ad_header').width();
                        $('.index_main header.ad_header').css('max-height',header_width);
                        $.getScript( '/../js/filter.js');
                        $.getScript( '/../js/filter_dropdown.js');
                        $.getScript( '/../js/ads.js');
                    }
                }
                $('.link_back').click(function(e){
                    e.preventDefault();
                    $('.' + url_modul + '_button').click();
                   
                }); 
            });
        }
        $( this).addClass('active');
    }

    if(body_width < 450) {
        $('.section_top_nav').removeClass('responsive');
    } else {
        $('.close_topnav').click();
    }
});
var section_top_width =  $('.section_top_nav').width();

function myTopNav() {
    var x = $(".section_top_nav");
    if (x.hasClass("responsive")) {
        x.removeClass("responsive");
    } else {
        x.addClass("responsive");
    } 
}

$('.logo_icon').click(function(){
    $('.section_top_nav').css('width','250px');
    $('#myTopnav:not(".responsive")').css('display','block');
    $('#myTopnav:not(".responsive")').css('width','250px');
    $('.header_nav .section_top_nav .close_topnav svg').show();
});

$('.close_topnav').click(function(){
    $('.header_nav .section_top_nav .close_topnav svg').hide();
    $('#myTopnav:not(".responsive")').css('display','none');
    $('#myTopnav:not(".responsive")').css('width',0);
    $('.section_top_nav').css('width', 0);
});

if(body_width > 768) {
    $("body").click(function(){
        $('.close_topnav').click();
    });
    $(".logo_icon").click(function(event) {
        event.stopPropagation();
    });
    $(".section_top_nav").click(function(event) {
        event.stopPropagation();
    });
}

$("a[rel='modal:open']").addClass('disable');

$( document ).ready(function() {
    $("a[rel='modal:open']").removeClass('disable');
    
    url_modul = window.location.pathname;
    url_modul = url_modul.replace("/","");
    if(url_modul.indexOf("/") > 0) {
        url_modul = url_modul.slice(0, url_modul.indexOf("/"));
    }
 
    if(url_modul.includes('campaign_sequences') ) {
        $('.button_nav').removeClass('active');
        $('.button_nav.'+ 'campaigns_button').addClass('active');
    } else if(url_modul == 'admin_panel/') { //povratna putanja sa admin_panel/templates 
        //
    } else if( $('.button_nav.'+url_modul+'_button').length > 0) {   // na reload stavlja klasu activ na button prema url pathname
        $('.button_nav').removeClass('active');
        $('.button_nav.'+url_modul+'_button').addClass('active');
    }
});