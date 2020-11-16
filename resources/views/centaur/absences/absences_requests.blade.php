@extends('Centaur::layout')

@section('title', __('absence.absences'))
@php
	use App\Http\Controllers\BasicAbsenceController;
@endphp
@section('content')
<main class="col-lg-12 col-xl-12 index_main main_absence float_right">
	<section>
		<header class="header_absence">
			<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
			<p>@lang('absence.all_requests')  {{ $month }}
				<a href="{{ route('absences.index') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi zahtjeve za djelatnika</a>
				<a href="{{ route('absences_table') }}" class="view_all" title="{{ __('absence.absences')}}" >vidi izračune</a>
			</p>
		
		</header>
		<main class="all_absences table_absences table_requests">
			<header class="main_header">
				<label class="search_label col-xs-11 col-sm-6 col-md-6 col-lg-4">
					<input type="search" placeholder="Traži" onkeyup="mySearchTable()" id="mySearchTbl">
				</label>
				<label class="search_label col-xs-1 col-sm-6 col-md-6 col-lg-8">
					<span class="show_button"><i class="fas fa-download"></i></span>
					@if( Sentinel::inRole('administrator') || Sentinel::inRole('superadmin') )
						<select id="filter_types" class="select_filter filter_types" >
							<option value="all" >SVI</option>
							@foreach ($types as $type)
								<option value="{{ $type->id }}" >{{ $type->mark }}</option>
							@endforeach
						</select>
						<select id="filter_years" class="select_filter filter_years" >
							@foreach ($years as $year)
								<option value="{{ $year }}" {!! $year == $month ? 'selected' : '' !!} >{{ $year }}</option>
							@endforeach
						</select>
					@endif
				</label>
			</header>
			<section class="overflow_auto bg_white section_main">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 padd_0 position_rel height100">
					<div class="table-responsive" >
						<table id="index_table" class="display table table-hover ">
							<thead>
								<tr>
									
								</tr>
							</thead>
							<tbody class="overflow_auto">
								
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</main>
	</section>
</main>
</div>
<script>

</script>
@stop