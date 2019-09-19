$(function() {
    var url_basic = location.origin + '/events';

    var data1 = JSON.parse( $('.dataArr').text());
    /*
    var data1 = [];
    for (i = 0; i < data.length; i++) { 
        var txt = '{"name": "' + data[i].name + '","date":"' + data[i].date + '"}'
        data1.push(JSON.parse(txt));
    }
    */
    $('.calender_view').pignoseCalendar({
        multiple: false,
        scheduleOptions: {
            colors: {
                event: '#1390EA',
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
                        $('.index_main .header_calendar').load( url + ' .index_main .header_calendar > div');
                        $('.index_main .main_calendar').load(url + ' .index_main .main_calendar .all_events');
                        $('.index_main .main_calendar .all_events').load(url + ' .index_main .main_calendar .all_events .show_event');
                    }
                }
            }
    });
});