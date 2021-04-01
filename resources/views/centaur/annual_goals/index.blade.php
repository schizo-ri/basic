@extends('Centaur::layout')

@section('title', __('basic.annual_goals'))

@section('content')
<div class="index_page index_documents">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.annual_goals')
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(['annual_goals.create']) || in_array('annual_goals.view', $permission_dep))
								<a class="add_new" href="{{ route('annual_goals.create') }}" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					@if(count($annual_goals) > 0)
						<table id="index_table" class="display table table-hover sort_3_desc">
							<thead>
								<tr>
									<th class="capitalize">@lang('basic.year')</th>
									<th>@lang('basic.name')</th>
									<th>@lang('basic.comment')</th>
									<th>@lang('absence.end_date')</th>
									<th class="not-export-column">@lang('basic.options')</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($annual_goals as $annual_goal)
									<tr>
										<td>{{ $annual_goal->year }}</td>
										<td>{{ $annual_goal->name }}</td>
										<td>{{ $annual_goal->comment }}</td>
										<td>{{ date('d.m.Y',strtotime($annual_goal->end_date)) }}</td>
										<td class="center">
											@if(Sentinel::getUser()->hasAccess(['annual_goals.update']) || in_array('annual_goals.update', $permission_dep))
												<a href="{{ route('annual_goals.edit', $annual_goal->id) }}" class="btn-edit" rel="modal:open">
														<i class="far fa-edit"></i>
												</a>
											@endif
											@if( Sentinel::getUser()->hasAccess(['annual_goals.delete']) || in_array('annual_goals.delete', $permission_dep))
												<a href="{{ route('annual_goals.destroy', $annual_goal->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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