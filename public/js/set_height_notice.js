$( window ).resize(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    if(body_width > 800) {
        var all_notice_height = $('.all_notices').height();
        var header_notice_height = $('.header_notice').height();
        var section_notice = all_notice_height - header_notice_height;

        $('.section_notice').height(section_notice - 30);
    }
   
});
$( document ).ready(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    if(body_width > 800) {
        var all_notice_height = $('.all_notices').height();
        var header_notice_height = $('.header_notice').height();
        var section_notice = all_notice_height - header_notice_height;

        $('.section_notice').height(section_notice - 30);
    }
});

