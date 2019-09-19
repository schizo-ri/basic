@extends('Centaur::layout')

@section('title', 'Events')
<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('/../css/calendar.css') }}"/>

<?php 
	use App\Http\Controllers\EventController;

	if(isset($_GET['dan'])) {
		$dan = $_GET['dan'];
	} else {
		$dan = date('Y-m-d');
	}
	
	$count_days = EventController::countDays($dataArr, $dan);
	$selected = EventController::selectedDay( $dan);
	if(count($events)>0) {
		$events_day = $events->where('date', $dan);
	}
?>
@section('content')
<div class="index_page posts_index">
	<aside class="col-4 index_aside calendar_aside">
		@include('Centaur::side_calendar')
	</aside>
	<main class="col-8 index_main">
		<section>
			<header class="header_calendar">
				<div class="col-6 float_left padd_0">
				<span class="event_day">
					<span class="week_day">{{ $selected['week_day'] }}</span>
					<span class="day">{{ $selected['dan_select'] }}</span>
					<span class="month" hidden>{{ $selected['mj_select'] }}</span>
					<span class="year" hidden>{{ $selected['god_select'] }}</span>
				</span>
					<span class="arrow"><img class="img_button day_before" src="{{ URL::asset('icons/arrow_left.png') }}" alt="arrow"/><img class="img_button day_after" src="{{ URL::asset('icons/arrow_right.png') }}" alt="arrow"/></span>
					<span class="month_year">{{ $selected['month'] . ' ' .  $selected['god_select'] }}</span>
				</div>
				<div class="col-6 float_left padd_0">
					<span class="meeting col-4"><span class="blue"></span>Meeting<span>{{ $count_days['dani_event'] }}</span></span>
					<span class="tasks col-4"><span class="orange"></span>Birthdays<span>{{ $count_days['dani_rodjendani'] }}</span></span>
					<span class="on_vacation col-4"><span class="green"></span>On vacation<span>{{ $count_days['dani_odmor'] }}</span></span>
				</div>
			</header>
			<main class="main_calendar" >
				<div class="all_events">
					@foreach($hours_array as $hour)
						<div class="hour_in_day">
							<a href="{{ route('events.create', ['time1' => $hour, 'date' => $selected['god_select'] . '-' .  $selected['mj_select'] . '-' . $selected['dan_select'] ]) }}" rel="modal:open" ><span class="hour_val">{{ $hour}}</span></a>
							@if(isset($dan))
								@if( isset($events_day))
									@foreach($events_day->where('time1',  $hour . ":00")  as $event)
									<div class="show_event blue" >
										<div class="event">
											<p>{{ date('H:i',strtotime($event->time1)) . ' - ' . date('G:i',strtotime($event->time2)) }} {{ $event->title }}, {{ $event->description }}</p>
										</div>
									</div>
									@endforeach
								@endif
								@foreach($dataArr as $key => $data)
									@if($data['name'] != 'birthday' && $data['name'] != 'event')	
										@if( $data['date'] == $dan && $hour == "08:00")
											<div class="show_event green" >
												<div class="event">
													{{ isset($data['employee']) ? $data['name'] . ' - ' . $data['employee'] : '' }}
												</div>
											</div>
										@endif
									@endif
									@if($data['name'] == 'birthday')
										@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($dan)) && $hour == "08:00" )
											<div class="show_event orange" >
												<div class="event">
													{{ $data['name'] . ' - ' . $data['employee'] }}
												</div>
											</div>
										@endif
									@endif
								@endforeach
							@endif
						</div>
					@endforeach
				</div>
				<div hidden class="dataArr">{!! json_encode($dataArr) !!}</div>
			</main>
		</section>
	</main>
</div>
<script>
	$.getScript( '/../js/load_calendar2.js');
</script>
@stop