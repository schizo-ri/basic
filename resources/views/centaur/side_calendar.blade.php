<link rel="stylesheet" href="{{ URL::asset('node_modules/pg-calendar/dist/css/pignose.calendar.css') }}" />
<?php 
    use App\Models\Event;
    use App\Http\Controllers\EventController;
    use App\Http\Controllers\DashboardController;
    $events_day = '';
	if(isset($_GET['dan'])) {
		$dan = $_GET['dan'];
	} else {
		$dan = date('Y-m-d');
    }
    $empl = Sentinel::getUser()->employee;
    $events = Event::where('employee_id', $empl->id)->get();
	$dataArr_day = EventController::event_for_selected_day( $dan );

	$uniqueType = array_unique(array_column($dataArr_day, 'type'));
	
	if(count($events)>0) {
		$events_day = $events->where('date', $dan);
    }
?>
 @if(Sentinel::getUser()->employee)
<div class="col-12 calendar_main">
        <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}" title="{{ __('calendar.add_event')}}" rel="modal:open">
            <i style="font-size:11px" class="fa">&#xf067;</i>
        </a>
    <div class="calender_view">
    </div>
</div>
<div class="col-12 day_events">
    <div class="col-12">
        <a class="three_dots" rel="modal:open" href="{{ route('all_event', ['dataArr_day' => $dataArr_day, 'uniqueType' => $uniqueType, 'dan' => $dan] ) }}" > @lang('basic.view_all')</a>
        @if(count($uniqueType)>0)
            @foreach ($uniqueType as $type)
                @php
                    $x = 0;
                @endphp
                <p class="day_events_data">
                    <span class="event_type" >{{ ucfirst($type)  }}</span>
                    @foreach ($dataArr_day as $data_day)
                        @if ($data_day['type'] == $type && $x < 3 )
                            @php
                                $image = '';
                                $user_name = '';
                                if($data_day['employee_id']) {
                                    $image = DashboardController::profile_image($data_day['employee_id']);
                                    $user_name =  DashboardController::user_name($data_day['employee_id']);
                                }
                            @endphp
                            <span class="event_user">
                                @if($image)
                                    <img class="profile_img radius50 float_left" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image)) }}" alt="Profile image"  />
                                @else
                                    <img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                                @endif
                                {{ $data_day['employee'] }}
                            </span>
                            @php
                                $x++;
                            @endphp
                        @endif
                    @endforeach		
                    
                </p>
            @endforeach
            
        @endif
    @if(isset($events_day) && count($events_day) > 0)
        <p class="day_events_data">
            @foreach ($events_day as $event)
                <span class="event_type" >{!! $event->type ? ucfirst($event->type) : __('calendar.event') !!}</span>
                <span class="event_user">
                    {{ date("H:i", strtotime($event->time1)) . '-' . date("H:i", strtotime($event->time2))  . '-' . $event->title . ', ' . $event->description }}
                </span>   
            @endforeach
        </p>     			
    @endif
   
</div>
</div>
<script>
    $(function() {
        $('.calender_view').pignoseCalendar({
        });
    });
</script>
@endif