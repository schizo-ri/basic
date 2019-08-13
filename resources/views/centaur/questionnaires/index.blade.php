@extends('Centaur::layout')

@section('title', __('questionnaire.questionnaires'))

@section('content')
<div class="row">
    <div class="page-header">
		<a href="{{ route('questionnaires.index') }}" class="load_page">@lang('questionnaire.questionnaires')</a> / 
		<a href="{{ route('evaluation_categories.index') }}" class="load_page">@lang('questionnaire.evaluation_categories')</a> / 
		<a href="{{ route('evaluation_questions.index') }}" class="load_page">@lang('questionnaire.evaluation_questions')</a> / 
		<a href="{{ route('evaluation_ratings.index') }}" class="load_page">@lang('questionnaire.evaluation_ratings')</a>  / 
		<a href="{{ route('evaluations.index') }}" class="load_page">@lang('questionnaire.results')</a>
        <div class='btn-toolbar pull-right'>
			@if(Sentinel::getUser()->hasAccess(['questionnaires.create']) || in_array('questionnaires.create', $permission_dep))
			    <a class="btn btn-primary btn-lg" href="{{ route('questionnaires.create') }}">
					<i class="fas fa-plus"></i>
					@lang('questionnaire.add_questionnaire')
				</a>
			@endif
        </div>
        <h1>@lang('questionnaire.questionnaires')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($questionnaires))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.name')</th>
							<th>@lang('basic.description')</th>
							<th>Status</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($questionnaires as $questionnaire)
							<tr>
								<td><a href="{{ route('evaluation_categories.index',['questionnaire_id' => $questionnaire->id ]) }}">{{ $questionnaire->name }}</a></td>
								<td>{{ $questionnaire->description }}</td>
								<td>{!! $questionnaire->status == 0 ? 'neaktivna' : 'aktivna' !!}</td>
								<td class="center">
									@if(Sentinel::getUser()->hasAccess(['questionnaires.create']) || in_array('questionnaires.create', $permission_dep))
									
									<a href="{{ action('QuestionnaireController@sendEmail', ['id' => $questionnaire->id ] ) }}" class="btn-edit">
											 <i class="far fa-envelope"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['questionnaires.update']) || in_array('questionnaires.update', $permission_dep))
										<a href="{{ route('questionnaires.edit', $questionnaire->id) }}" class="btn-edit">
											 <i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['questionnaires.delete']) || in_array('questionnaires.delete', $permission_dep))
										<a href="{{ route('questionnaires.destroy', $questionnaire->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}">
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
@stop