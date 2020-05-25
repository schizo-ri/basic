// on load
$( document ).ready(function() {
    // placeholder text
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

    var mouse_is_inside = false;

    $('.search_input').hover(function(){ 
        mouse_is_inside=true; 
    }, function(){ 
        mouse_is_inside=false; 
    });

    $("body").mouseup(function(){ 
        if(! mouse_is_inside) 
            $('.search_input').hide();

    });

    var url = location.search;
    var body_width = $('body').width();

    if(body_width > 768) {
        if( url ) {
            var id = url.replace("?id=", "");
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
    $(this).addClass('active');
    var comment = $(this).find('input#user_id').val();
    var post_content = $('form.active .post-content').val();
    var form = $(this);
    var url = form.attr('action');
    e.preventDefault();
    var data = form.serialize();
    var url = '/comment/store';
    var post = form.attr('method');
    var umetni = $(this);
     $('.post-content').val('');
    $.ajax({
        type : post,
        url : url,
        data : data,
        success:function(msg) {
           
        }
    })
});


var refresh_height;
var mess_comm_height;
var comment_height;

$( '.tablink' ).on( "click", function () {
    var post_id = $( this ).attr('id');
    var tab_id = '_' + post_id;
    var body_width = $('body').width();
    setPostAsRead(post_id);
    $('.refresh.'+tab_id).find('.message').last().addClass('last');
    $( 'button_nav_img').load( location.origin + '/posts .button_nav_img .line_btn');

    if(body_width < 768) {
        $('.latest_messages').hide();
        $('.posts_index .index_main').show();
    }
    $(".tabcontent").each(function() {
        if($(this).attr('id') != tab_id ) {
            $(this).hide();
        } else {
            $(this).show();
            
            refresh_height = $(this).find('.refresh').height();
            comment_height = $(this).find('.comments').height();
          /*   if(body_width <= 768) { 
                $(this).find('.mess_comm').height(refresh_height);                
            } else {
                 $(this).find('.mess_comm').height(comment_height);
            }  */
        

            if(refresh_height > comment_height ) {
                $(this).find('.refresh').css({"position": "static", "bottom": "0", "height": "100%"});
                $(this).find('.refresh').scrollTop(refresh_height);
            } else {
                $(this).find('.mess_comm').scrollTop(refresh_height);
            }
        }
        //$(this).find('last.post-content').focus();
    });
    
    (function poll(){
        var date = new Date();
        var url = location.origin;  // http://localhost:8000
       
        var url_update = "posts/" + post_id + "/" + date.getFullYear() + '/' +  (date.getMonth() +1) + '/' + date.getDate() + '/' + date.getHours()  + '/' + date.getMinutes() + '/' + date.getSeconds();
       
        setTimeout(function(){
            $.ajax({ url: url+"/"+url_update, success: function(data) {
                // Update your dashboard gauge
                if(data) {
                    $( '.refresh.' + tab_id ).load( url + '/posts .refresh.' + tab_id + ' .message',function(){
                        mess_comm_height = $("#" + tab_id ).find('.mess_comm').height();

                        refresh_height = 90;
                        $('.refresh.' + tab_id + ' .message').each( function() {
                            refresh_height+= $(this).height();
                        });
                      
                        comment_height = $("#" + tab_id ).find('.comments').height();
                        //   $('.refresh').height(mess_comm_height);
                    
                        if(refresh_height < mess_comm_height ) {
                            $("#" + tab_id ).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
                        }
                        if(refresh_height > comment_height ) {
                            $("#" + tab_id ).find('.refresh').css({"position": "static", "bottom": "0", "height": "100%"});
                            $("#" + tab_id ).find('.refresh').scrollTop(refresh_height);
                        } else {
                            $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
                        }
                    
                        $('.all_post ').load(  url + '/posts .all_post .main_post');
                        $( '.button_nav_img').load( url + '/posts .button_nav_img .line_btn');
                       
                        if($('.tablink#post_id').find('.count_coments')) {
                            setPostAsRead(post_id);
                        }
                    });
                }
              /*   $( '.topnav>.div_posts').load( url + '/posts .topnav>.div_posts .posts_button'); */
                //Setup the next poll recursively
                poll();
            }, dataType: "json"});
        }, 100);
     })();

    $( this).find('.count_coments').remove();
});

function setPostAsRead(post_id) {   
    var url_read = "setCommentAsRead/" + post_id;
    try {
        $.ajax({
            type: "GET",
            url: url+"/"+url_read, 
            success: function(response) {
             
            } 
        });
    } catch (error) {

    }
}