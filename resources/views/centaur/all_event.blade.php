@php
    use App\Http\Controllers\DashboardController;    
   
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
                    if(isset($data_day['employee_id']) && $data_day['employee_id']) {
                        $image = DashboardController::profile_image($data_day['employee_id']);
                        $user_name =  DashboardController::user_name($data_day['employee_id']);
                    }
                @endphp
                    @if ($data_day['type'] == $type )
                        <span class="event_user">
                            <span class="event_user_img">
                                @if($image)
                                    <img class="profile_img radius50 float_left" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image)) }}" alt="Profile image"  />
                                @else
                                    <img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
                                @endif
                            </span>
                            {{ $data_day['employee'] . ' - ' .  $data_day['type'] }}
                            @if (isset($data_day['title']))
                               {{ ' - ' .  $data_day['title'] }}
                            @endif
                            @if (isset($data_day['time1']))
                                {{ ' - ' . date('H:i', strtotime($data_day['time1'])) . '-' .  date('H:i', strtotime($data_day['time2'])) }}
                            @endif
                            @if (isset($data_day['car']))
                               {{ ' - ' .  $data_day['car'] }}
                            @endif
                        </span><br>
                    @endif
                @endforeach						
            </p>
        @endforeach
    @endif
   
</div>