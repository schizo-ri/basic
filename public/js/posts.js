$( document ).ready(function() {
    // placeholder text
    $( document ).ready(function(){
    $( '.type_message' ).attr('Placeholder','Type message...');
    });
    $('.type_message').focus(function(){
        $( this ).attr('Placeholder','');
    });
    $('.type_message').blur(function(){
        $( this ).attr('Placeholder','Type message...');
    });
    $('.search_post').hover(function(){
		$('.latest_messages h1').hide();
		$('.search_input').show();
	});
	$('.search_input').mouseleave(function(){
        $( this ).hide();
        $('.latest_messages h1').show();
    });

    var url = location.search;

    if( url ) {
        var id = url.replace("?id=", "");
        $('.tablink#' + id ).click();
    } else {
        $('.tablink').first().click();
    }

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
        $.ajax({
            type : post,
            url : url,
            data : data,
            success:function(msg) {
                $('.post-content').val('');
            }
        })
    });

});
//
$( '.tablink' ).on( "click", function () {
    var post_id = $( this ).attr('id');
    var tab_id = '_' + post_id;
    
    var input;
    input = 'post-content' + tab_id;

    $(".tabcontent").each(function() {
        if($(this).attr('id') != tab_id ) {
            $(this).hide();
        } else {
            $(this).show();
            var mess_comm_height = $(this).find('.mess_comm').height();
            var refresh_height = $(this).find('.refresh').height();
            if(refresh_height < mess_comm_height ) {
                $(this).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
            }
        }
    });

    function loadlink(tab_id){
        var url = location.origin;  // http://localhost:8000/admin/posts/index#_172
        var div_id = tab_id;
        
        $.ajaxSetup ({
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });

        $.ajax({
            type: 'POST',
            url: url + '/posts/?id=' + post_id + '#' + div_id,
            dataType: 'text',
            data: {
                '_token':  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $( '.refresh.' + tab_id ).load( url + '/posts .refresh.' + tab_id + ' .message');

                var mess_comm_height = $("#" + tab_id ).find('.mess_comm').height();
                var refresh_height = $("#" + tab_id ).find('.refresh').height();
                if(refresh_height < mess_comm_height ) {
                    $("#" + tab_id ).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
                } 
              
                $('.tablink .main_post').load(url + '.tablink .main_post .tablink');
                
            },
            error: function(xhr,textStatus,thrownError) {
                alert(xhr + "\n" + textStatus + "\n" + thrownError);
               
            }
        });
    }

    setInterval(function(){
        try {
            loadlink(tab_id)
        } catch (error) {
            //
        }
       
    },1000);
   
});