@extends('Centaur::admin')

@section('title', __('basic.tables'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::inRole('superadmin'))
				<a class="btn-new" href="{{ route('tables.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
		
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($tables))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.description')</th>
							<th>@lang('basic.emailing')</th>
							<th>@lang('basic.shortcut') <br> index</th>
							<th>@lang('basic.shortcut') <br> create</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($tables as $table)
							<tr>
								<td>{{ $table->name }}</td>
								<td>{{ $table->description }}</td>
								<td>{!! $table->emailing == '0' ? 'neaktivno' : 'aktivno' !!}</td>
								<td>{!! $table->shortcut_i == '1' ? 'da' : '' !!}</td>
								<td>{!! $table->shortcut_c == '1' ? 'da' : '' !!}</td>
								<td class="center">
									<!-- <button class="collapsible option_dots float_r"></button> -->
									@if(Sentinel::getUser()->hasAccess(['tables.update']))
										<a href="{{ route('tables.edit', $table->id) }}" class="btn-edit" title="{{ __('basic.edit')}}" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif								
									@if(Sentinel::getUser()->hasAccess(['tables.delete']))
										<a href="{{ route('tables.destroy', $table->id) }}" class="action_confirm btn-delete danger" title="{{ __('basic.delete')}}" data-method="delete" data-token="{{ csrf_token() }}">
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
		$(function(){
			$.getScript( '/../js/filter_table.js');

		/* $('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			});*/
		}); 
	</script>
@stop