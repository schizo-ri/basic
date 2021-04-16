@extends('Centaur::layout')

@section('title', __('basic.contacts'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.contacts')
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
						</div>
					</header>
					@if(count($employees))
						<table id="index_table" class="display table table-hover">
							<thead>
								<tr>
									<th>@lang('basic.lf_name')</th>
									<th>@lang('basic.phone')</th>
									<th>@lang('basic.priv_mobile')</th>
									<th>e-mail</th>
									<th>@lang('basic.priv_email')</th>
									<th>@lang('basic.address')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($employees as $employee)
									<tr>
										<td>{{ $employee->user['last_name'] . ' ' . $employee->user['first_name'] }}</td>
										<td>{!! $employee->mobile !!}</td>
										<td>{!! $employee->priv_mobile !!}</td>
										<td>{!! $employee->email !!}</td>
										<td>{!! $employee->priv_email !!}</td>
										<td>{{ $employee->prebiv_adresa }} {!! $employee->prebiv_grad ? ', ' . $employee->prebiv_grad : '' !!}</td>
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