@extends('Centaur::layout')

@section('title', __('basic.contracts'))
@php
/* 	dd($permission_dep); */
@endphp
@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.contracts')
				@if (Sentinel::inRole('administrator')	)
					<a href="{{ route('contract_templates.index') }}" class="view_all" title="{{ __('basic.contract_templates') }}" >@lang('basic.contract_templates')</a>
				@endif
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(['contracts.create']) || in_array('contracts.create', $permission_dep))
								<a class="add_new" href="{{ route('contracts.create') }}" {{-- rel="modal:open" --}}>
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					@if(count($contracts) > 0)
						<table id="index_table" class="display table table-hover">
							<thead>
								<tr>
									<th>@lang('basic.company')</th>
									<th>@lang('basic.contract_no')</th>
									<th>@lang('basic.name')</th>
									<th>@lang('basic.date')</th>
									<th>@lang('basic.duration_contract')</th>
									<th>@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($contracts as $contract)
									<tr>
										<td>{{ $contract->customer->name }}</td>
										<td>{{ $contract->contract_no }}</td>
										<td>Ugovor {{ $contract->name }}</td>
										<td>{{ date( 'd.m.Y', strtotime($contract->date)) }}</td>
										<td>{{ $contract->duration . ' mjeseci' }}</td>
										<td class="center">
											@if(Sentinel::getUser()->hasAccess(['contracts.update']) || in_array('contracts.update', $permission_dep))
												<a href="{{ route('contracts.show', ['contract_id' => $contract->id]) }}" class="btn-edit"  title="{{ __('basic.show_contract')}}" {{-- rel="modal:open" --}}>
													<i class="fas fa-file-contract"></i>
												</a>
											@endif
											{{-- @if(Sentinel::getUser()->hasAccess(['contract_subjects.create']) || in_array('contract_subjects.create', $permission_dep))
												<a href="{{ route('contract_subjects.create', ['contract_id' => $contract->id]) }}" class="btn-edit"  title="{{ __('basic.add_subject')}}" rel="modal:open">
													<i class="fas fa-plus"></i>
												</a>
											@endif --}}
											@if(Sentinel::getUser()->hasAccess(['contracts.update']) || in_array('contracts.update', $permission_dep))
												<a href="{{ route('contracts.edit', $contract->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" {{-- rel="modal:open" --}}>
													<i class="far fa-edit"></i>
												</a>
											@endif
											@if(Sentinel::getUser()->hasAccess(['contracts.delete']) || in_array('contracts.delete', $permission_dep) )
												<a href="{{ route('contracts.destroy', $contract->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
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