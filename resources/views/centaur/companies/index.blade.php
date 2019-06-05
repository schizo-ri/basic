@extends('Centaur::layout')

@section('title', __('basic.company'))

@section('content')
<div class="row">
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['companies.create']) || in_array('companies.create', $permission_dep) && count($companies) == 0 )
			    <a class="btn btn-primary btn-lg" href="{{ route('companies.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_company')
				</a>
			@endif
        </div>
        <h1>@lang('basic.company')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['companies.update']) || in_array('companies.update', $permission_dep))
										<a href="{{ route('companies.edit', $company->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['companies.delete']) || in_array('companies.delete', $permission_dep) && ! $departments->where('company_id',$company->id)->first())
										<a href="{{ route('companies.destroy', $company->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
<!-- Datatables -->
<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>

<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop