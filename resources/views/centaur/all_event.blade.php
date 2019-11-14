@php
    use App\Http\Controllers\DashboardController;    
    if(count($events)>0) {
		$events_day = $events->where('date', $dan);
    }
@endphp
<div class="modal-header">
    <span class="event_type" >{{ date('d.m.Y',strtotime( $dan)) }}</span>
</div>
<div class="modal-body">
    @if($dataArr_day && $dataArr_day != null)
        @foreach ($uniqueType as $type)
            <p class="day_events_data">
                <p class="event_type" >{{ ucfirst($type)  }}</p>
                @foreach ($dataArr_day as $data_day)
                @php
                    $image = '';
                    $user_name = '';
                    if($data_day['employee_id']) {
                        $image = DashboardController::profile_image($data_day['employee_id']);
                        $user_name =  DashboardController::user_name($data_day['employee_id']);
                    }
                @endphp
                    @if ($data_day['type'] == $type )
                        <span class="event_user">
                            @if($image)
                                <img class="profile_img radius50 float_left" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image)) }}" alt="Profile image"  />
                            @else
                                <img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                            @endif
                            {{ $data_day['employee'] }}
                        </span><br>
                    @endif
                @endforeach						
            </p>
        @endforeach
    @endif
    @if(isset($events_day) && count($events_day) > 0)
        <p class="day_events_data user_events">
            <p class="event_type" >@lang('calendar.event')</p>
            @foreach ($events_day as $event)
                <span class="event_user">
                    {{ date("H:i", strtotime($event->time1)) . ':' . date("H:i", strtotime($event->time2)) . '-' . $event->title . ', ' . $event->description }}
                </span>   
            @endforeach
        </p>     			
    @endif
</div>