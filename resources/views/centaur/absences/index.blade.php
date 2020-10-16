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
				@lang('absence.all_requests')
			</header>
			<main class="all_absences">
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
									<option >{{ $year }}</option>
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
								<table id="index_table" class="display table table-hover sort_1_desc">
									<thead>
										<tr>
											@if( Sentinel::inRole('administrator') )<th>@lang('basic.fl_name')</th>@endif
											<th class="sort_date">@lang('absence.request_date')</th>
											<th>@lang('absence.request_type')</th>
											<th class="sort_date">@lang('absence.start_date')</th>
											<th class="sort_date">@lang('absence.end_date')</th>
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
												$zahtjev = array('start_date' => $absence->start_date, 'end_date' => $absence->end_date);
												$array_dani_zahtjeva = BasicAbsenceController::array_dani_zahtjeva($zahtjev);
												$dani_go = BasicAbsenceController::daniGO($absence);

											 	$dana_GO_OG = count(array_intersect($array_dani_zahtjeva,($data_absence['zahtjevi'][ $ova_godina])));
												$dana_GO_PG = $dani_go - $dana_GO_OG;
												
												$hours   = $interval->format('%h'); 
												$minutes = $interval->format('%i');
											@endphp
											<tr class="tr {!! $absence->absence->mark == 'BOL' ? 'bol bol-'.date('Y',strtotime($absence->start_date)) : '' !!}">
												@if( Sentinel::inRole('administrator') )<td>{{ $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'] }}</td>@endif
													<td>{{ date('d.m.Y.',strtotime($absence->created_at)) }}</td>
													<td>{{ '[' . $absence->absence['mark'] . '] ' . $absence->absence['name'] }}</td>
													<td>{{ date('d.m.Y.',strtotime($absence->start_date))  }}</td>
													<td>{{  date('d.m.Y.',strtotime($absence->end_date))  }}</td>
													<!--<td>xx dana</td>
													<td>{{ $absence->start_time . '-' .  $absence->end_time }}</td>-->
													<td>
														@if( $absence->absence['mark'] != 'IZL' )
															[{{ $dani_go }} @lang('absence.days')  {!! $dana_GO_PG ? '| PG: ' .$dana_GO_PG : '' !!} ] 
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
													{{-- <td>{!! $absence->approved ? $absence->approved['first_name'] . ' ' . $absence->approved['last_name'] : ''!!}</td> --}}
													{{-- <td>{{ $absence->approved_date }}</td> --}}
													<td class="options center">
														@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep) || Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
															<!-- <button class="collapsible option_dots float_r"></button> -->
															@if(Sentinel::getUser()->hasAccess(['absences.update']) || in_array('absences.update', $permission_dep))
																<a href="{{ route('absences.edit', $absence->id) }}" class="btn-edit" title="{{ __('absence.edit_absence')}}" rel="modal:open" >
																	<i class="far fa-edit"></i>
																</a>
															@endif

															@if(Sentinel::getUser()->hasAccess(['absences.delete']) || in_array('absences.delete', $permission_dep))
																<a href="{{ route('absences.destroy', $absence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}"  title="{{ __('absence.delete_absence')}}" ><i class="far fa-trash-alt"></i></a>
															@endif
															<a href="{{ route('confirmation_show', [ 'absence_id' => $absence->id ]) }}" class="btn-edit" title="{{ __('absence.approve_absence')}}" rel="modal:open" >
																<i class="far fa-check-square"></i>
															</a>
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
<script>
	$( function () {
		jQuery.extend( jQuery.fn.dataTableExt.oSort, {
			"date-eu-pre": function ( date ) {
				date = date.replace(" ", "");
					
				if ( ! date ) {
					return 0;
				}
			
				var year;
				var eu_date = date.split(/[\.\-\/]/);
				/*year (optional)*/
				if ( eu_date[2] ) {
					year = eu_date[2];
				}
				else {
					year = 0;
				}
			
				/*month*/
				var month = eu_date[1];
				try {
					
					if ( month.length == 1 ) {
						month = 0+month;
					}
			
					/*day*/
					var day = eu_date[0];
					if ( day.length == 1 ) {
						day = 0+day;
					}
			
					return (year + month + day) * 1;
				} catch (error) {
					target = null;
				}
			},
			
			"date-eu-asc": function ( a, b ) {
				return ((a < b) ? -1 : ((a > b) ? 1 : 0));
			},
			
			"date-eu-desc": function ( a, b ) {
				return ((a < b) ? 1 : ((a > b) ? -1 : 0));
			}
		} );
		$('.all_absences #index_table').DataTable( {
			"order": [[ 1, "desc" ]],
			"fixedHeader": true,
			"columnDefs": [ {
				"targets"  :[1,3,4],
				"type": 'date-eu'
			}],
			dom: 'Bfrtip',
			"paging": false,
			buttons: [
				'copyHtml5',
				{
					extend: 'print',
					customize: function ( win ) {
						$(win.document.body).find('h1').addClass('title_print');
						$(win.document.body).find('table').addClass('table_print');
						$(win.document.body).find('table tr td').addClass('row_print');
						$(win.document.body).addClass('body_print');
						$(win.document.body).find('table tr th').addClass('hrow_print');
						$(win.document.body).find('table tr th:last-child').addClass('not_print');
						$(win.document.body).find('table tr td:last-child').addClass('not_print');
					}
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'A4',
					exportOptions: {
						columns: 'th:not(.not-export-column)',
						rows: ':visible'
					}
				},
				{
					extend: 'excelHtml5',
					autoFilter: true,
					exportOptions: {
						columns: 'th:not(.not-export-column)',
						rows: ':visible'
					},
					customize: function( xlsx ) {
						var sheet = xlsx.xl.worksheets['sheet1.xml'];
					/* 	$('row c', sheet).attr( 's', '25' );  borders */
						$('row:first c', sheet).attr( 's', '27' );
					}	
				}
			]
		} );
		$('#index_table_filter').prepend('<a class="add_new" href="{{ route('absences.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>@lang('absence.new_request')</a>');
		if($(".all_absences #index_table_filter .show_button").length == 0) {
			$('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
		}
		$('#index_table_filter').show();
		$('table.display').show();
	} );
</script>
@stop