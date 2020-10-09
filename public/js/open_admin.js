var prev_url = location.href;
$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;
var active_link;
var url_modul = location.pathname;
url_modul = url_modul.replace("/","");
url_modul = url_modul.split('/')[0];
console.log("open admin");
$(function(){
    if($('.car_links').find('.admin_link').hasClass('active_admin')) {
        $('.car_links').show();
    } else {
        $('.car_links').hide();
    }
    if(body_width > 768) {
        if(url_location.includes('templates')) {
            $('.admin_pages>li>a#emailings').trigger('click');
        
        } else {
            $('.admin_pages>li>a').first().trigger('click');
        }
    }
    $(".admin_pages a.admin_link").removeClass('disable');
});

if($(".index_table_filter .show_button").length == 0) {
    $('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
} 

var click_element;
/* 
$('.admin_pages>li>a').on('click',function(e) {
    click_element = $(this);
    var title = click_element.text();
    $("title").text( title ); 
    var url = $(this).attr('href');

    $('.admin_pages>li>a').removeClass('active_admin');
    $(this).addClass('active_admin');
    active_link = $('.admin_link.active_admin').attr('id');

    $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
       
        window.history.replaceState({}, document.title, url);
        if ( status == "error" ) {
            var msg = "Sorry but there was an error: ";
            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }
        $.getScript( 'js/datatables.js');
        $.getScript( 'js/filter_table.js');
    });
    return false;
}); */

if(body_width < 768) {
    $('.admin_pages>li>a').on('click',function(e) {
        $('aside.admin_aside').hide();
        $('main.admin_main').show();
        click_element = $(this);
        var title = click_element.text();
        $("title").text( title ); 
        var url = $(this).attr('href');
    
        $('.admin_pages>li>a').removeClass('active_admin');
        $(this).addClass('active_admin');
        active_link = $('.admin_link.active_admin').attr('id');
    
        $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
           
            window.history.replaceState({}, document.title, url);
            if ( status == "error" ) {
                var msg = "Sorry but there was an error: ";
                $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
            }
            $.getScript( 'js/datatables.js');
            $.getScript( 'js/filter_table.js');
        });
        return false;
    }); 

     $('.link_back').on('click',function () {
        $('aside.admin_aside').show();
        $('main.admin_main').hide();
    });
}