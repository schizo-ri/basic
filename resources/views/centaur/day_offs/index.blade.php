@extends('Centaur::admin')

@section('title', __('basic.days_off'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
		</div>
		@if(Sentinel::getUser()->hasAccess(['day_offs.create']) || in_array('day_offs.create', $permission_dep))
			<a class="btn-new" href="{{ route('day_offs.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($days_off) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>@lang('basic.no_days')</th>
							<th>@lang('basic.comment')</th>
							<th>@lang('basic.add_by_user')</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($days_off as $day_off)
							<tr>
								<td>{{ $day_off->employee->user->first_name . ' ' . $day_off->employee->user->last_name }}</td>
								<td>{{ $day_off->days_no }}</td>
								<td>{{ $day_off->comment }}</td>
								<td>{{ $day_off->addEmployee->user->first_name . ' ' . $day_off->addEmployee->user->last_name }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['day_offs.update']) || in_array('day_offs.update', $permission_dep))
										<a href="{{ route('day_offs.edit', $day_off->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['day_offs.delete']) || in_array('day_offs.delete', $permission_dep) )
										<a href="{{ route('day_offs.destroy', $day_off->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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
@stop