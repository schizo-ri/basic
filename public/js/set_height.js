$( window ).resize(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    if(body_width > 1200) {
        if(body_height < 1000) {
            $('.header_nav').css('height', 'fit-content');
        } 
      
        var header_height = $('header').height();
        var container_height = body_height - header_height;
        $('.container').height(container_height - 30);

        var salary_height = $('.salary').height();
        var efc_height = $('.efc').height();
        $('.layout_button').height(salary_height-efc_height-20);
        var calendar_height = $('section.calendar>div').height();
        var h2_height = $('section.calendar>div>h2').height();
        var cal_days_height = $('.cal_days').height();

        $('section.calendar>div>.comming_agenda').height(calendar_height-h2_height-35-cal_days_height);
    }
   
    
});
$( document ).ready(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    if(body_width > 1200) {
        if(body_height < 1000) {
            $('.header_nav').css('height', 'fit-content');
        } 
        var header_height = $('header').height();
        var container_height = body_height - header_height;
        $('.container').height(container_height - 30);

        var salary_height = $('.salary').height();
        var efc_height = $('.efc').height();
        $('.layout_button').height(salary_height-efc_height-20);

        var calendar_height = $('section.calendar>div').height();
        var h2_height = $('section.calendar>div>h2').height();
        var cal_days_height = $('.cal_days').height();

        $('section.calendar>div>.comming_agenda').height(calendar_height-h2_height-35-cal_days_height);
        
    }

    if(body_width < 768) {
      //  $('.container').height( $('.container').height());
    }
  
});

