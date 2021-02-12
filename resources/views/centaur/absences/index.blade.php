@extends('Centaur::layout')

@section('title', __('absence.absences'))
@php
	use App\Http\Controllers\BasicAbsenceController;
@endphp
<link href="{{ URL::asset('/../select2-develop/dist/css/select2.min.css') }}" />
@section('content')
<span id="user_admin" hidden>{{ Sentinel::inRole('administrator') ? 'true' : '' }}</span>
<div class="index_page index_absence">
	<main class="col-lg-12 col-xl-12 index_main main_absence float_right">
		<section>
			<header class="header_absence">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@if (Sentinel::inRole('administrator')	)
					<p>@lang('absence.all_requests')
						<a href="{{ route('absences_table') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi izračune</a>
						{{-- <a href="{{ route('absences_requests') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi zahtjeve za mjesec</a> --}}
					</p>
				@endif
			</header>
			<main class="all_absences ">
				<header class="main_header">
					<div class="col-3 info_abs">
						@if($data_absence['docs'])
							<img class="radius50" src="{{ URL::asset('storage/' . $data_absence['user_name'] . '/profile_img/' . end($data_absence['docs'])) }}" alt="Profile image"  />
						@else
							<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
						@endif
						<span class="empl_name">{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name']}}</span>
						<span class="empl_work">{{ $employee->work['name'] }}</span>
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.work_history')</span>
						<p class="col-6 float_l">
							{{ $data_absence['years_service']->y . '-' . 
							$data_absence['years_service']->m . '-' .  $data_absence['years_service']->d }}
							<span>@lang('absence.experience')<br><small>@lang('absence.yy_mm_dd')</small></span>
						<p class="col-6 float_l">
							{{ $data_absence['all_servise'][0] . '-' . $data_absence['all_servise'][1]  . '-' .  $data_absence['all_servise'][2]  }}
							<span>@lang('absence.experience') @lang('absence.total')<br><small>@lang('absence.yy_mm_dd')</small></span>
						</p>
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.vacat_days')
							<select id="year_vacation" class="year_select">
								@foreach ( $data_absence['years'] as $year)
									<option value="{{ $year }}">{{ $year }}</option>
								@endforeach
							</select>
						</span>
						<p class="col-6 float_l">
							@if( ! in_array($ova_godina,$data_absence['years']))
								<span class="go go_{{ $ova_godina }}"></span>
							@endif
							@foreach ($data_absence['years'] as $year)
								<span class="go go_{{ $year }}">{{ BasicAbsenceController::godisnjiGodina($employee, $year) }} ( {{ BasicAbsenceController::razmjeranGO_Godina($employee, $year) }} )
								</span>
							@endforeach	
							<span>@lang('absence.total_days') <br> ( @lang('absence.proportion') ) </span>
						</p>
						<p class="col-6 float_l">
							@foreach ($data_absence['years'] as $year)
								<span class="go go_{{ $year }}">
									{!! isset($data_absence['zahtjevi'][ $year]) ? count($data_absence['zahtjevi'][ $year]) : 0 !!}
									 - {{ BasicAbsenceController::razmjeranGO_Godina($employee, $year) - count($data_absence['zahtjevi'][ $year])}}
								</span>
							@endforeach
							<span>@lang('absence.used_unused')</span>
						</p>
						@if($employee->days_off == 1)
							<p class="col-6 float_l">
							</p>
							<p class="col-6 float_l days_off">
								<span>@lang('basic.days_off') {!! $data_absence['afterHours_withoutOuts']  - $data_absence['days_offUsed']  !!}</span>
							</p>
						@endif
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.sick_leave')
							<select id="year_sick" class="year_select">
								@foreach ($data_absence['years'] as $year)
									<option>{{ $year }}</option>
								@endforeach
							</select>
						</span>
						<p class="col-6 float_l">
							@foreach ($data_absence['years'] as $year)
								<span class="bol bol_{{ $year }}">{!! isset( $data_absence['bolovanje'][ $year]) ? $data_absence['bolovanje'][ $year] : 0 !!}</span>
							@endforeach
							<span>@lang('absence.total_used')</span>
						</p>
						<p class="col-6 float_l">
							<span class="bol_om">{!! isset($data_absence['bolovanje']['bolovanje_OM']) ? $data_absence['bolovanje']['bolovanje_OM'] : 0 !!}</span>
							<span>@lang('absence.this_month')</span>
						</p>
					</div>
					<div></div>
				</header>
				<section class="overflow_auto bg_white section_main">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel height100">
						<form name="contactform" class="after_form" method="post" action="{{ action('AfterhourController@storeConfMulti') }}">
							<div id="index_table_filter" class="dataTables_filter">
								<label class="col-md-12 col-lg-2 col-xl-2 float_left">
									<input type="search" placeholder="Search" onkeyup="mySearchTableAbsence()" id="mySearchTbl">
								</label>
								<div class="col-md-12 col-lg-4 col-xl-4 float_left approve_area">
									{{-- @if( count( $absences->where('approve', null) ) > 0 && (isset( $afterhours ) && count( $afterhours->where('approve', null) )> 0 )) --}}
										@if(Sentinel::inRole('administrator'))
											<div class="approve_buttons">
												<div class="col-3 col-sm-3 col-md-3 float_left">
													<span class="approve_button" id="checkall"><i class="fas fa-check green"></i> Označi sve <span class="approve_span">DA</span></span>
												</div>
												<div class="col-3 col-sm-3 col-md-3 float_left">
													<span class="approve_button" id="uncheckall" ><i class="fas fa-times red"></i> Označi sve <span class="approve_span">NE</span> </span>
												</div>
												<div class="col-3 col-sm-3 col-md-3 float_left">
													<span class="approve_button" id="nocheckall" >Ukloni oznake</span>
												</div>
												<div class="col-3 col-sm-3 col-md-3 float_left">
													<input class="btn-new btn-approve" type="submit" value="Obradi">
													{{ csrf_field() }}
												</div>
											</div>
										@endif
									{{-- @endif --}}
								</div>
								<div class="col-md-12 col-lg-6 col-xl-6 float_left filter_area">
									<div class="float_right padd_l_10">
										<a class="add_new" href="{{ route('absences.create') }}" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>@lang('basic.add')</a>
									</div>
									<div class="width_20 float_right padd_l_10 dropdown_empl">
										{{-- <input id="filter_employees" list="list_employees" autocomplete="off" value="SVI djelatnici" >
										<datalist id="list_employees">
											<option data-id="all" value="SVI djelatnici" selected />
												@foreach ($employees as $r_employee)
													<option value="{{ $r_employee->user->last_name . ' ' .$r_employee->user->first_name }}" data-id="{{ $r_employee->id }}" style="height: 12px"/>
												@endforeach
										</datalist> --}}
										<select id="filter_employees" class="js-example-basic-single select_filter filter_employees" name="state" >
											@if( Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') )
												<option value="all" selected >SVI djelatnici</option>
												@foreach ($employees as $r_employee)
													<option value="{{ $r_employee->id }}" >{{ $r_employee->user->last_name . ' ' .$r_employee->user->first_name }}</option>
												@endforeach
											@else
												<option value="{{ Sentinel::getUser()->employee->id }}" >{{ Sentinel::getUser()->last_name . ' ' . Sentinel::getUser()->first_name }}</option>
											@endif
										</select>
									</div>
									<div class="width_20 float_right padd_l_10">
										<select id="filter_types" class="select_filter filter_types" >
											<option value="all" >@lang('absence.all_types')</option>
											@foreach ($types as $type)
												@if( $type->mark == 'afterhour' && (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin')) )
													<option value="{{ $type->mark }}" >{{ $type->name }}</option>
												@else
													<option value="{{ $type->id }}" >{{ $type->name }}</option>
												@endif
											@endforeach
										</select>
									</div>
									<div class="width_20 float_right padd_l_10">
										<select id="filter_years" class="select_filter filter_years" >
											@foreach ($years_all as $year)
												<option value="{{ $year }}" {!! $year == date('Y-m') ? 'selected' : '' !!} >{{ $year }}</option>
											@endforeach
										</select>
									</div>
									<div class="width_20 float_right padd_l_10">
										<select id="filter_approve" class="select_filter filter_approve" >
											<option value="all">@lang('absence.all_requests') </option>
											@if(Sentinel::inRole('administrator'))
												<option value="approved">@lang('absence.approved')</option>
												<option value="refused">@lang('absence.refused')</option>
												<option value="not_approved">@lang('absence.not_approved')</option>
											@endif
										</select>
									</div>
								</div>
							</div>
							<div class="table-responsive" >
								<table id="index_table" class="display table table-hover sort_1_desc">
									<thead>
										<tr>
											@if( Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') )
												<th style="max-width:10%;width:10%">@lang('basic.fl_name')</th>
											@endif
											{{-- <th class="sort_date">@lang('absence.request_date')</th> --}}
											<th style="max-width:10%;width:10%">@lang('absence.request_type')</th>
											<th class="sort_date" style="max-width:7%;width:7%">@lang('absence.start_date')</th>
											<th class="sort_date absence_end_date" style="max-width:7%;width:7%">@lang('absence.end_date')</th>
											<!--<th>Period</th>-->
											<th class="absence_time" style="max-width:7%;width:7%">@lang('absence.time')</th>
											<th style="max-width:30%;width:30%">@lang('basic.comment')</th>
											<th style="max-width:10%;width:10%">@lang('absence.approved')</th>
											<th style="max-width:15%;width:15%">@lang('absence.approve_comment')</th>
											<!--<th>@lang('absence.aproved_by')</th>
											<th>@lang('absence.aprove_date')</th>-->
											@if( Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') )
												<th class="not-export-column no-sort" style="max-width:10%;width:10%">@lang('basic.options')</th>
											@endif
										</tr>
									</thead>
									<tbody class="overflow_auto">
										@if(count( $absences ) > 0 || isset( $afterhours ) && count( $afterhours )> 0)
											@if(count( $absences )>0)
												@foreach ( $absences as $absence )
													@if( $absence->employee)
														@php
															$start_date = new DateTime($absence->start_date . $absence->start_time);
															$end_date = new DateTime($absence->end_date . $absence->end_time );
															$interval1 = $start_date->diff($end_date);
															$zahtjev = array('start_date' => $absence->start_date, 'end_date' => $absence->end_date);
															$array_dani_zahtjeva = BasicAbsenceController::array_dani_zahtjeva($zahtjev);
															$dani_go = BasicAbsenceController::daniGO_count($zahtjev);

															$hours   = $interval1->format('%h'); 
															$minutes = $interval1->format('%i');
															
															$time1 = new DateTime($absence->start_time );
															$time2 = new DateTime($absence->end_time );
															$interval = $time2->diff($time1);
															$interval = $interval->format('%H:%I');
														@endphp
														<tr class="tr_open_link tr {!! $absence->absence->mark == 'BOL' ? 'bol bol-'.date('Y',strtotime($absence->start_date)) : '' !!}" data-href="/absences/{{ $absence->employee->id }} empl_{{ $absence->employee_id}}" id="requestAbs_{{ $absence->id}}" >
															@if( Sentinel::inRole('administrator') )
																<td style="max-width:10%;width:10%">{{ $absence->employee->user['last_name'] . ' ' . $absence->employee->user['first_name'] }}</td>
															@endif
															{{-- <td>{{ date('d.m.Y',strtotime($absence->created_at)) }}</td> --}}
															<td style="max-width:10%;width:10%">{{ '[' . $absence->absence['mark'] . '] ' . $absence->absence['name'] }}</td>
															<td style="max-width:7%;width:7%">{{ date('d.m.Y',strtotime($absence->start_date))  }}</td>
															<td class="absence_end_date" style="max-width:7%;width:7%">{!! $absence->end_date ? date('d.m.Y',strtotime($absence->end_date)) : '' !!}</td>
															<!--<td>xx dana</td>-->
															<td class="absence_time" style="max-width:7%;width:7%" >{!! $absence->absence['mark'] == 'IZL' ? date('H:i',strtotime($absence->start_time)) . '-' .  date('H:i',strtotime($absence->end_time)) :'' !!}</td>
															<td style="max-width:30%;width:30%">
																@if( $absence->absence['mark'] != 'IZL' )
																	[{!! $absence->approve == 0 ? 0 : $dani_go !!} @lang('absence.days')] 
																@else
																	[ {!! $absence->approve == 0 ? '00:00' : $hours . ' h, ' . $minutes . ' m' !!} ]
																@endif
																{{ $absence->comment }}
															</td>
															<td class="approve not_link"  style="max-width:15%;width:15%">
																@if($absence->approve == 1) 
																	<span class="img_approve"><span>@lang('absence.approved')</span></span>
																@elseif($absence->approve == "0") 
																	<span class="img_denied"><span>@lang('absence.refused')</span></span>
																@elseif($absence->approve == null) 
																	@if(Sentinel::inRole('administrator'))
																		<input type="hidden" name="id[{{ $absence->id}}]" class="id" value="{{ $absence->id}}">
																		<input type="hidden" name="type[{{ $absence->id}}]" class="id" value="abs">
																		<input class="check checkinput" type="radio" name="approve[{{ $absence->id}}]" value="1" id="odobreno{{ $absence->id}}" ><label class="check check_label" for="odobreno{{ $absence->id}}">DA</label>
																		<input class="uncheck checkinput" type="radio" name="approve[{{ $absence->id}}]" value="0" id="neodobreno{{ $absence->id}}" ><label class="uncheck check_label"  for="neodobreno{{ $absence->id}}">NE</label>
																		<input class="nocheck checkinput" type="radio" name="approve[{{ $absence->id}}]" value="" id="bezodobreno{{ $absence->id}}" ><label class="uncheck check_label"  for="bezodobreno{{ $absence->id}}">-</label>
																	@endif
																@endif
															</td>
															<td class="approve not_link"  style="max-width:10%;width:10%">
																@if($absence->approve == 1 || $absence->approve == "0") 
																	{!! $absence->approve_reason ? $absence->approve_reason : '' !!}
																@elseif($absence->approve == null) 
																	<textarea class="" type="text" name="approve_reason[{{ $absence->id}}]" rows="2"></textarea>
																@endif
															</td>
															{{-- <td>{!! $absence->approved ? $absence->approved['first_name'] . ' ' . $absence->approved['last_name'] : ''!!}</td> --}}
															{{-- <td>{{ $absence->approved_date }}</td> --}}
															
															@if( Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') )
																<td class="not_link options center">
																	@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) || Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
																		<!-- <button class="collapsible option_dots float_r"></button> -->
																		@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep))
																			<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit" title="{{ __('absence.edit_absence')}}" rel="modal:open" >
																				<i class="far fa-edit"></i>
																			</a>
																		@endif
																		@if(Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
																			{{-- <a href="{{ route('absences.destroy', $absence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}"  title="{{ __('absence.delete_absence')}}" ><i class="far fa-trash-alt"></i></a> --}}
																			<a href="{{ route('absences.destroy', $absence->id) }}" class="action_confirm btn-delete danger" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}"><i class="far fa-trash-alt"></i></a>
																		@endif
																		<a href="{{ route('confirmation_show', [ 'absence_id' => $absence->id ]) }}" class="btn-edit" title="{{ __('absence.approve_absence')}}" rel="modal:open" >
																			<i class="far fa-check-square"></i>
																		</a>
																		<a href="{{ route('print_requests', ['id' => $absence->id] ) }}" title="Print zahtjeva" target="_blank" ><i class="fas fa-print"></i></a> 
																	@endif
															{{-- 	@else
																	<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit" title="{{ __('absence.request_edit_absence')}}" rel="modal:open" >
																		<i class="far fa-edit"></i>
																	</a> --}}
																</td>
															@endif
															
														</tr>
													@endif
													
												@endforeach
											@endif
											@if(isset( $afterhours ) && count( $afterhours )>0 && Sentinel::inRole('administrator') )
												@foreach ( $afterhours as $afterhour )
													@php
														$time1 = new DateTime($afterhour->start_time );
														$time2 = new DateTime($afterhour->end_time );
														$interval = $time2->diff($time1);
														$interval = $interval->format('%H:%I');
													@endphp
													<tr class="tr_open_link tr" data-href="/absences/{{ $afterhour->employee->id }} empl_{{ $afterhour->employee_id}}"  id="requestAft_{{ $afterhour->id}}" >
														<td style="max-width:10%;width:10%">{{ $afterhour->employee->user['last_name'] . ' ' . $afterhour->employee->user['first_name'] }}</td>
														{{-- <td>{{ date('d.m.Y',strtotime($afterhour->created_at)) }}</td> --}}
														<td style="max-width:10%;width:10%">Prekovremeni sati</td>
														<td style="max-width:7%;width:7%">{{ date('d.m.Y',strtotime($afterhour->date))  }}</td>
														<td class="absence_end_date" style="max-width:10%;width:10%">-</td>
														<td class="absence_time" style="max-width:7%;width:7%">{{ date('H:i',strtotime($afterhour->start_time)) . '-' .  date('H:i',strtotime($afterhour->end_time)) }}</td>
														<td style="max-width:30%;width:30%">		
															{!! $afterhour->approve_h && $afterhour->approve == 1 ? 'Odobreno: '. $afterhour->approve_h : '' !!} [Traženo: {{ $interval }}] 
															{!! $afterhour->project ? $afterhour->project->erp_id . ' - '. $afterhour->project->name  : '' !!}
															{{ $afterhour->comment }} 
														</td>
														<td class="not_link approve" style="max-width:15%;width:15%">
															@if($afterhour->approve == 1) 
																<span class="img_approve"><span>@lang('absence.approved')</span></span>
															@elseif($afterhour->approve == '0')
																<span class="img_denied"><span>@lang('absence.refused')</span></span>
															@elseif($afterhour->approve == null)
																@if(Sentinel::inRole('administrator'))
																	<input type="hidden" name="id[{{ $afterhour->id}}]" class="id" value="{{ $afterhour->id}}">
																	<input type="hidden" name="type[{{ $afterhour->id}}]" class="id" value="aft">
																	<input name="approve_h[{{ $afterhour->id}}]" style="border-radius:5px;" class="odobreno_h[{{ $afterhour->id}}]" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" required>
																	<input class="check checkinput" type="radio" name="approve[{{ $afterhour->id}}]" value="1" id="odobreno{{ $afterhour->id}}" ><label class="check check_label" for="odobreno{{ $afterhour->id}}">DA</label>
																	<input class="uncheck checkinput" type="radio" name="approve[{{ $afterhour->id}}]" value="0" id="neodobreno{{ $afterhour->id}}" ><label class="uncheck check_label"  for="neodobreno{{ $afterhour->id}}">NE</label>
																	<input class="nocheck checkinput" type="radio" name="approve[{{ $afterhour->id}}]" value="" id="bezodobreno{{ $afterhour->id}}" ><label class="uncheck check_label"  for="bezodobreno{{ $afterhour->id}}">-</label>
																@endif
															@endif
														</td>
														<td class="not_link approve" style="max-width:10%;width:10%">
															@if($afterhour->approve == 1 || $afterhour->approve == '0') 
																{!! $afterhour->approved_reason ? $afterhour->approved_reason : '' !!}
															@elseif($afterhour->approve == null)
																<textarea class="" type="text" name="approved_reason[{{ $afterhour->id}}]" rows="2"></textarea>
															@endif
														</td>
														{{-- <td>{!! $afterhour->approved ? $afterhour->approved['first_name'] . ' ' . $afterhour->approved['last_name'] : ''!!}</td> --}}
														{{-- <td>{{ $afterhour->approved_date }}</td> --}}
														@if( Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') )
															<td class="not_link options center" style="max-width:10%;width:10%">
																@if(Sentinel::getUser()->hasAccess(['afterhours.update']) || in_array('afterhours.update', $permission_dep) || Sentinel::getUser()->hasAccess(['afterhours.delete']) || in_array('afterhours.delete', $permission_dep))
																	@if(Sentinel::getUser()->hasAccess(['afterhours.update']) || in_array('afterhours.update', $permission_dep))
																		<a href="{{ route('afterhours.edit', $afterhour->id) }}" class="btn-edit" title="{{ __('basic.edit_afterhour')}}" rel="modal:open" >
																			<i class="far fa-edit"></i>
																		</a>
																	@endif
																	@if(Sentinel::getUser()->hasAccess(['afterhours.delete']) || in_array('afterhours.delete', $permission_dep))
																		<a href="{{ route('afterhours.destroy', $afterhour->id) }}" class="action_confirm btn-delete danger" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}" ><i class="far fa-trash-alt"></i></a>
																	@endif
																	<a href="{{ route('confirmation_show_after', $afterhour->id) }}" class="btn-edit" title="{{ __('absence.approve_absence')}}" rel="modal:open" >
																		<i class="far fa-check-square"></i>
																	</a>
																@endif
															</td>
														@endif
													</tr>
												@endforeach
											@endif
										@else
											<tr class="tr_placeholder"><td colspan="9">
													<span class="placeholder_span">
														<img class="" src="{{ URL::asset('icons/placeholder_absence.png') }}" alt="Placeholder image" />
														<span >@lang('basic.no_absence1')
															<label type="text" class="add_new" rel="modal:open" >
																<i style="font-size:11px" class="fa">&#xf067;</i>
															</label>
															@lang('basic.no_absence2')
														</span>
													</span>
												</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</section>
			</main>
		</section>
	</main>
</div>
<div id="login-modal" class="modal">
	
</div>
<span class="selected_employee" hidden>{{ $selected_employee->user->last_name . ' ' . $selected_employee->user->first_name }}</span>
<script>	
	$.getScript('/../js/absence_create.js');
</script>
@stop