$(function() {
    var day = $('.event_day .day').text();
    var month =   $('.event_day .month').text();
    var year =  $('.event_day .year').text();
    var day_of_week = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
    var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

    var currentDate = new Date(year + '-' + month + '-' + day);
    
    $(document).on('click', '.arrow .day_after', function(e) {
        console.log("day_after");
        e.preventDefault(); 
        var date_after = new Date(currentDate.setDate(currentDate.getDate() +1));
        var searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
        $('.event_day .day').text(('0' + ( date_after.getDate()) ).slice(-2));
        $('.event_day .week_day').text(day_of_week[date_after.getDay()]);
        $('.month_year').text(monthNames[date_after.getMonth()] + ' ' + date_after.getFullYear());

        $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
    });
    $(document).on('click', '.arrow .day_before', function(e) {
        console.log("day_before");
        e.preventDefault(); 
        var date_before = new Date(currentDate.setDate(currentDate.getDate() -1));
        var searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
        $('.event_day .day').text(('0' + ( date_before.getDate()) ).slice(-2));
        $('.event_day .week_day').text(day_of_week[date_before.getDay()]);
        $('.month_year').text(monthNames[date_before.getMonth()] + ' ' +  date_before.getFullYear());
        $('.pignose-calendar-body').find('[data-date="' + searchDate_bef + '"] > a' ).click();
    });

    $( ".change_view_calendar" ).change(function() {
		var view = $( this ).val();
		
		if(view == 'day') {
		   $('.main_calendar_day').show();
		   $('.main_calendar_week').hide();
		   $('.main_calendar_month').hide();
	   } 
	   if(view == 'week') {
		$('.main_calendar_day').hide();
		   $('.main_calendar_week').show();
		   $('.main_calendar_month').hide();
		  
	   } 
	   if(view == 'month') {
			$('.main_calendar_day').hide();
		   $('.main_calendar_week').hide();
		   $('.main_calendar_month').show();
	   }
	});
   /*  $(document).on('click', '.arrow .month_after', function(e) {
        console.log("month_after");
        e.preventDefault(); 
        var next_month = new Date(currentDate.setMonth(currentDate.getMonth() +1));
        var searchDate = next_month.getFullYear() + '-' + ('0' + (next_month.getMonth() +1) ).slice(-2) + '-' + ('0' + ( next_month.getDate()) ).slice(-2);
        $('.month_year').text(monthNames[next_month.getMonth()] + ' ' + next_month.getFullYear());

        $('.pignose-calendar-top-next').click();
        $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
    });
    $(document).on('click', '.arrow .month_before', function(e) {
        console.log("month_before");
        e.preventDefault(); 
        var previous_month = new Date(currentDate.setMonth(currentDate.getMonth() -1));
        var searchDate = previous_month.getFullYear() + '-' + ('0' + (previous_month.getMonth() +1) ).slice(-2) + '-' + ('0' + ( previous_month.getDate()) ).slice(-2);
        $('.month_year').text(monthNames[previous_month.getMonth()] + ' ' + previous_month.getFullYear());
        $('.pignose-calendar-top-prev').click();
        $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
    }); */
    $('.main_calendar_month tbody td').click(function(){
        var date = $(this).attr('data-date');
        $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
    });
});