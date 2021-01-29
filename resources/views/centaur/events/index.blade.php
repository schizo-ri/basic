@extends('Centaur::layout')

@section('title', 'Events')
<script src="{{ URL::asset('node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ URL::asset('node_modules/moment/moment.js') }}" ></script>
<?php 
	setlocale(LC_TIME, "hr_HR");
?>
@section('content')
<div class="index_page posts_index">
	<aside class="col-xs-12 col-sm-12 col-md-4 col-lg-4 index_aside calendar_aside">
		<div>
			@include('Centaur::side_calendar')
		</div>
	</aside>
	<main class="col-xs-12 col-sm-12 col-md-8 col-lg-8 index_main index_event load_content">
		<section>
			<header class="header_calendar">
				<div class="col-4 float_left padd_0 daycontainer">
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
						{{ $selected['month'] . ' ' .  $selected['god_select'] . ', ' . __('calendar.wk') . ' ' .  $selected['tj_select'] }}
					</span>
				</div>
				<div class="col-4 float_left padd_0 event_container">
					<span class="meeting col-4"><span class="blue"></span>@lang('basic.meeting')<span>{{ $count_days['dani_event'] }}</span></span>
					<span class="tasks col-4"><span class="orange"></span>@lang('basic.birthdays')<span>{{ $count_days['dani_rodjendani'] }}</span></span>
					<span class="on_vacation col-4"><span class="green"></span>@lang('absence.absences')<span>{{ $count_days['dani_odmor'] }}</span></span>
				</div>
				<div class="col-4 float_left filtercontainer" >
					<select class="change_view_calendar col-4">
						<option value="month" selected>@lang('basic.view_monthly')</option>
						<option value="week">@lang('basic.view_weekly')</option>
						<option value="day">@lang('basic.view_daily')</option>
						<option value="list" >@lang('basic.view_list')</option>
					</select>
					<select class="change_employee col-4">
						<option value="" selected>{{ __('basic.view_all')}} </option>
						@foreach ($employees as $employee)
							<option value="empl_{{ $employee->id }}">{{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }}</option>
						@endforeach
					</select>
					<select class="change_car col-4">
						<option value="" selected>{{ __('basic.view_all') }}</option>
						@foreach ($cars as $car)
							<option value="{{ $car->registration }}">{{ $car->registration }}</option>
						@endforeach
					</select>
					<button class="show_loccos col-2"><i class="fas fa-car"></i></button>
				</div>
			</header>
			<main class="main_calendar main_calendar_day" >
				<div class="all_events">
					@if(count($hours_array) > 0)
						@foreach($hours_array as $hour)
							<div class="hour_in_day">
								<a href="{{ route('events.create', ['time1' => $hour, 'date' => $selected['god_select'] . '-' .  $selected['mj_select'] . '-' . $selected['dan_select'] ]) }}" title="{{ __('calendar.add_event')}}" rel="modal:open" ><span class="hour_val {!! $hour == '08:00' ? 'position_8' : '' !!} ">{{ $hour}}</span></a>
								@if(isset($selected_day))
									<div>
										@foreach($dataArr as $key => $data)
											@if($data['name'] != 'liječnički' && $data['name'] != 'birthday' && $data['name'] != 'event' && $data['name'] != 'task' && $data['name'] != 'holiday' )
												@if( date('N',strtotime( $data['date'] ) ) < 6 && $data['date'] == $selected_day && date('H', strtotime($hour)) == date('H', strtotime($data['start_time']) ) )
													<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 show_event empl_{{ $data['employee_id'] }}" >
														<div class="event green">
															{!! isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] : ''  !!}
															{!! $data['name'] == 'IZL' ? '('. date('H:i', strtotime($data['start_time'])) . '-' . date('H:i', strtotime($data['end_time'] )) . ')' : '' !!}
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'birthday')
												@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($selected_day)) && $hour == "08:00" )
													<div class="show_event empl_{{ $data['employee_id'] }} col-xs-12 col-sm-6 col-md-4 col-lg-3 " >
														<div class="event orange">
															{{ $data['type'] . ' - ' . $data['employee'] }}
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'liječnički')
												@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($selected_day)) && $hour == "08:00" )
													<div class="show_event empl_{{ $data['employee_id'] }} col-xs-12 col-sm-6 col-md-4 col-lg-3 " >
														<div class="event purple">
															{{ $data['type'] . ' - ' . $data['employee'] }}
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'task')
												@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($selected_day)) )
													@if (strstr($data['time1'],':',true) == strstr($hour,':',true) || 
														(intval(strstr($data['time1'],':',true)) < intval(strstr($hour,':',true) ) && intval(strstr($data['time2'],':',true)) > intval(strstr($hour,':',true)) ))
														<div class="show_event empl_{{ $data['employee_id'] }} col-xs-12 col-sm-6 col-md-4 col-lg-3" >
															<a href="{{ route('tasks.show', $data['id']) }}" rel="modal:open" >
																<div class="event" {!! $data['background'] ? 'style="background-color:' . $data['background'] . '"' : 'style="background-color:#aaa"' !!} >
																	<p>{{ $data['employee'] }} {!! $data['car'] ? ' - ' . $data['car'] : '' !!} {{ ' - ' . $data['title'] }}</p>
																</div>
															</a>
														</div>
													@endif
												@endif
											@endif
											@if($data['name'] == 'event')
												@if(date("m-d",strtotime($data['date'])) == date("m-d",strtotime($selected_day)) )
													@if (strstr($data['time1'],':',true) == strstr($hour,':',true) || 
														(intval(strstr($data['time1'],':',true)) < intval(strstr($hour,':',true) ) && intval(strstr($data['time2'],':',true)) > intval(strstr($hour,':',true)) ))
														<div class="show_event empl_{{ $data['employee_id'] }} col-xs-12 col-sm-6 col-md-4 col-lg-3" >
															<a href="{{ route('tasks.show', $data['id']) }}" rel="modal:open" >
																<div class="event" >
																	<p>{{ $data['employee'] }} {{ ' - ' . $data['title'] }}
																	
																	</p>
																</div>
															</a>
														</div>
													@endif
												@endif
											@endif
											@if($data['name'] == 'holiday')
												@if(date("Y-m-d",strtotime($data['date'])) == date("Y-m-d",strtotime($selected_day)) && $hour == "08:00" )
													<div class="show_event show_holiday col-xs-12 col-sm-6 col-md-4 col-lg-3 " >
														<div class=" ">
															{{ $data['title'] }}
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'locco')
												@if(date("Y-m-d",strtotime($data['date'])) == date("Y-m-d",strtotime($selected_day)) && $hour == date("H:i",strtotime($data['date'])) )
													<div class="show_locco col-xs-12 col-sm-6 col-md-4 col-lg-3 " >
														<div class=" ">
															{{  $data['reg'] . ' ' .$data['title'] . ' ' . $data['employee']  }}
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
			<main class="main_calendar main_calendar_month" >
				<table class="col-12 ">
					<thead class="col-12">
						<tr class="col-12">
							<th class="th_week">@lang('calendar.week')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.monday')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.tuesday')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.wednesday')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.thursday')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.friday')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.saturday')</th>
							<th class="col-sm-3 col-md-2">@lang('calendar.sunday')</th>
						</tr>
					</thead>
					<tbody class="col-12">
						@php
							$start_date = new DateTime($selected['god_select'] .'-'. $selected['mj_select'] .'-'. '01'); //2020-06-01
							$end_date =  date_modify(new DateTime($selected['god_select'] .'-'. $selected['mj_select'] .'-'. '01'), '+'. ($days_in_month-1) . 'days'); //2020-06-30
							$day_in_week = intval(date_format($start_date, 'N'));   // 1 (1.6.  == ponedjeljak)
							 
							if( $day_in_week > 1) {
								$date_modify = date_modify( new DateTime($selected['god_select'] .'-'. $selected['mj_select'] .'-'. '01'), '-'. ($day_in_week) . 'days');
								
							} else {
								$date_modify = date_modify(new DateTime($selected['god_select'] .'-'. $selected['mj_select'] .'-'. '01'), '-1days');
							}
						@endphp
						@for ($i = 0; $i < ($days_in_month + $day_in_week) ; $i++) {{-- dani u mjesecu --}}
							@if ( $i%7 == 0) 
								<tr class="col-12">
									@php
										$week = date('W', strtotime(date_format($date_modify,'Y-m-d') . ' +1 day'));
									@endphp
									<td class="td_week"><span>{{ $week }}</span></td>  {{-- tjedan u godini --}}
									@for ($j = 1; $j <= 7; $j++)  {{-- dani u tjednu --}}
										@php
											$next_date = date_modify($date_modify, '+ 1day');
										@endphp
										<td class="date_cell {!! date_format($next_date, 'Y-m-d') == $selected_day ? 'today ' : '' !!}{!! date_format($next_date, 'Y-m-d') == $selected_day ? 'selected_day' : '' !!}{!! $next_date < $start_date || $next_date > $end_date ? 'out_month' : '' !!}" data-date="{{ date_format($next_date, 'Y-m-d') }}">
											<span class="day_of_month">{{ date_format($next_date, 'd') }}</span>
												@foreach ($events->where('date', date_format($next_date, 'Y-m-d') ) as $event)
													<a href="{{ route('events.show', $event->id) }}" rel="modal:open">
														<div class="show_event empl_{{  $event->employee_id }} col-12" >
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
												@foreach($tasks->where('date', date_format($next_date, 'Y-m-d') ) as $task)
													<a href="{{ route('tasks.show', $task->id) }}" rel="modal:open">
														<div class="show_event empl_{{  $task->employee_id }} col-12" >
															<div class="event" {!! $task->employee->color ? 'style="background-color:' . $task->employee->color . '"' : 'style="background-color:#aaa"' !!} >
																<p>{{ $task->employee->user['first_name'] }} {!! $task->car_id ? ' - ' . $task->car->registration : '' !!} {{ ' - ' . $task->title }}
																	{{-- {{ date('H:i',strtotime($task->time1)) . ' - ' . date('G:i',strtotime($task->time2)) }} {{ $task->title }}, {{ $task->description }} --}}
																</p>
															
															</div>
														</div>
													</a>
												@endforeach
												@foreach($dataArr as $key => $data)
													@if($data['name'] != 'liječnički' &&  $data['name'] != 'birthday' && $data['name'] != 'event' && $data['name'] != 'task' && $data['name'] != 'holiday')
														@if(date('N',strtotime($data['date'])) < 6 )
															@if( $data['date'] == date_format($next_date, 'Y-m-d') )
																<div class="show_event empl_{{ $data['employee_id'] }} col-12" >
																	<div class="event green">
																		<p>
																		{{ isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] : ''  }}
																		{!! $data['name'] == 'IZL' ? '('. date('H:i', strtotime($data['start_time'])) . '-' . date('H:i', strtotime($data['end_time'] )) . ')' : '' !!}
																		</p>
																	</div>
																</div>
															@endif
														@endif
													@endif
													@if($data['name'] == 'birthday')
														@if( date("m-d",strtotime($data['date'])) == date_format($next_date, 'm-d') )
															<div class="show_event empl_{{ $data['employee_id'] }} col-12" >
																<div class="event orange">
																	<p>{{ $data['type'] . ' - ' . $data['employee'] }}</p>
																</div>
															</div>
														@endif
													@endif
													@if($data['name'] == 'liječnički')
														@if( date("m-d",strtotime($data['date'])) == date_format($next_date, 'm-d') )
															<div class="show_event empl_{{ $data['employee_id'] }} col-12" >
																<div class="event purple">
																	<p>{{ $data['type'] . ' - ' . $data['employee'] }}</p>
																</div>
															</div>
														@endif
													@endif
													@if($data['name'] == 'holiday')
														@if( date("Y-m-d",strtotime($data['date'])) == date_format($next_date, 'Y-m-d') )
															<div class="show_event show_holiday col-12" >
																<div class="event">
																	<p>{{ $data['title'] }}</p>
																</div>
															</div>
														@endif
													@endif
													@if($data['name'] == 'locco')
														@if( date("Y-m-d",strtotime($data['date'])) == date_format($next_date, 'Y-m-d') )
															<div class="show_locco col-xs-12">
																<div class="locco">
																	<p>{{  $data['reg'] . ' ' .$data['title'] . ' ' . $data['employee']  }}</p>
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
			<main class="main_calendar main_calendar_list" >
				<ul>
					@foreach($tasks->merge($events)->sortBy('date')->groupBy('week') as $key => $week)
						<li class="month_event week">{{ 'Tjedan ' .$key }}</li>
						@foreach($week as $task)
							@if (date('m',strtotime($task->date)) == $selected['mj_select'])
								<li class="month_event empl_{{ $task->employee_id }}" {!! class_basename($task) == 'Task' && $task->employee->color ? 'style="background-color:'.$task->employee->color.'"':'style="background:none"' !!}>
									<a href="{!! class_basename($task) == 'Task' ? route('tasks.show', $task->id) : route('events.show', $task->id) !!}" rel="modal:open">
										<span>{!! date('d.m.Y,', strtotime($task->date)) . ' '. iconv('ISO-8859-2', 'UTF-8',strftime("%a", strtotime($task->date))) !!}</span>
										<span>{{ $task->employee->user['first_name'] }}</span> 
										<span>{!! $task->car_id ? ' - ' . $task->car->registration : '' !!}</span>
										<span>{{  $task->title }}</span>
										<span>{{ class_basename($task) }} </span>
										
									</a>
								</li>
							@endif
						@endforeach
					@endforeach
				</ul>
			</main>
			<main class="main_calendar main_calendar_week" >
				@php
					$today = new DateTime($selected_day); //2020-03-31
					$date_in_week = new DateTime($selected_day); //2020-03-31
					$day_in_week = date_format($date_in_week, 'N') - 1 ;  // 2 -1 = ponedjeljak,
					$start_date = $date_in_week->modify('-'. $day_in_week.'day'); 
					$week =date_format($start_date,'W');
				@endphp
				<table class="col-12 ">
					<thead class="col-12">
						<tr class="col-12">
							<th class="col-2 td_week"><span>{{ $week }}</span></th>
							<th class="col-2 {!! date_format( $start_date->modify('+0day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.monday1')</span><span class="date_in_week">{{ date_format( $start_date, 'd') }}</span>
							</th>
							<th class="col-2 {!! date_format( $start_date->modify('+1day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.tuesday1')</span><span class="date_in_week">{{ date_format( $start_date, 'd') }}</span>
							</th>
							<th class="col-2 {!! date_format( $start_date->modify('+1day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.wednesday1')</span><span class="date_in_week">{{ date_format( $start_date, 'd') }}</span>
							</th>
							<th class="col-2 {!! date_format( $start_date->modify('+1day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.thursday1')</span><span class="date_in_week">{{ date_format( $start_date, 'd') }}</span>
							</th>
							<th class="col-2 {!! date_format( $start_date->modify('+1day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.friday1')</span><span class="date_in_week">{{  date_format( $start_date, 'd') }}</span>
							</th>
							<th class="col-2 {!! date_format( $start_date->modify('+1day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.saturday1')</span><span class="date_in_week">{{ date_format( $start_date, 'd') }}</span>
							</th>
							<th class="col-2 {!! date_format( $start_date->modify('+1day'), 'Y-m-d') == date_format( $today, 'Y-m-d') ? 'today' : '' !!}">
								<span class="day_in_week">@lang('calendar.sunday1')</span><span class="date_in_week">{{ date_format( $start_date, 'd') }}</span>
							</th>
						</tr>
					</thead>
					<tbody class="col-12">
						@foreach($hours_array as $hour)
							<tr class="col-12 {!! $hour == '08:00' ? 'position_8' : '' !!}">
								<td >{{ $hour }}</td>
								@for ($i = 0; $i < 7; $i++)
									@php
										$today = new DateTime($selected_day); //2020-03-31
										$date_in_week = new DateTime($selected_day); //2020-03-31
										$day_in_week = date_format($date_in_week, 'N') - 1 ;  // 2 -1 = ponedjeljak,
										$start_date = $date_in_week->modify('-'. $day_in_week.'day');
									
										$date1 = $start_date->modify('+'. $i .'day');
									@endphp
									<td id="{{ date_format($date1, 'Y-m-d') }}">
										@foreach ($events->where('date', date_format($date1, 'Y-m-d') ) as $event)
											@if (strstr($event->time1,':',true) == strstr($hour,':',true) || 
													(intval(strstr($event->time1,':',true)) < intval(strstr($hour,':',true) ) && intval(strstr($event->time2,':',true)) > intval(strstr($hour,':',true)) ))
												<a href="{{ route('events.show', $event->id) }}" rel="modal:open">
													<div class="show_event empl_{{ $event->employee_id }} col-12" >
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
											@endif
										@endforeach
										@foreach($tasks as $task)
											@if (strstr($task->time1,':',true) == strstr($hour,':',true) || 
													(intval(strstr($task->time1,':',true)) < intval(strstr($hour,':',true) ) && intval(strstr($task->time2,':',true)) > intval(strstr($hour,':',true)) ))
												@if( $task->date == date_format($date1, 'Y-m-d') )
													<a href="{{ route('tasks.show', $task->id) }}" rel="modal:open">
														<div class="show_event empl_{{ $task->employee_id }} col-12" >
															<div class="event" {!! $task->employee->color ? 'style="background-color:' . $task->employee->color . '"' : 'style="background-color:#aaa"' !!} >
																<p>{{ $task->employee->user['first_name'] }} {!! $task->car_id ? ' - ' . $task->car->registration : '' !!} {{ ' - ' . $task->title }}
																	{{-- {{ date('H:i',strtotime($task->time1)) . ' - ' . date('G:i',strtotime($task->time2)) }} {{ $task->title }}, {{ $task->description }} --}}
																</p>
															</div>
														</div>
													</a>
												@endif
											@endif
										@endforeach
										@foreach($dataArr as $key => $data)
											@if($data['name'] != 'liječnički' && $data['name'] != 'birthday' && $data['name'] != 'event' && $data['name'] != 'task' && $data['name'] != 'holiday')
												@if(date('N',strtotime($data['date'])) < 6 )
													@if( $data['date'] == date_format($date1, 'Y-m-d') && date('H', strtotime($hour)) == date('H', strtotime($data['start_time'])) )
														<div class="show_event empl_{{ $data['employee_id'] }} col-12" >
															<div class="event green">
																<p>
																{{ isset($data['employee']) ? $data['type'] . ' - ' . $data['employee'] : ''  }}
																{!! $data['name'] == 'IZL' ? '('. date('H:i', strtotime($data['start_time'])) . '-' . date('H:i', strtotime($data['end_time'] )) . ')' : '' !!}
																</p>
															</div>
														</div>
													@endif
												@endif
											@endif
											@if($data['name'] == 'birthday')
												@if(date("m-d",strtotime($data['date'])) == date_format($date1, 'm-d') && strstr($hour,':',true) == '08')
													<div class="show_event empl_{{ $data['employee_id'] }} col-12" >
														<div class="event orange">
															<p>{{ $data['type'] . ' - ' . $data['employee'] }}</p>
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'liječnički')
												@if(date("m-d",strtotime($data['date'])) == date_format($date1, 'm-d') && strstr($hour,':',true) == '08')
													<div class="show_event empl_{{ $data['employee_id'] }} col-12" >
														<div class="event purple">
															<p>{{ $data['type'] . ' - ' . $data['employee'] }}</p>
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'holiday')
												@if(date("Y-m-d",strtotime($data['date'])) == date_format($date1, 'Y-m-d') && strstr($hour,':',true) == '08')
													<div class="show_event show_holiday col-12" >
														<div class=" ">
															{{ $data['title'] }}
														</div>
													</div>
												@endif
											@endif
											@if($data['name'] == 'locco')
												@if(date("Y-m-d",strtotime($data['date'])) ==  date_format($date1, 'Y-m-d') && $hour == date("H:i",strtotime($data['date'])) )		
													<div class="show_locco col-12 " >
														<div class=" ">
															{{  $data['reg'] . ' ' .$data['title'] . ' ' . $data['employee']  }}
														</div>
													</div>
												@endif
											@endif
										@endforeach
									</td>
								@endfor
							</tr>
						@endforeach
					</tbody>
				</table>
			</main>
		</section>
	</main>
</div>
<script src="{{ URL::asset('node_modules/pg-calendar/dist/js/pignose.calendar.min.js') }}"></script>
<script>
	$.getScript( '/../js/load_calendar2.js');

</script>
@stop