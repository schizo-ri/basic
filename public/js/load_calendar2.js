console.log("load_calendar");
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
    $( ".change_employee" ).change(function() {
        var value = $(this).val().toLowerCase();
        console.log(value);
		$(".show_event").filter(function() {
			//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			$(this).toggle($(this).hasClass(value));
        });
        if(value == '') {
            $(".show_event").show();
        }
    });
    var view;
    $( ".change_view_calendar" ).change(function() {
		view = $( this ).val();
		if(view == 'day') {
		   $('.main_calendar_day').show();
		   $('.main_calendar_week').hide();
           $('.main_calendar_month').hide();
           $('.main_calendar_list').hide();
	   } 
	   if(view == 'week') {
		$('.main_calendar_day').hide();
		   $('.main_calendar_week').show();
           $('.main_calendar_month').hide();
           $('.main_calendar_list').hide();
       } 
       if(view == 'list') {
        $('.main_calendar_list').show();
        $('.main_calendar_day').hide();
        $('.main_calendar_week').hide();
        $('.main_calendar_month').hide();
    } 
	   if(view == 'month') {
			$('.main_calendar_day').hide();
		   $('.main_calendar_week').hide();
           $('.main_calendar_month').show();
           $('.main_calendar_list').hide();
	   }
	});
  
    $('.main_calendar_month tbody td').click(function(){
        var date = $(this).attr('data-date');
        $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
    });
});