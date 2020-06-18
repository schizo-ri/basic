$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;

$(function(){
    if(body_width > 768) {
        if(url_location.includes('templates')) {
            $('.admin_pages>li>a#emailings').click();
        
            window.history.pushState( url_location, 'Title',location.origin + '/admin_panel');
        } else {
            $('.admin_pages>li>a').first().click();
        }
    }
    $(".admin_pages a.admin_link").removeClass('disable');
});

var click_element;

$('.admin_pages>li>a').click(function(e) {
    click_element = $(this);
   
    var url = $(this).attr('href');
   // console.log(url);
    $('.admin_pages>li>a').removeClass('active_admin');
    $(this).addClass('active_admin');
    $( '#admin_page' ).load( url, function( response, status, xhr ) {
      //window.history.replaceState({}, document.title, url);
        if ( status == "error" ) {
            var msg = "Sorry but there was an error: ";
            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }
        $.getScript( 'js/datatables.js');
        $.getScript( 'js/filter_table.js');
    });
   
    return false;
});

if(body_width < 768) {
    $('.admin_pages>li>a').click(function(e) { 
        $('aside.admin_aside').hide();
        $('main.admin_main').show();
    });

    $('.link_back').click(function () {
        $('aside.admin_aside').show();
        $('main.admin_main').hide();
    });
}