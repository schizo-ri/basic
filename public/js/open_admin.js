var prev_url = location.href;
$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;
var active_link;
var url_modul = location.pathname;
url_modul = url_modul.replace("/","");
url_modul = url_modul.split('/')[0];

$(function(){
    var class_open;
    
    if(body_width > 992) {
        class_open = $('.admin_link.active_admin').parent().attr('class');
        if(class_open != undefined && class_open != '') {
            class_open = "."+class_open.replace(" ",".");
            $(class_open).show();
        }
    }

    $('.open_menu').on('click', function(e){
        e.preventDefault();
        class_open = $( this).attr('id');
        $('.'+class_open).toggle();
    });
    $(".admin_pages a.admin_link").removeClass('disable');
    // ako ima shortcut - href edit
    try {
        url_location = location.href;
        $.get( location.origin+"/shortcut_exist", {'url': url_location }, function( id ) {
            if(id != null && id != '') {
                $('.shortcut').attr('href', location.origin +'/shortcuts/'+id+'/edit/');
                $('.shortcut_text').text('Ispravi prečac'); 
            } else {
                title = $('.admin_link.active_admin').attr('id');
                $('.shortcut').attr('href', location.origin +'/shortcuts/create/?url='+url_location+'&title='+title );
                $('.shortcut_text').text('Dodaj prečac'); 
            }
        });
    } catch (error) {
        //
    }
});

if($(".index_table_filter .show_button").length == 0) {
    $('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
} 

var click_element;
var title;
var url;

$('.admin_pages li>a').not('.open_menu').on('click',function(e) {
    $('#login-modal').remove();
    e.preventDefault();
    click_element = $(this);
    title = click_element.text();
    $("title").text( title ); 
    url = $(this).attr('href');
    
    // ako ima shortcut - href edit
    try {
        $.get( location.origin+"/shortcut_exist", {'url': url }, function( id ) {
            if(id != null && id != '' && id) {
                $('.shortcut').attr('href', location.origin +'/shortcuts/'+id+'/edit/');
                $('.shortcut_text').text('Ispravi prečac'); 
            } else {
                title = $('.admin_link.active_admin').attr('id');

                $('.shortcut').attr('href', location.origin +'/shortcuts/create/?url='+url+'&title='+title );
                $('.shortcut_text').text('Dodaj prečac'); 
            }
        });
    } catch (error) {
        //
    }

    $('.admin_pages>li>a').removeClass('active_admin');
    $(this).addClass('active_admin');
    active_link = $('.admin_link.active_admin').attr('id');

    $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
        window.history.replaceState({}, document.title, url);
        if ( status == "error" ) {
            var msg = "Sorry but there was an error: ";
            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }
       
        $.getScript( '/../restfulizer.js');
        $.getScript( '/../js/filter_dropdown.js');
        $.getScript( '/../js/open_modal.js');
        $.getScript( '/../js/datatables.js');
        if (url.includes('/work_records')) {
            $.getScript( '/../js/work_records.js');
        } else if(url.includes('/loccos')) {
            $('a.open_locco').on('click',function(event) {
                event.preventDefault();
                click_element = $(this);
                title = click_element.text();
                $("title").text( title ); 
                url = $(this).attr('href');

                $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
                    window.history.replaceState({}, document.title, url);
                    if ( status == "error" ) {
                        var msg = "Sorry but there was an error: ";
                        $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
                    }
                    $.getScript( '/../restfulizer.js');
                    $.getScript( '/../js/filter_dropdown.js');
                    $.getScript( '/../js/datatables.js');
                    $.getScript( '/../js/open_modal.js');
                });
                return false;
            });
        }
       
        if(body_width < 992 ) {
            $('aside.admin_aside').hide();
            $('main.admin_main').show();
            if(url.includes('/users')) {
                $.getScript( '/../js/users.js');
            }
            $('.link_back').on('click',function (e) {
                e.preventDefault();
                $('aside.admin_aside').show();
                $('main.admin_main').hide();
            });
        }
    });
    return false;
});
/* 

/* var prev_url = location.href;
$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;
var active_link;
var url_modul = location.pathname;
url_modul = url_modul.replace("/","");
url_modul = url_modul.split('/')[0];
$(function(){
    if($('.car_links').find('.admin_link').hasClass('active_admin')) {
        $('.car_links').show();
    } else {
        $('.car_links').hide();
    }
    if(body_width > 991) {
        if(url_location.includes('templates')) {
            $('.admin_pages>li>a#emailings').trigger('click');
        
        } else {
         //    $('.admin_pages>li>a').first().trigger('click'); 
        }
    }
    $(".admin_pages a.admin_link").removeClass('disable');
});

if($(".index_table_filter .show_button").length == 0) {
    $('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
} 

var click_element;

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
        $.getScript( 'restfulizer.js');
        $.getScript( '/../js/filter_dropdown.js');
    });
    return false;
});

if(body_width < 992) {
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
            $.getScript( 'js/open_modal.js');
        });
        return false;
    }); 

     $('.link_back').on('click',function () {
        $('aside.admin_aside').show();
        $('main.admin_main').hide();
    });
} */