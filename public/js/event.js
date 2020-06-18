$(function() {
    var url_basic = location.origin + '/events';
    var calendar_main_height;
    var calendar_aside_height;
    var body_width = $('body').width();
    
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
            if($('body').width() > 450) {
                $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 110 );   
            } else {
                $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 60 );
            }
                   
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
                        
                        $('.index_aside .day_events').load(url + ' .index_aside .day_events >div', function() {
                                                
                        });
                        if($('.main_calendar_day').is(":visible")) {
                            $('.index_event').load( url + ' .index_event>section', function() {
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
                                if(body_width > 768) {
                                    $('.header_calendar>div:first-child').removeClass('col-5');
                                    $('.header_calendar>div:first-child').addClass('col-10');
                                }
                                $('.header_calendar>div:nth-child(2)').hide();
                            });
                            
                            $('.main_calendar_month').load( url + ' .main_calendar_month>table', function() {
                                $.getScript( '/../restfulizer.js');
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
                                $('.main_calendar_month tbody td').click(function(){
                                    var date = $(this).attr('data-date');
                                    $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
                                });
                            });
                        }
                        if($('.main_calendar_week').is(":visible")) {
                            $('.header_calendar').load(url + ' .header_calendar >div', function() {
                                $('.change_view_calendar').val('week') ;
                                if(body_width > 768) {
                                    $('.header_calendar>div:first-child').removeClass('col-5');
                                    $('.header_calendar>div:first-child').addClass('col-10');
                                }
                                $('.header_calendar>div:nth-child(2)').hide();
                            });

                            $('.main_calendar_week').load( url + ' .main_calendar_week>table', function() {
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
                                $.getScript( '/../restfulizer.js');
                                $('.main_calendar_month tbody td').click(function(){
                                    var date = $(this).attr('data-date');
                                    $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).click();
                                });
                            });
                        }
                        if(body_width < 768) {
                            $('.index_main.index_event').modal();
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
                
                $('.index_event').load( url + ' .index_event>section', function() {
                    $.getScript( '/../restfulizer.js');
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
                });

                $('.index_event_month').load( url + ' .index_event_month>section', function() {
                    $.getScript( '/../restfulizer.js');
                   
                });
                console.log("prev");
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

                $('.index_event').load( url + ' .index_event>section', function() {
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
                    $.getScript( '/../restfulizer.js');
                });
                $('.index_event_month').load( url + ' .index_event_month>section', function() {
                    $.getScript( '/../restfulizer.js');
                 
                });
                console.log("next");
            }   
    });
    
    $('.index_aside .day_events').show();
    
    $.getScript( '/../js/open_modal.js'); 
   
});