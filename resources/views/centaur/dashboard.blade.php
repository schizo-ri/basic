@extends('Centaur::layout')

@section('title', 'Dashboard')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        var Calendar = FullCalendar.Calendar;
        var Draggable = FullCalendarInteraction.Draggable;

        var containerEl = document.getElementById('external-events');
        var calendarEl = document.getElementById('calendar');
        var checkbox = document.getElementById('drop-remove');
        let project_id = null;
        // initialize the external events
        var url_basic = location.origin + '/dashboard';
        var url_update;
        var employee_id = null;
        var date = null;
        var events = JSON.parse( $('.dataArr').text());
        var drop_el;
        // -----------------------------------------------------------------

        new Draggable(containerEl, {
            itemSelector: '.fc-event',
            eventData: function(eventEl) {
                return {
                    title: eventEl.innerText
                };
            }
        });

        // initialize the calendar
        // -----------------------------------------------------------------

        var calendar = new Calendar(calendarEl, {
            events: events,
            firstDay:1,
            selectable: true,
            plugins: [ 'interaction', 'dayGrid' ],
            locale: 'hr',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
            },
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar

            drop: function(info) {
                if (checkbox.checked) {
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                }
                drop_el = info.draggedEl;
                employee_id = $(info.draggedEl).attr('id');
                date = info.date.getFullYear() + '-' + (info.date.getMonth() + 1) + '-' + info.date.getDate();      
            },
            eventMouseLeave: function(info) {
               project_id = info.event.id;
               if(employee_id && date && project_id ) {
                    var url_store = 'save/' + employee_id  + '/' + date + '/' + project_id; 
                    var url_tmp = location.href;
                    window.history.replaceState({}, document.title, url_basic);
                    $.ajax({ url:  url_store, success: function(data) {
                     //   console.log(data);
                    }, 
                    error: function(xhr,textStatus,thrownError) {
                     //   alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    
                    }, dataType: "json"});

                    url_update = location.origin + '/dashboard/?date=' + date;
                    window.history.replaceState({url_basic}, document.title, url_basic + '/?date=' + date );
            //      $('#external-events').load( url_update + ' #external-events .resource');

                    $.ajax({
                        type: 'GET',
                        url: url_update,
                        dataType: 'text',
                        data: {
                            '_token':  $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#external-events').load( url_update + ' #external-events .resource');
                           console.log(info.event.title);
                        },
                        error: function(xhr,textStatus,thrownError) {
                            alert(xhr + "\n" + textStatus + "\n" + thrownError);
                        
                        }
                    });

                }
              
               project_id = null;
               employee_id = null;
               date = null;
            
            },
            dateClick: function(info) {
                date = info.dateStr
                url_update = location.origin + '/dashboard/?date=' + date;
                window.history.replaceState({url_basic}, document.title, url_basic + '/?date=' + date );
           //      $('#external-events').load( url_update + ' #external-events .resource');

                $.ajax({
                    type: 'GET',
                    url: url_update,
                    dataType: 'text',
                    data: {
                        '_token':  $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#external-events').load( url_update + ' #external-events .resource');
                    },
                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    
                    }
                });
            },
            eventRender: function() {
                var d = new Date();
                var today = d.getFullYear() + '-' + (d.getMonth() +1) + '-' + d.getDate();

                var url = location.origin + '/dashboard/?date=' + today;
                window.history.replaceState({}, document.title, url);
                if( window.location.href == url ) {
                    //
                } else {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        dataType: 'text',
                        data: {
                            '_token':  $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#external-events').load( url + ' #external-events .resource');
                        },
                        error: function(xhr,textStatus,thrownError) {
                            alert(xhr + "\n" + textStatus + "\n" + thrownError);
                        
                        }
                    });

                   // window.history.replaceState({}, document.title, url);
                  //  window.location.replace(url);
                    
                }
            }
        });
       
        calendar.render();
    });


</script>
@section('content')
<div class="row">
    @if (Sentinel::check())
        <div class='btn-toolbar pull-right'>
            <a href="{{ route('projects.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
        </div>
        <div id='external-events'>
            <div class="resource">
            <p>
                <a href="{{ route('employees.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
            </p>
            
                @foreach ($employees as $employee)
                    @if(! $project_employees->where('employee_id', $employee->id )->first())
                        <div class='fc-event' id="{{$employee->id }}">{{ $employee->last_name . ' ' . $employee->first_name }} </div>
                    @endif
                @endforeach
            
            <p>
                <input type='checkbox' id='drop-remove' />
                <label for='drop-remove'>remove after drop</label>
            </p>

            </div>
        </div>  
       

        <div class="" style="float:left; width:75%;padding-left: 30px;">
            <div id='calendar'></div>
        </div>
        <div>


        </div>
        <div hidden class="dataArr">{!! json_encode($dataArr) !!}</div>
    @else
        
    @endif

    <?php
        $user = Sentinel::findById(1);

        // var_dump(Activation::create($user));
    ?>
</div>
@stop