$( document ).ready(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var user_header = $('.user_header').height();
    var container_height;
    var posts_height;
    
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
            $('.container').height(container_height - 15);
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
    $('.layout_button span.img.all_req').height(button_w);
});


$( window ).resize(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var user_header = $('.user_header').height();
    var container_height;
    var posts_height;
    
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
            } else {
                
                $('.container .calendar>div').height(posts_height -50);
            }

        } else {
            var header_height = $('header').height();
             container_height = body_height - header_height;
            var container_element_height = body_height - 90 - user_header - 20;
            $('.container').height(container_height - 15);
         //   $('.container .calendar').height(container_element_height);
         //   $('.container .posts').height(container_element_height);
          
            var calendar_height = $('section.calendar>div#calendar').height();
            var h2_height = $('section.calendar>div>h2').height();
            var cal_days_height = $('.cal_days').height();
        
            var comming_agenda_height = calendar_height-h2_height-cal_days_height;
            $('section.calendar>div>.comming_agenda').height(comming_agenda_height);
           
        //    $('.comming_agenda .placeholder').height(comming_agenda_height - $('.comming_agenda btn-new').height() );
           
        }
        

    } else if(body_width < 768 ) {
       
        var user_header_width = $('.user_header').width();
        $('.container .noticeboard').width(user_header_width);
        $('.container .calendar').width(user_header_width);
        $('.container .posts').width(user_header_width);
    } 
   

    var button_w = $('.layout_button span.img.all_req').width();
    $('.layout_button span.img.all_req').height(button_w);
});