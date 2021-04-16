@extends('Centaur::admin')

@section('title', __('basic.mail_templates'))
	@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['mail_templates.create']) || in_array('mail_templates.create', $permission_dep))
				<a class="btn-new" href="{{ route('mail_templates.create') }}" >
					<i class="fas fa-plus"></i>
				</a>
			@endif
		{{-- 	<span class="change_view"></span> --}}
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($mail_templates))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th class="sort_date">@lang('basic.b_day')</th>
							<th class="sort_date">@lang('basic.parent')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($mail_templates as $mail_template)
							<tr >
								<td>{{ $mail_template->name }}</td>
								<td>{!! $mail_template->description !!}</td>
								<td>{{ $mail_template->to_mail  }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['mail_templates.update']) || in_array('mail_templates.update', $permission_dep))
										<a href="{{ route('mail_templates.edit', $mail_template->id) }}" title="{{ __('basic.edit_employee') }}" >
											<i class="fas fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['mail_templates.delete']) || in_array('mail_templates.delete', $permission_dep))
										<a href="{{ route('mail_templates.destroy', $mail_template->id ) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
									<a href="{{ route('mail_test', $mail_template->id) }}" title="Test mail"  rel="modal:open">
										<i class="far fa-envelope-open"></i>
									</a>
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