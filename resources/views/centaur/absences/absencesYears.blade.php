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
					<p>@lang('absence.vacation') - {{ $employee->user->first_name . ' ' . $employee->user->last_name}}
						<a href="{{ route('absences_table') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi izračune</a>
						{{-- <a href="{{ route('absences_requests') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi zahtjeve za mjesec</a> --}}
					</p>
				@endif
			</header>
			<main class="all_absences all_absences_employee">
				<section class="overflow_auto bg_white section_main">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel height100">
						<div id="index_table_filter" class="dataTables_filter">
							<label class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 float_left">
								<input type="search" placeholder="Search" onkeyup="mySearchTableAbsence()" id="mySearchTbl">
							</label>							
						</div>
						<div class="table-responsive" >
							@if(count( $zahtjevi ) > 0 )
								<table id="index_table" class="display table table-hover sort_1_desc">
									<thead>
										<tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
											{{-- <th>@lang('basic.fl_name')</th>
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
											@endif --}}
										</tr>
									</thead>
									<tbody>
                                        @php
                                           rsort($zahtjevi['years']);
                                        @endphp
                                        @foreach ($zahtjevi['years'] as $year)
                                            <tr class="bg_ccc bold">
                                                <td colspan="3">{{ $year }}</td>
                                            </tr>
                                            <tr>
                                                <td >Razmjerni dani {{$zahtjevi[$year]['razmjerniDani'] }}</td>
                                                <td >Ukupno dana zahtjeva {{$zahtjevi[$year]['dani_zahtjeva'] }}</td>
                                                <td >Neiskorišteno dana {{$zahtjevi[$year]['preostalo_dana'] }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" >
                                                    @foreach ($zahtjevi[$year]['zahtjevi'] as $zahtjevi_godine)
                                                        <span class="col-2" style="display: inline-block">{{ date('d.m.Y',strtotime($zahtjevi_godine)) }}</span>
                                                    @endforeach
                                                </td>
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
	$.getScript('/../js/absence_create_new.js');
</script>
@stop