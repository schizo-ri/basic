@extends('Centaur::layout')

@section('title', __('absence.absences'))
@php
	use App\Http\Controllers\BasicAbsenceController;
@endphp
@section('content')
<div class="index_page index_absence">
	<main class="col-lg-12 col-xl-12 index_main main_absence float_right">
		<section>
			<header class="header_absence">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin')	)
					<p>@lang('absence.all_requests') 
						<a href="{{ route('absences_table') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi izračune</a>
						{{-- <a href="{{ route('absences_requests') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi zahtjeve za mjesec</a> --}}
					</p>
				@endif
			</header>
			<main class="all_absences all_absences_employee">
				{{-- <header class="main_header">
				
				</header> --}}
				<section class="overflow_auto bg_white section_main">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel height100">
						<div id="index_table_filter" class="dataTables_filter">
							<label class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 float_left">
								<input type="search" placeholder="Search" onkeyup="mySearchTableAbsence()" id="mySearchTbl">
							</label>
							<div  class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-xl-2 float_right">
								<select id="filter_types" class="select_filter filter_types" >
									<option value="all" >@lang('absence.all_types')</option>
									@foreach ($types as $type)
										<option value="{{ $type->id }}" >{{ $type->name }}</option>
									@endforeach
									<option value="afterhour" >Prekovremeni sati</option>
								</select>	
							</div>		
						</div>
						<div class="table-responsive" >
							@if(count( $absences ) > 0 || isset( $afterhours ) && count( $afterhours )> 0)
								<table id="index_table" class="display table table-hover sort_1_desc">
									<thead>
										<tr>
											<th>@lang('basic.fl_name')</th>
											<th>@lang('absence.request_type')</th>
											<th>@lang('absence.start_date')</th>
											<th>@lang('absence.end_date')</th>
											<th>Odobreno <br><small>dana / sati</small></th>
											<th>@lang('absence.time')</th>
											<th>@lang('basic.comment')</th>
											<th>@lang('absence.approved')</th>
											<th>@lang('absence.reason')</th>
											<th>@lang('absence.aprove_date')</th>
											@if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin')	)
											<th class="not-export-column">@lang('basic.options')</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@foreach ($absences as $absence)
											@php
												$start_date = new DateTime($absence->start_date . $absence->start_time);
												$end_date = new DateTime($absence->end_date . $absence->end_time );
												$interval1 = $start_date->diff($end_date);
												$zahtjev = array('start_date' => $absence->start_date, 'end_date' => $absence->end_date);
												$array_dani_zahtjeva = BasicAbsenceController::array_dani_zahtjeva($zahtjev);
												$dani_go = BasicAbsenceController::daniGO($absence);
											
												$dana_GO_OG = count(array_intersect($array_dani_zahtjeva,($data_absence['zahtjevi'][ date('Y')])));
												$dana_GO_PG = $dani_go - $dana_GO_OG;
												
												$hours   = $interval1->format('%h'); 
												$minutes = $interval1->format('%i');
												
												$time1 = new DateTime($absence->start_time );
												$time2 = new DateTime($absence->end_time );
												$interval = $time2->diff($time1);
												$interval = $interval->format('%H:%I');
											@endphp	
											<tr class="empl_{{ $absence->employee_id}}">
												<td>{{ $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'] }}</td>
												<td>{{ '[' . $absence->absence['mark'] . '] ' . $absence->absence['name'] }}</td>
												<td>{{ date('d.m.Y', strtotime($absence->start_date)) }}</td>
												<td>{{  date('d.m.Y', strtotime($absence->end_date)) }}</td>
												<td>
													@if( $absence->absence['mark'] != 'IZL' )
														{{ $dani_go }} @lang('absence.days')
													@else
														{!! $absence->approve == 1 ? date('H:i', strtotime($hours . ':' . $minutes))  . ' h' : '' !!}
													@endif
												</td>
												<td>{{ $absence->start_time . '-' .  $absence->end_time }}</td>
												<td>{{ $absence->comment }}</td>
												<td>{!! $absence->approve == 1 ? 'DA' : 'NE' !!} {!! $absence->approve_reason ? ' - ' . $absence->approve_reason : '' !!}</td>
												<td>{{ $absence->approved['first_name'] . ' ' . $absence->approved['last_name'] }}</td>
												<td>{{ $absence->approved_date }}</td>
												@if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin')	)
													<td class="center">
														@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) )
															<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit" rel="modal:open">
																<i class="far fa-edit"></i>
															</a>
														@endif
														@if(Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
															<a href="{{ route('absences.destroy', $absence->id) }}" class="action_confirm btn-delete danger"  {{-- data-method="delete" --}} data-token="{{ csrf_token() }}">
																<i class="far fa-trash-alt"></i>
															</a>
														@endif
													</td>
												@endif
											</tr>
										@endforeach
										@foreach ($afterhours as $afterhour)
											<tr>
												<td>{{ $afterhour->employee->user['first_name'] . ' ' . $afterhour->employee->user['last_name'] }}</td>
												<td>Prekovremeni sati</td>
												<td>{{ date('d.m.Y', strtotime($afterhour->date)) }}</td>
												<td></td>
												<td>{!! $afterhour->approve_h ? date('H:i', strtotime($afterhour->approve_h )) : '' !!}</td>
												<td>{{ $afterhour->start_time . '-' .  $afterhour->end_time }}</td>
												<td>{{ $afterhour->comment }}</td>
												<td>{!! $afterhour->approve == 1 ? 'DA' : 'NE' !!}</td>
												<td>{{ $afterhour->approved['first_name'] . ' ' . $afterhour->approved['last_name'] }}</td>
												<td>{{ $afterhour->approved_date }}</td>
												@if (Sentinel::inRole('administrator') || Sentinel::inRole('superadmin')	)
													<td class="center">
														@if(Sentinel::getUser()->hasAccess(['afterhours.update']) || in_array('afterhours.update', $permission_dep) )
															<a href="{{ route('afterhours.edit', $afterhour->id) }}" class="btn-edit" rel="modal:open">
																<i class="far fa-edit"></i>
															</a>
														@endif
														@if(Sentinel::getUser()->hasAccess(['afterhours.delete']) || in_array('afterhours.delete', $permission_dep))
															<a href="{{ route('afterhours.destroy', $afterhour->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
																<i class="far fa-trash-alt"></i>
															</a>
														@endif
													</td>
												@endif
											</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<p class="no_data">@lang('basic.no_data')</p>
							@endif
						</div>
					</div>
				</section>
			</main>	
		</section>
	</main>
</div>
<script>
	$.getScript('/../js/absence_create.js');
</script>
@stop