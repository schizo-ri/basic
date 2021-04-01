@extends('Centaur::admin')

@section('title', __('basic.visitors'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			{{-- @if(Sentinel::getUser()->hasAccess(['visitors.create']) || in_array('visitors.view', $permission_dep))
				<a class="btn-new" href="{{ route('visitors.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif --}}
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($visitors))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.fl_name')</th>
							<th>E-mail</th>
							<th>@lang('basic.company')</th>
							<th>card ID</th>
							<th>Povrat kartice</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($visitors as $visitor)
							<tr>
								<td>{{ $visitor->first_name . ' ' . $visitor->last_name }}</td>
								<td>{{ $visitor->email }}</td>
								<td>{{ $visitor->company }}</td>
								<td>{{ $visitor->card_id }}</td>
								<td>{!! $visitor->returned ? date('d.m.Y',strtotime($visitor->returned)) : '' !!}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['visitors.update']) || in_array('visitors.update', $permission_dep))
										<a href="{{ route('visitors.edit', $visitor->id) }}" class="btn-edit" rel="modal:open">
												<i class="far fa-edit"></i>
										</a>
									@endif
									@if( Sentinel::getUser()->hasAccess(['visitors.delete']) || in_array('visitors.delete', $permission_dep))
										<a href="{{ route('visitors.destroy', $visitor->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
	<script>
		/* $(function(){
			$.getScript( '/../js/filter_table.js');
		$('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			});
		});
		$.getScript( '/../restfulizer.js'); */
	</script>
@stop