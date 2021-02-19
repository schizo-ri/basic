var prev_url = location.href;
var url_modul;
var date;



/* $(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header_height = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
   
   if(body_width > 990) {
      
        $('.container > .calendar').height(container_height - user_header_height -20);  
        $('.container > .posts').height(container_height - user_header_height -20);  
    }
});

$( window ).on('resize',function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header_height = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
    if(body_width>990) {
        $('.container > .calendar').height(container_height - user_header_height -20);  
        $('.container > .posts').height(container_height - user_header_height -20);  
    }
});  */

var section_top_width =  $('.section_top_nav').width();

function myTopNav() {
    var x = $(".section_top_nav");
    if (x.hasClass("responsive")) {
        x.removeClass("responsive");
    } else {
        x.addClass("responsive");
    } 
}

$('span.logo_icon').on('click',function(){
    $('.section_top_nav').css('width','250px');
    $('#myTopnav:not(".responsive")').css('display','block');
    $('#myTopnav:not(".responsive")').css('width','250px');
    $('.header_nav .section_top_nav .close_topnav svg').show();
});

$('.close_topnav').on('click',function(){
    $('.header_nav .section_top_nav .close_topnav svg').hide();
    $('#myTopnav:not(".responsive")').css('display','none');
    $('#myTopnav:not(".responsive")').css('width',0);
    $('.section_top_nav').css('width', 0);
});
var body_width = $('body').width();

if(body_width > 768) {
    $("body").on('click',function(){
        $('.close_topnav').trigger('click');
    });
    
    $(".logo_icon").on('click',function(event) {
        event.stopPropagation();
    });
    $(".section_top_nav").on('click',function(event) {
        event.stopPropagation();
    });
    var layout_button_width = $('div.layout_button').width();
    var count_layout_button =  $('.layout_button button').length;
   
    if( count_layout_button > 0) {
        var button_width = (layout_button_width / count_layout_button);
        $('.layout_button button').width(button_width -15);
        $('.layout_button button').css('min-width',button_width -15);
        $('.layout_button button').css('max-width',button_width -15);
        $('.layout_button button:last-child').width(button_width);
        $('.layout_button button:last-child').css('min-width',button_width);
        $('.layout_button button:last-child').css('max-width',button_width);
    }


}

$("a[rel='modal:open']").addClass('disable');

$(function() {
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

/* $('.evidention_check>form button').on('click',function(e){
    $(this).attr('disabled','disabled');
}); */


$('.form_evidention').on('submit',function(e){
    e.preventDefault();
   // $(this).hide();
    var url = location.origin + '/work_records';
    var form = $(this);
    form.find('button').attr('disabled','disabled');
    var data = form.serialize();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });     
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function( response ) {
            $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + response + '</div></div></div>').appendTo('body').modal();
            $('.header_nav').load(location.href + ' .header_nav .topnav',function(){
                $.getScript('/../js/nav_active.js');
            });
        }, 
        error: function(jqXhr, json, errorThrown) {
            console.log(jqXhr.responseJSON);
            $(this).show();
            var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                'message':  jqXhr.responseJSON.message,
                                'file':  jqXhr.responseJSON.file,
                                'line':  jqXhr.responseJSON.line };
            $.modal.close();
            $.ajax({
                url: 'errorMessage',
                type: "get",
                data: data_to_send,
                success: function( response ) {
                   $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Prijava nije uspjela, osvježi stranicuu i pokušaj ponovno" + '</div></div></div>').appendTo('body').modal();
                   
                }, 
                error: function(jqXhr, json, errorThrown) {
                    console.log(jqXhr.responseJSON); 
                }
            });
        }
    });
});

if(body_width < 450) {
    document.addEventListener("visibilitychange", function() {
        if (document.hidden){
        } else {
            location.reload();
        }
    });
}

$('body').on($.modal.OPEN, function(event, modal) {
    if( $('input[type=datetime-local]').length > 0 ) {
        $('input[type=datetime-local]').on('change',function(){
            date = new Date( $(this).val());

            if( date == 'Invalid Date') {
                $( '<div class="error_date danger">Neispravan unos datuma. Molim provjeri!</div>' ).modal();
                $('.btn-submit').attr('disabled', 'disabled');
            } else {
                $('.btn-submit').attr('disabled', false);
                $('.error_date').remove();
            }
        });
    }
    if( $('input[type=date]').length > 0 ) {
        $('input[type=date]').on('change',function(){
            date = new Date( $(this).val());

            if( date == 'Invalid Date') {
                $( '<div class="error_date danger">Neispravan unos datuma. Molim provjeriti</div>' ).modal();
                $('.btn-submit').attr('disabled', 'disabled');
            } else {
                $('.btn-submit').attr('disabled', false);
                $('.error_date').remove();
            }
        });
    }
});