@extends('Centaur::layout')

@section('title', 'Events')
<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
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
	<aside class="col-4 index_aside">
		@include('Centaur::side_calendar')
	</aside>
	<main class="col-8 index_main">
		<section>
			<header class="header_calendar">
				<div class="col-6 float_left padd_0">
				<span class="event_day"><span class="week_day">{{ $selected['week_day'] }}</span><span class="day">{{ $selected['dan_select'] }}</span></span>
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
							<span class="hour_val">{{ $hour}}</span>
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
			</main>
		</section>
	</main>
		<script>
			$('.img_button.day_after').click(function(){
				console.log("radi");
			});
		</script>
		<script>
			$('.button_nav').css({
				'background': '#051847',
				'color': '#ffffff'
			});
			$( '.event_button' ).css({
				'background': '#0A2A79',
				'color': '#ccc'
			});
		
		</script>
		<script>
			$(function() {
				$('.link_event').css('color','orange');
				var url_basic = location.origin + location.pathname;
				var data =  <?php echo json_encode($dataArr); ?>;
				var data1 = [];
				for (i = 0; i < data.length; i++) { 
					var txt = '{"name": "' + data[i].name + '","date":"' + data[i].date + '"}'
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
</div>

@stop