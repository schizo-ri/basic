@extends('Centaur::layout')

@section('title', 'Events')
<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
@section('content')
<div class="row">
	<div class="page-header">
		<div class='btn-toolbar pull-right'>
			<a class="btn btn-primary btn-lg" href="{{ route('events.create') }}">
				<i class="fas fa-plus"></i>
				Add
			</a>
        </div>
        <h1>Events</h1>
    </div>
	<main class="table-responsive">
		<div class="calender_view"></div>
	</main> 
	<div class="show_event" hidden>
		<?php 
			$select_day = '';
			$dan_select = '';
			$mj_select = '';
			$mj_select = '';
			$god_select = '';
			if(isset($_GET['dan'])) {
				$select_day = explode('-',$_GET['dan']);  //get from URL
				$dan_select = $select_day[2];
				$mj_select = $select_day[1];
				$god_select = $select_day[0];
			} else {
				$danas = date('Y-m-d');
				$select_day = explode('-',$danas);  //get from URL
				$dan_select = $select_day[2];
				$mj_select = $select_day[1];
				$god_select = $select_day[0];
			}
		?>
		<div class="event">
			@foreach($events as $event)
				@if(isset($_GET['dan']))
					@if($event->date == $_GET['dan'])
						<p>{{ date('d.m.Y',strtotime($event->date)) }}</p>
						<p>{{ $event->title }}</p>
						<p>{{ $event->content }}</p>
						<p>{{ date('G:i',strtotime($event->time1)) . ' - ' . date('G:i',strtotime($event->time2)) }}</p>
					@endif
				@endif
			@endforeach
		</div>
		
	</div>
</div>
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
				event: '#f36c21',
				
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
						console.log(datum);
						$('.show_event').show();
						var url = url_basic + '?dan=' + datum;
						$('.show_event').load(url + ' .show_event .event');
					}
				 }
			}
	});
	
});
</script>

<link rel="stylesheet" href="{{ URL::asset('node_modules/pg-calendar/dist/css/pignose.calendar.css') }}" />
<script src="{{ URL::asset('node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>
@stop