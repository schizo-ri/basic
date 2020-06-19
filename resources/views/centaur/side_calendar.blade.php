<link rel="stylesheet" href="{{ URL::asset('node_modules/pg-calendar/dist/css/pignose.calendar.css') }}" />
<?php 
    use App\Http\Controllers\DashboardController;
 
?>
 @if(Sentinel::getUser()->employee)
<div class="col-12 calendar_main">
    {{-- <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}" title="{{ __('calendar.add_event')}}" rel="modal:open">
        <i style="font-size:11px" class="fa">&#xf067;</i>
    </a> --}}
    @if(Sentinel::getUser()->hasAccess(['tasks.create']) || in_array('tasks.create', $permission_dep))
        <a href="#event_show" rel="modal:open">
            <span class="btn btn-primary btn-lg btn-new" id="add_event" title="{{ __('calendar.add_event')}}" ><i style="font-size:11px" class="fa">&#xf067;</i></span>
        </a>
        <div class="event_show" id="event_show">
            <a href="#" rel="modal:close" class="close_event_show"><i class="fas fa-times"></i></a>
            <div class="" >
                <a class="" href="{{ route('events.create',['type', 'event']) }}"  rel="modal:open">
                    <h3><span class="blue"></span>@lang('calendar.add_event')</h3>
                    <p>@lang('calendar.create_your_event')</p>
                </a>
            </div>
            <div>
                <a class="" href="{{ route('tasks.create',['type', 'task']) }}"  rel="modal:open">
                    <h3><span class="orange"></span>@lang('calendar.add_task')</h3>
                    <p>@lang('calendar.save_any_task')</p>
                </a>
            </div>
        </div>
    @else
        <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create') }}" title="{{ __('calendar.add_event')}}" rel="modal:open">
            <i style="font-size:11px" class="fa">&#xf067;</i>
        </a>
    @endif
    <div class="calender_view">
    </div>
</div>
<div class="col-12 day_events">
    <div class="col-12">
        <h6>Global events <a class="three_dots" rel="modal:open" href="{{ route('all_event', ['dataArr_day' => $dataArr_day, 'uniqueType' => $uniqueType, 'dan' => $dan] ) }}" > @lang('basic.view_all')</a></h6>
        @if (isset($dataArr_day)  && count($dataArr_day) > 0)
            @if(isset($uniqueType) && count($uniqueType)>0)
                @foreach ($uniqueType as $type)
                    @php
                        $x = 0;
                    @endphp
                    <p class="day_events_data">
                        <span class="event_type" >{{ ucfirst($type)  }}</span>
                        @if (isset($dataArr_day) && count($dataArr_day) >0 )
                            @foreach ($dataArr_day as $data_day)
                                @if ( $data_day['name'] != 'holiday')
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
                                        <span class="event_user_img">
                                            @if($image)
                                                <img class="profile_img radius50 float_left" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image)) }}" alt="Profile image"  />
                                            @else
                                                <img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
                                            @endif
                                        </span>
                                        {{ $data_day['employee'] }}
                                    </span>
                                    @php
                                        $x++;
                                    @endphp
                                @endif
                            @endif
                               
                            @endforeach		
                        @endif
                    </p>
                @endforeach
            @endif
        @else
            <div class="placeholder">
                <img class="" src="{{ URL::asset('icons/placeholder_calendar.png') }}" alt="Placeholder image" />
                <p><span>@lang('basic.no_schedule')</span></p>
            </div>
        @endif
</div>
</div>
<script>
    $(function() {
        $('.calender_view').pignoseCalendar({
        });
    });
    $.getScript( '/../js/event.js');
</script>
@endif