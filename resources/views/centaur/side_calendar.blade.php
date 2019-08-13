<div class="col-12 calendar_main">
    @if(Sentinel::getUser()->employee)
        <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}"  rel="modal:open">
            <i style="font-size:11px" class="fa">&#xf067;</i>
        </a>
    @endif
    <div class="calender_view">
    </div>
</div>

<script>
    $(function() {
        $('.link_event').css('color','orange');
        var url_basic = location.origin + location.pathname;
        var data =  <?php echo json_encode($dataArr); ?>;
        var data1 = [];
        for (i = 0; i < data.length; i++) { 
            var txt = '{"name": "' + data[i].name + '","date":"' + data[i].date + '"}';
            data1.push(JSON.parse(txt));
        }
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
                            /*  promjena datum +1 dan !!!!!!!!!!!!!!!!
                            console.log(datum)
                            var newDate = new Date(datum);
                            console.log(newDate)
                            
                            newDate.setDate(newDate.getDate() + 1);
                            console.log(newDate)
                            */
                            var url = url_basic + '?dan=' + datum;
                            $('.index_main .header_calendar').load(url + ' .index_main .header_calendar>div');
                            
                            $('.index_main .main_calendar').load(url + ' .index_main .main_calendar .all_events');
                            $('.index_main .main_calendar .all_events').load(url + ' .index_main .main_calendar .all_events .show_event');
                            
                        }
                    }
                }
        });
    });
</script>
<link rel="stylesheet" href="{{ URL::asset('node_modules/pg-calendar/dist/css/pignose.calendar.css') }}" />
<script src="{{ URL::asset('node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>