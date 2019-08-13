@extends('Centaur::layout')

@section('title', __('absence.absences'))
<link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/index.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/modal.css') }}"/>
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
			'razmjeranGO'  	=> BasicAbsenceController::razmjeranGO( $employee ), 
			'razmjeranGO_PG' => BasicAbsenceController::razmjeranGO_PG( $employee ), 
			'zahtjevi' => BasicAbsenceController::zahtjevi( $employee ), 
		);
		$bolovanje = BasicAbsenceController::bolovanje( $employee );
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
								<option>{{ $ova_godina }}</option>
								<option>{{ $prosla_godina }}</option>
							</select>
						</span>
						<p class="col-6 float_l">
							{{  $data_absence['days_OG'] }} ( {{  $data_absence['razmjeranGO'] }} )
							
							<span>Total days</span>
						</p>
						<p class="col-6 float_l">
							<span class="go_og go_{{ $ova_godina }}">{{ $data_absence['zahtjevi']['zahtjevi_Dani_OG'] }} - {{ $data_absence['zahtjevi']['preostalo_OG'] }}</span>
							<span class="go_pg go_{{ $prosla_godina }}">{{ $data_absence['zahtjevi']['zahtjevi_Dani_PG'] }} - {{ $data_absence['zahtjevi']['preostalo_PG'] }}</span>
							<span>Used - Unused</span>
						</p>
					</div>
					<div class="col-3 info_abs">
						<span class="title">@lang('absence.sick_leave')
							<select id="year_sick" class="year_select">
								<option>{{ $ova_godina }}</option>
								<option>{{ $prosla_godina }}</option>
							</select>
						</span>

						<p class="col-6 float_l">
							<span class="bol_og bol_{{ $ova_godina }}">{{  $bolovanje['bolovanje_OG'] }}</span>
							<span class="bol_pg bol_{{ $ova_godina }}">{{  $bolovanje['bolovanje_PG'] }}</span>
							<span>Total used</span>
						</p>
						<p class="col-6 float_l">
							<span class="bol_om">{{  $bolovanje['bolovanje_OM'] }}</span>
							<span>This month</span>
						</p>
					</div>
				</header>
				<section class="overflow_auto bg_white">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel">
							<div class="table-responsive">
								@if(count($absences))
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
												<th>@lang('absence.aprove')</th>
												<!--<th>@lang('absence.aproved')</th>
												<th>@lang('absence.aprove_date')</th>-->
												<th class="not-export-column no-sort"></th>
											</tr>
										</thead>
										<tbody class="overflow_auto">
											@foreach ($absences as $absence)
												@if(date("Y",strtotime($absence->start_date)) == $ova_godina || date("Y",strtotime($absence->end_date)) == $ova_godina)
													<tr class="ova_godina">
												@elseif(date("Y",strtotime($absence->start_date)) == $prosla_godina || date("Y",strtotime($absence->end_date)) == $prosla_godina)
												<tr class="prosla_godina">
												@endif
													@if( Sentinel::inRole('administrator') )<td>{{ $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'] }}</td>@endif
														<td>{{ '[' . $absence->absence['mark'] . '] ' . $absence->absence['name'] }}</td>
														<td>{{ $absence->start_date }}</td>
														<td>{{ $absence->end_date }}</td>
														<!--<td>xx dana</td>
														<td>{{ $absence->start_time . '-' .  $absence->end_time }}</td>-->
														<td>{{ $absence->comment }}</td>
														<td class="approve">@if($absence->approve == 1) <span class="img_approve"><span>@lang('absence.approved')</span></span>@endif</td>
														<!--<td>{{ $absence->approved['first_name'] . ' ' . $absence->approved['last_name'] }}</td>
														<td>{{ $absence->approved_date }}</td>-->
														<td class="options center">
															@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) || Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
																<button class="collapsible option_dots float_r"></button>
																<div class="content">
																	
																</div>
															@endif
														<!--
															
														-->
														</td>
													</tr>
											@endforeach
										</tbody>
									</table>
								@else
									@lang('basic.no_data')
								@endif
							</div>
						</div>
				</section>
			</main>
		</section>
	</main>

</div>
<script src="{{URL::asset('js/collaps.js') }}"></script>
<script>
	$('#year_vacation').change(function(){
		$('.go_og').toggle();
		$('.go_pg').toggle();
		$('.prosla_godina').toggle();
		$('.ova_godina').toggle();
	});
	$('#year_sick').change(function(){
		$('.bol_og').toggle();
		$('.bol_pg').toggle();
	});

	$(function() {
		$('.table-responsive').prepend('<a class="add_new" href="{{ route('absences.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>@lang('absence.new_request')</a>');
		$('.content').append('@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) )
				<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit" rel="modal:open"><i class="far fa-edit"></i></a>@endif
			@if(Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
				<a href="{{ route('absences.destroy', $absence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}"><i class="far fa-trash-alt"></i></a>@endif');
	});
</script>
@stop

<!--
<div class="row">  
	<div class="page-header">
		<a href="{{ route('absences.index') }}"  class="load_page" >@lang('absence.absences')</a> / 
		<a href="{{ route('absence_types.index') }}"  class="load_page">@lang('absence.abs_types')</a>
		<div class='btn-toolbar pull-right'>
			@if(isset($empl))
				<a class="btn btn-primary btn-lg" href="{{ route('absences.create', ['emplyee' => $empl]) }}">
			@else
				<a class="btn btn-primary btn-lg" href="{{ route('absences.create') }}">
			@endif
				<i class="fas fa-plus"></i>
				@lang('absence.add_absence')
			</a>
        </div>
        <h1>@lang('absence.absences')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($employees))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>Staž tvrtka  <br>[y-m-d]</th>
							<th>Ukupan staž  <br>[y-m-d]</th>
							<th>Dani GO <br>[{{ $prosla_godina }}]</th>
							<th>Iskorišteni dani <br>[{{ $prosla_godina }}] </th>
							<th>Neiskorišteno dana <br>[{{ $prosla_godina }}]</th>
							<th>Dani GO <br>[{{ $ova_godina }}]</th>
							<th>Razmjeran GO <br>[{{ $ova_godina }}]</th>
							<th>Iskorišteni dani <br>[{{ $ova_godina }}]</th>
							<th>Neiskorišteno dana <br>[{{ $ova_godina }}]</th>
							<th>Ukupno <br> neiskorištenih<br> dana </th>
						</tr>
					</thead>
					<tbody>
						@foreach ($employees as $employee)
						<?php 
						$data_absence = array(
							'years_service'  => BasicAbsenceController::yearsServiceCompany( $employee ), 
							'all_servise'  	=> BasicAbsenceController::yearsServiceAll( $employee ), 
							'days_OG'  		=> BasicAbsenceController::daysThisYear( $employee ), 
							'razmjeranGO'  	=> BasicAbsenceController::razmjeranGO( $employee ), 
							'razmjeranGO_PG' => BasicAbsenceController::razmjeranGO_PG( $employee ), 
							'zahtjevi' => BasicAbsenceController::zahtjevi( $employee ), 
						 );
						?>
							<tr>
								<td><a href="{{ route('absences.show', $employee->id ) }}">{{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }}</a></td>
								<th>{{ $data_absence['years_service']->y . '-' . 
									$data_absence['years_service']->m . '-' .  $data_absence['years_service']->d }}</th>
								<th>{{ $data_absence['all_servise'][0] . '-' . $data_absence['all_servise'][1]  . '-' .  $data_absence['all_servise'][2]  }}</th>
								<th>{{ $data_absence['razmjeranGO_PG'] }}</th>
								<th>{{ $data_absence['zahtjevi']['zahtjevi_Dani_PG'] }}</th>
								<th>{{ $data_absence['zahtjevi']['preostalo_PG'] }}</th>
								<th>{{ $data_absence['days_OG'] }}</th>
								<th>{{ $data_absence['razmjeranGO'] }}</th>
								<th>{{ $data_absence['zahtjevi']['zahtjevi_Dani_OG'] }}</th>
								<th>{{ $data_absence['zahtjevi']['preostalo_OG'] }}</th>
								<th>{{ $data_absence['zahtjevi']['preostalo_ukupno'] }}</th>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				@lang('basic.no_data')
			@endif
		</div>
	</div>
</div>-->