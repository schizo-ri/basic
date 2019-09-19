$(function() {

    var day = $('.event_day .day').text();
    var month =   $('.event_day .month').text();
    var year =  $('.event_day .year').text();
    var day_of_week = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
    var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

    var currentDate = new Date(year + '-' + month + '-' + day);

    
    $(document).on('click', '.arrow .day_after', function(e) {
        e.preventDefault(); 
        var date_after = new Date(currentDate.setDate(currentDate.getDate() +1));
        var searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
        $('.event_day .day').text(date_after.getDate());
        $('.event_day .week_day').text(day_of_week[date_after.getDay()]);
        $('.month_year').text(monthNames[date_after.getMonth()] + ' ' + date_after.getFullYear());

        $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
    });
    $(document).on('click', '.arrow .day_before', function(e) {
        e.preventDefault(); 
        var date_before = new Date(currentDate.setDate(currentDate.getDate() -1));
        var searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
        $('.event_day .day').text(date_before.getDate());
        $('.event_day .week_day').text(day_of_week[date_before.getDay()]);
        $('.month_year').text(monthNames[date_before.getMonth()] + ' ' +  date_before.getFullYear());
        $('.pignose-calendar-body').find('[data-date="' + searchDate_bef + '"] > a' ).click();
    });


});
