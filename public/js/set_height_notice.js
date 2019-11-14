$( window ).resize(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    if(body_width > 450) {
        var all_notice_height = $('.all_notices').height();
        var header_notice_height = $('.header_notice').height();
        var section_notice = all_notice_height - header_notice_height;

        $('.section_notice').height(section_notice - 30);
    }
    var all_height = [];
    $('.noticeboard_notice_body .ad_main').each(function(){
        all_height.push($(this).height());
    });

    var max_height = all_height[0];
    $('.noticeboard_notice_body .ad_main').height(max_height);
});
$( document ).ready(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    if(body_width > 450) {
        var all_notice_height = $('.all_notices').height();
        var header_notice_height = $('.header_notice').height();
        var section_notice = all_notice_height - header_notice_height;

        $('.section_notice').height(section_notice - 30);
    }

    var all_height = [];
    $('.noticeboard_notice_body .notice_title').each(function(){
        all_height.push($(this).height());
    });
    all_height.sort(function(a, b) {
        return b-a;
    });
    var max_height = all_height[0];
    $('.noticeboard_notice_body .notice_title').height(max_height);

});

