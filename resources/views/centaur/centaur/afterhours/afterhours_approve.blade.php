@extends('Centaur::admin')

@section('title', __('basic.afterhours'))

@section('content')
<form name="contactform" class="after_form" method="post" action="{{ action('AfterhourController@storeConf') }}" title="Odobreno!">
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			{{-- @if(Sentinel::getUser()->hasAccess(['afterhours.create']) || ($permission_dep && in_array('afterhours.view', $permission_dep)))
				<a class="btn-new" href="{{ route('afterhours.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif --}}
			<span  class="approve_button" id="nocheckall" >Ukloni oznake</span>
			<span  class="approve_button" id="uncheckall" ><i class="fas fa-times"></i> Označi sve <span class="approve_span">NE</span> </span>
			<span  class="approve_button" id="checkall"><i class="fas fa-check"></i> Označi sve <span class="approve_span">DA</span></span>
			
			
		</div>
		<input class="btn-new btn-approve" type="submit" value="Pošalji odobrenje">
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if( count($afterhours))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('basic.fl_name')</th>
								<th>@lang('basic.date')</th>
								<th>@lang('absence.time')</th>
								<th>@lang('basic.project')</th>
								<th>@lang('basic.comment')</th>
								<th class="not-export-column">@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($afterhours as $afterhour)
								@php
									$time1 = new DateTime($afterhour->start_time );
									$time2 = new DateTime($afterhour->end_time );
									$interval = $time2->diff($time1);
									$interval = $interval->format('%H:%I');
								@endphp
								<tr class="empl_{{ $afterhour->employee_id }}">
									<td>{!!  $afterhour->employee->user ? $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name : $afterhour->employee->email !!}</td>
									<td>{!! $afterhour->date ? date('d.m.Y', strtotime($afterhour->date)) : '' !!}</td>
									<td>{!! $afterhour->start_time ? date('H:s', strtotime($afterhour->start_time))  : '' !!} - {!! $afterhour->end_time ? date('H:s', strtotime($afterhour->end_time)) : '' !!}</td>
									<td>{{ $afterhour->project->id }}</td>
									<td>{{ $afterhour->comment }}</td>
									<td class="center">
										<input type="hidden" name="id[{{ $afterhour->id}}]" class="id" value="{{ $afterhour->id}}">
										<input name="odobreno_h[{{ $afterhour->id}}]" style="border-radius:5px;" class="odobreno_h[{{ $afterhour->id}}]" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" id="date1" required>
										<input class="check checkinput" type="radio" name="odobreno[{{ $afterhour->id}}]" value="DA" id="odobreno{{ $afterhour->id}}" ><label class="check check_label" for="odobreno{{ $afterhour->id}}">DA</label>
										<input class="uncheck checkinput" type="radio" name="odobreno[{{ $afterhour->id}}]" value="NE" id="neodobreno{{ $afterhour->id}}" ><label class="uncheck check_label"  for="neodobreno{{ $afterhour->id}}">NE</label>
										<input class="nocheck checkinput" type="radio" name="odobreno[{{ $afterhour->id}}]" value="" id="bezodobreno{{ $afterhour->id}}" ><label class="uncheck check_label"  for="bezodobreno{{ $afterhour->id}}">-</label>

										@if(Sentinel::getUser()->hasAccess(['afterhours.update']) || in_array('afterhours.update', $permission_dep))
											<a href="{{ route('afterhours.edit', $afterhour->id) }}" class="btn-edit" rel="modal:open">
													<i class="far fa-edit"></i>
											</a>
										@endif
										@if( Sentinel::getUser()->hasAccess(['afterhours.delete']) || in_array('afterhours.delete', $permission_dep))
											<a href="{{ route('afterhours.destroy', $afterhour->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				
			@else
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
</form>
<script>
	$('span#checkall').on('click',function(){
		$('.check.checkinput').prop('checked',true);
		$('.uncheck.checkinput').prop('checked',false);

	});
	$('span#uncheckall').on('click',function(){
		$('.check.checkinput').prop('checked',false);
		$('.uncheck.checkinput').prop('checked',true);
		
	});
	$('span#nocheckall').on('click',function(){
		$('.check.checkinput').prop('checked',false);
		$('.uncheck.checkinput').prop('checked',false);
		
	});
</script>
@stop