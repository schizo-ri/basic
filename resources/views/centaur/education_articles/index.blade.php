@extends('Centaur::layout')

@section('title', __('basic.educationArticles'))

@section('content')
    <div class="page-header">
        <a href="{{ route('education.index') }}" >@lang('basic.educations')</a> / 
		<a href="{{ route('education_themes.index') }}" >@lang('basic.educationThemes')</a> / 
		<a href="{{ route('education_articles.index') }}" >@lang('basic.educationArticles')</a>
		<div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['education_articles.create']) || in_array('education_articles.create', $permission_dep))
			    @if(isset($educationTheme))
					<a class="btn btn-primary btn-lg" href="{{ route('education_articles.create', ['theme_id' => $educationTheme->id]) }}">
				@else
					<a class="btn btn-primary btn-lg" href="{{ route('education_articles.create') }}">
				@endif
					<i class="fas fa-plus"></i>
					@lang('basic.add_educationArticle')
				</a>
			@endif
        </div>
			<h1>@if(isset($educationTheme)){{ $educationTheme->name }}:@endif @lang('basic.educationArticles')</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
				@if(count($educationArticles))
					<table id="index_table" class="display table table-hover">
						<thead>
							<tr>
								@if(! isset($educationTheme))<th>@lang('basic.educationTheme')</th>@endif
								<th>@lang('basic.subject')</th>
								<th>@lang('basic.article')</th>
								<th>Status</th>
								<th>@lang('basic.options')</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($educationArticles as $educationArticle)
								<tr>
									@if(! isset($educationTheme))<th>{{ $educationArticle->educationTheme['name'] }}</th>@endif
									<td>{{ $educationArticle->subject }}</td>
									<td>{!! str_limit(strip_tags($educationArticle->article),100)  !!}</td>
									<td>{{ $educationArticle->status }}</td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['education_articles.update']) || in_array('education_articles.update', $permission_dep))
											<a href="{{ route('education_articles.edit', $educationArticle->id) }}" class="btn-edit">
												 <i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['education_articles.delete']) || in_array('education_articles.delete', $permission_dep))
											<a href="{{ route('education_articles.destroy', $educationArticle->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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