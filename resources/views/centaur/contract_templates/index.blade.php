@extends('Centaur::layout')

@section('title', __('basic.contract_templates'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ route('contracts.index') }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.contract_templates')
				{{-- @if (Sentinel::inRole('administrator')	)
					<a href="{{ route('contract_templates.index') }}" class="view_all" title="{{ __('basic.contract_templates') }}" >@lang('basic.contract_templates')</a>
				@endif --}}
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(['contract_templates.create']) || in_array('contract_templates.create', $permission_dep))
								<a class="add_new" href="{{ route('contract_templates.create') }}" rel="modal:open" title="{{ __('basic.add_contract_template') }}">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					@if(count($contract_templates) > 0)
						<table id="index_table" class="display table table-hover">
							<thead>
								<tr>
									<th>@lang('basic.name')</th>
									<th>@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($contract_templates as $contract_template)
									<tr>
										<td>Ugovor {{ $contract_template->name }}</td>
										<td class="center">
											@if(Sentinel::getUser()->hasAccess(['contract_templates.update']) || in_array('contract_templates.update', $permission_dep))
												<a href="{{ route('contract_templates.edit', $contract_template->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
													<i class="far fa-edit"></i>
												</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['contract_templates.delete']) || in_array('contract_templates.delete', $permission_dep) )
												<a href="{{ route('contract_templates.destroy', $contract_template->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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
		</section>
	</main>
</div>
@stop