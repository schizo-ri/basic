// on load
var form;
var data;
var url;
var post_id;
var tab_id;
var id;
var content;
var refresh_height;
var mess_comm_height;
var comment_height;
var body_width = $('body').width();
var mouse_is_inside = false;
var active_tabcontent;

$( document ).ready(function() {
 
    tablink_on_click();
    
    broadcastingPusher();
    $('.placeholder').show();
    $( '.type_message' ).attr('Placeholder','Type message...');

    $('.type_message').focus(function(){
        $( this ).attr('Placeholder','');
    });
    $('.type_message').blur(function(){
        $( this ).attr('Placeholder','Type message...');
    });
    $('.search_post').click(function(){
        $('.search_input').show();       
    });
    $('.search_input').hover(function(){ 
        mouse_is_inside=true; 
    }, function(){ 
        mouse_is_inside=false; 
    });
    $("body").mouseup(function(){ 
        if(! mouse_is_inside) 
            $('.search_input').hide();
    });
    url = location.search;
    if(body_width > 768) {
        if( url ) {
            id = url.replace("?id=", "");
            $('.tablink#' + id ).click();
        } else {
            $('.tablink').first().click();
        }
    }
});

$('.post_sent .link_back').click(function () {
    $('.latest_messages').show();
    $('.posts_index .index_main').hide();
});

// on submit ajax store
$('.form_post').on('submit',function(e){
    e.preventDefault();
    
    if($('.post-content').val() == '') {
        return false;
    } else {
        form = $(this);
        data = form.serialize();
        url = '/comment/store';
        post_id = $(this).find('input[name=post_id]').val();
        content = $(this).find('input[name=content]').val();
        tab_id = '_' + post_id;
        
        $('.post-content').val('');
        $('.refresh.'+tab_id).append('<b><div class="message"><div class="right"><p class="comment_empl"><small>sada</small></p><div class="content"><p class="comment_content" >'+content+'</p></div></div></div><b>');
        refreshHeight(tab_id);
        
        

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type : 'post',
            url : url,
            data : data,
            success:function(msg) {
                $('.all_post ').load(  location.origin + '/posts .all_post .main_post');
                $( '.posts_button .button_nav_img').load( location.origin + '/posts .posts_button .button_nav_img .line_btn');
                $( '.refresh.' + tab_id ).load( location.origin + '/posts .refresh.' + tab_id + ' .message',function(){
                
                    refreshHeight(tab_id);
                    tablink_on_click();

                    if($('.tablink#post_id').find('.count_coments')) {
                        setPostAsRead(post_id);
                    } 
                    
                });   
            },
            error: function(jqXhr, json, errorThrown) {
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };

                $.ajax({
                    url: 'errorMessage',
                    type: "get",
                    data: data_to_send,
                    success: function( response ) {
                        $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                    }, 
                    error: function(jqXhr, json, errorThrown) {
                        console.log(jqXhr.responseJSON); 
                        
                    }
                });
            }
        })
    }
});

function tablink_on_click() {
    $( '.tablink' ).on( "click", function () {
        
        post_id = $( this ).attr('id');
        tab_id = '_' + post_id;
        if(body_width < 768) {
            $('.latest_messages').hide();
            $('.posts_index .index_main').show();
        }
        $('.tabcontent').hide();
        active_tabcontent = $('.tabcontent#'+tab_id);
        $(active_tabcontent).show();
    
        $( this).find('.count_coments').remove();
    
        $('.all_post ').load(  location.origin + '/posts .all_post .main_post');
        $( '.posts_button .button_nav_img').load( location.origin + '/posts .posts_button .button_nav_img .line_btn');
        $( '.refresh.' + tab_id ).load( location.origin + '/posts .refresh.' + tab_id + ' .message',function(){
            if($('.tablink#post_id').find('.count_coments')) {
                setPostAsRead(post_id);
            } 
            tablink_on_click();
            
        });   
        $(active_tabcontent).find('.type_message ').focus();
        refreshHeight(tab_id);
    });
}


function setPostAsRead(post_id) {

    var url_read = location.origin +"/setCommentAsRead/" + post_id;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "GET",
        url: url_read, 
        success: function(response) {
        
        },
        error: function(jqXhr, json, errorThrown) {
            var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                'message':  jqXhr.responseJSON.message,
                                'file':  jqXhr.responseJSON.file,
                                'line':  jqXhr.responseJSON.line };

            $.ajax({
                url: 'errorMessage',
                type: "get",
                data: data_to_send,
                success: function( response ) {
                    $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                }, 
                error: function(jqXhr, json, errorThrown) {
                    console.log(jqXhr.responseJSON); 
                    
                }
            });
        }
    });
   
}

function refreshHeight(tab_id) {

     mess_comm_height = $("#" + tab_id ).find('.mess_comm').height();
    refresh_height = 90;
    $('.refresh.' + tab_id + ' .message').each( function() {
        refresh_height+= $(this).height();
    });
    comment_height = $("#" + tab_id ).find('.comments').height();
    if(refresh_height < mess_comm_height ) {
        $("#" + tab_id ).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
    }
    if(refresh_height > comment_height ) {
        $("#" + tab_id ).find('.refresh').css({"position": "static", "bottom": "0", "height": "100%"});
        $("#" + tab_id ).find('.refresh').scrollTop(refresh_height);
    } else {
        $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
    }
    $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
}

function broadcastingPusher () {

    // Enable pusher logging - don't include this in production
    /* Pusher.logToConsole = true; */
    var employee_id = $('#employee_id').text();

  /*   var pusher = new Pusher('ace40474cf33846103b6', {
                            cluster: 'eu'
                            }); */

    var channel = pusher.subscribe('message_receive');
    channel.bind('my-event', function(data) {
        console.log("pusher id employee " + data.show_alert_to_employee); //2
        console.log(data.comment);
        if(employee_id == data.show_alert_to_employee) {
            $('.all_post ').load(  location.origin + '/posts .all_post .main_post');
            $( '.posts_button .button_nav_img').load( location.origin + '/posts .posts_button .button_nav_img .line_btn');
            $( '.refresh.' + tab_id ).load( location.origin + '/posts .refresh.' + tab_id + ' .message',function(){
                refreshHeight(tab_id);

                if($('.tablink#post_id').find('.count_coments')) {
                    setPostAsRead(post_id);
                } 
            });   
        }
    }); 
}