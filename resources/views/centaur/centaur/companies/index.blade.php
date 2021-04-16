@extends('Centaur::admin')

@section('title', __('basic.company'))

@section('content')
	<header class="page-header ">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			<a class="page_link" href="{{ route('structure') }}">Struktura firme</a>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($companies))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.address')</th>
							<th>@lang('basic.city')</th>
							<th>@lang('basic.oib')</th>
							<th>@lang('basic.director')</th>
							<th>e-mail</th>
							<th>@lang('basic.phone')</th>
							<th>Modules</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($companies as $company)
							<tr>
								<td>{{ $company->name }}</td>
								<td>{{ $company->address }}</td>
								<td>{{ $company->city }}</td>
								<td>{{ $company->oib }}</td>
								<td>{{ $company->director }}</td>
								<td>{{ $company->email }}</td>
								<td>{{ $company->phone }}</td>
								<td>@foreach($modules as $key => $value) {{ $value }} <br>@endforeach</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['companies.update']) || in_array('companies.update', $permission_dep))
										<a href="{{ route('companies.edit', $company->id) }}" class="btn-edit"  title="{{ __('basic.edit')}}" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['companies.delete']) || in_array('companies.delete', $permission_dep) && ! $departments->where('company_id',$company->id)->first())
										<a href="{{ route('companies.destroy', $company->id) }}" class="action_confirm btn-delete danger" data-method="delete" title="{{ __('basic.delete')}}" data-token="{{ csrf_token() }}">
											<i class="far fa-trash-alt"></i>
										</a>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
	<script>
		$(function(){
		});
		
	</script>
@stop