$(function() {
    var day = $('.event_day .day').text();
    var month =   $('.event_day .month').text();
   
    var year =  $('.event_day .year').text();
    var day_of_week = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
    var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

    var view;
    function daysInMonth (month, year) { 
        return new Date(year, month, 0).getDate(); 
    } 
    function daysInPrevMonth (month, year) { 
        var d=new Date(year, month, 0);
        d.setDate(1); 
        d.setHours(-1);
        return d.getDate();
    } 
    var currentDate_day = new Date(year + '-' + month + '-' + day);
    var currentDate_week = new Date(year + '-' + month + '-' + day);
    var currentDate_list = new Date(year + '-' + month + '-' + day);
    var currentDate_month = new Date(year + '-' + month + '-' + day);
    var days_in_month = daysInMonth(month, year);
    var days_in_prev_month = daysInPrevMonth(month,year);

    view = $( ".change_view_calendar" ).val();
    day_after();
    day_before();

    function day_after() {
        $(document).on('click', '.arrow .day_after', function(e) {
            e.preventDefault(); 
            var date_after;
            var searchDate;
            if(view == 'day') {
                date_after = new Date(currentDate_day.setDate(currentDate_day.getDate() +1));
                searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
            } else if(view == 'week') {
                date_after =  new Date(currentDate_week.setDate(currentDate_week.getDate() +7));
                searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate ()) ).slice(-2);
            } else if(view == 'list') {
                date_after = new Date(currentDate_list.setDate(currentDate_list.getDate() + days_in_month));
                days_in_month = daysInMonth(date_after.getMonth() +1, date_after.getFullYear());
               
                searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
            } else if(view == 'month') {
                date_after = new Date(currentDate_month.setDate(currentDate_month.getDate() + days_in_month));
                days_in_month = daysInMonth(date_after.getMonth() +1, date_after.getFullYear());
                searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
                $('.pignose-calendar-top-nav.pignose-calendar-top-next').click();
            }
            console.log(searchDate);
            /* $('.event_day .day').text(('0' + ( date_after.getDate()) ).slice(-2));
            $('.event_day .week_day').text(day_of_week[date_after.getDay()]);
            $('.month_year').text(monthNames[date_after.getMonth()] + ' ' + date_after.getFullYear()); */
            
            $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
        });
    }
    function day_before() {
        $(document).on('click', '.arrow .day_before', function(e) {
            e.preventDefault(); 
          
            var date_before;
            var searchDate_bef;
            if(view == 'day') {
                date_before = new Date(currentDate_day.setDate(currentDate_day.getDate() -1));
                searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
            } else if(view == 'week') {
                date_before =  new Date(currentDate_week.setDate(currentDate_week.getDate() -7));
                searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate ()) ).slice(-2);
            } else if(view == 'list') {
                date_before = new Date(currentDate_month.setDate(currentDate_month.getDate() - days_in_prev_month));
                days_in_prev_month = daysInPrevMonth(date_before.getMonth() +1, date_before.getFullYear());
                
                searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
            } else if(view == 'month') {
                date_before = new Date(currentDate_month.setDate(currentDate_month.getDate() - days_in_prev_month));
                days_in_prev_month = daysInPrevMonth(date_before.getMonth() +1, date_before.getFullYear());
                searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
                $('.pignose-calendar-top-nav.pignose-calendar-top-prev').click();
            }
            console.log(searchDate);
           /*  $('.event_day .day').text(('0' + ( date_before.getDate()) ).slice(-2)); */
           /*  $('.event_day .week_day').text(day_of_week[date_before.getDay()]); */
           /*  $('.month_year').text(monthNames[date_before.getMonth()] + ' ' +  date_before.getFullYear()); */
            
            $('.pignose-calendar-body').find('[data-date="' + searchDate_bef + '"] > a' ).click();
        });
    }
   
    $( ".change_employee" ).change(function() {
        var value = $(this).val().toLowerCase();
		$(".show_event").filter(function() {
			//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			$(this).toggle($(this).hasClass(value));
        });
        if(value == '') {
            $(".show_event").show();
        }
    });
    $( ".change_car" ).change(function() {
        var value = $(this).val().toLowerCase();
        $(".show_locco").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            
        });
        if(value == '') {
            $(".show_locco").show();
        }
        
    });
    $( ".change_view_calendar" ).change(function() {
        view = $( this ).val();

        if(view == 'day') {
           $('.main_calendar_day').show();
           $('.main_calendar_week').hide();
           $('.main_calendar_month').hide();
           $('.main_calendar_list').hide();
           $('button.show_locco').show();
         
           var scroll = $('.hour_val.position_8').position().top;
           $('.main_calendar_day').scrollTop(scroll);
        } 
        if(view == 'week') {
            $('.main_calendar_day').hide();
            $('.main_calendar_week').show();
            $('.main_calendar_month').hide();
            $('.main_calendar_list').hide();
            $('button.show_locco').show();
            var scroll = $('.main_calendar_week tr.position_8').position().top;
            $('.main_calendar_week').scrollTop(scroll);
        } 
        if(view == 'list') {
            $('.main_calendar_list').show();
            $('.main_calendar_day').hide();
            $('.main_calendar_week').hide();
            $('.main_calendar_month').hide();
            $('.change_car').hide();
            $('button.show_locco').hide();
        } 
       if(view == 'month') {
            $('.main_calendar_day').hide();
            $('.main_calendar_week').hide();
            $('.main_calendar_month').show();
            $('.main_calendar_list').hide();
            $('button.show_locco').show();
       }
    });
    $('.main_calendar_month tbody td').click(function(){
        var date = $(this).attr('data-date');
        $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
    });
    $('button.show_loccos').click(function(e){
        e.preventDefault();
        $('.main_calendar td>a').toggle();
        $('.main_calendar .show_event').toggle();
        $('.main_calendar .show_locco ').toggle();
        $('.change_employee').toggle();
        $('.change_car').toggle();

    });
   $('.selected_day a[rel="modal:open"]').click(function(){
        $.getScript( '/../restfulizer.js');
   });
});