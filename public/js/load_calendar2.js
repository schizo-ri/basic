$(function() {
    var day = $('.event_day .day').text();
    var month =   $('.event_day .month').text();
    var year =  $('.event_day .year').text();
 
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

    day_after();
    day_before();

    var position_selected_day = $('.selected_day').position().top;
    $('.main_calendar_month').scrollTop(position_selected_day);
   
    function day_after() {
        $(document).on('click', '.arrow .day_after', function(e) {
            e.preventDefault(); 
            day = $('.event_day .day').text();
            month =   $('.event_day .month').text()
            year =  $('.event_day .year').text();
            currentDate_day = new Date(year + '-' + month + '-' + day);
            currentDate_week = new Date(year + '-' + month + '-' + day);
            currentDate_list = new Date(year + '-' + month + '-' + day);
            currentDate_month = new Date(year + '-' + month + '-' + day);
            days_in_month = daysInMonth(month, year);
            days_in_prev_month = daysInPrevMonth(month,year);

            var date_after;
            var searchDate;
            view = $( ".change_view_calendar" ).val();
            if(view == 'day') {
                date_after = new Date(currentDate_day.setDate(currentDate_day.getDate() +1));
                
                searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
                if(month < date_after.getMonth() +1 ) {
                    $('.pignose-calendar-top-nav.pignose-calendar-top-next').click();
                }
            } else if(view == 'week') {
                date_after =  new Date(currentDate_week.setDate(currentDate_week.getDate() +7));
               
                searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate ()) ).slice(-2);
                if(month < date_after.getMonth() +1 ) {
                    $('.pignose-calendar-top-nav.pignose-calendar-top-next').click();
                }
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
            
            /* $('.event_day .day').text(('0' + ( date_after.getDate()) ).slice(-2));
            $('.event_day .week_day').text(day_of_week[date_after.getDay()]);
            $('.month_year').text(monthNames[date_after.getMonth()] + ' ' + date_after.getFullYear()); */
            
            $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();
        });
    }

    function day_before() {
        $(document).on('click', '.arrow .day_before', function(e) {
            e.preventDefault(); 
            day = $('.event_day .day').text();
            month =   $('.event_day .month').text()
            year =  $('.event_day .year').text();
            currentDate_day = new Date(year + '-' + month + '-' + day);
            currentDate_week = new Date(year + '-' + month + '-' + day);
            currentDate_list = new Date(year + '-' + month + '-' + day);
            currentDate_month = new Date(year + '-' + month + '-' + day);
            days_in_month = daysInMonth(month, year);
            days_in_prev_month = daysInPrevMonth(month,year);
            var date_before;
            var searchDate_bef;
            view = $( ".change_view_calendar" ).val();
            if(view == 'day') {
                date_before = new Date(currentDate_day.setDate(currentDate_day.getDate() -1));
                searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
                if(month > date_before.getMonth() +1 ) {
                    $('.pignose-calendar-top-nav.pignose-calendar-top-prev').click();
                }
            } else if(view == 'week') {
                date_before =  new Date(currentDate_week.setDate(currentDate_week.getDate() -7));
                searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate ()) ).slice(-2);
                if(month > date_before.getMonth() +1 ) {
                    $('.pignose-calendar-top-nav.pignose-calendar-top-prev').click();
                }
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
          
           /*  $('.event_day .day').text(('0' + ( date_before.getDate()) ).slice(-2)); */
           /*  $('.event_day .week_day').text(day_of_week[date_before.getDay()]); */
           /*  $('.month_year').text(monthNames[date_before.getMonth()] + ' ' +  date_before.getFullYear()); */
            
            $('.pignose-calendar-body').find('[data-date="' + searchDate_bef + '"] > a' ).click();
        });
    }
   
    $( ".change_employee" ).on('change',function() {
        var value = $(this).val().toLowerCase();
		$(".show_event").filter(function() {
			//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			$(this).toggle($(this).hasClass(value));
        });
        $(".month_event").filter(function() {
			//$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			$(this).toggle($(this).hasClass(value));
        });
        if(value == '') {
            $(".show_event").show();
            $(".month_event").show();
        }
    });
    
    $( ".change_car" ).on('change',function() {
        var value = $(this).val().toLowerCase();
        $(".show_locco").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            
        });
        if(value == '') {
            $(".show_locco").show();
        }
        
    });
    
    var scroll_day;
    var scroll_week;
    $( ".change_view_calendar" ).on('change',function() {
        view = $( this ).val();
        
        if(view == 'day') {
            $('.main_calendar_day').show();
            $('.main_calendar_week').hide();
            $('.main_calendar_month').hide();
            $('.main_calendar_list').hide();
            $('button.show_locco').show();
            
            scroll_day = $('.hour_val.position_8').position().top;
            if(scroll_day != 0) {
                $('.main_calendar_day').scrollTop(scroll_day);
            }
        } 
        if(view == 'week') {
            $('.main_calendar_day').hide();
            $('.main_calendar_week').show();
            $('.main_calendar_month').hide();
            $('.main_calendar_list').hide();
            $('button.show_locco').show();
            scroll_week = $('.main_calendar_week tr.position_8').position().top;
            if(scroll_week != 0) {
                $('.main_calendar_week').scrollTop(scroll_week);
            }
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
    
    $('.main_calendar_month tbody td').on('click',function(){
        var date = $(this).attr('data-date');
        $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
    });
    
    $('button.show_loccos').on('click',function(e){
        e.preventDefault();
        $('.main_calendar td>a').toggle();
        $('.main_calendar .show_event').toggle();
        $('.main_calendar .show_locco ').toggle();
        $('.change_employee').toggle();
        $('.change_car').toggle();

    });
    
    $('.selected_day a[rel="modal:open"]').on('click',function(){
        $.getScript( '/../restfulizer.js');
    });
});