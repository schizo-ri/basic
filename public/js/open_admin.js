$(function(){
    $('.admin_pages>li>a').first().click();
});

$('.admin_pages>li>a').click(function(e) {
    var url = $(this).attr('href');
    $('.admin_pages>li>a').removeClass('active_admin');
    $(this).addClass('active_admin');
    $( '#admin_page' ).load( url, function( response, status, xhr ) {
        if ( status == "error" ) {
            var msg = "Sorry but there was an error: ";
            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }
        $.getScript( 'js/datatables.js');
        $.getScript( 'js/filter_table.js');
    });  
    return false
});

var body_width = $('body').width();
if(body_width < 450) {
    $('.admin_pages>li>a').click(function(e) { 
        $('aside.admin_aside').hide();
        $('main.admin_main').show();
    });

    $('.link_back').click(function () {
        $('aside.admin_aside').show();
        $('main.admin_main').hide();
    });
} 
