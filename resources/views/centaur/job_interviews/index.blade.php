@extends('Centaur::admin')

@section('title', __('basic.job_interviews'))

@section('content')
	<header class="page-header fuel_header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>		
			@if(Sentinel::getUser()->hasAccess(['job_interviews.create']) || in_array('job_interviews.create', $permission_dep))
				<a class="btn-new" href="{{ route('job_interviews.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if (count($job_interviews) > 0)
				<table id="index_table" class="display table table-hover sort_1_desc">
					<thead>
						<tr>
							<th >@lang('basic.fl_name')</th>
							<th >@lang('basic.date')</th>
							<th >OIB</th>
							<th >@lang('basic.work')</th>
							<th >Status</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody class="">
						@foreach ($job_interviews as $job_interview)
							<tr class="tr_open_link panel"  data-href="/job_interviews/{{ $job_interview->id }}" data-modal >
								<td>{{ $job_interview->last_name . ' ' .  $job_interview->first_name }} </td>
								<td>{{ date('d.m.Y.', strtotime($job_interview->date)) }}</td>
								<td>{{ $job_interview->oib }} </td>
								<td>{{ $job_interview->work->name }} </td>
								<td>{!! $job_interview->employee ? 'zaposlen' : '' !!}</td>
								<td>
									@if ( ! $job_interview->employee_id)
										@if(Sentinel::getUser()->hasAccess(['users.create']) || in_array('users.create', $permission_dep))
											<a href="{{ route('users.create', ['job_interview_id' => $job_interview->id] ) }}" class="edit_service btn-edit" title="{{ __('basic.register_employee')}}" rel="modal:open">
												<i class="fas fa-user-plus"></i>
											</a>
										@endif
									@endif
									@if(Sentinel::getUser()->hasAccess(['job_interviews.update']) || in_array('job_interviews.update', $permission_dep))
										<a href="{{ route('job_interviews.edit',$job_interview->id ) }}" class="edit_service btn-edit" title="{{ __('basic.edit_job_interview')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( ! $job_interview->employee && Sentinel::getUser()->hasAccess(['job_interviews.delete']) || in_array('job_interviews.delete', $permission_dep))
										<a href="{{ route('job_interviews.destroy', $job_interview->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
	<div id="login-modal" class="modal">
		
	</div>
	<script>
		$(function(){
			/* $.getScript( '/../js/filter_table.js');
			$.getScript( '/../js/filter_dropdown.js');
			$.getScript( '/../js/open_modal.js'); 
			$.getScript( '/../restfulizer.js'); */
		});
	</script>
@stop