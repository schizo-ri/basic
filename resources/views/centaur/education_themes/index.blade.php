@extends('Centaur::layout')

@section('title', __('basic.educationThemes'))

@section('content')
    <div class="page-header">
		<a href="{{ route('education.index') }}" >@lang('basic.educations')</a> / 
		<a href="{{ route('education_themes.index') }}" >@lang('basic.educationThemes')</a> / 
		<a href="{{ route('education_articles.index') }}" >@lang('basic.educationArticles')</a>
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['education_themes.create']) || in_array('education_themes.create', $permission_dep))
			    @if(isset($education))
					<a class="btn btn-primary btn-lg" href="{{ route('education_themes.create', ['education_id' => $education->id]) }}">
				@else
					<a class="btn btn-primary btn-lg" href="{{ route('education_themes.create') }}">
				@endif
					<i class="fas fa-plus"></i>
					@lang('basic.add_educationTheme')
				</a>
			@endif
        </div>
			<h1>@if(isset($education)){{ $education->name }}:@endif @lang('basic.educationThemes')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($educationThemes))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								<th>@lang('basic.name')</th>
								@if(! isset($education))<th>@lang('basic.education')</th>@endif
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($educationThemes as $educationTheme)
								<tr>
									<td><a href="{{ route('education_articles.index', ['theme_id' => $educationTheme->id] ) }}" >{{ $educationTheme->name }}</a></td>
									@if(! isset($education))<td>{{ $educationTheme->education['name'] }}</td>@endif
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['education_themes.update'])|| in_array('education_themes.update', $permission_dep))
											<a href="{{ route('education_themes.edit', $educationTheme->id) }}" class="btn-edit">
												 <i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['education_themes.delete'])|| in_array('education_themes.delete', $permission_dep))
											<a href="{{ route('education_themes.destroy', $educationTheme->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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