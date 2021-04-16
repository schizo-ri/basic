@extends('Centaur::admin')

@section('title', __('basic.instructions'))

@section('content')
	<header class="page-header fuel_header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>		
			@if(Sentinel::getUser()->hasAccess(['instructions.create']) || in_array('instructions.create', $permission_dep))
				<a class="btn-new" href="{{ route('instructions.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if (count($instructions) > 0)
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.title')</th>
							<th>@lang('basic.responsible')</th>
							<th>Status</th>
							<th>@lang('basic.to_department')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody class="">
						@foreach ($instructions as $instruction)
							<tr class="tr_open_link" data-href="/instructions/{{ $instruction->id }}" data-modal >
								<td>{{ $instruction->title }}</td>
								<td>{{ $instruction->employee->user->first_name .' '. $instruction->employee->user->last_name }}</td>
								<td>{{ $instruction->department->name }}</td>
								<td>{!! $instruction->active == 1 ? __('basic.active') : __('basic.inactive') !!}</td>
								<td class="not_link">
									@if(Sentinel::getUser()->hasAccess(['instructions.view']) || in_array('instructions.view', $permission_dep))
										<a href="{{ route('instructions.edit', $instruction->id ) }}" class="edit_service btn-edit" title="{{ __('basic.edit_instruction')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['instructions.delete']) || in_array('instructions.delete', $permission_dep))
										<a href="{{ route('instructions.destroy', $instruction->id) }}" class="action_confirm btn-delete danger edit_service " data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
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
@stop