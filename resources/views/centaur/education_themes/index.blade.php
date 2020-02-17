@extends('Centaur::layout')

@section('title', __('basic.educationThemes'))

@section('content')
<div class="row">
    <div class="page-header">
		<a href="{{ route('education.index') }}" class="load_page" >@lang('basic.educations')</a> / 
		<a href="{{ route('education_themes.index') }}" class="load_page" >@lang('basic.educationThemes')</a> / 
		<a href="{{ route('education_articles.index') }}" class="load_page" >@lang('basic.educationArticles')</a>
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
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($educationThemes))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							@if(! isset($education))<th>@lang('basic.education')</th>@endif
							<th class="not-export-column">@lang('basic.options')</th>
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
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</div>
</div>
@stop