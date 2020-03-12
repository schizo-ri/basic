@extends('Centaur::layout')

@section('title', 'Events')
<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}"></script>
<?php 
	use App\Http\Controllers\EventController;
	use App\Http\Controllers\DashboardController;
	use App\Http\Controllers\TaskController;

	$today = date('Y-m-d');
	if(isset($_GET['dan'])) {
		$dan = $_GET['dan'];
	} else {
		$dan = $today;
	}
	
	$count_days = EventController::countDays($dataArr, $dan);
	$selected = EventController::selectedDay( $dan );
	
	$day = $selected['dan_select'];
	$month = $selected['mj_select'];
	$year = $selected['god_select'];
	$selected_day = $year .'-'. $month .'-'. $day;
	
//	$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);  // broj dana u mjesecu

	if ($year%4 == 0)
	{
		$daysInMonth = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	}
	else
	{
		$daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	}
	$days_in_month = $daysInMonth[intval($month)-1];

	$dataArr_day = EventController::event_for_selected_day( $dan );

	$uniqueType = array_unique(array_column($dataArr_day, 'type'));
	
	if(count($events)>0) {
		$events_day = $events->where('date', $dan);
	}
	$tasks_day = TaskController::task_for_selected_day( $dan );
	//dd(get_defined_vars()); //sve varijable
?>
@section('content')
<div class="index_page posts_index">
	<aside class="col-4 index_aside calendar_aside">
		@include('Centaur::side_calendar')
	</aside>
	<main class="col-8 index_main index_event">
		<section>
			<header class="header_calendar">
				<div class="col-6 float_left padd_0">
					<span class="event_day">
						<span class="week_day">{{ $selected['week_day'] }}</span>
						<span class="day">{{ $selected['dan_select'] }}</span>
						<span class="month" hidden>{{ $selected['mj_select'] }}</span>
						<span class="year" hidden>{{ $selected['god_select'] }}</span>
					</span>
					<span class="arrow"><img class="img_button day_before" src="{{ URL::asset('icons/arrow_left.png') }}" alt="arrow"/>
						<img class="img_button day_after" src="{{ URL::asset('icons/arrow_right.png') }}" alt="arrow"/>
					</span>
					<span class="month_year">
						{{ $selected['month'] . ' ' .  $selected['god_select'] }}
					</span>
				</div>
				<div class="col-6 float_left padd_0">
					<span class="meeting col-4"><span class="blue"></span>@lang('basic.meeting')<span>{{ $count_days['dani_event'] }}</span></span>
					<span class="tasks col-4"><span class="orange"></span>@lang('basic.birthdays')<span>{{ $count_days['dani_rodjendani'] }}</span></span>
					<span class="on_vacation col-4"><span class="green"></span>@lang('basic.on_vacation')<span>{{ $count_days['dani_odmor'] }}</span></span>
				</div>
				<div class="change_view">@lang('basic.view_monthly')</div>				
			</header>
			<main class="main_calendar" >
				<div class="all_events">
					@if(count($hours_array) > 0)
						@foreach($hours_array as $hour)
							<div class="hour_in_day">
								<a href="{{ route('events.create', ['time1' => $hour, 'date' => $selected['god_select'] . '-' .  $selected['mj_select'] . '-' . $selected['dan_select'] ]) }}" title="{{ __('calendar.add_event')}}" rel="modal:open" ><span class="hour_val">{{ $hour}}</span></a>
								@if(isset($dan))
									<div>
										@if( isset($events_day))
											@foreach($events_day as $event)
												@if (strstr($event->time1,':',true) == strstr($hour,':',true) || 
													(intval(strstr($event->time1,':',true)) < intval(strstr($hour,':',true) ) && intval(strstr($event->time2,':',true)) > intval(strstr($hour,':',true)) ))
													<div class="show_event col-xs-12 col-sm-6 col-md-4 col-lg-3 " >														
														<div class="event blue">
															<p>{{ date('H:i',strtotime($event->time1)) . ' - ' . date('G:i',strtotime($event->time2)) }} {{ $event->title }}, {{ $event->description }}
																<a href="{{ route('events.edit', $event->id) }}" class="btn-edit" rel="modal:open" >
																	<i class="far fa-edit"></i>
																</a>
																<a href="{{ route('events.destroy', $event->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
																	<i class="far fa-trash-alt"></i>
																</a>
															</p>
															
														</div>
													</div>
												@endif
											@endforeach
										@endif
										@if( isset($tasks_day))
											@foreach($tasks_day as $task)
												@if (strstr($task->time1,':',true) == strstr($hour,':',true) || 
													(intval(strstr($task->time1,':',true)) < intval(strstr($hour,':',true) ) && intval(strstr($task->time2,':',true)) > intval(strstr($hour,':',true)) ))
													<div class="show_event col-xs-12 col-sm-6 col-md-4 col-lg-3" >
														<a href="{{ route('tasks.show', $task->id) }}" rel="modal:open">
															<div class="event" {!! $task->employee->color ? 'style="background-color:' . $task->employee->color . '"' : 'style="background-color:#aaa"' !!} >
																<p>{{ $task->employee->user['first_name'] }} {!! $task->car_id ? ' - ' . $task->car->registration : '' !!} {{ ' - ' . $task->title }}
																	{{-- {{ date('H:i',strtotime($task->time1)) . ' - ' . date('G:i',strtotime($task->time2)) }} {{ $task->title }}, {{ $task->description }} --}}
																	
																</p>
															
															</div>
														</a>
														<a href="{{ route('tasks.edit', $task->id) }}" class="btn-edit" rel="modal:open" >
															<i class="far fa-edit"></i>
														</a>
														<a href="{{ route('tasks.destroy', $task->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
															<i class="far fa-trash-alt"></i>
														</a>
													</div>
												@endif
											@endforeach
										@endif
										@foreach($dataArr as $key => $data)
											@if($data['name'] != 'birthday' && $data['name'] != 'event' && $data['name'] != 'task')
												@if( $data['date'] == $dan && date('H', strtotime($hour)) == date('H', strtotime($data['start_time'])) )
													<div class="show_event col-xs-12 col-sm-6 col-md-4 col-lg-3" >
														<div class="event green">
															{{ isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] : ''  }}
															{!! $data['name'] == 'IZL' ? '('. date('H:i', strtotime($data['start_time'])) . '-' . date('H:i', strtotime($data['end_time'] )) . ')' : '' !!}
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'birthday')
												@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($dan)) && $hour == "08:00" )
													<div class="show_event  col-xs-12 col-sm-6 col-md-4 col-lg-3 " >
														<div class="event orange">
															{{ $data['type'] . ' - ' . $data['employee'] }}
														</div>
													</div>
												@endif
											@endif
										@endforeach
									</div>
								@endif
							</div>
						@endforeach
					@endif
				</div>				
				<div hidden class="dataArr">{!! ! empty($dataArr) ? json_encode($dataArr) : '' !!}</div>
			</main>
		</section>
	</main>
	<main class="col-8 index_main index_event_month">
		<section>
			<header class="header_calendar_month">
				<div class="col-6 float_left padd_0">
					<span class="arrow"><img class="img_button month_before" src="{{ URL::asset('icons/arrow_left.png') }}" alt="arrow"/><img class="img_button month_after" src="{{ URL::asset('icons/arrow_right.png') }}" alt="arrow"/></span>
					<span class="month_year">{{ $selected['month'] . ' ' .  $selected['god_select'] }}</span>
				</div>
				<div class="change_view2">@lang('basic.view_daily')</div>
			</header>
			<main class="main_calendar_month" >				
				<table class="col-12 ">
					<thead class="col-12">
						<tr class="col-12">
							<th class="col-2">@lang('calendar.monday')</th>
							<th class="col-2">@lang('calendar.tuesday')</th>
							<th class="col-2">@lang('calendar.wednesday')</th>
							<th class="col-2">@lang('calendar.thursday')</th>
							<th class="col-2">@lang('calendar.friday')</th>
							<th class="col-2">@lang('calendar.saturday')</th>
							<th class="col-2">@lang('calendar.sunday')</th>
						</tr>
					</thead>
					<tbody class="col-12">
						@php
							$start_date = new DateTime($year .'-'. $month .'-'. '01'); //2020-03-01
							$end_date =  date_modify(new DateTime($year .'-'. $month .'-'. '01'), '+'. ($days_in_month-1) . 'days'); //2020-03-31
							$day_in_week = intval(date_format($start_date, 'N'));  //7,
							 
							if( $day_in_week > 1) {
								$date_modify = date_modify( new DateTime($year .'-'. $month .'-'. '01'), '-'. ($day_in_week) . 'days');
							} else {
								$date_modify = $start_date;
							}
						@endphp
						@for ($i = 0; $i < ($days_in_month + $day_in_week) ; $i++) {{-- dani u mjesecu --}}
							@if ( $i%7 == 0)
								<tr class="col-12">
									@for ($j = 1; $j <= 7; $j++)  {{-- dani u tjednu --}}
										@php
											$next_date = date_modify($date_modify, '+ 1day');
										@endphp
										<td class="{!! date_format($next_date, 'Y-m-d') == $today ? 'today' : '' !!} {!! date_format($next_date, 'Y-m-d') == $selected_day ? 'selected_day' : '' !!} {!! $next_date < $start_date || $next_date > $end_date ? 'out_month' : '' !!}" data-date="{{ date_format($next_date, 'Y-m-d') }}">
											<span>{{ date_format($next_date, 'd') }}</span>
											@foreach ($events->where('date', date_format($next_date, 'Y-m-d') ) as $event)
												<a href="{{ route('events.show', $event->id) }}" rel="modal:open">
													<div class="show_event col-12" >
														<div class="event blue">
															<p>{{ date('H:i',strtotime($event->time1)) . ' - ' . date('G:i',strtotime($event->time2)) }} {{ $event->title }}
																{{-- <a href="{{ route('events.edit', $event->id) }}" class="btn-edit" rel="modal:open" >
																	<i class="far fa-edit"></i>
																</a>
																<a href="{{ route('events.destroy', $event->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
																	<i class="far fa-trash-alt"></i>
																</a> --}}
															</p>
														</div>
													</div>
												</a>
											@endforeach
											@foreach($tasks as $task)
												@if( $task->date == date_format($next_date, 'Y-m-d') )
													<a href="{{ route('tasks.show', $task->id) }}" rel="modal:open">
														<div class="show_event col-12" >
															<div class="event" {!! $task->employee->color ? 'style="background-color:' . $task->employee->color . '"' : 'style="background-color:#aaa"' !!} >
																<p>{{ $task->employee->user['first_name'] }} {!! $task->car_id ? ' - ' . $task->car->registration : '' !!} {{ ' - ' . $task->title }}
																	{{-- {{ date('H:i',strtotime($task->time1)) . ' - ' . date('G:i',strtotime($task->time2)) }} {{ $task->title }}, {{ $task->description }} --}}
																</p>
															
															</div>
														</div>
													</a>
												@endif
											@endforeach
											@foreach($dataArr as $key => $data)
												@if($data['name'] != 'birthday' && $data['name'] != 'event' && $data['name'] != 'task')
													@if( $data['date'] == date_format($next_date, 'Y-m-d') )
														<div class="show_event col-12" >
															<div class="event green">
																<p>
																{{ isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] : ''  }}
																{!! $data['name'] == 'IZL' ? '('. date('H:i', strtotime($data['start_time'])) . '-' . date('H:i', strtotime($data['end_time'] )) . ')' : '' !!}
																</p>
															</div>
														</div>
													@endif
												@endif
												@if($data['name'] == 'birthday')
													@if( date("m-d",strtotime($data['date'])) == date_format($next_date, 'm-d') )
														<div class="show_event col-12" >
															<div class="event orange">
																<p>{{ $data['type'] . ' - ' . $data['employee'] }}</p>
															</div>
														</div>
													@endif
												@endif
											@endforeach
										</td>
									@endfor
								</tr>
							@endif
						@endfor
					</tbody>
				</table>
			</main>
		</section>
	</main>
</div>
<script>
	$.getScript( '/../js/load_calendar2.js');	
	
</script>
@stop