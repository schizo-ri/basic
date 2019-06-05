@extends('Centaur::layout')

@section('title', __('basic.educations'))

@section('content')
<div class="row">
    <div class="page-header">
		<a href="{{ route('education.index') }}" >@lang('basic.educations')</a> / 
		<a href="{{ route('education_themes.index') }}" >@lang('basic.educationThemes')</a> / 
		<a href="{{ route('education_articles.index') }}" >@lang('basic.educationArticles')</a>
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['educations.create']) || in_array('educations.view', $permission_dep))
			    <a class="btn btn-primary btn-lg" href="{{ route('education.create') }}">
					<i class="fas fa-plus"></i>
					@lang('basic.add_education')
				</a>
			@endif
        </div>
        <h1>@lang('basic.educations')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($educations))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.to_department')</th>
							<th>Status</th>
							<th>@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($educations as $education)
							<tr>
								<td><a href="{{ route('education_themes.index', ['education_id' => $education->id] ) }}" >{{ $education->name }}</a></td>
								<?php  
									$departments_id = '';
									if($education->to_department_id){
										$departments_id = explode(",",$education->to_department_id);
									}
								?>
								<td>
									@if(isset($departments_id))
										@foreach($departments_id as $department_id)
											<span style="padding: 0 20px;">{{ $departments->where('id', $department_id)->first()->name }}</span>
										@endforeach
									@endif
								</td>
								<td>{{ $education->status }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['educations.update']) || in_array('educations.update', $permission_dep))
										<a href="{{ route('education.edit', $education->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['educations.delete']) || in_array('educations.delete', $permission_dep))
										<a href="{{ route('education.destroy', $education->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
	<!-- Datatables -->
<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>

	<script src="{{ URL::asset('js/datatables.js') }}"></script>
</div>
@stop