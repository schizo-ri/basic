// on load
if( $('.posts_index').length > 0) {

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
    
    $(function() {
        broadcastingPusher();
        tablink_on_click();
        submit_form (); 
        $('.placeholder').show();
        $( '.type_message' ).attr('Placeholder','Type message...');
    
        $('.type_message').on('focus',function(){
            $( this ).attr('Placeholder','');
        });
        $('.type_message').on('blur',function(){
            $( this ).attr('Placeholder','Type message...');
        });
        $('.search_post').on('click',function(){
            $('.search_input').show();     
            console.log("search_post");  
        });
        $('.search_input').on('hover',function(){ 
            mouse_is_inside=true; 
        }, function(){ 
            mouse_is_inside=false; 
        });
        $("body").on('mouseup',function(){ 
            console.log(mouse_is_inside);  
            if(! mouse_is_inside) 
                $('.search_input').hide();
        });
        url = location.search;
       
        if(body_width > 768 && location.href.includes('/posts') ) {
            if( url ) {
                id = url.replace("?id=", "");
                $('.tablink#' + id ).trigger('click');
            } else {
                $('.tablink').first().trigger('click');
            }
        }
    });
    
    // on submit ajax store
    function submit_form () {
        $('.form_post').on('submit',function(e){
            e.preventDefault();
            if( $(this).find('.post-content').val() == '' ) {
                return false;
            } else {
                form = $(this);
                data = form.serialize();
                
                url = '/comment/store';
                post_id = $(this).find('input[name=post_id]').val();
                content = $(this).find('textarea[name=content]').val();
                tab_id = '_' + post_id;
                
                $('.post-content').val('');
                $('.refresh.'+tab_id).append('<b><div class="message"><div class="right"><p class="comment_empl"><small>sada</small></p><div class="content"><p class="comment_content" >'+content+'</p></div></div></div><b>');
               
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
                        $.get(location.origin + '/posts', function(data, status){
                            var content =  $('.posts>.all_post',data ).get(0).outerHTML;
                            $( '.posts').html( content );
                            var content2 =  $( '.posts_button .button_nav_img .line_btn',data ).get(0).outerHTML;
                            $( '.posts_button .button_nav_img').html( content2 );
                            var content3 =  $('.index_main>section',data ).get(0).outerHTML;
                            $( '.index_main' ).html( content3 );
                            $('.tabcontent#'+tab_id).show();
                            broadcastingPusher();
                            submit_form (); 
                            refreshHeight(tab_id);
                            setPostAsRead(post_id);
                            
                        });
                   
                        $('.tablink#'+post_id).trigger('click');
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
          
            $('.post-content').css('line-height','70px');
        });
        
    }
    
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
        
            $.get(location.origin + '/posts', function(data, status){
                var content =  $('.posts>.all_post',data ).get(0).outerHTML;
                $( '.posts').html( content );
                var content2 =  $( '.posts_button .button_nav_img .line_btn',data ).get(0).outerHTML;
                $( '.posts_button .button_nav_img').html( content2 );
                var content3 =  $('.index_main>section',data ).get(0).outerHTML;
                $( '.index_main' ).html( content3 );
                $('.tabcontent#'+tab_id).show();
                broadcastingPusher();
                submit_form (); 
               
                if(post_id != undefined) {
                    refreshHeight(tab_id);
                    setPostAsRead(post_id);
                }
                $('.post_sent .link_back').on('click',function () {
                    $('.latest_messages').show();
                    $('.posts_index .index_main').hide();
                    console.log("link_back");
                });
            });
          
            $(active_tabcontent).find('.type_message ').trigger('focus');
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
                $('.tablink#'+post_id).load( location.href + ' .tablink#'+post_id+'>span',function(){
                    tablink_on_click();
                } );
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
       /*  Pusher.logToConsole = true; */
        var employee_id = $('#employee_id').text();
    
         var pusher = new Pusher('d2b66edfe7f581348bcc', {
                                cluster: 'eu'
                                }); 
        var channel = pusher.subscribe('message_receive');
        channel.bind('my-event', function(data) {
            console.log("data");
            console.log(data);
            console.log("pusher id employee " + data.show_alert_to_employee); //2
            console.log(data.comment);
            if(employee_id == data.show_alert_to_employee) {
                $('.all_post ').load(  location.origin + '/posts .all_post .main_post');
                $( '.posts_button .button_nav_img').load( location.origin + '/posts .posts_button .button_nav_img .line_btn');
                $( '.refresh.' + tab_id ).load( location.origin + '/posts .refresh.' + tab_id + ' .message',function(){
                });
            }
        }); 
    }
    
    function onKeyClick() {
      
        var key = window.event.keyCode;
        // If the user has pressed enter
        if (key === 13) {
            $('.post-content').css('line-height','unset');
        }
        else {
            return true;
        }
    }
}