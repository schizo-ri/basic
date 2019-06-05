@extends('Centaur::layout')

@section('title', __('basic.posts'))
<?php use App\Http\Controllers\PostController; ?>
@section('content')
<div class="row">    <div class="page-header">
		@if(Sentinel::getUser()->hasAccess(['posts.create']) || in_array('posts.create', $permission_dep))
			<div class='btn-toolbar pull-right'>
				<a class="btn btn-primary btn-lg" href="{{ route('posts.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.send_post')
				</a>
			</div>
		@endif
        <h1>@lang('basic.posts')</h1>
    </div>
    
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($posts))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.from')</th>
							<th>@lang('basic.to')</th>
							<th>@lang('basic.title')</th>
							<th>@lang('basic.content')</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($posts as $post)
						<?php
						$count_comment = PostController::countComment( $post->id );
						$count_post = PostController::countPost( $post->id );
						?>
							<tr>
								<td>{{ $post->employee->user['first_name'] . ' ' . $post->employee->user['last_name'] }}</td>
								<td>{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}</td>
								<td><a href="{{ route('posts.show', $post->id) }}" >{{ $post->title }} 
								@if($count_post > 0)<span class="count_post">Novo!</span>@endif
								@if($count_comment > 0)<span class="count_comment">{{ $count_comment }}</span>@endif
								</a></td>
								<td>{!! $post->content !!}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['posts.update']) || in_array('posts.update', $permission_dep))
										<a href="{{ route('posts.edit', $post->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['posts.delete']) || in_array('posts.delete', $permission_dep))
										<a href="{{ route('posts.destroy', $post->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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