$( document ).ready(function() {
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


$( window ).resize(function() {
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
   
}); 