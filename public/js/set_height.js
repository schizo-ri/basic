$( document ).ready(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
    var padding_calendar = user_header - section_top_nav_height + 15;
/* 
    console.log("padding_calendar " + padding_calendar);
    console.log("user_header " + user_header);
    console.log("section_top_nav_height " + section_top_nav_height); */
    

    if(body_width > 768  ) {
        $('.container').height(body_height - header_height - 30);
        if(padding_calendar > 0) {
            $('.container .calendar').css('padding-top', padding_calendar);
            $('.container .posts').css('padding-top', padding_calendar);
        } else {
            $('.container .calendar').css('padding-top', '20px');
            $('.container .posts').css('padding-top', '20px');
        }
        
        var button_w = $('.layout_button span.img.all_req').width();
        $('.layout_button span.img.all_req').height(button_w); 

       // $('.salary').height(user_header_info_height);

    }
    //$('.container >section').height(body_height - header_height );
    /* 
    if(body_width > 768  ) {
      
        if ( body_height <= 600 ){
            $('body').css('overflow','auto');
            $('.container').css('height','auto');
            $('.container .calendar>div').css('height','auto');
            $('.container .posts .all_post').css('height','auto');
            $('.container .calendar').css('height','auto');
            $('.container .posts').css('height','auto');

            container_height = $('.container .calendar').height();
            posts_height = $('.container .posts').height();
           
            if(container_height > posts_height) {                
                $('.container .posts').height(container_height);
                $('.container .posts .all_post').css('height','100%');
            } else {                
                $('.container .calendar>div').height(posts_height);
            }

        } else {
            var header_height = $('header').height();
            container_height = body_height - header_height - 15;
            var container_element_height = body_height - 90 - user_header - 20;
            if(body_width < 1050  ) {
                $('.container').height(container_height);
                $('.container .calendar').css('padding-top', '30px');
                $('.container .posts').css('padding-top', '30px');
            } else {
                $('.container').height(container_height - 15);
            }
           
        //    $('.container .calendar').height(container_element_height);
        //    $('.container .posts').height(container_element_height);
          
            var calendar_height = $('section.calendar>div#calendar').height();
            var h2_height = $('section.calendar>div>h2').height();
            var cal_days_height = $('.cal_days').height();
         
            var comming_agenda_height = calendar_height-h2_height-cal_days_height;
            $('section.calendar>div>.comming_agenda').height(comming_agenda_height);
           
        //    $('.comming_agenda .placeholder').height(comming_agenda_height - $('.comming_agenda btn-new').height() );
        }

    } else if(body_width < 768  ) {
        var user_header_width = $('.user_header').width();
       
        $('.container .noticeboard').width(user_header_width);
        $('.container .calendar').width(user_header_width);
        $('.container .posts').width(user_header_width);
    } 
   

    var button_w = $('.layout_button span.img.all_req').width();
    $('.layout_button span.img.all_req').height(button_w); */
});


$( window ).resize(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
    var padding_calendar = user_header - section_top_nav_height + 15;
    if(body_width > 768  ) {

        $('.container').height(body_height - header_height -30);
        $('.container .calendar').css('padding-top', padding_calendar);
        $('.container .posts').css('padding-top', padding_calendar);

        var button_w = $('.layout_button span.img.all_req').width();
        $('.layout_button span.img.all_req').height(button_w); 

       // $('.salary').height(user_header_info_height);

    }
}); 