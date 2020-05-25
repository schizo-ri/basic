$(function() {
    var url_basic = location.origin + '/events';
    var calendar_main_height;
    var calendar_aside_height;
    var data1;
    if( $('.dataArr').text()) {
        var data1 = JSON.parse( $('.dataArr').text());
    }
    
    /*
    var data1 = [];
    for (i = 0; i < data.length; i++) { 
        var txt = '{"name": "' + data[i].name + '","date":"' + data[i].date + '"}'
        data1.push(JSON.parse(txt));
    }
    */
    $('.calender_view').pignoseCalendar({
        multiple: false,
        init: function(contex) {
            calendar_aside_height = $('.calendar_aside').height();
            calendar_main_height = $('.calendar_main').height();
            $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 110 );          
        },
        scheduleOptions: {
            colors: {
                event: '#1390EA',
                task: '#eb0e0e',
                birthday: '#EA9413',
                GO: '#13EA90',
                IZL: '#13EA90',
                BOL: '#13EA90',
            }
        },
        schedules: data1,
            select: function(date, schedules, context) {
                /**
                 * @params this Element
                 * @params event MouseEvent
                 * @params context PignoseCalendarContext
                 * @returns void
                 */
                var $this = $(this); // This is clicked button Element.
                if(date[0] != null && date[0] != 'undefined') {
                    if(date[0]['_i'] != 'undefined' && date[0]['_i'] != null) {
                        var day = date[0]['_i'].split('-')[2];
                        var month = date[0]['_i'].split('-')[1]; // (from 0 to 11)
                        var year = date[0]['_i'].split('-')[0];
                        var datum = year + '-' + month + '-' + day;
                        var url = url_basic + '?dan=' + datum;
                        
                        $('.index_aside .day_events').load(url + ' .index_aside .day_events >div');

                        if($('.main_calendar_day').is(":visible")) {
                            $('.index_event').load( url + ' .index_event>section', function() {
                                $.getScript( '/../js/load_calendar2.js');
                                $.getScript( '/../restfulizer.js');
                                $('.main_calendar_month tbody td').click(function(){
                                    var date = $(this).attr('data-date');
                                    $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
                                });
                            });
                        }
                        if($('.main_calendar_month').is(":visible")) {
                            $('.header_calendar').load(url + ' .header_calendar >div', function() {
                                $('.change_view_calendar').val('month') ;
                                $('.header_calendar>div:first-child').removeClass('col-5');
                                $('.header_calendar>div:first-child').addClass('col-10');
                                $('.header_calendar>div:nth-child(2)').hide();
                            });
                           
                            $('.main_calendar_month').load( url + ' .main_calendar_month>table', function() {
                                $.getScript( '/../js/load_calendar2.js');
                                $.getScript( '/../restfulizer.js');
                                $('.main_calendar_month tbody td').click(function(){
                                    var date = $(this).attr('data-date');
                                    $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
                                });
                            });
                        }
                        if($('.main_calendar_week').is(":visible")) {
                            $('.header_calendar').load(url + ' .header_calendar >div', function() {
                                $('.change_view_calendar').val('week') ;
                                $('.header_calendar>div:first-child').removeClass('col-5');
                                $('.header_calendar>div:first-child').addClass('col-10');
                                $('.header_calendar>div:nth-child(2)').hide();
                            });

                            $('.main_calendar_week').load( url + ' .main_calendar_week>table', function() {
                                $.getScript( '/../js/load_calendar2.js');
                                $.getScript( '/../restfulizer.js');
                                $('.main_calendar_month tbody td').click(function(){
                                    var date = $(this).attr('data-date');
                                    $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
                                });
                            });
                        }
                    }
                }
            },
            prev: function(info, context) {
                // This is clicked arrow button element.
                var $this = $(this);

                // `info` parameter gives useful information of current date.
                var type = info.type; // it will be `prev`.
                var year = info.year; // current year (number type), ex: 2020
                var month = info.month; // current month (number type), ex: 2
                var day = info.day; // current day (number type), ex: 22
               
                // You can get target element in `context` variable.
                var element = context.element;

                // You can also get calendar element, It is calendar view DOM.
                var calendar = context.calendar;
               
                var prevDate = new Date(year + '-' + month + '-' + day);
                var month_before = prevDate.getMonth()+1; 
                var searchDate = year + '-' + ('0' + (month_before) ).slice(-2) + '-' + ('0' + (day)).slice(-2);
                
               /*  $('.pignose-calendar-unit-date').find('[data-date="' + searchDate + '"] > a' ).click(); */

                var url = url_basic + '?dan=' + searchDate;
               
                $('.index_aside .day_events').load(url + ' .index_aside .day_events >div');
               

                $('.index_event_month').load( url + ' .index_event_month>section');
                
                $('.index_event').load( url + ' .index_event>section', function() {
                    $.getScript( '/../restfulizer.js');
                   
                });

                $('.index_event_month').load( url + ' .index_event_month>section', function() {
                    $.getScript( '/../restfulizer.js');
                
                });
              
            },
            next: function(info, context) {
                /**
                 * @params context PignoseCalendarPageInfo
                 * @params context PignoseCalendarContext
                 * @returns void
                 */

                // This is clicked arrow button element.
                var $this = $(this);

                // `info` parameter gives useful information of current date.
                var type = info.type; // it will be `next`.
                var year = info.year; // current year (number type), ex: 2017
                var month = info.month; // current month (number type), ex: 6
                var day = info.day; // current day (number type), ex: 22
               
                // You can get target element in `context` variable.
                var element = context.element;

                // You can also get calendar element, It is calendar view DOM.
                var calendar = context.calendar;

                var currentDate = new Date(year + '-' + month + '-' + day);
                var month_after = currentDate.getMonth() +1; 
                var searchDate = year + '-' + ('0' + (month_after) ).slice(-2) + '-' + ('0' + (day)).slice(-2);                
               
                var url = url_basic + '?dan=' + searchDate;

                $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();

                $('.index_aside .day_events').load(url + ' .index_aside .day_events >div');
                $('.index_event_month').load( url + ' .index_event_month>section');

                $('.index_event').load( url + ' .index_event>section', function() {
                    $.getScript( '/../restfulizer.js');
                    

                });

                $('.index_event_month').load( url + ' .index_event_month>section', function() {
                    $.getScript( '/../restfulizer.js');
                   
                });
            }   
    });
    
    $('.index_aside .day_events').show();
    
    $.getScript( '/../js/open_modal.js'); 
   /*  $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    }; */
});