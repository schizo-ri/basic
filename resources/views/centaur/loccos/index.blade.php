@extends('Centaur::admin')

@section('title', __('basic.loccos'))

@section('content')
	<header class="page-header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['loccos.create']) || in_array('loccos.create', $permission_dep))
				<a class="btn-new" href="{{ route('loccos.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($cars))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.car')</th>
							<th></th>			
						</tr>				
					</thead>
					<tbody>
						@foreach ($cars as $car)
							<tr>
								<td><a class="open_locco" href="{{ route('loccos.show', $car->id) }}">{{ $car->manufacturer . ' ' . $car->model . ' ' . $car->registration }}</a></td>
								<td></td>
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
		/* 	var prev_url = location.href;

			$('a.open_locco').click(function(e) {
				click_element = $(this);
				$.getScript( '/../restfulizer.js');
				var url = $(this).attr('href');
			//	console.log(url);
			
				$( '#admin_page' ).load( url, function( response, status, xhr ) {
					$('.back_to_prev').click(function(){
						if(url.includes("/loccos")) {
							$('.admin_pages>li>a#loccos').click();
						}
						console.log("prev_url "+prev_url);
						console.log("url_location "+url);
					});
					if ( status == "error" ) {
						var msg = "Sorry but there was an error: ";
						$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
					}
					$.getScript( 'js/datatables.js');
					$.getScript( 'js/filter_table.js');
				});
				return false;
			}); */
			
		/* 	$('.collapsible').click(function(event){        
				$(this).siblings().toggle();
			}); */
		});
		
		/* $.getScript( '/../restfulizer.js'); */
	</script>
@stop