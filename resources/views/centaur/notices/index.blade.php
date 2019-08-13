@extends('Centaur::layout')

@section('title', __('basic.notices'))

@section('content')
<div class="row">  
	<div class="page-header">
        <h1>@lang('basic.notices')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($notices))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.employee')</th>
							<th>@lang('basic.to_department')</th>
							<th>@lang('basic.title')</th>
							<th>@lang('basic.notice')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($notices as $notice)
							<tr>
								<td>{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name']  }}</td>
								<td>{{ $notice->to_department }}</td>
								<td><a href="{{ route('notices.show', $notice->id) }}" >{{ $notice->title }}</a></td>
								<td>{!! str_limit(strip_tags($notice->notice),100) !!}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['notices.update']) || in_array('notices.update', $permission_dep) )
										<a href="{{ route('notices.edit', $notice->id) }}" class="btn-edit">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['notices.delete']) || in_array('notices.delete', $permission_dep))
										<a href="{{ route('notices.destroy', $notice->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
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
</div>
@stop