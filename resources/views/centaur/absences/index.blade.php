
<!DOCTYPE html>
@extends('Centaur::layout')

@section('title', __('absence.absences'))
<link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
@php
	use App\Http\Controllers\BasicAbsenceController;
	$datum = new DateTime('now');    /* današnji dan */
	$ova_godina = date_format($datum,'Y');
	$prosla_godina = date_format($datum,'Y')-1;

	$employee = Sentinel::getUser()->employee;

	$docs = '';
	if($employee) {
		$user_name = explode('.',strstr($employee->email,'@',true));
		if(count($user_name) == 2) {
			$user_name = $user_name[1] . '_' . $user_name[0];
		} else {
			$user_name = $user_name[0];
		}

		$path = 'storage/' . $user_name . "/profile_img/";
		if(file_exists($path)){
			$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
		}else {
			$docs = '';
		}
		
		$data_absence = array(
			'years_service'  => BasicAbsenceController::yearsServiceCompany( $employee ),  
			'all_servise'  	=> BasicAbsenceController::yearsServiceAll( $employee ), 
			'days_OG'  		=> BasicAbsenceController::daysThisYear( $employee ), 
			'razmjeranGO'  	=> BasicAbsenceController::razmjeranGO( $employee ),  //razmjeran go ova godina
		//	'razmjeranGO_PG' => BasicAbsenceController::razmjeranGO_PG( $employee ), 
			'zahtjevi' 		 => BasicAbsenceController::requestAllYear( $employee ), 
		);
		$bolovanje = BasicAbsenceController::bolovanje( $employee );
		$prijenos_zahtjeva = 0;
		$ukupno_GO = 0;
		$ukupnoDani = 0;
	} 
@endphp
@section('content')
<div class="index_page index_absence">
	<aside class="col-lg-12 col-xl-12 float_left">
		@include('Centaur::side_noticeboard')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main main_absence float_right">
		<section>
			<header class="header_absence">
				<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>All requests
			</header>
			<main class="all_absences">
				<header class="main_header">
					<div class="col-3 info_abs">
						@if($docs)
							<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image"  />
						@else
							<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
						@endif
						<span class="empl_name">{{ $employee->user['first_name'] . ' ' .  $employee->user['last_name']}}</span>
						<span class="empl_work">{{ $employee->work['name'] }}</span>
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.work_history')</span>
						<p class="col-6 float_l">
							{{ $data_absence['years_service']->y . '-' . 
							$data_absence['years_service']->m . '-' .  $data_absence['years_service']->d }}
							<span>Experience<br><small>gg-mm-dd</small></span>
						<p class="col-6 float_l">
							{{ $data_absence['all_servise'][0] . '-' . $data_absence['all_servise'][1]  . '-' .  $data_absence['all_servise'][2]  }}
							<span>Experience total<br><small>gg-mm-dd</small></span>
						</p>
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.vacat_days')
							<select id="year_vacation" class="year_select">
								@foreach ($years as $year)
									<option >{{ $year }}</option>
								@endforeach								
							</select>
						</span>
						<p class="col-6 float_l">
							{{  $data_absence['days_OG'] }} ( {{  $data_absence['razmjeranGO'] }} )
							
							<span>Total days</span>
						</p>
						<p class="col-6 float_l">
							@foreach ($years as $year)
								@php
									$razmjeranGO_PG = BasicAbsenceController::razmjeranGO_Godina($employee, $year); // razmjerni dani prošla godina
								
									if ($year == $prosla_godina && date('n') < 7) {   //  ako je danas mjesec manji od 7
										$ukupno_GO += $razmjeranGO_PG;
									} elseif ( $year == $ova_godina ){
										$ukupno_GO += $razmjeranGO_PG;
									}
								
									$daniZahtjeviGodina = BasicAbsenceController::daniZahtjeviGodina($employee, $year); // zahtjevi - svi dani za godinu
									
									$daniZahtjeviGodina = $daniZahtjeviGodina + $prijenos_zahtjeva;
									$prijenos_zahtjeva = 0;
									if($daniZahtjeviGodina > $razmjeranGO_PG ) {
										$prijenos_zahtjeva = $daniZahtjeviGodina - $razmjeranGO_PG;
									} else {
										$prijenos_zahtjeva = 0;
									} 
									if ( $year == $ova_godina ||$year == $prosla_godina  ){
										$ukupnoDani += count ($data_absence['zahtjevi'][ $year]);
									}
									
								@endphp
								<span class="go go_{{ $year }}">
									{{ intval(count ($data_absence['zahtjevi'][ $year]))}} - {{ intval($razmjeranGO_PG) -  intval(count ($data_absence['zahtjevi'][ $year])) }}
								</span>
							@endforeach
							<span>Used - Unused</span>
						</p>
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.sick_leave')
							<select id="year_sick" class="year_select">
								@foreach ($years as $year)
									<option>{{ $year }}</option>
								@endforeach
							</select>
						</span>
						<p class="col-6 float_l">
							@foreach ($years as $year)
								<span class="bol bol_{{ $year }}">{{  $bolovanje[ $year] }}</span>
							@endforeach
							<span>Total used</span>
						</p>
						<p class="col-6 float_l">
							<span class="bol_om">{{ $bolovanje['bolovanje_OM'] }}</span>
							<span>This month</span>
						</p>
					</div>
				</header>
				<section class="overflow_auto bg_white">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel height100">
						<div class="table-responsive" >
							<div id="index_table_filter" class="dataTables_filter">
								<label>
									<input type="search" placeholder="Search" onkeyup="mySearchTableAbsence()" id="mySearchTbl">
								</label>
							</div>
							@if(count($absences)>0)
								<table id="index_table" class="display table table-hover">
									<thead>
										<tr>
											@if( Sentinel::inRole('administrator') )<th>@lang('basic.fl_name')</th>@endif
											<th>@lang('absence.request_type')</th>
											<th>@lang('absence.start_date')</th>
											<th>@lang('absence.end_date')</th>
											<!--<th>Period</th>
												<th>@lang('absence.time')</th>-->
											<th>@lang('basic.comment')</th>
											<th>@lang('absence.approved')</th>
											<!--<th>@lang('absence.aproved_by')</th>
											<th>@lang('absence.aprove_date')</th>-->
											<th class="not-export-column no-sort"></th>
										</tr>
									</thead>
									<tbody class="overflow_auto">
										@foreach ($absences as $absence)
										@php
											$start_date = new DateTime($absence->start_date . $absence->start_time);
											$end_date = new DateTime($absence->end_date . $absence->end_time );
											$interval = $start_date->diff($end_date);
											
											$hours   = $interval->format('%h'); 
											$minutes = $interval->format('%i');
										@endphp
											<tr class="tr_{!! date('Y', strtotime($absence->start_date) )!!} {!!  $absence->absence->mark == 'BOL' ? 'bol' : '' !!}">
												@if( Sentinel::inRole('administrator') )<td>{{ $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'] }}</td>@endif
													<td>{{ '[' . $absence->absence['mark'] . '] ' . $absence->absence['name'] }}</td>
													<td>{{ $absence->start_date }}</td>
													<td>{{ $absence->end_date }}</td>
													<!--<td>xx dana</td>
													<td>{{ $absence->start_time . '-' .  $absence->end_time }}</td>-->
													<td>
														@if( $absence->absence['mark'] != 'IZL' )
															[{{ BasicAbsenceController::daniGO($absence) }} @lang('absence.days') ] 
														@else
															[{{ $hours . ' h, ' . $minutes . ' m'}}]
														@endif
														{{ $absence->comment }}
													</td>
													<td class="approve">
														@if($absence->approve == 1) 
															<span class="img_approve"><span>@lang('absence.approved')</span></span>
														@endif
														@if($absence->approve == "0") 
															<span class="img_denied"><span>@lang('absence.not_approved')</span></span>
														@endif
													</td>
													<!--<td>{{ $absence->approved['first_name'] . ' ' . $absence->approved['last_name'] }}</td>
													<td>{{ $absence->approved_date }}</td>-->
													<td class="options center">
														@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) || Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
															<button class="collapsible option_dots float_r"></button>
															@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep))
																<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit" title="{{ __('absence.edit_absence')}}" style="display:none" rel="modal:open" >
																	<i class="far fa-edit"></i>
																</a>
															@endif

															@if(Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
																<a href="{{ route("absences.destroy", $absence->id) }}" class="action_confirm btn-delete danger" style="display:none" data-method="delete" data-token="{{ csrf_token() }}"  title="{{ __('absence.delete_absence')}}" ><i class="far fa-trash-alt"></i></a>
															@endif
														@endif
													
													</td>
												</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<div class="placeholder">
									<img class="" src="{{ URL::asset('icons/placeholder_absence.png') }}" alt="Placeholder image" />
									<p>@lang('basic.no_absence1')
										<label type="text" class="add_new" rel="modal:open" >
											<i style="font-size:11px" class="fa">&#xf067;</i>
										</label>
										@lang('basic.no_absence2')
									</p>
								</div>
							@endif
						</div>
					</div>
				</section>
			</main>
		</section>
	</main>
</div>
@if(isset($absence))
	<script>
		$( function () {
			$('#index_table_filter').show();
			$('#index_table_filter').prepend('<a class="add_new" href="{{ route('absences.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>@lang('absence.new_request')</a>');
			$('#index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
			$.getScript( 'js/datatables.js');
			$.getScript( 'js/filter_table.js');
			$.getScript( 'js/absence.js');
			$.getScript("js/collaps.js");
		});
	</script>
@endif
@stop