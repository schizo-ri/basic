@extends('Centaur::layout')

@section('title', __('questionnaire.evaluation_categories'))

@section('content')
<div class="row">
    <div class="page-header">
		<a href="{{ route('questionnaires.index') }}" class="load_page">@lang('questionnaire.questionnaires')</a> / 
		<a href="{{ route('evaluation_categories.index') }}" class="load_page">@lang('questionnaire.evaluation_categories')</a> / 
		<a href="{{ route('evaluation_questions.index') }}" class="load_page">@lang('questionnaire.evaluation_questions')</a> / 
		<a href="{{ route('evaluation_ratings.index') }}" class="load_page">@lang('questionnaire.evaluation_ratings')</a> / 
		<a href="{{ route('evaluations.index') }}" class="load_page">@lang('questionnaire.results')</a>
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['evaluation_categories.create']) || in_array('evaluation_categories.create', $permission_dep))
			    @if(isset($questionnaire_id))
					<a class="btn btn-primary btn-lg" href="{{ route('evaluation_categories.create', ['questionnaire_id' => $questionnaire_id]) }}">
				@else
					<a class="btn btn-primary btn-lg" href="{{ route('evaluation_categories.create') }}">
				@endif
					<i class="fas fa-plus"></i>
					@lang('questionnaire.add_category')
				</a>
			@endif
        </div>
        <h1>@lang('questionnaire.evaluation_categories')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($evaluationCategories))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('questionnaire.questionnaire')</th>
							<th>@lang('basic.name')</th>
							<th>@lang('questionnaire.coef')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($evaluationCategories as $category)
							<tr>
								<td>{{ $category->questionnaire['name'] }}</td>
								<td><a href="{{ route('evaluation_questions.index', ['category_id' => $category->id ]) }}">{{ $category->name }}</a></td>
								<td>{{ $category->coefficient }}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['evaluation_categories.update']) || in_array('evaluation_categories.update', $permission_dep))
										<a href="{{ route('evaluation_categories.edit', $category->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['evaluation_categories.delete']) || in_array('evaluation_categories.delete', $permission_dep))
										<a href="{{ route('evaluation_categories.destroy', $category->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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