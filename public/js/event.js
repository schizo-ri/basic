$(function() {
    var url_basic = location.origin + '/events';
    var calendar_main_height;
    var calendar_aside_height;
    var body_width = $('body').width();
    var view;
    var data1;
    if( $('.dataArr').text()) {
        var data1 = JSON.parse( $('.dataArr').text());
    }
   if( $('.calender_view').length >0) {
        $('.calender_view').pignoseCalendar({
        multiple: false,
        week: 1,
        weeks: [
            'Ned',
            'Pon',
            'Uto',
            'Sri',
            'Čet',
            'Pet',
            'Sub',
        ],
        monthsLong: [
            'Siječanj',
            'Veljača',
            'Ožujak',
            'Travanj',
            'Svibanj',
            'Lipanj',
            'Srpanj',
            'Kolovoz',
            'Rujan',
            'Listopad',
            'Studeni',
            'Prosinac'
        ],
        months: [
            'Sij',
            'Velj',
            'Ožu',
            'Tra',
            'Svi',
            'Lip',
            'Srp',
            'Kol',
            'Ruj',
            'Lis',
            'Stu',
            'Pro'
        ],
        controls: {
                ok: 'ok',
                cancel: 'poništi'
        },
        init: function(context) {
            calendar_aside_height = $('.calendar_aside').height();
            calendar_main_height = $('.calendar_main').height();
            if($('body').width() > 450 && $('body').height() < 768) {
                $('.index_aside .day_events').height('fit-content');   
            } else if($('body').width() > 450) {
                $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 110 );   
            } else {
              //  $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 60 );
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
                locco: '#009933',
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
                    view = $('.change_view_calendar').val();
                    
                    var d = new Date(datum);
                    if( d != 'Invalid Date') {
                        var url = url_basic + '?dan=' + datum;
                        get_url(url, datum);
                    } 
                    /*    if(body_width < 768) {
                        $('.index_main.index_event').modal();
                    }   */
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
            var d = new Date(searchDate);
            /*  $('.pignose-calendar-unit-date').find('[data-date="' + searchDate + '"] > a' ).click(); */
            if( d != 'Invalid Date') {
                var url = url_basic + '?dan=' + searchDate;
            
                get_url(url, searchDate);
            }
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
            var d = new Date(searchDate);
            if( d != 'Invalid Date') {
                var url = url_basic + '?dan=' + searchDate;

                get_url(url, searchDate);
            }
        }   
        });
   }
    
   $('.index_aside .day_events').show();

    function get_url(url, datum ) {
        $.get(url, { dan: datum }, function(data, status){
            var content =  $('.day_events>div',data ).get(0).outerHTML;
            $( ".day_events" ).html( content );
            $('.index_aside .day_events').show();
            var content_2 = $('.index_event>section',data ).get(0).outerHTML;
            $( ".index_event" ).html( content_2 );
            /* var content_3 = $('.calender_view>.pignose-calendar ',data ).get(0).outerHTML;
            $( ".calender_view" ).html( content_3 );  */
            $('.main_calendar_month tbody td').on('click',function(){
                var date = $(this).attr('data-date');
                $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).trigger("click");
            });
        
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
            
            $( ".change_view_calendar" ).on('change',function() {
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
            
            $('button.show_loccos').on('click',function(e){
                e.preventDefault();
                $('.main_calendar td>a').toggle();
                $('.main_calendar .show_event').toggle();
                $('.main_calendar .show_locco ').toggle();
                $('.change_employee').toggle();
                $('.change_car').toggle();
            });
            
            var position_selected_day = $('.selected_day').position().top -30;
            $('.main_calendar_month').scrollTop(position_selected_day);
        
            select_view();
        });
    }
    function select_view() {
        if(view == 'day') {
            $('.change_view_calendar').val('day') ;
            $('.main_calendar').hide();
            $('.main_calendar_day').show();
            
        } else if(view == 'month') {
            $('.change_view_calendar').val('month') ;                                
            $('.main_calendar').hide();
            $('.main_calendar_month').show();
           
        } else if(view == 'week') {
            $('.change_view_calendar').val('week') ;
            $('.main_calendar').hide();
            $('.main_calendar_week').show();
        } else if(view == 'list') {
            $('.change_view_calendar').val('list') ;
            $('.main_calendar').hide();
            $('.main_calendar_week').show();
        }
    }
});